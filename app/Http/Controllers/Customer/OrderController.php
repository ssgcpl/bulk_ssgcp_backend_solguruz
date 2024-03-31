<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderItem;

class OrderController extends Controller
{
    
    public function my_cart()
    {
        $page_title = trans('web.my_cart');
        return view('customer.my_cart',compact('page_title')); 
    }

    public function books_checkout()
    {
        $page_title = trans('web.books_checkout');
        return view('customer.books_checkout',compact('page_title')); 
    }

    public function my_orders()
    {
        $page_title = trans('web.my_orders');
        return view('customer.my_orders',compact('page_title')); 
    }

    public function order_details($order_id)
    {
        $page_title = trans('web.order_details');
        return view('customer.order_details',compact('page_title','order_id')); 
    }

    public function order_book_view($order_item_id)
    {
        $page_title = trans('web.order_book_view');
        $book_id = OrderItem::find($order_item_id)->product_id;
        $ordered_quantity = OrderItem::find($order_item_id)->supplied_quantity;
        return view('customer.order_book_view',compact('page_title','order_item_id', 'book_id', 'ordered_quantity')); 
    }
}
