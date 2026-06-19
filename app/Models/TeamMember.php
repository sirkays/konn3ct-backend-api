<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'team_owner_id',
        'user_id',
        'email',
        'role',
        'status',
        'activation_token',
        'activated_at',
    ];

    public function teamOwner()
    {
        return $this->belongsTo(User::class, 'team_owner_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
