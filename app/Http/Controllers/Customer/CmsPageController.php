<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CmsPageController extends Controller
{
    public function about_us()
    {
    	$page_title = trans('web.about_us');
    	return view('customer.about_us',compact('page_title')); 
    }
    public function privacy_policy()
    {
    	$page_title = trans('web.privacy_policy');
    	return view('customer.privacy_policy',compact('page_title')); 
    }
    public function terms_and_condition()
    {
    	$page_title = trans('web.terms_and_condition');
    	return view('customer.terms_and_condition',compact('page_title')); 
    }
}
