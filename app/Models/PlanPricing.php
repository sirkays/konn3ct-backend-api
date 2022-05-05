<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanPricing extends Model
{
    use HasFactory;

    protected $guarded = [];

    function plan()
    {
        return $this->belongsTo(PlanModel::class);
    }
}
