<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $fillable = ['user_id','payment_for','order_id','amount','payment_type','tran_ref','api_response','status', 'refund_amount','refund_tran_ref','refund_api_response','refund_status'];

    public function user()
    {
       return $this->belongsTo(User::class,'user_id' );
    }

    public function order()
    {
       return $this->belongsTo(Order::class,'order_id' );
    }
}