<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class WishController extends Controller
{
    public function retailer_wish_list(){
    	$page_title = trans('web.wish_list');
    	return view('customer.retailer_request_wishlist',compact('page_title'));
    }

    public function request_wishlist_detail($wish_list_request_id){
    	$page_title = trans('web.wishlist_detail');
    	return view('customer.request_wishlist_detail',compact('page_title','wish_list_request_id'));
    }

    public function retailer_wish_return(){
    	$page_title = trans('web.wish_return');
    	return view('customer.retailer_wish_return',compact('page_title'));
    }

    public function retailer_wishreturn_detail($wishreturn_request_id){
        $page_title = trans('web.wishreturn_detail');
        return view('customer.request_wishreturn_detail',compact('page_title','wishreturn_request_id'));
    }

    public function wishlist(){
        $page_title = trans('web.wishlist');
        return view('customer.wishlist',compact('page_title'));
    }

    public function create_wishlist($book_id){
        $page_title = trans('web.create_wishlist');
        return view('customer.create_wishlist',compact('page_title','book_id'));
    }

    public function wish_return(){
        $page_title = trans('web.wish_return');
        return view('customer.wish_return',compact('page_title'));
    }

    public function wish_return_product($book_id){
        $page_title = trans('web.wish_return_product');
        return view('customer.wish_return_product',compact('page_title','book_id'));
    }

    public function edit_wish_return($wish_return_id){
        $page_title = trans('web.edit_wish_return');
        return view('customer.edit_wish_return',compact('page_title','wish_return_id'));
    }
}
