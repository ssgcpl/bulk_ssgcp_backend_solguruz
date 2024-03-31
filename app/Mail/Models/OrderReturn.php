<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReturn extends Model
{
    use HasFactory;

    protected $table = 'order_returns';
    protected $guarded = [];

    
    protected $casts = [
        'returned_at' => 'datetime',
    ];

    public function user(){
    	 return $this->belongsTo(User::class,'user_id','id');
    }

    //Order items
    public function order_items(){
        return $this->hasMany('App\Models\OrderReturnItem','order_return_id','id');
    }

    public function order_return_item(){
        return $this->hasOne('App\Models\OrderReturnItem','order_return_id','id');
    }
}
