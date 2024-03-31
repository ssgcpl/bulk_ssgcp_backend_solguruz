<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealerRetailer extends Model
{
     use HasFactory;
    protected $table = 'dealer_retailers';
    protected $fillable = ['dealer_id','retailer_id'];

    public function retailer()
    {
        return $this->belongsTo(User::class,'retailer_id' );
    }

    public function dealer()
    {
        return $this->belongsTo(User::class,'dealer_id' );
    }

}
