<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

/**
 * Odoo Outbound Signal — Delivery Outbox
 *
 * One record per unique business event sent from Konn3ct to Odoo 19.
 * Payload is encrypted at rest using Laravel's encryption facilities.
 * The queue job carries only the signal's UUID, never the raw payload.
 */
class OdooOutboundSignal extends Model
{
    // -------------------------------------------------------------------------
    // Status constants
    // -------------------------------------------------------------------------

    /** Signal created, not yet queued. */
    const STATUS_PENDING = 'pending';

    /** Signal dispatched to the queue worker. */
    const STATUS_QUEUED = 'queued';

    /** Job is actively attempting HTTP delivery. */
    const STATUS_DELIVERING = 'delivering';

    /** HTTP 2xx received from Odoo. Terminal success. */
    const STATUS_DELIVERED = 'delivered';

    /** All retry attempts exhausted. Terminal failure. */
    const STATUS_FAILED = 'failed';

    /** Permanent non-retryable response (e.g. 400/422). No further retries. */
    const STATUS_BLOCKED = 'blocked';

    /** Paid-event signal cannot be dispatched: no Konn3ct user_id resolved. */
    const STATUS_WAITING_FOR_IDENTITY = 'waiting_for_identity';

    // -------------------------------------------------------------------------
    // Active statuses that a job may transition away from
    // -------------------------------------------------------------------------
    const RETRYABLE_STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_QUEUED,
        self::STATUS_DELIVERING,
    ];

    // -------------------------------------------------------------------------
    // Eloquent configuration
    // -------------------------------------------------------------------------

    protected $table = 'odoo_outbound_signals';

    protected $fillable = [
        'event_id',
        'event_name',
        'schema_version',
        'idempotency_key',
        'endpoint_key',
        'payload',
        'status',
        'attempts',
        'last_http_status',
        'last_error',
        'next_attempt_at',
        'queued_at',
        'delivered_at',
        'failed_at',
    ];

    protected $casts = [
        'next_attempt_at' => 'datetime',
        'queued_at'       => 'datetime',
        'delivered_at'    => 'datetime',
        'failed_at'       => 'datetime',
        'attempts'        => 'integer',
        'last_http_status'=> 'integer',
    ];

    protected $hidden = [
        'payload', // Never expose the encrypted payload in serialised output.
    ];

    // -------------------------------------------------------------------------
    // Payload encryption / decryption
    // -------------------------------------------------------------------------

    /**
     * Store the payload as a Laravel-encrypted string.
     *
     * @param array $value
     */
    public function setPayloadAttribute(array $value): void
    {
        $this->attributes['payload'] = Crypt::encryptString(json_encode($value));
    }

    /**
     * Decrypt and decode the payload.
     *
     * @return array|null
     */
    public function getDecryptedPayload(): ?array
    {
        if (empty($this->attributes['payload'])) {
            return null;
        }

        try {
            $json = Crypt::decryptString($this->attributes['payload']);
            return json_decode($json, true);
        } catch (\Exception $e) {
            return null;
        }
    }

    // -------------------------------------------------------------------------
    // Claim helper — prevents two workers delivering the same signal
    // -------------------------------------------------------------------------

    /**
     * Atomically claim this signal for delivery.
     *
     * Uses a database-level UPDATE WHERE so that only one worker can transition
     * the record from a claimable status to 'delivering' at a time.
     * A signal already in 'delivering' cannot be claimed again.
     *
     * @return bool  true if this worker successfully claimed the signal.
     */
    public function claim(): bool
    {
        // Only allow claiming from pending or queued — not from delivering.
        $claimableStatuses = [self::STATUS_PENDING, self::STATUS_QUEUED];

        return (bool) static::where('id', $this->id)
            ->whereIn('status', $claimableStatuses)
            ->update(['status' => self::STATUS_DELIVERING]);
    }

    // -------------------------------------------------------------------------
    // Convenience query scopes
    // -------------------------------------------------------------------------

    public function scopeRetryable($query)
    {
        return $query->whereIn('status', self::RETRYABLE_STATUSES);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }
}
