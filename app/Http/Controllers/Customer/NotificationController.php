<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(){
    	$page_title = trans('web.notifications');
    	return view('customer.notifications',compact('page_title'));
    }
}
