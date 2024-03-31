<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function signin(){
    	$page_title = trans('web.signin');
    	return view('customer.auth.signin',compact('page_title'));
    }
    public function signup(){
    	$page_title = trans('web.signup');
    	return view('customer.auth.signup',compact('page_title'));
    }

    public function forgot_password(){
    	$page_title = trans('web.forgot_password');
    	return view('customer.auth.forgot_password',compact('page_title'));
    }

    public function reset_password(){
        $page_title = trans('web.reset_password');
        return view('customer.auth.reset_password',compact('page_title'));
    }
}
