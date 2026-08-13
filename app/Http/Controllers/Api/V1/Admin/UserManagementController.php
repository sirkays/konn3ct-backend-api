<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Contracts\Admin\MeetingEnforcementGateway;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\BanUserRequest;
use App\Http\Requests\Api\V1\Admin\SuspendUserRequest;
use App\Http\Requests\Api\V1\Admin\UserListRequest;
use App\Models\AdminAuditLog;
use App\Models\AdminRefreshToken;
use App\Models\User;
use App\Services\Admin\AdminAuditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * UserManagementController
 *
 * Handles:
 *   GET  /api/v1/admin/users                    — List and search users (users:read)
 *   POST /api/v1/admin/users/{id}/suspend        — Suspend a user (users:suspend)
 *   POST /api/v1/admin/users/{id}/ban            — Ban a user (users:ban)
 *
 * All routes are protected by admin.jwt middleware.
 * Permission gates use admin.permission middleware.
 *
 * Self-moderation prevention:
 *   - An actor with only users:suspend or users:ban cannot moderate another admin.
 *   - Only admin.* holders can moderate other administrators.
 */
class UserManagementController extends Controller
{
    public function __construct(
        protected AdminAuditService $auditService,
        protected MeetingEnforcementGateway $meetingGateway
    ) {}

    // ─────────────────────────────────────────────────────────────────────────
    // GET /api/v1/admin/users
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * List and search users with pagination, filtering and sorting.
     *
     * Search strategy:
     *   MySQL/MariaDB: FULLTEXT MATCH() for multi-token name/email searches.
     *     Short terms (< FULLTEXT min word length, typically 3) fall back to LIKE prefix.
     *     Numeric terms use exact users.id match.
     *     Boolean-mode operators are escaped before use.
     *   SQLite (tests): case-insensitive LIKE across firstname, lastname, email, id.
     */
    public function index(UserListRequest $request): JsonResponse
    {
        $page      = (int) $request->input('page', 1);
        $limit     = (int) $request->input('limit', 25);
        $search    = $request->input('search');
        $roleFilter   = $request->input('role');
        $statusFilter = $request->input('status');
        $sortBy    = $request->input('sortBy', 'createdAt');
        $sortOrder = strtolower($request->input('sortOrder', 'desc'));

        // Map sortBy to column(s) + secondary id sort for determinism
        $sortMap = [
            'id'        => [['users.id', $sortOrder]],
            'name'      => [['users.firstname', $sortOrder], ['users.lastname', $sortOrder], ['users.id', $sortOrder]],
            'email'     => [['users.email', $sortOrder], ['users.id', $sortOrder]],
            'role'      => [['users.type', $sortOrder], ['users.created_at', 'desc'], ['users.id', 'desc']],
            'status'    => [['users.account_status', $sortOrder], ['users.created_at', 'desc'], ['users.id', 'desc']],
            'createdAt' => [['users.created_at', $sortOrder], ['users.id', $sortOrder]],
            'lastUsed'  => [['users.last_used', $sortOrder], ['users.id', $sortOrder]],
        ];

        $orders = $sortMap[$sortBy] ?? [['users.created_at', 'desc'], ['users.id', 'desc']];

        $query = User::query()->select([
            'users.id',
            'users.firstname',
            'users.lastname',
            'users.email',
            'users.type',
            'users.status',
            'users.account_status',
            'users.plan',
            'users.country',
            'users.created_at',
            'users.last_used',
        ]);

        // ── Search ───────────────────────────────────────────────────────────
        if (!empty($search)) {
            $driver = DB::getDriverName();

            if (in_array($driver, ['mysql', 'mariadb'], true)) {
                // Numeric search → exact ID match first
                if (ctype_digit($search)) {
                    $numericId = (int) $search;
                    $query->where(function ($q) use ($numericId, $search) {
                        $q->where('users.id', $numericId)
                          ->orWhere(function ($q2) use ($search) {
                              $q2->whereRaw($this->buildFulltextCondition($search));
                          });
                    });
                } else {
                    // Use FULLTEXT MATCH or prefix fallback for short terms
                    $query->where(function ($q) use ($search) {
                        $q->whereRaw($this->buildFulltextCondition($search));
                    });
                }
            } else {
                // SQLite / other: LIKE fallback for tests
                $likeTerm = '%' . $search . '%';
                if (ctype_digit($search)) {
                    $numericId = (int) $search;
                    $query->where(function ($q) use ($numericId, $likeTerm) {
                        $q->where('users.id', $numericId)
                          ->orWhereRaw('LOWER(users.firstname) LIKE LOWER(?)', [$likeTerm])
                          ->orWhereRaw('LOWER(users.lastname) LIKE LOWER(?)', [$likeTerm])
                          ->orWhereRaw('LOWER(users.email) LIKE LOWER(?)', [$likeTerm]);
                    });
                } else {
                    $query->where(function ($q) use ($likeTerm) {
                        $q->whereRaw('LOWER(users.firstname) LIKE LOWER(?)', [$likeTerm])
                          ->orWhereRaw('LOWER(users.lastname) LIKE LOWER(?)', [$likeTerm])
                          ->orWhereRaw('LOWER(users.email) LIKE LOWER(?)', [$likeTerm]);
                    });
                }
            }
        }

        // ── Role filter (maps to users.type) ────────────────────────────────
        if (!empty($roleFilter)) {
            $query->whereRaw('LOWER(users.type) = LOWER(?)', [trim($roleFilter)]);
        }

        // ── Account status filter (maps to users.account_status) ────────────
        if (!empty($statusFilter)) {
            $upper = strtoupper(trim($statusFilter));
            // Treat ACTIVE filter as: account_status = 'ACTIVE' OR account_status IS NULL
            if ($upper === 'ACTIVE') {
                $query->where(function ($q) {
                    $q->whereNull('users.account_status')
                      ->orWhereRaw("UPPER(users.account_status) = 'ACTIVE'");
                });
            } else {
                $query->whereRaw('UPPER(users.account_status) = ?', [$upper]);
            }
        }

        // ── Ordering ─────────────────────────────────────────────────────────
        foreach ($orders as [$column, $direction]) {
            $query->orderBy($column, $direction);
        }

        // ── Pagination ───────────────────────────────────────────────────────
        $total      = $query->count();
        $totalPages = $limit > 0 ? (int) ceil($total / $limit) : 0;
        $offset     = ($page - 1) * $limit;

        $users = $query->offset($offset)->limit($limit)->get();

        $data = $users->map(fn ($u) => $this->formatUser($u))->values()->all();

        return response()->json([
            'success' => true,
            'data'    => $data,
            'meta'    => [
                'page'        => $page,
                'limit'       => $limit,
                'total'       => $total,
                'total_pages' => $totalPages,
                'has_next'     => $page < $totalPages,
                'has_previous' => $page > 1,
            ],
        ])->header('Cache-Control', 'no-store');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // POST /api/v1/admin/users/{id}/suspend
    // ─────────────────────────────────────────────────────────────────────────

    public function suspend(SuspendUserRequest $request, int $id): JsonResponse
    {
        $correlationId = (string) Str::uuid();
        $actor         = $request->attributes->get('admin_user');
        $actorClaims   = $request->attributes->get('admin_claims');

        // Prevent self-moderation
        if ((int) $actor->id === $id) {
            return $this->conflictResponse('You cannot suspend your own account.', $correlationId);
        }

        $target = User::find($id);

        if (!$target) {
            return $this->notFoundResponse($correlationId);
        }

        // Prevent moderation of another admin unless actor holds admin.*
        if (strtolower((string) $target->type) === 'admin') {
            $actorPermissions = $actorClaims ? ($actorClaims->get('permissions') ?? []) : [];
            if (!in_array('admin.*', (array) $actorPermissions, true)) {
                return response()->json([
                    'success' => false,
                    'code'    => 'FORBIDDEN',
                    'message' => 'Moderating another administrator requires the admin.* permission.',
                ], 403)->header('Cache-Control', 'no-store')
                        ->header('X-Correlation-Id', $correlationId);
            }
        }

        $reason = $request->input('reason');

        try {
            $result = DB::transaction(function () use ($target, $actor, $reason, $correlationId, $request) {
                // Lock the row to prevent concurrent moderation
                $lockedTarget = User::lockForUpdate()->find($target->id);

                if (!$lockedTarget) {
                    return ['error' => 'not_found'];
                }

                $currentStatus = $lockedTarget->account_status
                    ? strtoupper($lockedTarget->account_status)
                    : 'ACTIVE';

                // Idempotency: already suspended
                if ($currentStatus === 'SUSPENDED') {
                    return ['idempotent' => true, 'user' => $lockedTarget];
                }

                // Cannot downgrade a banned user to suspended
                if ($currentStatus === 'BANNED') {
                    return ['error' => 'banned_conflict'];
                }

                // Apply suspension
                $lockedTarget->account_status = 'SUSPENDED';
                $lockedTarget->save();

                // Revoke Sanctum personal access tokens
                $lockedTarget->tokens()->delete();

                // Revoke admin_refresh_tokens if target is admin
                if (strtolower((string) $lockedTarget->type) === 'admin') {
                    AdminRefreshToken::where('user_id', $lockedTarget->id)
                        ->whereNull('revoked_at')
                        ->update(['revoked_at' => now()]);
                }

                // Persist audit record inside transaction for consistency
                $this->auditService->record(
                    AdminAuditService::EVENT_USER_SUSPENDED,
                    AdminAuditService::PRIORITY_NORMAL,
                    (int) $actor->id,
                    (int) $lockedTarget->id,
                    $reason,
                    $correlationId,
                    $request->ip(),
                    $request->userAgent(),
                    [
                        'previous_account_status' => $currentStatus,
                        'sanctum_tokens_revoked'  => true,
                        'admin_tokens_revoked'    => strtolower((string) $lockedTarget->type) === 'admin',
                        'meeting_enforcement'     => 'BLOCKED_PENDING_MEETING_SERVICE_CONTRACT',
                    ]
                );

                return ['user' => $lockedTarget];
            });
        } catch (\Throwable $e) {
            Log::error('User suspension transaction failed', [
                'user_id'        => $id,
                'correlation_id' => $correlationId,
                'error'          => $e->getMessage(),
            ]);
            throw $e;
        }

        if (isset($result['error'])) {
            if ($result['error'] === 'not_found') {
                return $this->notFoundResponse($correlationId);
            }
            if ($result['error'] === 'banned_conflict') {
                return response()->json([
                    'success' => false,
                    'code'    => 'USER_STATE_CONFLICT',
                    'message' => 'Cannot suspend a user who is already banned.',
                ], 409)->header('Cache-Control', 'no-store')
                        ->header('X-Correlation-Id', $correlationId);
            }
        }

        // Meeting enforcement (outside transaction — does not block 200 for this unsupported stub)
        $meetingEnforcement = $this->meetingGateway->revokeAndDisconnect($id, $correlationId);

        $user = $result['user'];

        return response()->json([
            'success'     => true,
            'message'     => isset($result['idempotent'])
                ? 'User is already suspended (idempotent).'
                : 'User suspended successfully.',
            'data'        => [
                'id'                  => (int) $user->id,
                'status'              => $user->account_status ?? 'ACTIVE',
                'subscription_status' => $user->status,
            ],
            'enforcement' => [
                'account_access'      => 'ENFORCED',
                'sanctum_tokens'      => 'REVOKED',
                'admin_refresh_tokens' => strtolower((string) $user->type) === 'admin' ? 'REVOKED' : 'N/A',
                'meeting_join_tokens' => $meetingEnforcement['meeting_join_tokens'],
                'live_disconnect'     => $meetingEnforcement['live_disconnect'],
                'complete'            => $meetingEnforcement['complete'],
            ],
        ])->header('Cache-Control', 'no-store')
          ->header('X-Correlation-Id', $correlationId);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // POST /api/v1/admin/users/{id}/ban
    // ─────────────────────────────────────────────────────────────────────────

    public function ban(BanUserRequest $request, int $id): JsonResponse
    {
        $correlationId = (string) Str::uuid();
        $actor         = $request->attributes->get('admin_user');
        $actorClaims   = $request->attributes->get('admin_claims');

        // Prevent self-moderation
        if ((int) $actor->id === $id) {
            return $this->conflictResponse('You cannot ban your own account.', $correlationId);
        }

        $target = User::find($id);

        if (!$target) {
            return $this->notFoundResponse($correlationId);
        }

        // Prevent moderation of another admin unless actor holds admin.*
        if (strtolower((string) $target->type) === 'admin') {
            $actorPermissions = $actorClaims ? ($actorClaims->get('permissions') ?? []) : [];
            if (!in_array('admin.*', (array) $actorPermissions, true)) {
                return response()->json([
                    'success' => false,
                    'code'    => 'FORBIDDEN',
                    'message' => 'Banning another administrator requires the admin.* permission.',
                ], 403)->header('Cache-Control', 'no-store')
                        ->header('X-Correlation-Id', $correlationId);
            }
        }

        $reason = $request->input('reason');

        try {
            $result = DB::transaction(function () use ($target, $actor, $reason, $correlationId, $request) {
                $lockedTarget = User::lockForUpdate()->find($target->id);

                if (!$lockedTarget) {
                    return ['error' => 'not_found'];
                }

                $currentStatus = $lockedTarget->account_status
                    ? strtoupper($lockedTarget->account_status)
                    : 'ACTIVE';

                $isIdempotent = ($currentStatus === 'BANNED');

                // Apply ban (idempotent — re-applying ensures tokens are still revoked)
                $lockedTarget->account_status = 'BANNED';
                $lockedTarget->save();

                // Revoke Sanctum personal access tokens
                $sanctumRevoked = $lockedTarget->tokens()->delete();

                // Revoke admin_refresh_tokens if target is admin
                $adminTokensRevoked = false;
                if (strtolower((string) $lockedTarget->type) === 'admin') {
                    AdminRefreshToken::where('user_id', $lockedTarget->id)
                        ->whereNull('revoked_at')
                        ->update(['revoked_at' => now()]);
                    $adminTokensRevoked = true;
                }

                // Persist audit record inside transaction
                $this->auditService->record(
                    AdminAuditService::EVENT_USER_BANNED,
                    AdminAuditService::PRIORITY_HIGH,
                    (int) $actor->id,
                    (int) $lockedTarget->id,
                    $reason,
                    $correlationId,
                    $request->ip(),
                    $request->userAgent(),
                    [
                        'previous_account_status'  => $currentStatus,
                        'idempotent'               => $isIdempotent,
                        'sanctum_tokens_revoked'   => true,
                        'admin_tokens_revoked'     => $adminTokensRevoked,
                        'meeting_enforcement'      => 'BLOCKED_PENDING_MEETING_SERVICE_CONTRACT',
                    ]
                );

                return [
                    'user'                => $lockedTarget,
                    'idempotent'          => $isIdempotent,
                    'admin_tokens_revoked' => $adminTokensRevoked,
                ];
            });
        } catch (\Throwable $e) {
            Log::error('User ban transaction failed', [
                'user_id'        => $id,
                'correlation_id' => $correlationId,
                'error'          => $e->getMessage(),
            ]);
            throw $e;
        }

        if (isset($result['error']) && $result['error'] === 'not_found') {
            return $this->notFoundResponse($correlationId);
        }

        // Meeting enforcement — does not gate HTTP 200 for unsupported stub
        $meetingEnforcement = $this->meetingGateway->revokeAndDisconnect($id, $correlationId);

        $user = $result['user'];

        return response()->json([
            'success'     => true,
            'message'     => $result['idempotent']
                ? 'User is already banned (idempotent).'
                : 'User banned successfully.',
            'data'        => [
                'id'                  => (int) $user->id,
                'status'              => $user->account_status ?? 'ACTIVE',
                'subscription_status' => $user->status,
            ],
            'enforcement' => [
                'account_access'       => 'ENFORCED',
                'sanctum_tokens'       => 'REVOKED',
                'admin_refresh_tokens' => $result['admin_tokens_revoked'] ? 'REVOKED' : 'N/A',
                'meeting_join_tokens'  => $meetingEnforcement['meeting_join_tokens'],
                'live_disconnect'      => $meetingEnforcement['live_disconnect'],
                'complete'             => $meetingEnforcement['complete'],
            ],
        ])->header('Cache-Control', 'no-store')
          ->header('X-Correlation-Id', $correlationId);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Build a MySQL FULLTEXT MATCH() condition.
     *
     * Short terms (< 3 chars, below typical FULLTEXT minimum word length) fall
     * back to LIKE prefix on firstname/lastname/email.
     * Boolean-mode operators are stripped from user input before use.
     *
     * @return string Raw SQL fragment (no bindings — term is embedded after escaping)
     */
    protected function buildFulltextCondition(string $term): string
    {
        // Escape boolean-mode operators: + - > < ( ) ~ * : " @
        $safeTerm = preg_replace('/[+\-><()\~\*:"@]+/', ' ', $term);
        $safeTerm = trim(preg_replace('/\s+/', ' ', $safeTerm));

        // Short terms that would not match FULLTEXT minimum word length
        if (mb_strlen($safeTerm) < 3) {
            $escaped = addslashes($safeTerm);
            return "(users.firstname LIKE '%{$escaped}%' OR users.lastname LIKE '%{$escaped}%' OR users.email LIKE '%{$escaped}%')";
        }

        $escaped = addslashes($safeTerm);

        // Use IN BOOLEAN MODE for partial matching with *
        $boolTerm = implode('* ', array_filter(explode(' ', $safeTerm))) . '*';
        $escapedBool = addslashes($boolTerm);

        return "MATCH(users.firstname, users.lastname, users.email) AGAINST ('{$escapedBool}' IN BOOLEAN MODE)";
    }

    /**
     * Format a user row for API response.
     * Only safe, explicitly selected fields are returned.
     * Passwords, tokens, MFA secrets, and remember tokens are never present
     * because we SELECT only safe columns in the query.
     */
    protected function formatUser(User $u): array
    {
        return [
            'id'                  => (int) $u->id,
            'firstname'           => $u->firstname,
            'lastname'            => $u->lastname,
            'name'                => trim(($u->firstname ?? '') . ' ' . ($u->lastname ?? '')),
            'email'               => $u->email,
            'role'                => $u->type,
            'status'              => $u->account_status ?? 'ACTIVE',
            'subscription_status' => $u->status,
            'plan'                => $u->plan,
            'country'             => $u->country,
            'created_at'          => $u->created_at?->toIso8601String(),
            'last_used'           => $u->last_used
                ? \Carbon\Carbon::parse($u->last_used)->toIso8601String()
                : null,
        ];
    }

    protected function notFoundResponse(string $correlationId): JsonResponse
    {
        return response()->json([
            'success' => false,
            'code'    => 'USER_NOT_FOUND',
            'message' => 'User not found.',
        ], 404)->header('Cache-Control', 'no-store')
                ->header('X-Correlation-Id', $correlationId);
    }

    protected function conflictResponse(string $message, string $correlationId): JsonResponse
    {
        return response()->json([
            'success' => false,
            'code'    => 'USER_STATE_CONFLICT',
            'message' => $message,
        ], 409)->header('Cache-Control', 'no-store')
                ->header('X-Correlation-Id', $correlationId);
    }
}
