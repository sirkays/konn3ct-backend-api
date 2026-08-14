<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ReplayAccessToken
 *
 * Short-lived, cryptographically opaque token granting API-layer access to
 * retrieve a BBB recording playback URL.
 *
 * SECURITY NOTE:
 *   - Only the SHA-256 hash of the token is stored — never the plaintext.
 *   - The /stream endpoint reveals a permanently public BBB URL.
 *   - This provides "sharing resistance" at the API layer, NOT full content protection.
 *   - BBB protection status: PARTIAL.
 *
 * Rejection conditions (all enforced in ReplayAccessService::validate()):
 *   - Token hash not found in database
 *   - Token expired (expires_at < now())
 *   - Token revoked (revoked_at is not null)
 *   - User mismatch (token.user_id != auth user id)
 *   - Recording mismatch (token.recording_id != requested recording_id)
 *
 * @property int    $id
 * @property string $token_hash   SHA-256 hex of the plaintext token
 * @property int    $user_id
 * @property string $recording_id BBB recordID
 * @property int    $event_id     prereg.id
 * @property \Carbon\Carbon $expires_at
 * @property \Carbon\Carbon|null $revoked_at
 */
class ReplayAccessToken extends Model
{
    use HasFactory;

    protected $table = 'replay_access_tokens';

    protected $guarded = ['id'];

    protected $casts = [
        'expires_at'  => 'datetime',
        'revoked_at'  => 'datetime',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    // ---------------------------------------------------------------------------
    // Validation
    // ---------------------------------------------------------------------------

    /**
     * Find a valid (non-expired, non-revoked) token by its plaintext value.
     * Returns null if not found or invalid.
     *
     * @param  string $plaintextToken
     * @return static|null
     */
    public static function findValid(string $plaintextToken): ?static
    {
        $hash = hash('sha256', $plaintextToken);

        return static::where('token_hash', $hash)
            ->whereNull('revoked_at')
            ->where('expires_at', '>', now())
            ->first();
    }

    /**
     * Revoke this token immediately.
     */
    public function revoke(): void
    {
        $this->revoked_at = now();
        $this->save();
    }

    /**
     * Check if this token is valid for the given user and recording.
     * All four conditions must pass simultaneously.
     *
     * @param  int    $userId
     * @param  string $recordingId
     * @return bool
     */
    public function isValidFor(int $userId, string $recordingId): bool
    {
        return $this->user_id === $userId
            && $this->recording_id === $recordingId
            && $this->revoked_at === null
            && $this->expires_at->isFuture();
    }

    // ---------------------------------------------------------------------------
    // Relationships
    // ---------------------------------------------------------------------------

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
