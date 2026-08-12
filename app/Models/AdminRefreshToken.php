<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminRefreshToken extends Model
{
    use HasFactory;

    protected $table = 'admin_refresh_tokens';

    protected $fillable = [
        'user_id',
        'token_id',
        'expires_at',
        'revoked_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
