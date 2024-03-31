<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function ticket_history()
    {
    	$page_title = trans('web.ticket_history');
    	return view('customer.ticket_history',compact('page_title'));
    }
}
