<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReturnProductController extends Controller
{
 	public function index(){
 		$page_title = trans('web.return_product_list');
 		return view('customer.return_products',compact('page_title'));
 	}

 	public function return_placed($id){
 		$page_title = trans('web.return_placed');
 		return view('customer.return_product',compact('page_title','id'));
 	}
 	public function return_accepted($id){
 		$page_title = trans('web.return_accepted');
 		return view('customer.return_accepted',compact('page_title','id'));
 	}
 	public function return_rejected($id){
 		$page_title = trans('web.return_rejected');
 		return view('customer.return_rejected',compact('page_title','id'));
 	}
 	public function return_dispatched($id){
 		$page_title = trans('web.return_dispatched');
 		return view('customer.return_dispatched',compact('page_title','id'));
 	}

 	public function return_cart(){
 		$page_title = trans('web.return_cart');
 		return view('customer.return_cart',compact('page_title'));
 	}

 	public function make_my_return(){
 		$page_title = trans('web.make_my_return');
 		return view('customer.make_my_return',compact('page_title'));
 	}
 	
}
