<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//CUSTOMER
Route::prefix('customer')->group( function() {

    // LOGIN / SIGNUP
    Route::get('country_list', 'Api\Customer\Auth\AuthController@country_list');
    Route::post('signup', 'Api\Customer\Auth\AuthController@signup');
    Route::post('verify_otp', 'Api\Customer\Auth\AuthController@verify_otp');
    Route::post('resend_otp', 'Api\Customer\Auth\AuthController@resend_otp');
    Route::post('verify_email_otp', 'Api\Customer\Auth\AuthController@verify_email_otp');
    Route::post('resend_email_otp', 'Api\Customer\Auth\AuthController@resend_email_otp');
    Route::post('login_with_otp', 'Api\Customer\Auth\AuthController@login_with_otp');
    Route::post('login_with_password', 'Api\Customer\Auth\AuthController@login_with_password');
    // Route::post('socialLogin', 'Api\Customer\Auth\AuthController@socialLogin');
    // Route::get('email/verify/{id}', 'Api\Customer\Auth\VerificationController@verify')->name('api.verification.verify');
    Route::post('forgot_password', 'Api\Customer\Auth\AuthController@forgot_password');
    Route::post('forgot_password_verify_otp', 'Api\Customer\Auth\AuthController@forgot_password_verify_otp');
    Route::post('forgot_password_resend_otp', 'Api\Customer\Auth\AuthController@forgot_password_resend_otp');
    Route::post('reset_password', 'Api\Customer\Auth\AuthController@reset_password');

    // //Test
    // Route::post('send_sms', 'Api\Customer\TestController@send_sms');

    //STATE CITY PINCODE
    Route::get('states', 'Api\Customer\StateController@index');
    Route::get('cities/{state_id?}', 'Api\Customer\CityController@index');
    Route::get('postcodes/{city_id?}', 'Api\Customer\CityController@postcodes');
    Route::get('app_version', 'Api\Customer\HomeController@app_version');

    // CMS
    Route::get('cms','Api\Customer\CmsController@cms_page');
    Route::get('terms_and_conditions','Api\Customer\CmsController@customer_terms_conditions_url')->name('cms.customer_terms');
    Route::get('about_us','Api\Customer\CmsController@about_us_url')->name('cms.about_us');
    Route::get('privacy_policy','Api\Customer\CmsController@privacy_policy_url')->name('cms.privacy_policy');
    Route::get('refer_and_earn','Api\Customer\CmsController@refer_and_earn')->name('cms.refer_and_earn');

    //Books
    Route::get('books_list', 'Api\Customer\ProductController@books_list');
    Route::post('book_details', 'Api\Customer\ProductController@book_details');


    //Home
    Route::post('home/home_search', 'Api\Customer\HomeController@home_search');
    Route::get('home/trending_book_list', 'Api\Customer\HomeController@trending_book_list');
    Route::get('home/business_category_section_data', 'Api\Customer\HomeController@business_category_section_data');
    
    //Ticket
    Route::get('reason_list','Api\Customer\TicketController@reason_list');
    Route::post('send_ticket','Api\Customer\TicketController@send_ticket');
       

    Route::middleware(['auth:api'])->group( function () {
        Route::post('logout', 'Api\Customer\Auth\AuthController@logout');
        Route::post('update_device_token', 'Api\Customer\Auth\AuthController@update_device_token');

        // PROFILE
        Route::get('profile', 'Api\Customer\ProfileController@profile');
        Route::post('profile/update_personal_details', 'Api\Customer\ProfileController@update_personal_details');
        Route::post('profile/update_image', 'Api\Customer\ProfileController@update_image');
        Route::post('profile/update_company_images', 'Api\Customer\ProfileController@update_company_images');
        Route::post('profile/update/mobile', 'Api\Customer\ProfileController@update_mobile_number');
        Route::post('profile/verify/mobile', 'Api\Customer\ProfileController@verifyMobileNumber');
        Route::post('profile/update/email', 'Api\Customer\ProfileController@update_email');
        Route::post('profile/verify/email', 'Api\Customer\ProfileController@verify_email');
        Route::post('profile/resend_mobile_otp', 'Api\Customer\ProfileController@resend_mobile_otp');
        Route::post('profile/resend_email_otp', 'Api\Customer\ProfileController@resend_email_otp');
        Route::post('profile/earn_accounts', 'Api\Customer\ProfileController@earn_accounts');
        Route::post('change_password', 'Api\Customer\ProfileController@change_password');

        //ADDRESS
        Route::get('addresses', 'Api\Customer\AddressController@index');
        Route::post('address/add', 'Api\Customer\AddressController@store');
        Route::post('address/edit', 'Api\Customer\AddressController@edit');
        Route::post('address/update', 'Api\Customer\AddressController@update');
        Route::post('address/delete', 'Api\Customer\AddressController@delete');

        // TICKET
        Route::get('ticket_list','Api\Customer\TicketController@index');
        Route::get('ticket/{id}','Api\Customer\TicketController@detail');

        //Notification
        Route::get('notification_list', 'Api\Customer\NotificationController@notification_list');
        Route::post('delete_notification', 'Api\Customer\NotificationController@delete_notification');
        Route::get('clear_all_notifications', 'Api\Customer\NotificationController@clear_all_notifications');
        Route::get('notification_count', 'Api\Customer\NotificationController@unreadNotifCount');

        // Cart & Checkout (For Physical Books)
        Route::post('books/add_to_cart', 'Api\Customer\OrderController@add_to_cart');
        Route::post('books/update_quantity', 'Api\Customer\OrderController@update_quantity');
        Route::get('books/my_cart', 'Api\Customer\OrderController@my_cart');
        Route::post('books/update_cart_summary', 'Api\Customer\OrderController@update_cart_summary');
        Route::post('books/checkout', 'Api\Customer\OrderController@checkout');
        Route::post('books/dealer_checkout', 'Api\Customer\OrderController@dealer_checkout');
        Route::get('books/my_cart_item_count', 'Api\Customer\OrderController@my_cart_item_count');

        // Cart & Checkout (For Digital Coupons)
        Route::post('coupon/add_to_cart', 'Api\Customer\CouponController@add_to_cart');
        Route::get('redeemed_points/{operation}', 'Api\Customer\CouponController@redeemed_points');
        Route::post('coupon/checkout', 'Api\Customer\CouponController@checkout');
        //For both physical and digital cart & checkout
        Route::get('markOrderFailed', 'Api\Customer\OrderController@markOrderFailed');
        
        //My Orders
        Route::post('my_orders', 'Api\Customer\OrderController@my_orders');
        Route::get('order_detail/{order_id}', 'Api\Customer\OrderController@order_detail');
        Route::get('order_item_details/{order_item_id}', 'Api\Customer\OrderController@order_item_details');

        //My Digital Coupons
        Route::post('my_digital_coupons', 'Api\Customer\CouponController@my_digital_coupons');
        Route::get('my_digital_coupon_details/{order_id}', 'Api\Customer\CouponController@my_digital_coupon_details');
        Route::post('sale_coupon', 'Api\Customer\CouponController@sale_coupon');

        //My Return
        Route::post('my_return/make_my_return_list', 'Api\Customer\ReturnController@make_my_return_list');
        Route::post('my_return/add_to_cart', 'Api\Customer\ReturnController@add_to_cart');
        Route::post('my_return/update_quantity', 'Api\Customer\ReturnController@update_quantity');
        Route::get('my_return/my_cart', 'Api\Customer\ReturnController@my_cart');
        Route::post('my_return/place_order_return', 'Api\Customer\ReturnController@place_order_return');
        Route::post('my_return/return_orders_list', 'Api\Customer\ReturnController@return_orders_list');
        Route::post('my_return/dispatch_order_return', 'Api\Customer\ReturnController@dispatch_order_return');
        Route::post('my_return/order_return_details', 'Api\Customer\ReturnController@order_return_details');

        //WishList
        Route::get('wishlist/out_of_stock_wishlist', 'Api\Customer\WishListController@out_of_stock_wishlist');
        Route::get('wishlist/retailer_wishlist', 'Api\Customer\WishListController@retailer_wishlist');
        Route::get('wishlist/retailer_dealer_list', 'Api\Customer\WishListController@retailer_dealer_list');
        Route::post('wishlist/add_to_wishlist', 'Api\Customer\WishListController@add_to_wishlist');
        Route::post('wishlist/update_quantity', 'Api\Customer\WishListController@update_quantity');
        Route::post('wishlist/remove_from_wishlist', 'Api\Customer\WishListController@remove_from_wishlist');
        Route::post('wishlist/wishlist_details', 'Api\Customer\WishListController@wishlist_details');
        Route::post('wishlist/dealer_wishlist_requests', 'Api\Customer\WishListController@dealer_wishlist_requests');
        Route::post('wishlist/dealer_wishlist_request_details', 'Api\Customer\WishListController@dealer_wishlist_request_details');

        //Wish Return
        Route::post('wish_return/all_wish_return_list', 'Api\Customer\WishReturnController@all_wish_return_list');
        Route::get('wish_return/my_wish_return_list', 'Api\Customer\WishReturnController@my_wish_return_list');
        Route::post('wish_return/add_to_wish_return', 'Api\Customer\WishReturnController@add_to_wish_return');
        Route::post('wish_return/edit_wish_return_item', 'Api\Customer\WishReturnController@edit_wish_return_item');
        Route::post('wish_return/remove_from_wishlist', 'Api\Customer\WishReturnController@remove_from_wishlist');
        Route::post('wish_return/wish_return_details', 'Api\Customer\WishReturnController@wish_return_details');
        Route::post('wish_return/dealer_wish_return_requests', 'Api\Customer\WishReturnController@dealer_wish_return_requests');
        Route::post('wish_return/dealer_wishlist_request_details', 'Api\Customer\WishReturnController@dealer_wishlist_request_details');

        //Suggestion
        Route::post('suggestion_book_list', 'Api\Customer\SuggestionController@suggestion_book_list');
        Route::post('add_ssgc_suggestion', 'Api\Customer\SuggestionController@add_ssgc_suggestion');
        Route::post('add_wish_suggestion', 'Api\Customer\SuggestionController@add_wish_suggestion');

        //Home
        Route::get('home/make_my_return', 'Api\Customer\HomeController@make_my_return');
        

    });
    
    //Digital Coupons
    Route::post('coupon_list', 'Api\Customer\CouponController@coupon_list');
    Route::Post('coupon_detail', 'Api\Customer\CouponController@coupon_detail');

    //Nested & Business Categories List
    Route::get('nested_categories/{business_category_id?}','Api\Customer\NestedCategoryController@nested_categories');

    // Nested Categories for make my return 
    Route::get('nested_categories_return/','Api\Customer\NestedCategoryController@nested_categories_return');    
    //Home
    Route::get('business_categories', 'Api\Customer\HomeController@business_categories');
    Route::get('dealers', 'Api\Customer\HomeController@dealers');
    Route::get('contact_us_info', 'Api\Customer\HomeController@contact_us_info');


    // copy coupon data from ssgc to  ssgc2
    Route::get('bulk_coupons', 'Api\Customer\HomeController@bulk_coupons');
    // ssgc to ssgc 2 connection for coupon apis
    Route::post('update_coupon', 'Api\Customer\CouponController@update_coupon');

    Route::get('get_social_media_link','Api\Customer\HomeController@getSocialMediaLink');
});
