<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WishReturn extends Model
{
    use HasFactory, SoftDeletes;

    protected $table ='wish_return';

    protected $fillable = ['user_id','product_id','dealer_id','wish_return_qty','user_type','description'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function dealer()
    {
        return $this->belongsTo(User::class,'dealer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
