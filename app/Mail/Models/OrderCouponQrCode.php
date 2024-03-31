<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCouponQrCode extends Model
{
	protected $table = 'order_coupon_qr_codes';
    protected $guarded = [];

    /**
     * @return mixed
     */
   	public function order_item(){
    	 return $this->belongsTo(OrderItem::class,'order_item_id');
    }

    public function coupon_qr_code(){
    	 return $this->belongsTo(CouponQrCode::class,'coupon_qr_code_id');
    }

}
