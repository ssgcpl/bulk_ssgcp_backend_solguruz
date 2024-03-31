<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
 	protected $fillable =['product_id','pof_no','pof_qty','ecm_no','ecm_qty'];

 		//cover images
    public function stock_gro(){
           return $this->hasMany('App\Models\StockGro','stock_id');
    }

}
