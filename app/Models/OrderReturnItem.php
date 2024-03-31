<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReturnItem extends Model
{
    use HasFactory;

    protected $table = 'order_return_items';
    
    protected $fillable = ['order_item_id','product_id','total_sale_price','return_sale_price','total_quantity','accepted_quantity','rejected_quantity','refuse_reason_id','refuse_comment','description','requested_as','order_return_id','sale_price'];

    public function product(){
    	 return $this->hasOne(Product::class,'id','product_id');
    }

    public function order_item(){
    	return $this->hasOne(OrderItem::class,'id','order_item_id');
    }

    public function order_return(){
    	 return $this->belongsTo(OrderReturn::class,'order_return_id');
    }

   


}
