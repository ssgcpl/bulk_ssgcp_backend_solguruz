<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';
    protected $guarded = [];


    public function product(){
    	 return $this->belongsTo(Product::class,'product_id');
    }

    public function coupon(){
    	 return $this->belongsTo(SubCoupon::class,'coupon_id');
    }

    public function coupon_master(){
         return $this->belongsTo(CouponMaster::class,'coupon_id');
    }
    public function order(){
    	return $this->belongsTo(Order::class,'order_id');
    }

    //order coupon qr codes
    public function order_coupon_qr_codes(){
        return $this->hasMany('App\Models\OrderCouponQrCode','order_item_id');
    }
}
