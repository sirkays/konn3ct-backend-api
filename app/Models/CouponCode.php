<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponCode extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'type', 'status', 'used_by', 'reoccuring', 'discount'];
}
