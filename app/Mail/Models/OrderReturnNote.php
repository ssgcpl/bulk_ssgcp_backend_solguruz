<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReturnNote extends Model
{
    use HasFactory;
    protected $table= 'order_return_notes';
    protected $guarded = [];
}
