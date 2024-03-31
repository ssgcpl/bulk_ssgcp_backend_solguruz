<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WishList extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'wish_list';

    protected $fillable = ['user_id','product_id','user_type','wish_product_qty'];
   
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function dealers()
    {
        return $this->hasMany(WishListDealer::class,'wish_list_id','id');
    }

}
