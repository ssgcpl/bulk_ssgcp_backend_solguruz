<?php

namespace App\Http\Controllers\Api\Customer;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Validator,DB,Auth;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use App\Models\Address;
use App\Models\OrderAddress;
use App\Models\Payment;
use Illuminate\Validation\Rule;
use App\Http\Resources\Customer\CartResource;
use App\Http\Resources\Customer\CartItemResource;
use App\Http\Resources\Customer\OrderListResource;
use App\Http\Resources\Customer\OrderDetailResource;
use App\Http\Resources\Customer\OrderItemDetailResource;
use App\Models\Helpers\CommonHelper;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOrderPlacedEmailCustomer;
// use App\Models\Helpers\PaymentHelper;

/**
 * @group Customer Endpoints
 *
 * Customer Apis
 */

class OrderController extends BaseController
{	
    use CommonHelper;
    /**
    * Cart & Checkout: Add To Cart
    * @authenticated
    * 
    * @bodyParam book_id number required 
    * @bodyParam quantity number required 
    * @bodyParam language string 
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
            'book_id'  => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1',
            'language' => 'nullable|in:english,hindi'
        ]);

        if($validator->fails()) {
            return $this->sendValidationError('', $validator->errors()->first());
        }

        $language = $request->language;

        DB::beginTransaction();
        try{
            $user = Auth::guard('api')->user();
            $quantity = $request->quantity;
            // check if item exist
            $item = Product::where(['id'=>$request->book_id, 'status'=>'active', 'is_live'=>'1']);
            
            // check if category of product is active /publish
            $item = $item->whereHas('categories',function($q) {
                      $q->whereHas('category',function($q1){
                        $q1->where('is_live','1')
                          ->where('status','active');
                      });
                });
            $item = $item->first();
            if($item) {
                if($item->stock_status == 'out_of_stock') {
                    return $this->sendError('',trans('orders_api.item_out_of_stock'));
                }
            }else{
                return $this->sendError('',trans('orders_api.item_not_available'));
            }

            if(!$language)
            {
              if($item->language == 'both')
              {
                //set default lang as hindi if book available in both languages
                $language = 'hindi';
              }
              else
              {
                $language = $item->language;
              }
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
                $cart_item = OrderItem::where('product_id',$request->book_id)
                      ->where('order_id',$cart->id)
                      ->first();

                if($cart_item) {
                    return $this->sendError('', trans('orders_api.already_added_in_cart'));
                }   

                //delete the digital coupon cart items if already exist
                $cart_item_books = OrderItem::whereNotNull('coupon_id')
                  ->where('order_id',$cart->id)
                  ->delete();

                //remove the applied coin points if applied
                if($cart->redeemed_points > 0)
                {
                    $user->points = $user->points + $cart->redeemed_points;
                    $user->save();

                    $cart->redeemed_points = NULL;
                    $cart->redeemed_points_discount = 0;
                    $cart->save();
                }
                
            }

        
            $item_sale_price = $item->get_price($user);
            
            $total_mrp = $cart ? $cart->total_mrp + ($item->mrp*$quantity) : ($item->mrp*$quantity);
            $total_sale_price = $cart ? $cart->total_sale_price + ($item_sale_price*$quantity) : ($item_sale_price*$quantity);
            $discount_on_mrp = $cart ? $cart->discount_on_mrp + (($item->mrp - $item_sale_price)*$quantity) : (($item->mrp - $item_sale_price)*$quantity);
            
            $delivery_charges = $this->get_delivery_charges($total_sale_price);
           // $user_type = @($cart)? $cart->user_type: $user->user_type;
            if($user->user_type == 'dealer'){
              //  $delivery_charges = @($cart)? $cart->delivery_charges :'0';
                $delivery_charges = '0';
            }
            $user = Auth::guard('api')->user();
            $data = [
                'user_id'          => $user->id,
                'user_type'        => $user->user_type,
                'total_mrp'        => $total_mrp,
                'total_sale_price' => $total_sale_price,
                'discount_on_mrp'  => $discount_on_mrp,
                'delivery_charges' => $delivery_charges,
                'order_type'       => 'physical_books',
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
            
            //store book details in selected language
            if($language == 'english'){

                $book_name            = $item->name_english ? (string)$item->name_english : '' ;
                $book_sub_heading     = $item->sub_heading_english ? (string)$item->sub_heading_english : '' ;
                $book_description     = $item->description_english ? (string)$item->description_english : '' ;
                $book_additional_info = $item->additional_info_english ? (string)$item->additional_info_english : '' ;

            }else{

                $book_name            = $item->name_hindi ? (string)$item->name_hindi : '' ;
                $book_sub_heading     = $item->sub_heading_hindi ? (string)$item->sub_heading_hindi : '' ;
                $book_description     = $item->description_hindi ? (string)$item->description_hindi : '' ;
                $book_additional_info = $item->additional_info_hindi ? (string)$item->additional_info_hindi : '' ;
            }

            $order_item_data = [
                'order_id'             => $cart->id,
                'product_id'           => $item->id,
                'book_name'            => $book_name,
                'book_sub_heading'     => $book_sub_heading,
                'book_description'     => $book_description,
                'book_additional_info' => $book_additional_info,
                'mrp'                  => $mrp,
                'sale_price'           => $item_sale_price,
                'total_mrp'            => $total_mrp,
                'total_sale_price'     => $total_sale_price,
                'total'                => $total,
                'ordered_quantity'     => $quantity,
                'supplied_quantity'    => $quantity,
                'language'             => $language
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
    * Cart & Checkout: Update Quantity
    * @authenticated
    * 
    * @bodyParam cart_item_id number required
    * @bodyParam quantity number required 
    *
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "Quantity updated successfully",
    }
    */
    public function update_quantity(Request $request)
    {
        $user = Auth::guard('api')->user();
        $delay_time = Setting::get('payu_job_delay_in_seconds');
        $delay_time = floor($delay_time/60);
        //check any payment intiated for this user or not
        if($this->closePaymentAttempt($user->id)){
             return $this->sendError('',trans('orders_api.order_under_process',['delay_time'=>$delay_time])); 
        }
        //VALIDATION ..
        $validator=  Validator::make($request->all(),[
            'cart_item_id' => 'required|exists:order_items,id',
            'quantity'     => 'required|numeric',
        ]);

        if($validator->fails()) {
            return $this->sendValidationError('', $validator->errors()->first());
        }
        DB::beginTransaction();
        try{
            $order_item = OrderItem::where('id',$request->cart_item_id)->first();
            if(!$order_item) {
                return $this->sendError('',trans('orders_api.item_not_found'));
            }
            $cart = $order_item->order;
            $user = Auth::guard('api')->user();
            if($cart->user_id != $user->id) {
                return $this->sendError('',trans('orders_api.item_not_found'));
            }
            if($cart->is_cart != '1') {
                return $this->sendError('',trans('orders_api.cart_not_found')); 
            }
          
            if($request->quantity == 0) {
                $order_item->delete();
                if(count($cart->order_items) == 0)
                {
                    // remove the applied coins
                    if($cart->order_type == 'digital_coupons')
                    {
                        $this->maintainReferralHistory((integer)$cart->redeemed_points, 'added','no');
                    }
                    $cart->delete();
                } 
            }else{
                if($cart->order_type == 'physical_books')
                {
                    if($order_item->product->stock_status == 'out_of_stock') {
                        return $this->sendError('',trans('orders_api.item_out_of_stock'));
                    }
                }
                else
                {
                    if($order_item->coupon->available_quantity < $request->quantity) {
                        return $this->sendError('',trans('orders_api.requested_qty_not_available',['qty' => $order_item->coupon->available_quantity]));
                    }
                }

                $order_item->ordered_quantity  = $request->quantity;
                $order_item->supplied_quantity = $request->quantity;
                $order_item->total_mrp         = $order_item->mrp*$request->quantity;
                $order_item->total_sale_price  = $order_item->sale_price*$request->quantity;
                $order_item->total             = ($order_item->sale_price*$request->quantity) - $order_item->coupon_discount;
                $order_item->save();
            }

            if(count($cart->order_items) == 0) {
                DB::commit();
                return $this->sendResponse('', trans('orders_api.cart_is_empty'));
            }

            $this->updateCartCalc($cart->id,'no_redeem_action');
            /* remove and re apply the coins 
                1.if total payable 0 or 
                2.less than 0 or
                3.requested  quantity is 0 (item removed from the cart) or 
                4.requested quantity is less than coin_discount
            */
            if($cart->total_payable == 0 || $cart->total_payable < 0 || $request->quantity == 0 || $request->quantity < $cart->redeemed_points_discount)
            {
                $this->maintainReferralHistory((integer)$cart->redeemed_points, 'added','no');
                //  echo "<pre>"; print_r($cart); die;
                if($cart->order_type == 'digital_coupons')
                {
                    if($user->points > 0)
                    {
                        if($cart->redeemed_points === NULL || $cart->redeemed_points > 0)
                        {
                            // echo "<pre>";print_r($cart);exit;
                            $redeemed_points = 0;
                            $redeemed_points_discount = 0;
                         
                            $redeem = $this->get_redeemed_points_data($cart->id);
                            $redeemed_points = $redeem['redeemed_points'];
                            $redeemed_points_discount = $redeem['redeemed_points_discount']; 
                            $this->maintainReferralHistory((integer)$redeemed_points, 'deducted','no');
                           
                            $cart->redeemed_points          = $redeemed_points;
                            $cart->redeemed_points_discount = $redeemed_points_discount;
                            $cart->total_payable            = $cart->total_sale_price - $cart->redeemed_points_discount;
                            $cart->save();
                        }
                        
                    }
                }
            }
            
            DB::commit();
            return $this->sendResponse('', trans('orders_api.quantity_updated'));
        } catch(\Exception $e) {
            DB::rollback();
            return $this->sendError('',trans('common.something_went_wrong'));
        }
    }

    
    /**
    * Cart & Checkout: My Cart
    * @authenticated
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "Cart data",
        "data": {
        "cart_id": "8",
        "items": [
            {
                "cart_item_id": "9",
                "quantity": "1",
                "item_id": "10",
                "heading": "Highcourt 2022",
                "mrp": "600.00",
                "sale_price": "500.00",
                "cover_image": "http://localhost/ssgc/public/uploads/media/a320ca76bf2eff0eb009566da69212c6.png",
                "rating": "0.0",
                "coupon_id": "1",
                "qr_code" : "qwqwqqw",
                "is_favorite": "0"
            },
            {
                "cart_item_id": "10",
                "quantity": "1",
                "item_id": "9",
                "heading": "Test Book Physical Ebbboknow",
                "mrp": "400.00",
                "sale_price": "300.00",
                "cover_image": "http://localhost/ssgc/public/uploads/media/bd47379207043595463a3e2af9b6dd8f.png",
                "rating": "0.0",
                "coupon_id": "1",
                "qr_code" : "qwqwqqw",
                "is_favorite": "0"
            }
        ],
        "earned_points": "0",
        "points_formula": "10 coins = 1 Rs.",
        "order_summary": {
            "total_mrp": "1800.00",
            "discount_on_sale": "450.00",
            "delivery_charges": "40.00",
            "coin_point_discount": "0.00",
            "total_amount": "1390.00"
        },
        "address_required": "1",
        "payment_methods": [
            "payu",
            "ccavenue",
            "cod"
        ],
        "points_redeemed": "1"
        }
    }
    */
    public function my_cart()
    {
        DB::beginTransaction();
        try{
            $user = Auth::guard('api')->user();
            $cart = Order::where('user_id',$user->id)->where('is_cart','1')->first();

            if(!$cart) {
                return $this->sendError('',trans('orders_api.cart_is_empty')); 
            }

            if($cart->order_type == 'physical_books') {
                foreach ($cart->order_items as $key => $cart_item) {
                    $is_available = $this->isItemAvailableForUser($cart_item->product_id,$user->id);
                    if($is_available == '0'){
                        $cart_item->delete();
                    }
                }
            }
          
            $cart->user_type = $user->user_type;
            $cart->save();
 
            $cart = $this->updateCartCalc($cart->id, 'no_redeem_action');


            //by default apply coin discount if order type is digital_coupons
            if($cart->order_type == 'digital_coupons')
            {
                if($user->points > 0 && $cart->total_payable > 0)
                {
                    if($cart->redeemed_points === NULL || $cart->redeemed_points > 0)
                    {
                        if($cart->redeemed_points > 0){
                           $redeemed_points = $cart->redeemed_points;
                           $redeemed_points_discount = $cart->redeemed_points_discount;
                        }else {
                           $redeemed_points = 0;
                           $redeemed_points_discount = 0;
                        }
                        
                        $redeem = $this->get_redeemed_points_data($cart->id);

                        if($redeem['required_points'] > $user->points)
                        {
                            if($cart->redeemed_points > 0){
                               $redeemed_points += $redeem['redeemed_points'];
                               $redeemed_points_discount += $redeem['redeemed_points_discount'];
                            }else{
                               $redeemed_points = $redeem['redeemed_points'];
                               $redeemed_points_discount = $redeem['redeemed_points_discount'];
                            }
                            $points_to_deduct = $redeem['redeemed_points'];
                        }
                        else
                        {
                            $redeemed_points = $redeem['redeemed_points'];
                            $redeemed_points_discount = $redeem['redeemed_points_discount'];
                            $points_to_deduct = $redeem['redeemed_points'] - $cart->redeemed_points;
                        }
                        
                        
                        $this->maintainReferralHistory((integer)$points_to_deduct, 'deducted','no');

                        $cart->redeemed_points          = $redeemed_points;
                        $cart->redeemed_points_discount = $redeemed_points_discount;
                        $cart->total_payable            = $cart->total_sale_price - $cart->redeemed_points_discount;
                        $cart->save();
                    }
                    
                }
            }

            DB::commit();
            return $this->sendResponse(new CartResource($cart), trans('common.data_found'));
        } catch(\Exception $e) {
            DB::rollback();
            return $this->sendError('','Something went wrong');
        }
    }



     /**
    * Cart Item Count
    * @authenticated
    * 
    *
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "Data Found Successfully",
      "data": { "cart_item_count": "1"}
    }
    */
    public function my_cart_item_count()
    {
        DB::beginTransaction();
        try {
            $user = Auth::guard('api')->user();

            $cart = Order::where('user_id', $user->id)->where('is_cart', '1')->first();

            $itemCount = 0;
            if (!$cart) {
                return $this->sendError(['cart_item_count' => $itemCount], trans('orders_api.cart_is_empty'));
            }

            if ($cart->order_type == 'physical_books') {
                foreach ($cart->order_items as $key => $cart_item) {
                    $is_available = $this->isItemAvailableForUser($cart_item->product_id, $user->id);
                    if ($is_available == '0') {
                        $cart_item->delete();
                    } else {
                        $itemCount++; // Increment item count
                    }
                }
            } else {
                if ($user->points > 0 && $cart->total_payable > 0) {
                    if ($cart->redeemed_points === NULL || $cart->redeemed_points > 0) {
                        if ($cart->redeemed_points > 0) {
                            $redeemed_points = $cart->redeemed_points;
                            $redeemed_points_discount = $cart->redeemed_points_discount;
                        } else {
                            $redeemed_points = 0;
                            $redeemed_points_discount = 0;
                        }

                        $redeem = $this->get_redeemed_points_data($cart->id);

                        if ($redeem['required_points'] > $user->points) {
                            if ($cart->redeemed_points > 0) {
                                $redeemed_points += $redeem['redeemed_points'];
                                $redeemed_points_discount += $redeem['redeemed_points_discount'];
                            } else {
                                $redeemed_points = $redeem['redeemed_points'];
                                $redeemed_points_discount = $redeem['redeemed_points_discount'];
                            }
                            $points_to_deduct = $redeem['redeemed_points'];
                        } else {
                            $redeemed_points = $redeem['redeemed_points'];
                            $redeemed_points_discount = $redeem['redeemed_points_discount'];
                            $points_to_deduct = $redeem['redeemed_points'] - $cart->redeemed_points;
                        }


                        $this->maintainReferralHistory((int)$points_to_deduct, 'deducted', 'no');

                        $cart->redeemed_points          = $redeemed_points;
                        $cart->redeemed_points_discount = $redeemed_points_discount;
                        $cart->total_payable            = $cart->total_sale_price - $cart->redeemed_points_discount;
                        $cart->save();
                    }
                }
                // If type is digital_coupons, count the items without deletion
                $itemCount = $cart->order_items->count();
            }

            $cart->user_type = $user->user_type;
            $cart->save();

            $cart = $this->updateCartCalc($cart->id, 'no_redeem_action');

            DB::commit();
            return $this->sendResponse(['cart_item_count' => $itemCount], trans('common.data_found'));

        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError('', 'Something went wrong');
        }
    }


    /**
    * Cart & Checkout: Update Cart Summary
    * @authenticated
    * 
    * @bodyParam checkout_items array required array of cart_item_id from Cart & Checkout: My Cart Example:[1,2]
    *
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "Data Found Successfully",
      "data": { "url": "http://localhost/ssgc/public/order_payment/5/1/payu"}
    }
    */
    public function update_cart_summary(Request $request)
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
          
            $cart = Order::where('user_id',$user->id)->where('is_cart','1')->where('order_type','physical_books')->first();
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
            
            $validator=  Validator::make($request->all(),[
                'checkout_items'      => 'array|required',
                'checkout_items.*'    => 'required|exists:order_items,id,order_id,'.$cart->id
            ],[
                'checkout_items.required' => 'Please select at least one item to checkout.',
                'checkout_items.*.required' => 'Please select at least one item to checkout.'
            ]);

            if($validator->fails()) {
                return $this->sendValidationError('', $validator->errors()->first());
            }

            //update cart summary for selected items
            $checkout_items      = OrderItem::whereIn('id',$request->checkout_items)->get();
            $total_mrp           = 0;
            $total_sale_price    = 0;
            $discount_on_mrp     = 0;
            $coin_point_discount = 0;
            $total_payable       = 0;

            foreach ($checkout_items as $item) {
                $product = $item->product;
                $coupon = $item->coupon;

                $item_mrp = $product->mrp;
                $item_sale_price = $product->get_price($user);
                
                $item_total_mrp        = $item_mrp * $item->supplied_quantity;
                $item_total_sale_price = $item_sale_price * $item->supplied_quantity;

                // update order master as per latest updated product prices
                $total_mrp        += $item_total_mrp;
                $total_sale_price += $item_total_sale_price;
                $discount_on_mrp  += ($item_total_mrp - $item_total_sale_price);
                $total_payable    += $item_total_sale_price;
            }

            $delivery_charges = $this->get_delivery_charges($total_sale_price); 
            if($cart->user_type == 'dealer')
            {
               // $delivery_charges = $cart->delivery_charges;
                 $delivery_charges = '0';
            }          

            $total_payable = $total_payable + $delivery_charges;

            $response = [
                'total_mrp'           => (string)number_format($total_mrp,2),
                'discount_on_mrp'     => (string)number_format($discount_on_mrp,2),
                'delivery_charges'    => (string)number_format($delivery_charges,2),
                'coin_point_discount' => (string)number_format($coin_point_discount,2),
                'total_payable'       => (string)number_format($total_payable,2),
            ];
            return $this->sendResponse($response, trans('orders_api.cart_summary_updated'));
        } catch(\Exception $e) {
            DB::rollback();
            return $this->sendError('','Something went wrong');
        }

    }

    /**
    * Cart & Checkout: Checkout
    * @authenticated
    * 
    * @bodyParam billing_address_id number 
    * @bodyParam shipping_address_id number 
    * @bodyParam payment_method string required
    * @bodyParam checkout_items array required array of cart_item_id from Cart & Checkout: My Cart Example:[1,2]
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

            /*if($cart->user_type != 'retailer')
            {
                return $this->sendError('',trans('orders_api.user_not_valid')); 
            }*/
            if($cart->user_type != 'retailer' ||  $user->user_type != 'retailer')
            {
                return $this->sendError('',trans('orders_api.user_not_valid'),'200','202'); 
            }


            foreach ($cart->order_items as $key => $cart_item) {
                $is_available = $this->isItemAvailableForUser($cart_item->product_id,$user->id);
                if($is_available == '0'){
                    //$cart_item->delete();
                    return $this->sendError('',trans('orders_api.item_not_valid'),'200','202'); 
                }
            }
          
            $this->updateCartCalc($cart->id);
            $address_required = '1';
            $payment_methods = $this->get_payment_methods($cart->user_type);
            if($cart->total_payable == 0.00){
              $payment_methods[] = '0-amount';
            }

            if($address_required == '1') {
                $adress_validation = 'required|exists:addresses,id';
            }else{
                $adress_validation = 'nullable';
            }

            if($cart->user_type == 'retailer')
            {
                $payment_method_validation = [
                    'required',
                    Rule::in($payment_methods)
                ];
            }
            else
            {
                $payment_method_validation = "nullable";
            }
            $validator=  Validator::make($request->all(),[
                'checkout_items'      => 'array|required',
                'checkout_items.*'    => 'required|exists:order_items,id,order_id,'.$cart->id,
                'billing_address_id'  => $adress_validation,
                'shipping_address_id' => $adress_validation,
                'payment_method'      => $payment_method_validation,
            ],[
                'checkout_items.required' => 'Please select at least one item to checkout.',
                'checkout_items.*.required' => 'Please select at least one item to checkout.',
                'checkout_items.*.exists' => 'item_invalid',
                'billing_address_id.required' => 'Billing address is required.',
                'shipping_address_id.required' => 'Shipping address is required.',
                'payment_method.required' => 'Payment method is required.'

            ]);


            //Create new return cart for not selected items
            $checkout_items = OrderItem::whereIn('id',$request->checkout_items)->get();

            //check whether product is active /publish and check whether book is instock or outof stock
            //foreach ($cart->order_items as $key => $cart_item) {
            foreach ($checkout_items as $key => $cart_item) {
                $item_name = '';
                $message = '';
                $is_active = $this->getAllActiveItemFromTypeAndId($cart->order_type, $cart_item->product_id);
                
                if(!$is_active){
                  $item_name = $cart_item->product->get_name();
                  $message = $item_name." is inactive now. please remove it from the cart to proceed.";
                  return $this->sendError('',$message); 
                }
                if($is_active) {
                    if($cart_item->product->stock_status == 'out_of_stock') {
                        $item_name = $cart_item->product->get_name();
                        $message = $item_name." is out of stock now. please remove it from the cart to proceed.";
                        return $this->sendError('',$message);  exit;
                    }
                }

            }

            if($validator->fails()) {
                return $this->sendValidationError('', $validator->errors()->first());
            }


            //Create new return cart for not selected items
            if($cart->order_items->count() > $checkout_items->count())
            {
                $new_cart = new Order();
                $user = Auth::guard('api')->user();
                $new_cart_data = [
                    'order_id'         => $this->generateOrderId(),
                    'user_id'          => $user->id,
                    'user_type'        => $user->user_type,
                    'total_mrp'        => '0',
                    'total_sale_price' => '0',
                    'discount_on_mrp'  => '0',
                    'delivery_charges' => '0',
                    'order_type'       => 'physical_books',
                    'order_status'     => 'pending',
                    'is_cart'          => '1'
                ];

                $new_cart->fill($new_cart_data);
                if($new_cart->save()){

                    $new_cart_items = OrderItem::where('order_id',$cart->id)
                              ->whereNotIn('id',$request->checkout_items)
                              ->get();

                    foreach($new_cart_items as $new_cart_item){
                      $new_cart_item->order_id = $new_cart->id;
                      $new_cart_item->save();
                    }


                    $new_cart = $this->updateCartCalc($new_cart->id);
                    $cart =  $this->updateCartCalc($cart->id);

                    DB::commit();
                  

                }else{
                    DB::rollback();
                    return $this->sendError('',trans('orders_api.new_cart_error'));
                }
            }
            if($address_required == '1') {
                $billing_address = Address::where('user_id',$user->id)->where('id',$request->billing_address_id)->first();
                $shipping_address = Address::where('user_id',$user->id)->where('id',$request->shipping_address_id)->first();
                if(!$billing_address) {
                    return $this->sendError('', trans('orders_api.billing_address_not_valid'));
                }
                if(!$shipping_address) {
                    return $this->sendError('', trans('orders_api.shipping_address_not_valid'));
                }


                $billing_address_data = [
                    'order_id'       => $cart->id,
                    'company_name'   => $billing_address->company_name,
                    'customer_name'  => $billing_address->contact_name,
                    'contact_number' => $billing_address->contact_number,
                    'email'          => $billing_address->email,
                    'city_id'        => $billing_address->post_code->city_id,
                    'city'           => $billing_address->city,
                    'area'           => $billing_address->area,
                    'house_no'       => $billing_address->house_no,
                    'street'         => $billing_address->street,
                    'landmark'       => $billing_address->landmark,
                    'state'          => $billing_address->state,
                    'postal_code'    => $billing_address->postcode,
                    'address_type'   => $billing_address->address_type,
                    'is_billing'     => '1',
                ];
                OrderAddress::create($billing_address_data);

                $shipping_address_data = [
                    'order_id'       => $cart->id,
                    'company_name'   => $shipping_address->company_name,
                    'customer_name'  => $shipping_address->contact_name,
                    'contact_number' => $shipping_address->contact_number,
                    'email'          => $shipping_address->email,
                    'city_id'        => $shipping_address->post_code->city_id,
                    'city'           => $shipping_address->city,
                    'area'           => $shipping_address->area,
                    'house_no'       => $shipping_address->house_no,
                    'street'         => $shipping_address->street,
                    'landmark'       => $shipping_address->landmark,
                    'state'          => $shipping_address->state,
                    'postal_code'    => $shipping_address->postcode,
                    'address_type'   => $shipping_address->address_type,
                    'is_shipping'    => '1',
                ];
                OrderAddress::create($shipping_address_data);
              
            }

            $address_id = isset($shipping_address) ? $shipping_address->id : 0;
            $response = [
              'url' => route('order_payment',
                        [
                            'cart_id'    => $cart->id,
                            'user_id'    => $user->id, 
                            'address_id' => $address_id,
                            'gateway'    => $request->payment_method
                        ])
            ];
            DB::commit();
            return $this->sendResponse($response, trans('common.data_found'));
        } catch(\Exception $e) {
            DB::rollback();
           //return $e->getMessage().$e->getLine();
            return $this->sendError('','Something went wrong');
        }

    }

    /**
    * Cart & Checkout: Checkout for dealer
    * @authenticated
    * 
    * @bodyParam billing_address_id number 
    * @bodyParam shipping_address_id number 
    * @bodyParam checkout_items array required array of cart_item_id from Cart & Checkout: My Cart Example:[1,2]
    *
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "Data Found Successfully",
      "data": { "url": "http://localhost/ssgc/public/order_payment/5/1/payu"}
    }
    */
    public function dealer_checkout(Request $request)
    {
        try{
            DB::beginTransaction();
            $user = Auth::guard('api')->user();
            //check any payment intiated for this user or not
          /*  if($this->closePaymentAttempt($user->id)){
                return $this->sendError('',trans('orders_api.order_under_process')); 
            }*/
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

            /*if($cart->user_type != 'dealer')
            {
                return $this->sendError('',trans('orders_api.user_not_valid')); 
            }*/
            if($cart->user_type != 'dealer' ||  $user->user_type != 'dealer')
            {
              return $this->sendError('',trans('orders_api.user_not_valid'),'200','202'); 
            }
            foreach ($cart->order_items as $key => $cart_item) {
                $is_available = $this->isItemAvailableForUser($cart_item->product_id,$user->id);
                if($is_available == '0'){
                    return $this->sendError('',trans('orders_api.item_not_valid'),'200','202'); 
                }
            }
           
            $address_required = '1';
            if($address_required == '1') {
                $adress_validation = 'required|exists:addresses,id';
            }else{
                $adress_validation = 'nullable';
            }

            
            $validator=  Validator::make($request->all(),[
                'checkout_items'      => 'array|required',
                'checkout_items.*'    => 'required|exists:order_items,id,order_id,'.$cart->id,
                'billing_address_id'  => $adress_validation,
                'shipping_address_id' => $adress_validation
            ],[
                'checkout_items.required' => 'Please select at least one item to checkout.',
                'checkout_items.*.required' => 'Please select at least one item to checkout.',
                'billing_address_id.required' => 'Billing address is required.',
                'shipping_address_id.required' => 'Shipping address is required.'

            ]);

            if($validator->fails()) {
                return $this->sendValidationError('', $validator->errors()->first());
            }
          
            //Create new cart for not selected items
            $checkout_items = OrderItem::whereIn('id',$request->checkout_items)->get();

            //check whether product is active /publish and check whether book is instock or outof stock
            //foreach ($cart->order_items as $key => $cart_item) {
            foreach ($checkout_items as $key => $cart_item) {
                $item_name = '';
                $message = '';
                $is_active = $this->getAllActiveItemFromTypeAndId($cart->order_type, $cart_item->product_id);
                 if(!$is_active){
                  $item_name = $cart_item->product->get_name();
                  $message = $item_name." is inactive now. please remove it from the cart to proceed.";
                  return $this->sendError('',$message);  exit;
                }

                if($is_active) {
                    if($cart_item->product->stock_status == 'out_of_stock') {
                        $item_name = $cart_item->product->get_name();
                        $message = $item_name." is out of stock now. please remove it from the cart to proceed.";
                        return $this->sendError('',$message);  exit;
                    }
                }
            }
          
            //Create new cart for not selected items
            if($cart->order_items->count() > $checkout_items->count())
            {
                $new_cart = new Order();
                $user = Auth::guard('api')->user();
                $new_cart_data = [
                    'user_id'          => $user->id,
                    'user_type'        => $user->user_type,
                    'total_mrp'        => '0',
                    'total_sale_price' => '0',
                    'discount_on_mrp'  => '0',
                    'delivery_charges' => '0',
                    'order_type'       => 'physical_books',
                    'order_status'     => 'pending',
                    'is_cart'          => '1'
                ];

                $new_cart->fill($new_cart_data);
                if($new_cart->save()){

                    $new_cart_items = OrderItem::where('order_id',$cart->id)
                              ->whereNotIn('id',$request->checkout_items)
                              ->get();

                    foreach($new_cart_items as $new_cart_item){
                      $new_cart_item->order_id = $new_cart->id;
                      $new_cart_item->save();
                    }


                    DB::commit();
                    $new_cart = $this->updateCartCalc($new_cart->id);
                    $cart =  $this->updateCartCalc($cart->id);

                }else{
                    DB::rollback();
                    return $this->sendError('',trans('orders_api.new_cart_error'));
                }
            }
            if($address_required == '1') {
                $billing_address = Address::where('user_id',$user->id)->where('id',$request->billing_address_id)->first();
                $shipping_address = Address::where('user_id',$user->id)->where('id',$request->shipping_address_id)->first();
                if(!$billing_address) {
                    return $this->sendError('', trans('orders_api.billing_address_not_valid'));
                }
                if(!$shipping_address) {
                    return $this->sendError('', trans('orders_api.shipping_address_not_valid'));
                }


                $billing_address_data = [
                    'order_id'       => $cart->id,
                    'company_name'   => $billing_address->company_name,
                    'customer_name'  => $billing_address->contact_name,
                    'contact_number' => $billing_address->contact_number,
                    'email'          => $billing_address->email,
                    'city_id'        => $billing_address->post_code->city_id,
                    'city'           => $billing_address->city,
                    'area'           => $billing_address->area,
                    'house_no'       => $billing_address->house_no,
                    'street'         => $billing_address->street,
                    'landmark'       => $billing_address->landmark,
                    'state'          => $billing_address->state,
                    'postal_code'    => $billing_address->postcode,
                    'address_type'   => $billing_address->address_type,
                    'is_billing'     => '1',
                ];
                OrderAddress::create($billing_address_data);

                $shipping_address_data = [
                    'order_id'       => $cart->id,
                    'company_name'   => $shipping_address->company_name,
                    'customer_name'  => $shipping_address->contact_name,
                    'contact_number' => $shipping_address->contact_number,
                    'email'          => $shipping_address->email,
                    'city_id'        => $shipping_address->post_code->city_id,
                    'city'           => $shipping_address->city,
                    'area'           => $shipping_address->area,
                    'house_no'       => $shipping_address->house_no,
                    'street'         => $shipping_address->street,
                    'landmark'       => $shipping_address->landmark,
                    'state'          => $shipping_address->state,
                    'postal_code'    => $shipping_address->postcode,
                    'address_type'   => $shipping_address->address_type,
                    'is_shipping'    => '1',
                ];
                OrderAddress::create($shipping_address_data);
              
            }           

            //update order details for dealer
            $cart->order_status   = 'on_hold';
            $cart->is_cart        = '0';
            $cart->payment_status = 'pending';
            $cart->placed_at   = date('Y-m-d H:i:s');
            // $this->generateInvoice($cart);
            $cart->save();
            DB::commit();
            //###### Send Order Placed Email ######
            if($user->email) {
                try
                {
                  $subject = "SSGC- We are happy to announce your order confirmation!";
                  Mail::to($user->email)->send(new SendOrderPlacedEmailCustomer($user,$cart,$subject));
                } catch(\Exception $e){
                  //skip sending mail and redirect to success page
                  return $this->sendResponse('', trans('orders_api.dealer_order_placed'));
                }
                
            }
            //###### Send Order Placed Email ######

            //###### Customer Order Placed Notification ######
            $title    = 'Order Placed Successfully';
             $body     = "Congratulations! Your order ".$cart->id.", worth ".$cart->total_payable." Rs. has been placed successfully.";
            $slug     = 'customer_order_placed';
            $this->sendNotification($user,$title,$body,$slug,$cart,null);
            //###### Customer Order Placed Notification ######

            //###### Admin New Order Placed ######
            $admin = User::where('user_type','admin')->first();
            if($admin){
                $title    = 'Customer Purchased New Product';
                $body     = "Customer purchased new product physical or digital product";
                $slug     = 'admin_new_order_placed';
                $this->sendNotification($admin,$title,$body,$slug,$cart,null);
            }
            //###### Admin New Order Placed ######

            //###### Send Order Placed SMS to Customer #####
            $send_order_placed_sms = $this->sendOrderStatusSMS('',$user->mobile_number,$cart,'order_placed');
            //###### Send Order Placed SMS to Customer #####
          
            DB::commit();
            return $this->sendResponse('', trans('orders_api.dealer_order_placed'));
        } catch(\Exception $e) {
            DB::rollback();
            //echo $e->getMessage(); die;
            return $this->sendError('','Something went wrong');
        }
    }

    /**
    * My Orders: My Orders
    * @authenticated
    *
    * @bodyParam type string required Order Type (Enums : upcoming, past) 
    * @bodyParam order_status array Order Status (Enums : on_hold,pending_payment,processing,shipped,completed,cancelled,refunded) 
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
    public function my_orders(Request $request) {
        if($request->type == 'upcoming')
        {
            $order_status = ['on_hold','pending_payment','processing','shipped'];
            $validator = Validator::make($request->all(),[
                'type'         => 'required|in:upcoming,past',
                'order_status'   => 'nullable|array',
                'order_status.*' => 'in:on_hold,pending_payment,processing,shipped'
            ]);
        }
        else
        {
            $order_status = ['completed','cancelled','refunded','failed'];
            $validator = Validator::make($request->all(),[
                'type'           => 'required|in:upcoming,past',
                'order_status'   => 'nullable|array',
                'order_status.*' => 'in:completed,cancelled,refunded,failed'
            ]);
        }
        $user = Auth::guard('api')->user();
        $total_orders = array();
        $order_pending = array();
       
        if(!empty($request->order_status))
        {
           /* if(in_array('under_process',$request->order_status) && count($request->order_status)== 1){
                $orders_placed = Order::where('user_id',$user->id)
                ->where('order_status','pending')->where('is_payment_attempt','1')->where('order_type','physical_books')->pluck('id')->toArray();
            }else if(in_array('under_process',$request->order_status)){
                $orders_placed = Order::where('user_id',$user->id)->whereIn('order_status',$request->order_status)->where('order_type','physical_books')->pluck('id')->toArray();  
                $order_pending = Order::where('user_id',$user->id)
                  ->where('order_status','pending')->where('is_payment_attempt','1')->where('order_type','physical_books')->pluck('id')->toArray();
              //  $total_orders = array_merge($total_orders,$order_pending); 
            }else{*/
                $order_status = $request->order_status; 
                $orders_placed = Order::where('user_id',$user->id)
                 ->where('order_status','<>','pending')->where('order_type','physical_books')->whereIn('order_status',$order_status)->pluck('id')->toArray();  
            //}
        }else{
            $orders_placed = Order::where('user_id',$user->id)
              ->where('order_status','<>','pending')->whereIn('order_status',$order_status)->pluck('id')->toArray(); 
           /* $order_pending = Order::where('user_id',$user->id)
                  ->where('order_status','pending')->where('is_payment_attempt','1')->pluck('id')->toArray();*/
        }

        if($validator->fails()) {
              return $this->sendValidationError('', $validator->errors()->first());
        }
        $total_orders = array_merge($total_orders,$orders_placed);
      
        /*if($request->type == 'upcoming')
        {
            $total_orders = array_merge($total_orders,$order_pending); 
        }else {
            $total_orders = $total_orders; 
        }*/
        //$orders = Order::whereIn('id',$total_orders)->where('order_type','physical_books')->orderBy('created_at','DESC')->paginate();
        $orders = Order::whereIn('id',$total_orders);
        $orders = $orders->where('order_type','physical_books')->orderBy('id','DESC')->paginate();
       /* $orders = Order::where('user_id',$user->id)
                  ->whereIn('order_status',$order_status)
                 // ->orderBy('created_at','DESC')
                  ->orderBy('placed_at','DESC')
                  ->where('order_type','physical_books')
                  ->paginate();  */     

        if(count($orders) > 0){
          return $this->sendPaginateResponse(OrderListResource::Collection($orders), trans('common.data_found'));
        } else {
          return $this->sendResponse([], trans('common.no_data'));
        }
    }

    /**
    * My Orders: Order Detail
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
    public function order_detail($order_id) {
        $user = Auth::guard('api')->user();
        $order = Order::where('id',$order_id)->where('user_id', $user->id)->where('order_type','physical_books')->first();
      //    $order = Order::where('id',$order_id)->where('user_id', $user->id)->first();
        if(!$order) {
          return $this->sendError('', trans('common.no_data'));
        }
        return $this->sendResponse(new OrderDetailResource($order), trans('common.data_found'));
    }

    /**
    * My Orders: Order Item Details
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
    public function order_item_details($order_item_id) {
        $user = Auth::guard('api')->user();
        $order_item = OrderItem::where('id',$order_item_id)->first();
        if(!$order_item) {
          return $this->sendError('', trans('common.no_data'));
        }
        return $this->sendResponse(new OrderItemDetailResource($order_item), trans('common.data_found'));
    }

    public function markOrderFailed(){
        $user = Auth::guard('api')->user();
        $cart = Order::where('user_id',$user->id)->where('is_cart','1')->where('is_payment_attempt','1')->latest()->first();

        if($cart){
            $data = $this->verifyPaymentForPayu($cart);
            if($data){
              if($data['status'] == 'success'){
                $this->markOrderStatusAsSuccess($cart->id,$user,$data);
                return $this->sendResponse('',trans('orders_api.order_placed'));
              }else{
                $this->markOrderStatusAsFailed($cart->id,$user,null,1);
                return $this->sendResponse('',trans('orders_api.order_marked_failed'));
              } 
            }
        }
        else {
            return $this->sendError('', trans('orders_api.order_status_already_updated'));
        }
    }

}