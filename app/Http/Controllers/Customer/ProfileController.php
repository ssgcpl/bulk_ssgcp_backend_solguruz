<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(){
    	$page_title = trans('web.profile');
    	return view('customer.profile',compact('page_title'));
    }
    public function refer_earn(){
    	$page_title = trans('web.refer_earn');

       /* $shareComponent = \Share::page(
                '091737'
        )->whatsapp()->linkedin();*/
    	return view('customer.refer_earn',compact('page_title'));
    }
  
}
