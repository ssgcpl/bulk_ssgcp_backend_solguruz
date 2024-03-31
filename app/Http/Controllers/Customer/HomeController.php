<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessCategory;

class HomeController extends Controller
{
    public function index(){
    	$page_title = trans('web.home');
    	return view('customer.home',compact('page_title'));
    }

    public function search(){
        $page_title = trans('web.search');
        return view('customer.search',compact('page_title'));
    }

    public function books_list($business_category_id = null){
    	$page_title = trans('web.books');
    	$business_category = BusinessCategory::find($business_category_id);
    	return view('customer.books_list',compact('page_title','business_category'));
    }

    public function trending_books(){
    	$page_title = trans('web.trending_books');
    	return view('customer.trending_books',compact('page_title'));
    }

    public function book_detail($book_id){
    	$page_title = trans('web.home');
    	return view('customer.book_detail',compact('page_title','book_id'));
    }

    public function contact_us(){
    	$page_title = trans('web.contact_us');
    	return view('customer.contact_us',compact('page_title'));
    }
}
