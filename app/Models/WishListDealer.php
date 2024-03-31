<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WishListDealer extends Model
{
    
    protected $table = 'wish_list_dealers';

    protected $fillable = ['wish_list_id','dealer_id','user_id'];
   
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function wish_list()
    {
        return $this->belongsTo(WishList::class,'wish_list_id');
    }

    public function dealer()
    {
        return $this->belongsTo(User::class,'dealer_id');
    }

}
