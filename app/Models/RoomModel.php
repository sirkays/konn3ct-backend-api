<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomModel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'room';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name', 'url', 'password_attendee', 'password_moderator', 'welcome_message', 'dial_number', 'logout_url', 'max_participants', 'duration', 'muj', 'dpuc', 'dprc', 'ewma', 'dum', 'dsn', 'default_room', 'prereg'
    ];

    function owner()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    function prereg_model()
    {
        return $this->hasOne(PreRegModel::class, 'reference', 'prereg');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password_moderator',
    ];
}
