<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreRegModel extends Model
{
    use HasFactory;

    protected $table = 'prereg';

    protected $fillable = ['user_id', 'room_id', 'reference', 'title', 'host_name', 'date', 'time', 'timezone', 'about', 'status', 'logo', 'reminder'];
}
