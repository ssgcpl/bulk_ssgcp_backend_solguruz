<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBarcodeCSVFile extends Model
{
    use HasFactory;
    protected $table = "product_barcodes_csv_files";
    protected $fillable = ['product_id','is_requested','is_completed','filename'];
}
