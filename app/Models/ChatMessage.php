<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $guarded = [];

    function replyMessage()
    {
        $this->belongsTo($this, 'reply_to', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'sender');
    }

}
