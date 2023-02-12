<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    function user()
    {
        return $this->belongsTo(User::class)->select(['firstname', 'lastname', 'id']);
    }
}
