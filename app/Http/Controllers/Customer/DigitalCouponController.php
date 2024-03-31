<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessCategory;

class DigitalCouponController extends Controller
{
    public function index()
    {
    	$page_title = trans('web.digital_coupons');
    	return view('customer.digital_coupons',compact('page_title')); 
    }
    public function digital_coupon_detail_purchased($id)
    {
    	$page_title = trans('web.digital_coupon_detail_purchased');
    	return view('customer.digital_coupon_detail_purchased',compact('page_title','id')); 
    }
    public function digital_coupon_detail_expired($id)
    {
    	$page_title = trans('web.digital_coupon_detail_expired');
    	return view('customer.digital_coupon_detail_expired',compact('page_title','id')); 
    }
    public function latest_digital_coupons($business_category_id)
    {
        $page_title = trans('web.latest_digital_coupons');
        $business_category = BusinessCategory::find($business_category_id);
        return view('customer.latest_digital_coupons',compact('page_title','business_category')); 

    }
    public function digital_coupon_details($id)
    {
        $page_title = trans('web.digital_coupon_details');
        return view('customer.coupon_details',compact('page_title','id')); 
    }

    public function coupons_checkout()
    {
        $page_title = trans('web.coupons_checkout');
        return view('customer.coupons_checkout',compact('page_title')); 
    }
    

}
