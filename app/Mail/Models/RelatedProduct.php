<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelatedProduct extends Model
{
    use HasFactory;

    protected $table = "related_products";
    protected $fillable = ['user_id','product_id','related_product_id'];

     public function product()
    {
        return $this->hasOne(Product::class,'id','product_id');
    }
}
