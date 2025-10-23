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
        return $this->belongsTo(RoomModel::class, 'room_id', 'id')->select('id','name','url');
    }

    function owner()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select('id','firstname','lastname');
    }

    function users()
    {
        return $this->belongsToMany(PreRegUserModel::class, 'prereg_id', 'id');
    }

}
