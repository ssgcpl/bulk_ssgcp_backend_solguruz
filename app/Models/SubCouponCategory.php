<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCouponCategory extends Model
{
    use HasFactory;

    protected $table = "sub_coupon_category";

    protected $fillable = ['sub_coupon_id','category_id'];

  	public function category()
    {
        return $this->belongsTo(Category::class,'category_id' );
    }
}
