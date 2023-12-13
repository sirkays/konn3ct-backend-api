<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    
    protected $guarded = [];

    function room()
    {
        return $this->belongsTo(RoomModel::class);
    }

    function owner()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
