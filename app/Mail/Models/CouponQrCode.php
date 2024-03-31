<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponQrCode extends Model
{
    use HasFactory;
    protected $table = 'coupon_qr_codes';
    protected $fillable = ['coupon_master_id','qr_code_value','qr_code','state'];

    public function coupon_master()
    {
        return $this->belongsTo(CouponMaster::class,'coupon_master_id');
    }

    public function order_coupon_qr_code()
    {
        return $this->hasOne(OrderCouponQrCode::class,'coupon_qr_code_id','id');
    }
}
