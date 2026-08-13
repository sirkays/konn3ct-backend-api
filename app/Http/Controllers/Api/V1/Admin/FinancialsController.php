<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\TransactionListRequest;
use App\Models\PaymentModel;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * FinancialsController
 *
 * GET /api/v1/admin/financials/transactions — List payment transactions (financials:read)
 *
 * Design notes:
 * - Uses payment.gateway_response via explicit column selection (not model serialization)
 *   so that PaymentModel::$hidden remains intact for other endpoints.
 * - Do NOT join paystackhook — no reliable FK relationship exists.
 * - Historical attempts not persisted by payment gateway flows cannot be reconstructed.
 * - Raw gateway payloads are never written to application logs.
 */
class FinancialsController extends Controller
{
    /**
     * GET /api/v1/admin/financials/transactions
     */
    public function transactions(TransactionListRequest $request): JsonResponse
    {
        $correlationId = (string) Str::uuid();

        $page        = (int) $request->input('page', 1);
        $limit       = (int) $request->input('limit', 25);
        $status      = $request->input('status');
        $paymentType = $request->input('paymentType');
        $gateway     = $request->input('gateway');
        $startDate   = $request->input('startDate');
        $endDate     = $request->input('endDate');

        // Explicit column selection — gateway_response is fetched here only
        // and will NOT appear through the default model serialization on other endpoints.
        $query = PaymentModel::query()->select([
            'payment.id',
            'payment.reference',
            'payment.gateway_reference',
            'payment.amount',
            'payment.currency',
            'payment.status',
            'payment.gateway',
            'payment.type',
            'payment.user_id',
            'payment.date',
            'payment.gateway_response', // explicitly selected; still hidden on default model output
        ]);

        // ── Filters ──────────────────────────────────────────────────────────

        if (!empty($status)) {
            $query->whereRaw('LOWER(payment.status) = LOWER(?)', [trim($status)]);
        }

        if (!empty($paymentType)) {
            $query->whereRaw('LOWER(payment.type) = LOWER(?)', [trim($paymentType)]);
        }

        if (!empty($gateway)) {
            $query->whereRaw('LOWER(payment.gateway) LIKE LOWER(?)', [trim($gateway)]);
        }

        if (!empty($startDate)) {
            $query->where('payment.date', '>=', Carbon::parse($startDate)->startOfDay());
        }

        if (!empty($endDate)) {
            // A date-only endDate must include the whole day
            $query->where('payment.date', '<=', Carbon::parse($endDate)->endOfDay());
        }

        // ── Default ordering: date desc, id desc ────────────────────────────
        $query->orderBy('payment.date', 'desc')
              ->orderBy('payment.id', 'desc');

        // ── Pagination ───────────────────────────────────────────────────────
        $total      = $query->count();
        $totalPages = $limit > 0 ? (int) ceil($total / $limit) : 0;
        $offset     = ($page - 1) * $limit;

        $rows = $query->offset($offset)->limit($limit)->get();

        $data = $rows->map(fn ($row) => $this->formatTransaction($row))->values()->all();

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
        ])->header('Cache-Control', 'no-store')
          ->header('X-Correlation-Id', $correlationId);
    }

    /**
     * Format a single payment row for the API response.
     *
     * Maps payment.gateway_response → raw_webhook_payload.
     * Decodes valid JSON to object/array; returns safe fallback for invalid JSON.
     * Never logs the raw payload.
     */
    protected function formatTransaction(PaymentModel $row): array
    {
        $rawGatewayResponse = $row->getRawOriginal('gateway_response');
        $rawWebhookPayload  = $this->decodeGatewayResponse($rawGatewayResponse);

        return [
            'id'                  => (int) $row->id,
            'reference'           => $row->reference,
            'gateway_reference'   => $row->gateway_reference,
            'amount'              => $row->amount,
            'currency'            => $row->currency,
            'status'              => $row->status,
            'gateway'             => $row->gateway,
            'payment_type'        => $row->type,
            'user_id'             => (int) $row->user_id,
            'date'                => $row->date
                ? Carbon::parse($row->date)->toIso8601String()
                : null,
            'raw_webhook_payload' => $rawWebhookPayload,
        ];
    }

    /**
     * Safely decode the gateway_response field.
     *
     * - Valid JSON → decoded object/array
     * - Invalid JSON (legacy raw string) → wrapped in a safe structure
     * - Null/empty → null
     * - Never logs the raw value
     */
    protected function decodeGatewayResponse(?string $raw): mixed
    {
        if ($raw === null || trim($raw) === '') {
            return null;
        }

        $decoded = json_decode($raw, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        // Invalid JSON: return as explicitly marked raw-value object
        return [
            '__type'     => 'raw_non_json_legacy_value',
            '__safe_len' => strlen($raw),
        ];
    }
}
