<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

/**
 * EventTransaction
 *
 * Represents a single Paystack or Stripe event ticket purchase attempt.
 * The Vulte event payment flow continues to use prereg_users directly.
 *
 * @property int         $id
 * @property string      $uuid
 * @property string      $ticket_number
 * @property int         $user_id
 * @property int         $event_id
 * @property int         $amount_minor      Price in minor units (e.g. kobo/cents)
 * @property string      $currency          ISO 4217 (e.g. 'NGN', 'USD')
 * @property string      $provider          'paystack' or 'stripe'
 * @property string      $local_reference   Our internal reference
 * @property string|null $provider_reference Provider's reference/event ID
 * @property string|null $provider_session_id
 * @property string      $status            pending|paid|failed|initialization_failed
 * @property string      $pricing_snapshot  Encrypted JSON of locked price
 * @property string|null $metadata
 * @property \Carbon\Carbon|null $paid_at
 */
class EventTransaction extends Model
{
    use HasFactory;

    protected $table = 'event_transactions';

    protected $guarded = ['id'];

    protected $casts = [
        'paid_at'    => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ---------------------------------------------------------------------------
    // Status constants — only PENDING → PAID transitions are valid.
    // ---------------------------------------------------------------------------

    const STATUS_PENDING              = 'pending';
    const STATUS_PAID                 = 'paid';
    const STATUS_FAILED               = 'failed';
    const STATUS_INITIALIZATION_FAILED = 'initialization_failed';

    // ---------------------------------------------------------------------------
    // Boot — generate UUID and ticket_number on creation.
    // ---------------------------------------------------------------------------

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->ticket_number)) {
                $model->ticket_number = self::generateTicketNumber();
            }
        });
    }

    /**
     * Generate a human-readable, non-enumerable ticket number.
     * Format: TICK-{YEAR}-{12 random uppercase alphanumeric chars}
     */
    public static function generateTicketNumber(): string
    {
        return 'TICK-' . date('Y') . '-' . strtoupper(Str::random(12));
    }

    // ---------------------------------------------------------------------------
    // Pricing snapshot — encrypted at rest, never logged.
    // ---------------------------------------------------------------------------

    /**
     * Store the pricing snapshot as an encrypted JSON string.
     *
     * @param array $snapshot
     */
    public function setPricingSnapshot(array $snapshot): void
    {
        $this->pricing_snapshot = Crypt::encryptString(json_encode($snapshot));
    }

    /**
     * Retrieve and decrypt the pricing snapshot.
     *
     * @return array|null
     */
    public function getDecryptedPricingSnapshot(): ?array
    {
        try {
            return json_decode(Crypt::decryptString($this->pricing_snapshot), true);
        } catch (\Exception $e) {
            return null;
        }
    }

    // ---------------------------------------------------------------------------
    // Transition guard — only PENDING → PAID is valid.
    // ---------------------------------------------------------------------------

    /**
     * Atomically attempt the PENDING → PAID transition.
     * Returns true if the transition succeeded, false otherwise.
     * Must be called inside a DB::transaction() with lockForUpdate().
     *
     * @param  string $providerReference
     * @return bool
     */
    public function transitionToPaid(string $providerReference): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            return false;
        }

        $this->status             = self::STATUS_PAID;
        $this->provider_reference = $providerReference;
        $this->paid_at            = now();
        $this->save();

        return true;
    }

    // ---------------------------------------------------------------------------
    // Relationships
    // ---------------------------------------------------------------------------

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(PreRegModel::class, 'event_id');
    }

    public function fulfillmentLog()
    {
        return $this->hasOne(FulfillmentLog::class);
    }
}
