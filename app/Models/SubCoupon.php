<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCoupon extends Model
{
    use HasFactory;
    protected $table = 'sub_coupons';

    protected $fillable = ['coupon_master_id','coupon_id','mrp','sale_price','description','available_quantity','state','image','status','is_deleted','business_category_id'];

    public function coupon(){
        return $this->hasOne(CouponMaster::class, 'id','coupon_master_id');
  	}

    public function business_category(){
        return $this->hasOne(BusinessCategory::class, 'id','business_category_id');
    }
     //categories
     public function categories(){
        return $this->hasMany('App\Models\SubCouponCategory','sub_coupon_id');
    }

    //cover images
    public function cover_images(){
        return $this->hasMany('App\Models\SubCouponImage','sub_coupon_id');
    }

    public function get_price($user){

      //check for user type and user specific discount given by admin for each user
      $user_discount = 0;
      if($user)
      {
        if($user->user_discount != NULL)
        {
          $user_discount = ($this->sale_price * $user->user_discount) / 100;
        }
      }
      $sale_price = $this->sale_price - $user_discount;
      //return round($sale_price);
      return $sale_price;
      
    }

    public function get_name(){

      return $this->coupon->name; 
    }

}
