<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingsModel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meetings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'meeting_id', 'name', 'email', 'password_attendee', 'status', 'identifier', 'keyword'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password_attendee',
        'updated_at'
    ];


    function room()
    {
        return $this->belongsTo(RoomModel::class, 'meeting_id');
    }
    function roomInfo()
    {
        return $this->belongsTo(RoomModel::class, 'meeting_id')->select("id","name");
    }
}
