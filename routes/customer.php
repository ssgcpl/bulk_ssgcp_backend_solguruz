<?php

use App\Http\Controllers\Customer\Controller;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['namespace'=>'Customer'] ,function() {
  
  // Customer Home Page
  Route::get('/', 'HomeController@index')->name('web_home');
  Route::get('search', 'HomeController@search')->name('search');

  //books list
  Route::get('books/{cat_id?}', 'HomeController@books_list')->name('books_list');
  Route::get('trending_books', 'HomeController@trending_books')->name('trending_books');
  Route::get('book_detail/{book_id?}', 'HomeController@book_detail')->name('book_detail');
  
   // Login/SignUp
  Route::get('signin', 'AuthController@signin')->name('signin');
  Route::get('signup', 'AuthController@signup')->name('signup');
  Route::get('forgot_password', 'AuthController@forgot_password')->name('forgot_password');
  Route::get('reset_password', 'AuthController@reset_password')->name('reset_password');
  
  Route::get('profile','ProfileController@index')->name('customer.profile');
  //Refer & Earn
  Route::get('refer_earn','ProfileController@refer_earn')->name('refer_earn'); 
  

  //Payment for Orders  i.e. Books, EBooks, Video Lecture, Courses, Quiz
  Route::get('order_payment/{cart_id}/{user_id}/{address_id}/{gateway?}','PaymentController@order_payment')->name('order_payment');
  Route::post('payu_callback_order/{user_id}/{cart_id}', 'PaymentController@payu_callback_order')->name('payu_callback_order');
  Route::post('ccavenue_callback_order/{user_id}/{cart_id}', 'PaymentController@ccavenue_callback_order')->name('ccavenue_callback_order');
  Route::get('order_payment_success', 'PaymentController@order_payment_success')->name('order_payment_success');
  Route::get('order_payment_error', 'PaymentController@order_payment_error')->name('order_payment_error');
  Route::get('order_error', 'PaymentController@order_error')->name('order_error');

  //Cart for coupon and books both
  Route::get('my_cart','OrderController@my_cart')->name('my_cart');

  //checkout & orders for books
  Route::get('books_checkout','OrderController@books_checkout')->name('books_checkout');
  Route::get('my_orders','OrderController@my_orders')->name('my_orders');
  Route::get('order_details/{order_id}','OrderController@order_details')->name('order_details');
  Route::get('order_book_view/{order_item_id}','OrderController@order_book_view')->name('order_book_view');

  //Digital Coupons
  Route::get('digital_coupons','DigitalCouponController@index')->name('digital_coupons_list');
  Route::get('digital_coupon_detail_purchased/{id}','DigitalCouponController@digital_coupon_detail_purchased')->name('digital_coupon_detail_purchased');
   Route::get('digital_coupon_detail_expired/{id}','DigitalCouponController@digital_coupon_detail_expired')->name('digital_coupon_detail_expired');

   // WISH LIST  
   Route::get('retailer_wish_list','WishController@retailer_wish_list')->name('retailer_wish_list');
   Route::get('retailer_wishlist_detail/{wish_list_request_id}','WishController@request_wishlist_detail')->name('request_wishlist_detail');
   Route::get('wishlist','WishController@wishlist')->name('wishlist');
   Route::get('create_wishlist/{book_id}','WishController@create_wishlist')->name('create_wishlist');

   // Wish Return
   Route::get('retailer_wish_return','WishController@retailer_wish_return')->name('retailer_wish_return');
   Route::get('retailer_wishreturn_detail/{wishreturn_request_id}','WishController@retailer_wishreturn_detail')->name('request_wishreturn_detail');
   Route::get('wish_return','WishController@wish_return')->name('wish_return');
   Route::get('wish_return_product/{book_id}','WishController@wish_return_product')->name('wish_return_product');
   Route::get('edit_wish_return/{wish_return_id}','WishController@edit_wish_return')->name('edit_wish_return');

   // Return Product
   Route::get('return_products_list','ReturnProductController@index')->name('return_products_list');
   Route::get('return_placed/{id}','ReturnProductController@return_placed')->name('return_placed');
   Route::get('return_dispatched/{id}','ReturnProductController@return_dispatched')->name('return_dispatched');
   Route::get('return_accepted/{id}','ReturnProductController@return_accepted')->name('return_accepted');
   Route::get('return_rejected/{id}','ReturnProductController@return_rejected')->name('return_rejected');
   Route::get('return_cart','ReturnProductController@return_cart')->name('return_cart');
   Route::get('make_my_return','ReturnProductController@make_my_return')->name('make_my_return');

   // Suggestion
   Route::get('suggestion','SuggestionController@index')->name('suggestions'); 

   //Latest Digital Coupons
   Route::get('latest_digital_coupons/{cat_id?}','DigitalCouponController@latest_digital_coupons')->name('latest_digital_coupons');
   Route::get('digital_coupon_details/{id}','DigitalCouponController@digital_coupon_details')->name('digital_coupon_details');

   //coupon checkout
   Route::get('coupons_checkout','DigitalCouponController@coupons_checkout')->name('coupons_checkout');


   //Contact Us 
   Route::get('contact_us','HomeController@contact_us')->name('contact_us');
   Route::get('ticket_history','TicketController@ticket_history')->name('ticket_history');


   //CMS PAGES 
   Route::get('about_us','CmsPageController@about_us')->name('about_us');
   Route::get('privacy_policy','CmsPageController@privacy_policy')->name('privacy_policy');
   Route::get('terms_and_condition','CmsPageController@terms_and_condition')->name('terms_and_condition');

   // Notification
   Route::get('notifications', 'NotificationController@index')->name('notifications');
});




