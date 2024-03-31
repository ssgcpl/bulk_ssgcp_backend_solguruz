<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    /*public function reason(){
        return $this->belongsTo('App\Models\Reason');
    }*/

     //reason
    public function reason(){
        return $this->hasOne('App\Models\Reason','id');
    }

  
}
