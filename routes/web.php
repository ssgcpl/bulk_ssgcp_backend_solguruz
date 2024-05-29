<?php

use Illuminate\Support\Facades\Route;

include 'customer.php';


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/webhookForPayu', 'HomeController@webhookForPayu');
Route::get('/webhookForCCAvenue', 'HomeController@webhookForCCAvenue');
Route::get('/fix-user-migration', 'HomeController@fixUserMigration');
Route::get('/fix-product-migration', 'HomeController@fixProductMigration');


Route::prefix('admin')->group( function() {
	Auth::routes(['verify' => true]);
});

Route::get('/testJob', 'HomeController@testJob');

Route::get('/home', 'HomeController@index');

Route::group(['prefix' => 'admin','namespace' => 'Admin'], function () {

	Route::middleware(['auth:web','verified'])->group( function() {

		//roles and permisisons
        Route::resource('roles','RoleController');
        Route::post('/roles/ajax', 'RoleController@index_ajax')->name('dt_roles');
        Route::post('/roles/status', 'RoleController@status')->name('status_roles');
        Route::resource('permissions','PermissionController')->except(['show','edit','update']);

        //Home
		    Route::get('/home', 'HomeController@index')->name('home');
         Route::post('/date_wise_dashboard_data', 'HomeController@date_wise_dashboard_data')->name('date_wise_dashboard_data');

         // Customers
        Route::resource('customers', 'CustomerController');
        Route::post('/customers/ajax', 'CustomerController@index_ajax')->name('dt_customers');
         Route::post('/customers/status', 'CustomerController@status')->name('status_customers');
         Route::post('/customers/change_usertype', 'CustomerController@is_dealer_or_retailer')->name('change_user_type');
        Route::post('/customer_orders/ajax', 'CustomerController@orders_ajax')->name('ajax_customer_orders');
        Route::post('/customers/ajax_retailers', 'CustomerController@ajax_retailers')->name('dt_retailers');
        Route::post('/customers/add_retailers', 'CustomerController@add_more_retailers')->name('add_retailers');
         Route::get('/customers/show_retailers/{id}', 'CustomerController@show_retailers')->name('show_retailers');
        Route::delete('/customers/remove_retailers/{id}', 'CustomerController@remove_retailers')->name('remove_retailers');
        Route::post('/customers/ajax_user_orders', 'CustomerController@ajax_user_orders')->name('dt_user_orders');

        // send email to customer
        Route::get('/email', 'SendEmailController@index')->name('email');
        Route::post('/customers/send_email','SendEmailController@send_email')->name('send_email');
        Route::post('/customers/template_message','SendEmailController@template_message')->name('template_message');

          // send sms to customer
        Route::get('/sms', 'SendSmsController@index')->name('sms');
        Route::post('/customers/send_sms','SendSmsController@send_sms')->name('send_sms');
        Route::post('/customers/ajax_sms', 'SendSmsController@index_ajax')->name('dt_sms_list');

        // send push notification to customer
        Route::get('/notification','SendNotificationController@index')->name('notification');
        Route::post('/customers/send_notification/','SendNotificationController@send_notification')->name('send_notification');
         Route::post('/customers/ajax_notification', 'SendNotificationController@index_ajax')->name('dt_push_notification_list');


	 	// Notifications
        Route::resource('notifications', 'NotificationController');
        Route::post('/notifications/ajax', 'NotificationController@index_ajax')->name('dt_notification');

		//Admin Profiles
        Route::get('/profile', 'ProfileController@profile')->name('profile');
        Route::post('/profile/update', 'ProfileController@update_profile')->name('update_profile');
        Route::post('/update', 'ProfileController@admin_update')->name('admin_update');
        //email update
        Route::post('/update/email','ProfileController@update_email')->name('admin.update_email');
        Route::post('/verify/email','ProfileController@verifyEmail')->name('admin.verify_updated_email');
        Route::post('/resend_email_otp','ProfileController@resend_email_otp')->name('admin.resend_email_otp');
        Route::post('resend_mobile_otp', 'ProfileController@resend_mobile_otp')->name('admin.resend_mobile_otp');


         //mobile number
        Route::post('update/mobile','ProfileController@update_mobile_number')->name('admin.update_mobile_number');
        Route::post('verify/mobile','ProfileController@verifyMobileNumber')->name('admin.verify_updated_mobile_number');

        //Master Module Management
        // Countries
        Route::resource('country', 'CountryController');
        Route::post('/country/ajax', 'CountryController@index_ajax')->name('dt_country');
        Route::post('/country/status', 'CountryController@status')->name('status_country');

        //States
        Route::resource('states', 'StateController');
        Route::post('/states/ajax', 'StateController@index_ajax')->name('dt_states');
        Route::post('/state/status', 'StateController@status')->name('status_states');
        Route::get('state/{country_id}', 'StateController@get_state')->name('state_list');

        //Cities
        Route::resource('cities', 'CityController');
        Route::post('/cities/ajax', 'CityController@index_ajax')->name('dt_cities');
        Route::post('/cities/status', 'CityController@status')->name('status_cities');
        Route::get('city/{state_id}', 'CityController@get_city')->name('city_list');

        //Postal codes
        Route::resource('post_codes', 'PostCodeController');
        Route::post('/post_codes/ajax', 'PostCodeController@index_ajax')->name('dt_post_codes');
        Route::post('/post_codes/status', 'PostCodeController@status')->name('status_post_codes');

          // Reason
        Route::resource('reasons', 'ReasonController');
        Route::post('/reasons/ajax', 'ReasonController@index_ajax')->name('dt_reason');
        Route::post('/reasons/status', 'ReasonController@status')->name('status_reason');
      
        // Settings
        Route::resource('settings','SettingController')->only(['index','create','store']);
        Route::post('settings/update/','SettingController@update')->name('settings.update');

        //Cms
        Route::resource('cms', 'CmsController');
        Route::post('/cms/status', 'CmsController@status')->name('cms_status');

        //Datewise Reports 
        Route::resource('reports','ReportController')->only(['index']);
        Route::post('/datewise_report/ajax', 'ReportController@index_ajax')->name('dt_datewise_report');
         
        //Order Reports
        Route::resource('orderreport','OrderReportController')->only(['index']);
        Route::post('/order_report/ajax', 'OrderReportController@index_ajax')->name('dt_order_report');
     
        //Business Categories
        Route::resource('business_categories','BusinessCategoryController');
        Route::post('/business_categories/ajax', 'BusinessCategoryController@index_ajax')->name('dt_business_categories');
        Route::post('/business_categories/status','BusinessCategoryController@status')->name('status_business_categories');
        Route::post('/business_categories/is_live','BusinessCategoryController@is_live')->name('is_live_business_categories');

         //Nested Categories
        Route::resource('nested_categories','NestedCategoryController');
        Route::post('/nested_categories/ajax', 'NestedCategoryController@index_ajax')->name('dt_nested_categories');
        Route::post('/nested_categories/status','NestedCategoryController@status')->name('status_nested_categories');
        Route::post('/nested_categories/is_live','NestedCategoryController@is_live')->name('is_live_nested_categories');
        Route::post('/nested_categories/get_category_childs','NestedCategoryController@get_category_childs')->name('get_category_childs');

        //Sub Admin List
        Route::resource('sub_admin','SubAdminController');
        Route::post('/sub_admin/ajax', 'SubAdminController@index_ajax')->name('dt_sub_admin');
        Route::post('/sub_admin/status','SubAdminController@status')->name('status_sub_admin');

        //Tickets
        Route::resource('tickets','TicketController');
         Route::post('/tickets/ajax', 'TicketController@index_ajax')->name('dt_tickets');
        Route::post('/tickets/status','TicketController@status')->name('ticket_status');
        Route::post('tickets/r_status','TicketController@r_status')->name('r_status');//r - resolved status
        Route::post('tickets/acknowledged_status','TicketController@acknowledged_status')->name('acknowledged_status');

        //Product management
        Route::resource('products','ProductController');
        Route::post('/products/ajax', 'ProductController@index_ajax')->name('dt_products');
        Route::post('/products/status','ProductController@status')->name('status_product');
        Route::post('/products/is_live', 'ProductController@is_live')->name('is_live_product');
        Route::post('/products/remove_cover_image', 'ProductController@remove_cover_image')->name('remove_cover_image');


        //Stock management
        Route::resource('stocks','StockController');
        Route::post('/stocks/ajax', 'StockController@index_ajax')->name('dt_stocks');
        Route::get('/view_gro', 'StockController@view_gro')->name('view_gro_detail');
        Route::post('/view_gro_ajax/', 'StockController@index_gro_ajax')->name('dt_stocks_gro');

        //Stock Transfer
        Route::resource('stocks_transfer','StockTransferController');
        Route::post('/stocks_transfer/ajax', 'StockTransferController@index_ajax')->name('dt_stocks_transfer');

        //Stock Summary
        Route::get('/stock_summary','StockController@get_stock_summary')->name('stock_summary');
         //Stock Report
        Route::resource('stock_report','StockReportController');
        Route::post('/stock_report/ajax', 'StockReportController@index_ajax')->name('dt_stock_report');
        Route::post('/stock_report/download_stock_report_pdf','StockReportController@download_stock_report_pdf')->name('download_stock_report_pdf');


        //Wish management
        Route::resource('wish_list','WishListController');
        Route::post('/wish_list/ajax', 'WishListController@index_ajax')->name('dt_wishlist');
        Route::post('/wish_list/detail_ajax', 'WishListController@index_ajax_detail')->name('dt_wishlist_detail');
         Route::get('/wish_list/view_dealer_detail/{id}', 'WishListController@view_dealer_detail')->name('view_dealer_detail');
        Route::post('/wish_list/dealer_ajax_detail', 'WishListController@dealer_ajax_detail')->name('dt_dealer_ajax_detail');
        Route::get('/wish_list/view_customer_detail/{id}', 'WishListController@view_customer_detail')->name('view_customer_detail');

        Route::resource('wish_return','WishReturnController');
        Route::post('/wish_return/ajax', 'WishReturnController@index_ajax')->name('dt_wishreturn');
        Route::post('/wish_return/detail_ajax', 'WishReturnController@index_ajax_detail')->name('dt_wishreturn_detail');
         Route::get('/wish_return/view_customer_details/{id}', 'WishReturnController@view_customer_details')->name('view_customer_details');


        Route::resource('ssgc_suggestions','SsgcSuggestionController');
        Route::post('/ssgc_suggestions/ajax', 'SsgcSuggestionController@index_ajax')->name('dt_ssgc_suggestions');
        Route::post('/ssgc_suggestions/detail_ajax', 'SsgcSuggestionController@index_ajax_detail')->name('dt_ssgc_suggestions_detail');
         Route::get('/ssgc_suggestions/view_suggestion_customers/{id}', 'SsgcSuggestionController@view_suggestion_customers')->name('view_suggestion_customers');

        Route::resource('wish_suggestions','WishSuggestionController');
        Route::post('/wish_suggestions/ajax', 'WishSuggestionController@index_ajax')->name('dt_wish_suggestions');
        Route::post('/wish_suggestions/detail_ajax', 'WishSuggestionController@index_ajax_detail')->name('dt_wish_suggestions_detail');
        Route::get('/wish_suggestion_images', 'WishSuggestionController@view_wish_suggestion_images')->name('view_wish_suggestion_images');
         Route::post('/wish_suggestion_images/detail_ajax', 'WishSuggestionController@dt_wish_suggestion_images')->name('dt_wish_suggestion_images');

        Route::resource('product_barcodes','ProductBarcodeController');
        Route::post('/product_barcodes/ajax', 'ProductBarcodeController@index_ajax')->name('dt_product_barcodes');
        Route::get('/product_barcodes/get_product_detail/{id}','ProductBarcodeController@get_product_detail')->name('get_product_detail');
         Route::post('/product_barcodes/barcode_ajax', 'ProductBarcodeController@index_ajax_barcode')->name('dt_barcodes');
         Route::post('/product_barcodes/download_pdf','ProductBarcodeController@download_pdf')->name('download_pdf');
         Route::post('/product_barcodes/download_barcode_csv','ProductBarcodeController@download_barcode_csv')->name('download_barcode_csv');
         Route::post('/product_barcodes/reset_download','ProductBarcodeController@reset_download')->name('reset_download');


        //Coupon Management
        Route::resource('coupons','CouponController');
        Route::post('/coupons/ajax', 'CouponController@index_ajax')->name('dt_coupons');
         Route::post('/coupons/detail_ajax', 'CouponController@index_ajax_detail')->name('dt_coupon_detail');
         Route::get('/coupon_master_list/{item_type}', 'CouponController@coupon_master_list')->name('coupon_master_list');
         Route::get('/coupon_master_detail/{id}', 'CouponController@coupon_master_detail')->name('coupon_master_detail');
         Route::post('/sub_coupons/status','CouponController@status')->name('status_sub_coupon');
         Route::post('/sub_coupons/remove_coupon_cover_image', 'CouponController@remove_coupon_cover_image')->name('remove_coupon_cover_image');
         Route::post('/coupons/qr_code', 'CouponController@qr_coupon_ajax')->name('qr_coupon_ajax');


         Route::resource('orders','OrderController');
         Route::post('/orders/ajax', 'OrderController@index_ajax')->name('dt_orders');

         Route::post('/orders/order_items', 'OrderController@ajax_order_items')->name('dt_order_items');
         Route::post('/orders/update_order_status','OrderController@update_order_status')->name('update_order_status');
         Route::post('/orders/update_supplied_qty','OrderController@update_supplied_qty')->name('update_supplied_qty');
         Route::post('/orders/add_item','OrderController@add_more_item')->name('add_more_item');
         Route::post('/orders/add_order_item','OrderController@add_order_item')->name('add_order_item');
         Route::post('/orders/update_order_detail','OrderController@update_order_detail')->name('update_order_detail');
         Route::post('/orders/update_shipping_address','OrderController@update_shipping_address')->name('update_shipping_address');
         Route::post('/orders/update_billing_address','OrderController@update_billing_address')->name('update_billing_address');
         Route::post('/orders/notify_user','OrderController@notify_user')->name('notify_user');
         Route::post('/orders/update_order_summary','OrderController@update_order_summary')->name('update_order_summary');
         Route::post('/orders/notify_order_update','OrderController@notify_order_update')->name('notify_order_update');
          Route::post('/orders/download_order_pdf','OrderController@download_order_pdf')->name('download_order_pdf');
          Route::get('/order/view_product_details/{id}','OrderController@view_product_details')->name('view_product_details');
          Route::post('/order/bulk_mark_printed','OrderController@bulk_mark_as_printed')->name('bulk_mark_as_printed');
          Route::post('/order/update_refund_details','OrderController@update_refund_details')->name('update_refund_details');

         Route::resource('coupon_orders','CouponOrderController');
         Route::post('/coupon_orders/order_items', 'CouponOrderController@ajax_order_items')->name('dt_coupon_order_items');

         Route::post('/coupon_orders/order_items/qr_code', 'CouponOrderController@ajax_order_items_qr_codes')->name('dt_coupon_order_items_qr_codes');

         Route::resource('pending_orders','PendingOrderController');
         Route::get('pending_orders/coupon_edit/{id}','PendingOrderController@coupon_edit')->name('coupon_edit');
         Route::post('/pending_orders/ajax', 'PendingOrderController@pending_orders_ajax')->name('dt_pending_orders');
         Route::post('/markOrderSuccess', 'PendingOrderController@markOrderSuccess')->name('mark_order');
         Route::post('/markOrderFailure', 'PendingOrderController@markOrderFailure')->name('mark_order_failed');



         Route::resource('order_return','OrderReturnController');
         Route::post('/order_return/ajax', 'OrderReturnController@index_ajax')->name('dt_order_return');
         Route::post('/order_return/order_return_items', 'OrderReturnController@ajax_order_return_items')->name('dt_order_return_items');
         Route::post('/order_return/update_order_return_status','OrderReturnController@update_order_return_status')->name('update_order_return_status');
         Route::post('/order_return/update_rejected_qty','OrderReturnController@update_rejected_qty')->name('update_rejected_qty');
         Route::post('/order_return/notify_user_order_return','OrderReturnController@notify_user_order_return')->name('notify_user_order_return');
          Route::post('/order_returns/add_return_item','OrderReturnController@add_more_return_item')->name('add_more_return_item');
         Route::post('/order_returns/add_order_return_item','OrderReturnController@add_order_return_item')->name('add_order_return_item');
         Route::post('/order_returns/update_order_return_summary','OrderReturnController@update_order_return_summary')->name('update_order_return_summary');
          Route::post('/order_returns/notify_order_return_update','OrderReturnController@notify_order_return_update')->name('notify_order_return_update');
          Route::post('/order_returns/verify_product_barcodes','OrderReturnController@verify_product_barcodes')->name('verify_product_barcodes');
          Route::post('/order_returns/verify_qty','OrderReturnController@verify_qty')->name('verify_qty');
           Route::post('/order_returns/check_barcodes','OrderReturnController@check_barcodes')->name('check_barcodes');
             Route::post('/order_returns/product_list_ajax','OrderReturnController@product_list_ajax')->name('dt_product_list');
          Route::post('/order_returns/create_order_returns_item','OrderReturnController@create_order_returns_item')->name('create_order_returns_item');
          Route::post('/order_returns/download_order_return_pdf','OrderReturnController@download_order_return_pdf')->name('download_order_return_pdf');
          Route::get('/order_returns/view_product_detail/{id}','OrderReturnController@view_product_detail')->name('view_product_detail');
           Route::post('/order_returns/delete_verified_product/{id}','OrderReturnController@delete_verified_product')->name('delete_verified_product');


	});
});

//Clear Cache
Route::get('clear-cache/all', 'CacheController@clear_cache');
