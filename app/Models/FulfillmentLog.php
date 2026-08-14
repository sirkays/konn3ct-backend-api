<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * FulfillmentLog
 *
 * Tracks per-step fulfillment state for a paid EventTransaction.
 * Each step is independently idempotent: a job checks the flag before
 * executing, and sets it inside a lockForUpdate() scope.
 *
 * @property int  $id
 * @property int  $event_transaction_id
 * @property bool $receipt_generated
 * @property string|null $receipt_path
 * @property bool $ticket_generated
 * @property string|null $ticket_path
 * @property bool $email_sent
 * @property bool $odoo_notified
 * @property string|null $last_error  Sanitized, no PII
 * @property int  $retry_count
 */
class FulfillmentLog extends Model
{
    use HasFactory;

    protected $table = 'fulfillment_log';

    protected $guarded = ['id'];

    protected $casts = [
        'receipt_generated' => 'boolean',
        'ticket_generated'  => 'boolean',
        'email_sent'        => 'boolean',
        'odoo_notified'     => 'boolean',
    ];

    // ---------------------------------------------------------------------------
    // Factory method — creates a log row when a transaction is first paid.
    // ---------------------------------------------------------------------------

    /**
     * Create a fresh fulfillment log for the given transaction.
     * Idempotent: does nothing if a log already exists.
     *
     * @param  int $eventTransactionId
     * @return static
     */
    public static function initializeFor(int $eventTransactionId): static
    {
        return static::firstOrCreate(
            ['event_transaction_id' => $eventTransactionId],
            [
                'receipt_generated' => false,
                'ticket_generated'  => false,
                'email_sent'        => false,
                'odoo_notified'     => false,
                'retry_count'       => 0,
            ]
        );
    }

    // ---------------------------------------------------------------------------
    // Relationships
    // ---------------------------------------------------------------------------

    public function eventTransaction()
    {
        return $this->belongsTo(EventTransaction::class);
    }
}
