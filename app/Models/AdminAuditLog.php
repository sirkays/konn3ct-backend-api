<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Append-only admin audit log record.
 *
 * Application code must only INSERT through AdminAuditService::record().
 * Never update or delete rows from this model.
 *
 * @property int $id
 * @property string $event_code
 * @property string $priority
 * @property int|null $actor_admin_id
 * @property int|null $target_user_id
 * @property string|null $reason
 * @property string $correlation_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property array|null $metadata
 * @property \Carbon\Carbon $created_at
 */
class AdminAuditLog extends Model
{
    public $timestamps = false;

    protected $table = 'admin_audit_logs';

    /**
     * The model has no updated_at — audit records are append-only.
     */
    const UPDATED_AT = null;

    protected $fillable = [
        'event_code',
        'priority',
        'actor_admin_id',
        'target_user_id',
        'reason',
        'correlation_id',
        'ip_address',
        'user_agent',
        'metadata',
        'created_at',
    ];

    protected $casts = [
        'metadata'   => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Prevent accidental updates to audit records.
     */
    public function save(array $options = [])
    {
        if ($this->exists) {
            throw new \LogicException('Audit log records are immutable and may not be updated.');
        }
        return parent::save($options);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_admin_id', 'id');
    }

    public function target()
    {
        return $this->belongsTo(User::class, 'target_user_id', 'id');
    }
}
