<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreRegModel extends Model
{
    use HasFactory;

    protected $table = 'prereg';

    protected $guarded = [];

    protected $appends = ['event_url'];

    public function getEventUrlAttribute()
    {
        return $this->reference
            ? 'https://www.konn3ct.com/event/' . $this->reference
            : null;
    }

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
