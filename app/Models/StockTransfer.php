<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    use HasFactory;
    protected $table = 'stock_transfer';

    protected $fillable = ['product_id','gto_in_no','gto_in_qty','gto_out_no','gto_out_qty','scrap_no','scrap_qty'];
}
