<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponMaster extends Model
{
    use HasFactory;
    protected $table = 'coupon_master';

    protected $fillable = ['coupon_id','type','name','item_type','item_name','start_date','end_date','usage_limit','quantity','discount','description','is_live','state','status','deleted_at'];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'end_date' => 'datetime',
    ];

    public function qr_codes()
    {
        return $this->hasMany('App\Models\CouponQrCode','coupon_master_id');
    }

    public function sub_coupon(){
        return $this->hasOne(SubCoupon::class, 'coupon_id','coupon_id');
  	}

}
