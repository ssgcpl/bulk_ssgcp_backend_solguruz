<?php

namespace App\Http\Controllers\Api\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use App\Models\SubCoupon;
use App\Models\CouponQrCode;
use App\Models\CouponMaster;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderCouponQrCode;
use Illuminate\Validation\Rule;
use App\Http\Resources\Customer\CouponResource;
use App\Http\Resources\Customer\CartResource;
use App\Http\Resources\Customer\CouponDetailResource;
use App\Http\Resources\Customer\OrderListResource;
use App\Http\Resources\Customer\OrderDetailResource;
use Validator;
use Auth, DB;
use App\Models\Helpers\CommonHelper;
use Carbon\Carbon;
use App\Models\Setting;

use function PHPUnit\Framework\returnSelf;

/**
 * @group Customer Endpoints
 *
 * Customer Apis
 */
class CouponController extends BaseController
{
    use CommonHelper;

    /**
     * Coupon: Coupon list
     *
     * @bodyParam category_id string optional Category_id (category_id or subcategory_id of any level). Example:3
     *
     * @response
    {
        "success": "1",
        "status": "200",
        "message": "Coupon available",
        "data": [
            {
                "sub_coupon_id": "2",
                "name": "Course 5/9 Multiple coupon",
                "sale_price": "100.00",
                "mrp": "200.00",
                "image": "http://localhost/ssgc-bulk-order-web/public/uploads/media/canada-flag-png-4604_(1)_16746497122843.png",
                "type": "courses",
                "expiry_date": "2023-09-29 19:00:00"
            },
            {
                "sub_coupon_id": "1",
                "name": "Coupon Multi 14",
                "sale_price": "50.00",
                "mrp": "100.00",
                "image": "http://localhost/ssgc-bulk-order-web/public/uploads/media/canada-flag-png-4604_-_Copy_16746485149778.png",
                "type": "books",
                "expiry_date": "2024-10-31 15:30:00"
            }
        ],
        "links": {
            "first": "http://localhost/ssgc-bulk-order-web/public/api/customer/coupon_list?page=1",
            "last": "http://localhost/ssgc-bulk-order-web/public/api/customer/coupon_list?page=1",
            "prev": "",
            "next": ""
        },
        "meta": {
            "current_page": 1,
            "last_page": 1,
            "per_page": 15,
            "total": 2
        }
    }
     */
    public function coupon_list(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'business_category_id'    => 'required|exists:business_categories,id,is_live,1,status,active',
            'category_id'  => 'nullable|exists:categories,id,is_live,1',
            // 'page_number' => 'required|numeric|min:1',
            // 'user_id' => 'sometimes|nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError($this->object, $validator->errors()->first());
        }

        DB::beginTransaction();
        try {
            $user = Auth::guard('api')->user();
            $all_category_ids = $this->get_all_child_categories($request->category_id);
            $data = SubCoupon::query()->where('business_category_id',$request->business_category_id)->where('status','active')->where('available_quantity','>','0');

            $data = $data->whereHas('coupon', function ($q) {
                $q->where('is_live', '1')
                    ->where('is_deleted','0')
                    ->where('end_date', '>=',Carbon::now()->format('Y-m-d h:i') );
            });

            // check if category is active/publish
            $data = $data->whereHas('categories',function($q) {
                      $q->whereHas('category',function($q1){
                        $q1->where('is_live','1')
                           ->where('status','active')
                           ->whereNull('parent_id');
                      });
                  });

            if($request->category_id)
            {
                $data = $data->whereHas('categories',function($q) use($all_category_ids){
                    $q->whereIn('category_id',$all_category_ids);
                   /* $q->whereHas('category',function($q1){
                        $q1->where('is_live','1')
                           ->where('status','active');
                      });*/
                  });
            }

            $data = $data->orderBy('id','desc')->paginate();
            

            // $data   = $data->orderBy('id','desc')->paginate();
            if ($data->count() > 0) {
                foreach($data as $d){
                    if(isset($user)){
                      $cart = $this->itemAddedToCartChecker('coupon_order',$d->id,$user);
                      if($cart){
                        $d->added_to_cart = '1';
                        $d->quantity = $cart->supplied_quantity;
                        $d->cart_item_id = $cart->id;
                      }
                    }
                  }

                $response       = CouponResource::Collection($data);

                return $this->sendPaginateResponse($response, trans('coupons.coupon_list_success'));

            } else {

                return $this->sendResponse($this->array, trans('coupons.coupon_list_empty'));
            }
        } catch (\Exception $e) {
            return $this->sendError($this->array, trans('common.api_error'));
        }
    }

    /**
     * Coupon: Coupon Detail
     *
     * @bodyParam sub_coupon_id string required Sub Coupon Id . Example:3
     *
     * @response
    {
        "success": "1",
        "status": "200",
        "message": "Coupon Details",
        "data": {
            "sub_coupon_id": "1",
            "name": "Railway Discount",
            "sale_price": "10.00",
            "mrp": "20.00",
            "image": "http://localhost/ssgc-bulk-order-web/public/uploads/media/image_2021_08_11T13_34_48_563Z_1674641468276.png",
            "type": "e_books",
            "expiry_date": "2023-09-29 13:15:00",
            "description": "meet mehta"
        }
    }
     */
    public function coupon_detail(Request $request) {

        DB::beginTransaction();
        try{

          $validator = Validator::make($request->all(), [
            'sub_coupon_id'    => 'required|exists:sub_coupons,id',
          ]);

          if($validator->fails()){
            return $this->sendError($this->object, $validator->errors()->first());
          }
          $user = Auth::guard('api')->user();
          $coupon = SubCoupon::find($request->sub_coupon_id);

            if(isset($coupon)){
              $cart = $this->itemAddedToCartChecker('coupon_order',$coupon->id,$user);
              if($cart){
                $coupon->added_to_cart = '1';
                $coupon->quantity = $cart->supplied_quantity;
                $coupon->cart_item_id = $cart->id;
              }
            }


          return $this->sendResponse(new CouponDetailResource($coupon),trans('coupons.coupon_details'));

        }catch(\Exception $e){
          return $this->sendError($this->object,trans('common.api_error'));
        }
    }


    /**
     * For Backend Use Only
     */
    public function update_coupon(Request $request)
    {
        try {
            if($request->key != 'ssgc_1212'){
                return false;
            }
            $sub_coupon = SubCoupon::where('coupon_id',$request->coupon_id)->first();
            $coupon_master = CouponMaster::where('coupon_id', $request->coupon_id)->first();
         
            if ($request->delete == '1') {
                $order_item = OrderItem::where('coupon_id',$request->coupon_id)->count();
                if($order_item > 0){
                    $coupon_master->update(['is_deleted' => '1']);
                }else{
                    if($sub_coupon){
                        $sub_coupon->delete();
                    }
                    $coupon_master->delete();
                }
                return true;
            }
            $data = $request->except('qr_codes');
            if ($coupon_master) {
                $coupon_master->update($data);
		 // update coupon state when used in ssgc1
                foreach ($request->qr_codes as $qr) {
                    $coupon_qr_code = CouponQrCode::where('coupon_master_id',$coupon_master->id)->where('qr_code_value',$qr['qr_code_value'])->first();
                    $coupon_qr_code->state = $qr['state'];
                    $coupon_qr_code->update();
                }

            } else {
                $coupon_master = CouponMaster::create($data);
                $qr_codes = $request->qr_codes;
                // Make directory if available
                if (!is_dir('uploads/qr_codes/')) {
                    mkdir('uploads/qr_codes/',0777, true);
                }
                $app_url = config('adminlte.APP_BASE_URL');
                foreach ($qr_codes as $qr) {
                    $src = "https://samsamayikghatnachakra.com/".$qr['qr_code'];
                    $destination_path = '/uploads/qr_codes/'.basename($src);
                    $dest = public_path($destination_path);
                    $image = $this->does_url_exists($src);
                    if ($image == 'true'){
                        file_put_contents($dest, file_get_contents($src));
                    }
                    CouponQrCode::create([
                    'coupon_master_id' => $coupon_master->id,
                    'qr_code_value'    => $qr['qr_code_value'],
                    'qr_code'          => $qr['qr_code'],
                    'state'            => $qr['state'],
                    ]);
                }
            }

            return true;
        } catch (\Exception $e) {
             return $e->getMessage();
            return false;
        }
    }

    // Update Coupon state : fresh/redeemed when used in ssgc1
   /* public function update_coupon_qr_state(Request $request)
    {
        try {
            if($request->key != 'ssgc_1212'){
                return false;
            }
            $sub_coupon = SubCoupon::where('coupon_id',$request->coupon_id)->first();
            $coupon_master = CouponMaster::where('coupon_id', $request->coupon_id)->first();
            $coupon_qr_codes = CouponQrCode::where('coupon_master_id',$coupon_master->id)->get();
            $qr_code = $request->qr_code;
            $qr_code_state = $request->qr_code_state;
              foreach ($coupon_qr_codes as $coupon_qr) {
                if($coupon_qr == $qr_code){
                    $coupon_qr['state'] = $qr_code_state;
                    $coupon_qr->update();
                }  
              }
            }
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
            return false;
        }
    }*/

    // check image available or not
    function does_url_exists($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($code == 200) {
            $status = true;
        } else {
            $status = false;
        }
        curl_close($ch);
        return $status;
    }

    /**
    * Coupon Cart & Checkout: Add To Cart
    * @authenticated
    *
    * @bodyParam coupon_id number required
    * @bodyParam quantity number required
    *
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "Item has been added to cart",
    }
    */
    public function add_to_cart(Request $request) {
        $user = Auth::guard('api')->user();
        $delay_time = Setting::get('payu_job_delay_in_seconds');
        $delay_time = floor($delay_time/60);
        //check any payment intiated for this user or not
        if($this->closePaymentAttempt($user->id)){
             return $this->sendError('',trans('orders_api.order_under_process',['delay_time'=>$delay_time])); 
        }

        $validator=  Validator::make($request->all(),[
            'coupon_id' => 'required|exists:sub_coupons,id',
            'quantity'  => 'required|numeric|min:1',
        ]);

        if($validator->fails()) {
            return $this->sendValidationError('', $validator->errors()->first());
        }

        DB::beginTransaction();
        try{
            $user = Auth::guard('api')->user();
            $quantity = $request->quantity;
            // check if item exist
            $item = SubCoupon::where(['id'=>$request->coupon_id, 'status'=>'active'])->first();
            if($item) {
                if($item->available_quantity < $request->quantity) {
                    return $this->sendError('',trans('orders_api.requested_qty_not_available',['qty' => $item->available_quantity]));
                }
            }else{
                return $this->sendError('',trans('orders_api.item_not_available'));
            }

            //check if user is available
            if($user->verified != '1' && $user->email_verified_at == NULL) {
                return $this->sendError('',trans('orders_api.user_not_verified'));
            }

            if($user->status != 'active') {
                return $this->sendError('',trans('orders_api.user_not_active'));
            }

            // check if item already in the cart
            $cart = Order::where('user_id',$user->id)->where('is_cart','1')->first();
            if($cart) {
                $cart_item = OrderItem::where('coupon_id',$request->coupon_id)
                      ->where('order_id',$cart->id)
                      ->first();

                if($cart_item) {
                    return $this->sendError('', trans('orders_api.already_added_in_cart'));
                }

                //delete the physical book cart items if already exist
                $cart_item_books = OrderItem::whereNotNull('product_id')
                  ->where('order_id',$cart->id)
                  ->delete();
            }



            $item_sale_price = $item->get_price($user);

            $total_mrp = $cart ? $cart->total_mrp + ($item->mrp*$quantity) : ($item->mrp*$quantity);
            $total_sale_price = $cart ? $cart->total_sale_price + ($item_sale_price*$quantity) : ($item_sale_price*$quantity);
            $discount_on_mrp = $cart ? $cart->discount_on_mrp + (($item->mrp - $item_sale_price)*$quantity) : (($item->mrp - $item_sale_price)*$quantity);

            $delivery_charges = 0;

            $data = [
                'user_id'          => Auth::guard('api')->user()->id,
                'total_mrp'        => $total_mrp,
                'total_sale_price' => $total_sale_price,
                'discount_on_mrp'  => $discount_on_mrp,
                'delivery_charges' => $delivery_charges,
                'order_type'       => 'digital_coupons',
                'order_status'     => 'pending',
                'is_cart'          => '1'
            ];

            //create cart or update cart if exist
            if($cart)
            {
                Order::where('id',$cart->id)->update($data);
            }
            else
            {
                $cart = Order::create($data);
                $cart->order_id = $this->generateOrderId();
                \Log::info('New Order created with Order Id. : '.$cart->id.' For User - '.$user->id);
            }

            $total_payable       = $total_mrp - $discount_on_mrp + $delivery_charges;
            $cart->total_payable = $total_payable;
            $cart->save();

            //Add cart items
            $mrp              = $item->mrp;
            $total_mrp        = $mrp*$quantity;
            $total_sale_price = $item_sale_price*$quantity;
            $total            = $total_sale_price;


            $order_item_data = [
                'order_id'          => $cart->id,
                'coupon_id'         => $item->id,
                'mrp'               => $mrp,
                'sale_price'        => $item_sale_price,
                'total_mrp'         => $total_mrp,
                'total_sale_price'  => $total_sale_price,
                'total'             => $total,
                'ordered_quantity'  => $quantity,
                'supplied_quantity' => $quantity,
            ];

            $order_item = OrderItem::create($order_item_data);

            if($cart) {
                $this->updateCartCalc($cart->id,'no_redeem_action');
                $cart = Order::find($cart->id);
                DB::commit();
                return $this->sendResponse(new CartResource($cart), trans('orders_api.item_added_to_cart'));
            } else {
                DB::rollback();
                return $this->sendError('', trans('common.something_went_wrong'));
            }
        } catch(\Exception $e)
        {
            DB::rollback();
            return $this->sendError('',trans('common.something_went_wrong'));
        }

    }

    /**
    * Coupon Cart & Checkout: Redeemed Points : apply/remove
    * @authenticated
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "Cart updated",
    }
    */
    public function redeemed_points($operation)
    {

        DB::beginTransaction();
        try{
            $user = Auth::guard('api')->user();
            $cart = Order::where('user_id',$user->id)->where('is_cart','1')->first();

            if(!$cart) {
                return $this->sendError('',trans('orders_api.cart_not_found'));
            }

            if($cart->order_type != 'digital_coupons')
            {
                return $this->sendError('',trans('orders_api.only_applicable_on_digital_coupons'));
            }

            $delivery_charges = 0;
            $redeemed_points = 0;
            $redeemed_points_discount = 0;
            if($operation == 'apply'){
                $redeem = $this->get_redeemed_points_data($cart->id);
                $redeemed_points = $redeem['redeemed_points'];
                $redeemed_points_discount = $redeem['redeemed_points_discount'];
                $this->maintainReferralHistory((integer)$redeemed_points, 'deducted','no');

            }else{
                $this->maintainReferralHistory((integer)$cart->redeemed_points, 'added','no');
            }
            $cart->redeemed_points          = $redeemed_points;
            $cart->redeemed_points_discount = $redeemed_points_discount;
            $cart->total_payable            = $cart->total_sale_price - $redeemed_points_discount;
            $cart->save();
            // echo "<pre>";print_r($cart);exit;
            DB::commit();
            if($operation == 'apply'){
                return $this->sendResponse('', 'Points redeemed successfully');
            }else{
                return $this->sendResponse('', 'Redeemed points removed successfully');
            }
        } catch(\Exception $e)
        {
            DB::rollback();
            return $this->sendError('',trans('common.something_went_wrong'));
        }
    }

    /**
    * Coupon Cart & Checkout: Checkout
    * @authenticated
    *
    * @bodyParam payment_method string required
    *
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "Data Found Successfully",
      "data": { "url": "http://localhost/ssgc/public/order_payment/5/1/payu"}
    }
    */
    public function checkout(Request $request)
    {
        $user = Auth::guard('api')->user();
        $delay_time = Setting::get('payu_job_delay_in_seconds');
        $delay_time = floor($delay_time/60);
        //check any payment intiated for this user or not
        if($this->closePaymentAttempt($user->id)){
             return $this->sendError('',trans('orders_api.order_under_process',['delay_time'=>$delay_time])); 
        }
        try{
            DB::beginTransaction();
          
            $cart = Order::where('user_id',$user->id)->where('is_cart','1')->first();
            if(!$cart) {
                return $this->sendError('',trans('orders_api.cart_is_empty'));
            }

            //check if user is available
            if($user->verified != '1' && $user->email_verified_at == NULL) {
                return $this->sendError('',trans('orders_api.user_not_verified'));
            }

            if($user->status != 'active') {
                return $this->sendError('',trans('orders_api.user_not_active'));
            }

            $payment_methods = [
                'payu','ccavenue'
            ];
            if($cart->total_payable == 0.00){
              $payment_methods[] = '0-amount';
            }
            
            $validator=  Validator::make($request->all(),[
                'payment_method' => [
                    'required',
                    Rule::in($payment_methods)
                 ],
            ]);

            if($validator->fails()) {
                return $this->sendValidationError('', $validator->errors()->first());
            }

            //check whether coupon is active /publish and check whether coupon qty is available or not
            foreach ($cart->order_items as $key => $cart_item) {
                $item_name = '';
                $message = '';
                $is_active = $this->getAllActiveItemFromTypeAndId($cart->order_type, $cart_item->coupon_id);
                if(!$is_active){
                  $item_name = $cart_item->coupon->coupon->name;
                  $message = $item_name." is inactive now. please remove it from the cart to proceed.";
                  return $this->sendError('',$message); exit;
                }

                if($is_active){
                    if($is_active->available_quantity < $cart_item->ordered_quantity) {
                        $item_name = $cart_item->coupon->coupon->name;
                        $message = "Requested quantity for ". $item_name."  is not available. Available qty :".$is_active->available_quantity;
                        return $this->sendError('',$message);
                    }
                    if($cart_item->coupon->coupon->end_date <= Carbon::now()->format('Y-m-d H:i')){
                        $item_name = $cart_item->coupon->coupon->name;
                        $message = "This coupon ". $item_name."  is expired.Please remove it from the cart.";
                        return $this->sendError('',$message);
                    }
                }
            }
             

            $response = [
                'url' => route('order_payment',
                        [
                            'cart_id'    => $cart->id,
                            'user_id'    => $user->id,
                            'address_id' => '0',
                            'gateway'    => $request->payment_method
                        ])
            ];
            DB::commit();
            return $this->sendResponse($response, trans('common.data_found'));
        } catch(\Exception $e) {
            DB::rollback();
            return $this->sendError('','Something went wrong');
        }
    }

    /**
    * My Digital Coupons: Purchased Coupon List
    * @authenticated
    *
    * @bodyParam type string Order Type (Enums : books, e_books, packages, videos, courses, tests)
    * @bodyParam status array Order Status (Enums : processing,completed)
    *
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "Data Found Successfully",
        "data": [
            {
                "id": "8",
                "title":  "Order #ERER455",
                "image": "http://localhost/ssgc/public/default_media/order_image.png",
                "action": "Order ",
                "order_date": "04 Jul 2022"
            },
            {
                "id": "14",
                "title":  "Order #ERER455",
                "image": "http://localhost/ssgc/public/default_media/order_image.png",
                "action": "Order ",
                "order_date": "05 Jul 2022"
            }
        ],
        "links": {
            "first": "http://localhost/ssgc/public/api/customer/my_orders?page=1",
            "last": "http://localhost/ssgc/public/api/customer/my_orders?page=1",
            "prev": "",
            "next": ""
        },
        "meta": {
            "current_page": 1,
            "last_page": 1,
            "per_page": 15,
            "total": 5
        }
    }
    */
    public function my_digital_coupons(Request $request) {

        $validator = Validator::make($request->all(),[
            'type'     => 'nullable|array',
            'type.*'   => 'in:books,e_books,packages,videos,courses,tests',
            'status'   => 'nullable|array',
            'status.*' => 'in:completed,cancelled,refunded,under_process,failed'
        ]);


        if($validator->fails()) {
              return $this->sendValidationError('', $validator->errors()->first());
        }


        $user = Auth::guard('api')->user();
        $type = $request->type ?? array();
        $total_orders = array();
        // echo "<pre>";print_r($type);exit;
        /*if(empty($type))
        {
            $type = array('books','e_books','packages','videos','courses','tests');
        }*/

        $status = $request->status;
        if(empty($status))
        {
            $status = array('completed', 'cancelled', 'refunded','failed');
        }
        if(!empty($request->status)){
            if(in_array('under_process',$request->status) && count($request->status)== 1){
                $orders_placed = Order::where('user_id',$user->id)
                  ->where('order_status','pending')->where('is_payment_attempt','1')->where('order_type','digital_coupons')->pluck('id')->toArray();
            }else if(in_array('under_process',$request->status)){
                $orders_placed = Order::where('user_id',$user->id)->whereIn('order_status',$request->status)->where('order_type','digital_coupons')->pluck('id')->toArray();  
                $order_pending = Order::where('user_id',$user->id)
                  ->where('order_status','pending')->where('is_payment_attempt','1')->where('order_type','digital_coupons')->pluck('id')->toArray();
                $total_orders = array_merge($total_orders,$order_pending); 

            }else {
                $orders_placed = Order::where('user_id',$user->id)->whereIn('order_status',$request->status)->where('order_type','digital_coupons')->pluck('id')->toArray();  
               /* $order_pending = Order::where('user_id',$user->id)
                  ->where('order_status','pending')->where('is_payment_attempt','1')->where('order_type','digital_coupons')->pluck('id')->toArray();
                $total_orders = array_merge($total_orders,$order_pending); */
            }
        }   
        else {
            $orders_placed = Order::where('user_id',$user->id)
              ->where('order_status','<>','pending')->whereIn('order_status',$status)->where('order_type','digital_coupons')->pluck('id')->toArray();  
            $order_pending = Order::where('user_id',$user->id)
                  ->where('order_status','pending')->where('is_payment_attempt','1')->where('order_type','digital_coupons')->pluck('id')->toArray();
            $total_orders = array_merge($total_orders,$order_pending);   
        }
        
        $total_orders = array_merge($total_orders,$orders_placed);
        
        $orders = Order::where('user_id',$user->id)
                  //->whereIn('order_status',$status)
                  ->whereIn('id',$total_orders)
                  ->orderBy('created_at','DESC')
                  ->where('order_type','digital_coupons')
                  ->whereHas('order_items',function($q)use($type){
                    //subcoupon
                    $q->whereHas('coupon',function($qu)use($type){
                        //coupon master
                        $qu->whereHas('coupon',function($que)use($type){
                            //filter for type
                            if(!empty($type))
                            {
                                $que->whereIn('item_type',$type);
                            }
                        });
                    });
                  })
                  ->paginate();

        if(count($orders) > 0){
          return $this->sendPaginateResponse(OrderListResource::Collection($orders), trans('common.data_found'));
        } else {
          return $this->sendError('', trans('common.no_data'));
        }
    }

    /**
    * My Digital Coupons: Purchased Coupon Details
    * @authenticated
    *
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "Data Found Successfully",
      "data": {
        "order_id": "2",
        "order_display": "",
        "order_date": "07 Jul 2022",
        "order_time": "10:15 AM",
        "order_total": "116.50",
        "order_status": "Placed",
        "delivery_address": {
            "id": "60",
            "contact_name": "Parth House",
            "contact_number": "1231231235",
            "state_id": "2",
            "state_name": "Maharashtra",
            "city_id": "3",
            "city_name": "Mumbai",
            "postcode_id": "5",
            "postcode": "380020",
            "area": "Parth House",
            "house_no": "153",
            "street": "Parth House",
            "landmark": "Parth House landmark",
            "address_type": "Home",
            "is_delivery_address": "no"
        },
        "items": [
            {
                "order_item_id": "6",
                "quantity": "1",
                "item_id": "5",
                "item_type": "books",
                "heading": "Test5",
                "mrp": "100.00",
                "sale_price": "90.00",
                "cover_image": "http://cloud1.kodyinfotech.com:7000/ssgc/public/uploads/media/386d1968e9850353b0f2a3a40b3ae716.png",
                "rating": "0.0"
            }
        ],
        "order_summary": {
            "total_mrp": "100.00",
            "discount_on_sale": "23.50",
            "delivery_charges": "40.00",
            "coin_point_discount": "0.00",
            "total_amount": "116.50"
        }
      }
    }
    */
    public function my_digital_coupon_details($order_id) {
        $user = Auth::guard('api')->user();
        $order = Order::where('id',$order_id)->where('user_id', $user->id)->where('order_type','digital_coupons')->first();
        if(!$order) {
          return $this->sendError('', trans('common.no_data'));
        }
        return $this->sendResponse(new OrderDetailResource($order), trans('common.data_found'));
    }

    /**
    * My Digital Coupons: Sale Coupon
    * @authenticated
    *
    * @bodyParam qr_id number required From available coupons array
    * @bodyParam customer_name string required Full name. Example:John
    * @bodyParam customer_contact string required  max:10  Mobile Number. Example:1234567890
    * @bodyParam sale_price number required
    *
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "Data Found Successfully",
      "data": {

      }
    }
    */
    public function sale_coupon(Request $request) {

        $validator = Validator::make($request->all(), [
            'qr_id'            => 'required|exists:order_coupon_qr_codes,id',
            'customer_name'    => 'required|string|min:3|max:50',
            'customer_contact' => 'required|digits:10',
            'sale_price'       => 'required|numeric',
        ]);

        if($validator->fails()) {
            return $this->sendError($this->object, $validator->errors()->first());
        }

        $order_coupon_qr = OrderCouponQrCode::find($request->qr_id);
        if(!$order_coupon_qr) {
          return $this->sendError('', trans('common.no_data'));
        }

        if($order_coupon_qr->status == 'sold')
        {
            return $this->sendError('', trans('orders_api.coupon_already_sold'));
        }
        $order_coupon_qr->status           = 'sold';
        $order_coupon_qr->customer_name    = $request->customer_name;
        $order_coupon_qr->customer_contact = $request->customer_contact;
        $order_coupon_qr->sale_price       = $request->sale_price;

        if($order_coupon_qr->save())
        {
            return $this->sendResponse('', trans('orders_api.coupon_sold'));
        }
        else
        {
            return $this->sendError('', trans('orders_api.coupon_not_sold'));
        }
    }
}
