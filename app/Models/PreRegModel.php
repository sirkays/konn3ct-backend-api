<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreRegModel extends Model
{
    use HasFactory;

    protected $table = 'prereg';

    protected $guarded = [];

    function room()
    {
        return $this->belongsTo(RoomModel::class, 'room_id', 'id');
    }

}
