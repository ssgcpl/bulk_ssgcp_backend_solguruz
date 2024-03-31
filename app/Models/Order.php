<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $guarded = [];

    public function user(){
    	return $this->belongsTo(User::class,'user_id');
    }

    //order items
    public function order_items(){
        return $this->hasMany('App\Models\OrderItem','order_id');
    }

    //order items
    public function order_item(){
        return $this->hasOne('App\Models\OrderItem','order_id');
    }

    //billing address
    public function billing_address(){
        return $this->hasOne('App\Models\OrderAddress','order_id')->where('is_billing','1');
    }

    //shipping address
    public function shipping_address(){
        return $this->hasOne('App\Models\OrderAddress','order_id')->where('is_shipping','1');
    }

    // Payments
    public function payments(){
        return $this->hasMany('App\Models\Payment','order_id');
    }
}
