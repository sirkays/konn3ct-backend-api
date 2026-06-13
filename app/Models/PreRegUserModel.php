<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreRegUserModel extends Model
{
    use HasFactory;

    protected $table = "prereg_users";
    protected $guarded = [];

    public function preReg()
    {
        return $this->belongsTo(PreRegModel::class, 'prereg_id')->select('id','title','free');
    }
}
