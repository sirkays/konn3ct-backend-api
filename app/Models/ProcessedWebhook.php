<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ProcessedWebhook
 *
 * Idempotency record for payment webhook events.
 *
 * INSERT of this record must happen INSIDE the same DB::transaction() as the
 * payment provisioning. If provisioning rolls back, this record is also rolled
 * back so the event can be retried.
 *
 * @property int    $id
 * @property string $provider           e.g. 'paystack', 'stripe'
 * @property string $event_type         e.g. 'charge.success'
 * @property string $provider_event_id  Provider's unique event identifier
 * @property string $idempotency_key    Our computed key: "{provider}:{type}:{event_id}"
 * @property int|null $event_transaction_id
 * @property \Carbon\Carbon $processed_at
 */
class ProcessedWebhook extends Model
{
    use HasFactory;

    protected $table = 'processed_webhooks';

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    /**
     * Build a deterministic idempotency key for the given event.
     */
    public static function buildIdempotencyKey(string $provider, string $eventType, string $providerEventId): string
    {
        return "{$provider}:{$eventType}:{$providerEventId}";
    }

    /**
     * Check whether a webhook has already been processed.
     */
    public static function alreadyProcessed(string $idempotencyKey): bool
    {
        return static::where('idempotency_key', $idempotencyKey)->exists();
    }
}
