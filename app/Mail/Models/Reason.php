<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reason extends Model
{
    use HasFactory;
    protected $guarded = [];

    const CUSTOMER_TICKET_TYPE         = 'customer_ticket';
    const CUSTOMER_CANCEL_ORDER_TYPE   = 'customer_order_cancel';

}
