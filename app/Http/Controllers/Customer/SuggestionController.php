<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function index(){
    	$page_title = trans('web.suggestions_list');
    	return view('customer.suggestion',compact('page_title'));
    }
}
