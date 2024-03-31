<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBarcode extends Model
{
    use HasFactory;

    protected $table = 'product_barcodes';
    protected $fillable = ['product_id','barcode_value','barcode_image'];

    public function product(){
    	 return $this->belongsTo(Product::class,'product_id');
    }
}
