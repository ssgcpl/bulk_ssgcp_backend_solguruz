<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferHistory extends Model
{
    protected $table = 'referral_history';

    protected $fillable = ['customer_id', 'referrer_id', 'points','point_status','status','refunded','order_id','wishlist_id','wish_return_id'];

    /**
     * @return mixed
     */


    //Customer
    public function customer()
    {
      return $this->hasOne('App\Models\User','id','referrer_id');
    }


}