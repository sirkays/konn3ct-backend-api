<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitesModel extends Model
{
    use HasFactory;

    protected $table = 'invites';
    protected $fillable = ["user_id", "type", "hostname", "roomlink", "accesscode", "title", "date", "time", "roomname", "timezone", "additional", "guest"];
}
