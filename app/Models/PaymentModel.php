<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentModel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment';

    function planDetails()
    {
        return $this->belongsTo(PlanModel::class, "plan", "id");
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'plan', 'gateway', 'status', 'amount', 'date', 'reference', 'gateway_reference', 'gateway_response', 'duration', 'currency', 'type'
    ];

    protected $hidden =[
        'gateway_response'
    ];
}
