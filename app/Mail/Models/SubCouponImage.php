<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCouponImage extends Model
{
    use HasFactory;
    protected $table = 'sub_coupon_images';

    protected $fillable = ['sub_coupon_id','cover_image'];
}
