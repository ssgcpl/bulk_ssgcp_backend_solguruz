<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockGro extends Model
{
    use HasFactory;
    protected $table ='stock_gro';
    protected $fillable =['stock_id','gro_no','gro_qty'];
}
