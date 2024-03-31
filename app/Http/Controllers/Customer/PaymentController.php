<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
use App\Models\Payment;
use App\Models\Address;
use App\Models\Order;
use App\Models\SubscriptionPackage;
use App\Models\UserSubscription;
use App\Models\OrderShipment;
use DB;
use Auth;
use Carbon\Carbon;
use App\Models\Helpers\CommonHelper;
use App\Models\Helpers\PaymentHelper;
use App\Mail\SendOrderPlacedEmailCustomer;
use App\Mail\SendOrderFailedEmailAdmin;
use Illuminate\Support\Facades\Mail;
use App\Jobs\GetOrderStatusFromPayu;

class PaymentController extends Controller
{
  use CommonHelper,PaymentHelper;
		

    // ######### FOR ORDERS ##########

    public function order_payment(Request $request)
    { 
      // print_r($request->all());die;
      try {
        DB::beginTransaction();

        $customer = User::where(['id' => @$request->user_id,'verified' => '1', 'status' => 'active'])->whereIn('user_type',['retailer','dealer'])->first();
        $admin = User::where('user_type','admin')->first();
        $settings = Setting::pluck('value','name')->all();
        $admin_email = $settings['admin_email'];
        
        if(!$customer) {
          echo 'You are not verified user'; exit;
        }

        if((string)$request->address_id != '0') {
          $address = Address::where(['user_id' => $customer->id, 'id' => @$request->address_id])->first();
          if(!$address) {
            echo 'Address is not valid'; exit;          
          }
        }

        $cart_id = @$request->cart_id;
        $order = Order::where('id',$cart_id)->where('user_id',$customer->id)->where('is_cart','1')->first();
        if(!$order) {
            echo trans('orders_api.cart_is_empty'); exit; 
        }

       if($order->order_type == 'physical_books') {
          //check whether product is active /publish and check whether book is instock or outof stock
          foreach ($order->order_items as $key => $cart_item) {
            $item_name = '';
            $message = '';
            $is_active = $this->getAllActiveItemFromTypeAndId($order->order_type, $cart_item->product_id);
            if(!$is_active){
              $item_name = $cart_item->product->get_name();
              $message = $item_name." is inactive now. please remove it from the cart to proceed.";
              $subject = "SSGC BO - Order Failed Due to inactive product for Order Id : ".$order->id;
              \Log::info($item_name."Physical Product inactive/unpublish Product Id: ".$cart_item->coupon_id." Order Id :".$order->order_id);
              if($admin_email){
                Mail::to($admin_email)->send(new SendOrderFailedEmailAdmin($admin,$order,$subject));
              }
              return redirect('order_error'); 
            }
            if($is_active) {
              if($cart_item->product->stock_status == 'out_of_stock') {
                $item_name = $cart_item->product->get_name();
                $message = $item_name." is out of stock now. please remove it from the cart to proceed.";
                $subject = "SSGC BO - Order Failed Due to  out of stock product for Order Id : ".$order->id;
                \Log::info($item_name." Physical Product inactive/unpublish Product Id: ".$cart_item->coupon_id." Order Id :".$order->order_id);
                if($admin_email){
                  Mail::to($admin_email)->send(new SendOrderFailedEmailAdmin($admin,$order,$subject));
                }
                return redirect('order_error'); 
              }
            }
          }
        }
        else if($order->order_type == 'digital_coupons')
        {
           //check whether coupon is active /publish and check whether coupon qty is available or not
            foreach ($order->order_items as $key => $cart_item) {
                $item_name = '';
                $message = '';
                $is_active = $this->getAllActiveItemFromTypeAndId($order->order_type, $cart_item->coupon_id);
                if(!$is_active){
                  $item_name = $cart_item->coupon->coupon->name;
                  $message = $item_name." is inactive now. please remove it from the cart to proceed.";
                  if($admin_email){
                    Mail::to($admin_email)->send(new SendOrderFailedEmailAdmin($admin,$order,$subject));
                  }
                  \Log::info($item_name."Digital Product inactive/unpublish Product Id: ".$cart_item->coupon_id." Order Id :".$order->order_id);
                  return redirect('order_error'); 
                }

                if($is_active){
                    if($is_active->available_quantity < $cart_item->ordered_quantity) {
                        $item_name = $cart_item->coupon->coupon->name;
                        $message = "Requested quantity for ". $item_name."  is not available. Available qty :".$is_active->available_quantity;
                        $subject = "SSGC BO - Order Failed Due to digital product out of stock for Order Id : ".$cart->id;
                        if($admin_email){
                          Mail::to($admin_email)->send(new SendOrderFailedEmailAdmin($admin,$cart,$subject));
                        }
                        \Log::info($item_name."Digital Product requested quantity not available for Product Id: ".$cart_item->coupon_id." Order Id :".$cart->id);
                        return redirect('order_error'); 
                    }
                    if($cart_item->coupon->coupon->end_date <= Carbon::now()->format('Y-m-d H:i')){
                        $item_name = $cart_item->coupon->coupon->name;
                        $message = "This coupon ". $item_name."  is expired.Please remove it from the cart.";
                         $subject = "SSGC BO - Order Failed Due to digital product expired for Order Id : ".$cart->id;
                        if($admin_email){
                          Mail::to($admin_email)->send(new SendOrderFailedEmailAdmin($admin,$order,$subject));
                        }
                        \Log::info($item_name."Digital Product expired for Product Id: ".$cart_item->coupon_id." Order Id :".$order->id);
                        return redirect('order_error'); 
                    }
                }

            }
        } 

        // online payment for digital coupon (for both retailer and dealer) and physical books (for only retailer) 
        $gateway = @$request->gateway;
        if(!($gateway == 'ccavenue' || $gateway == 'payu' || $gateway == '0-amount')) {
          echo 'Gateway is wrong, it should be ccavenue or payu '; exit;
        }

        $settings = Setting::pluck('value','name')->all();

        $payment               = new Payment();
        $payment->user_id      = $customer->id;
        $payment->payment_for  = 'order';
        $payment->order_id     = $order->id;
        $payment->amount       = $order->total_payable;
        $payment->payment_type = $gateway;
        $payment_res           = $payment->save();

        $order->payment_type = $gateway;
        $order->save();
        
        DB::commit();

        $payment_mode = $gateway;
        //Check Payment Option
        if($payment_mode == 'ccavenue'){

          //Generate Request
          $data = array();
          $data['merchant_id'] = $settings['ccavenue_merchant_id']; 
          $data['order_id'] = $order->id; //Make it Dynamic Value
          $data['amount'] = $order->total_payable; //Make it Dynamic Value
          $data['currency'] = 'INR';
          $data['redirect_url'] = route('ccavenue_callback_order',['user_id' => $customer->id, 'cart_id' => $cart_id]);//
          $data['language'] = 'EN';

          //The above are required parameters.Check views/admin/payment/ccavenue_request.blade.php for all optional request parameters.

          $working_key = $settings['ccavenue_working_key'];//Shared by CCAVENUES
          $access_code = $settings['ccavenue_access_code'];//Shared by CCAVENUES
          $merchant_data = '';

          foreach ($data as $key => $value){
            $merchant_data.=$key.'='.$value.'&';
          }

          if($settings['ccavenue_mode'] == 'sandbox'){
            $payment_url = $settings['ccavenue_sandbox_url'].'transaction/transaction.do?command=initiateTransaction';
          }else{
            $payment_url = $settings['ccavenue_production_url'].'transaction/transaction.do?command=initiateTransaction';
          }

          $encrypted_data = $this->encrypt($merchant_data,$working_key);

          // Update Is Payment attempt status  :: start
          //$order->is_cart = '0';
          $order->placed_at = date('Y-m-d H:i:s');
          $order->is_payment_attempt = '1';
          $order->save();
          // Update Is Payment attempt status  :: end
          //Will Redirect To Payment Page 
          return view('customer.payment.ccavenue_redirect',compact('encrypted_data','access_code','payment_url')); 


        } else if($payment_mode == 'payu'){

          if($settings['payu_mode'] == 'sandbox'){
            $payment_url = $settings['payu_sandbox_url'].'_payment';
            $key = $settings['payu_sandbox_key'];
            $salt = $settings['payu_sandbox_salt'];
          }else{
            $payment_url = $settings['payu_production_url'].'_payment';
            $key = $settings['payu_live_key'];
            $salt = $settings['payu_live_salt'];
          }

          $data = new \stdClass();
          $data->payment_url = $payment_url;
          $data->key = $key;
          $data->salt = $salt;
          //Transaction ID (There is no length requirement, only needs to be unique)
          //Can Directly Use Order Id
          //$data->txnid    = \Str::random(10);
          $data->txnid = $order->order_id;
          $data->amount = $order->total_payable;
          $data->productinfo =  'Purchase items for Order ID: id='.$order->id;
          $data->firstname = $customer->first_name ? $customer->first_name : 'Customer';
          $data->lastname = $customer->last_name ? $customer->last_name : '';
          $data->email = $customer->email;
          $data->phone = $customer->mobile_number;
          //If you change Success/Failure Redirect Url, Change route's CSRF Token Exception in app/Http/Middleware/VerifyCsrfToken.php 
          $data->surl = route('payu_callback_order',['user_id' => $customer->id, 'cart_id' => $cart_id]);//Success
          $data->furl = route('payu_callback_order',['user_id' => $customer->id, 'cart_id' => $cart_id]); //Failure

          //Genrate Hash Token
          $hash_string = $data->key.'|'.$data->txnid.'|'.$data->amount.'|'.$data->productinfo.'|'.$data->firstname.'|'.$data->email.'|||||||||||'.$data->salt;
          $hash = hash('sha512', $hash_string);
          \Log::info("Hash String:".$hash_string);
          $data->hash = $hash;

        	  // check whether any parameter is missing for payu request 
        	  if(($data->txnid == '' || $data->txnid == null)|| ($data->key == '' || $data->key == null )||($data->amount == '' || $data->amount == null) || ($data->firstname == '' || $data->firstname == null)  || ($data->email == '' || $data->email == null) || ($data->salt == '' || $data->salt == null)){
                      return redirect('order_payment_error');
              }

          // Update Is Payment attempt status  :: start
          //$order->is_cart = '0';
          $order->placed_at = date('Y-m-d H:i:s');
          $order->is_payment_attempt = '1';
          $order->save();
          // Update Is Payment attempt status  :: end
          //Save Transaction ID (txnid) with Order data in Table For Later Verification
          $delay = $settings['payu_job_delay_in_seconds'];
          $job = (new GetOrderStatusFromPayu($order->id))->delay($delay);
          \Log::info("job dispatched : ");
          dispatch($job); 
          return view('customer.payment.payu_redirect',compact('data')); 

        }
        else
        {
          //0-amount
          $user = User::find($customer->id);
          $cart = Order::where('id',$cart_id)->where('user_id',@$user->id)->where('is_cart','1')->first();
          if(!$cart) {
            return redirect('order_payment_error');
          }

          $cart->order_status   = 'processing';
          if($cart->order_type == 'digital_coupons')
          {
            $cart->order_status   = 'completed';
          }
          $cart->placed_at          = date('Y-m-d H:i:s');
          $cart->is_cart            = '0';
          $cart->is_payment_attempt = '2';
          $cart->payment_status     = 'paid';
          // $this->generateInvoice($cart);
          $cart->save();

          $payment = Payment::where(['order_id'=>$cart->id, 'user_id' => $user->id, 'payment_type' => '0-amount'])->latest()->first();
          $payment->status = 'paid';
          $payment->save();
          DB::commit();
        
          //###### Send Order Placed Email ######
          if($user->email) {
              try
              {
                $subject = "SSGC- We are happy to announce your order confirmation!";
                Mail::to($user->email)->send(new SendOrderPlacedEmailCustomer($user,$cart,$subject));
              } catch(\Exception $e){
                //skip sending mail and redirect to success page
                if($cart->order_type == 'physical_books')
                {
                  return redirect('order_payment_success');
                }
                else
                {
                  return redirect('order_payment_success?type=coupon');
                }
              }
          }
          //###### Send Order Placed Email ######

          //###### Customer Order Placed Notification ######
          $title    = 'Order Placed Successfully';
         // $body     = "Congratulations! Your order ".$cart->order_id.", worth ".$cart->total_payable." Rs. has been placed successfully.";
           $body     = "Congratulations! Your order ".$cart->id.", worth ".$cart->total_payable." Rs. has been placed successfully.";

          if($cart->order_type == 'physical_books')
          {
            $slug = 'customer_order_placed';
          }
          else
          {
            $slug = 'customer_coupon_order_placed';
          }
          \Log::info("Slug".$slug);
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

          //only if digital coupon purchased
          if($cart->order_type == 'digital_coupons')
          {
            //assign QR Codes and update refer history
            $this->assign_qr_codes($cart);
            $this->maintainReferralHistory((integer)$cart->redeemed_points, 'deducted', 'no_deduct',$user,'',$cart->id);
          }

          if($cart->order_type == 'physical_books')
          {
            return redirect('order_payment_success');
          }
          else
          {
            return redirect('order_payment_success?type=coupon');
          }
        }
        
        
      } catch(\Exception $e){
        // return redirect('order_payment_error');
        \Log::info("Error From order payment :".$e->getMessage());
        echo $e->getMessage(); exit;
      }
    }

    public function payu_callback_order(Request $request, $user_id, $cart_id)
    {
      try{
        DB::beginTransaction();
        //PayU Money Sample Response

        // Array ( [mihpayid] => 403993715526336513 [mode] => CC [status] => success [unmappedstatus] => captured [key] => gtKFFx [txnid] => C2DgoBQb111ZmkkKK [amount] => 10.00 [cardCategory] => domestic [discount] => 0.00 [net_amount_debit] => 10 [addedon] => 2022-06-01 11:44:49 [productinfo] => iPhone [firstname] => Ashish [lastname] => Kumar [address1] => [address2] => [city] => [state] => [country] => [zipcode] => [email] => test@gmail.com [phone] => 9988776655 [udf1] => [udf2] => [udf3] => [udf4] => [udf5] => [udf6] => [udf7] => [udf8] => [udf9] => [udf10] => [hash] => e3d17d90132a4de84f4c4efc3223ae902083002f7396c84d1adca5427c40d6d5649f47ef56c6f3770ccab7d7852fd2c6a3e29b11a966ae3fb71f42bef67bb7a5 [field1] => 810440 [field2] => 706338 [field3] => 20220601 [field4] => 0 [field5] => 247074285622 [field6] => 00 [field7] => AUTHPOSITIVE [field8] => Approved or completed successfully [field9] => No Error [payment_source] => payu [PG_TYPE] => AXISPG [bank_ref_num] => 810440 [bankcode] => VISACC [error] => E000 [error_Message] => No Error [name_on_card] => Test [cardnum] => 401200XXXXXX1112 [cardhash] => This field is no longer supported in postback params. [issuing_bank] => AXIS [card_type] => VISA )

        $data = $request->all();
        $admin = User::where('user_type','admin')->first();
        $user = User::find($user_id);
        
       // $cart = Order::where('id',$cart_id)->where('user_id',@$user->id)->where('is_cart','1')->first();
        //$cart = Order::where('id',$cart_id)->where('user_id',@$user->id)->first();
        $cart = Order::where('order_id',$data['txnid'])->first();
        $settings = Setting::pluck('value','name')->all();
        $admin_email = $settings['admin_email'];
              
        if(!$cart) {
          return redirect('order_payment_error');
        }

        if($data['status'] == 'success'){
        /*  $cart = Order::where('id',$cart_id)->where('user_id',@$user->id)->where('is_cart','1')->first();
         if(!$cart) {
            return redirect('order_payment_error');
          }*/
          $cart->transaction_id = $data['mihpayid'];

          $cart->order_status   = 'processing';
          if($cart->order_type == 'digital_coupons')
          {
            $cart->order_status   = 'completed';
          }

          $cart->placed_at      = date('Y-m-d H:i:s');
          $cart->is_cart        = '0';
          $cart->payment_status = 'paid';
          $cart->is_payment_attempt = '2';
         // $this->generateInvoice($cart);
          $cart->save();

          $payment = Payment::where(['order_id' => $cart->id, 'user_id' => $user->id, 'payment_type' => 'payu'])->latest()->first();
          $payment->tran_ref     = $data['mihpayid'];
          $payment->api_response = json_encode($data);
          $payment->status       = 'paid';
          $payment->save();
          DB::commit();

          //check whether product/coupon is active /publish and check whether book is instock or outof stock ::start
          if($cart->order_type == 'physical_books') 
          {
            foreach ($cart->order_items as $key => $cart_item) {
              $item_name = '';
              $message = '';
              $is_active = $this->getAllActiveItemFromTypeAndId($cart->order_type, $cart_item->product_id);
                    
              if(!$is_active){
                $cart->order_status = 'failed';
                $cart->save();
                $item_name = $cart_item->product->get_name();
                $message = $item_name." is inactive now. please remove it from the cart to proceed.";
                $subject = "SSGC BO - Order Failed Due to inactive/unpublish product for Order Id : ".$cart->id;
                if($admin_email){
                  Mail::to($admin_email)->send(new SendOrderFailedEmailAdmin($admin,$cart,$subject));
                }
                \Log::info($item_name."Physical Product inactive/unpublish Product Id: ".$cart->product_id." Order Id :".$cart->order_id);
                return redirect('order_error'); 
              }
              if($is_active) {
                if($cart_item->product->stock_status == 'out_of_stock') {
                  $cart->order_status = 'failed';
                  $cart->save();
                  $item_name = $cart_item->product->get_name();
                  $message = $item_name." is out of stock now. please remove it from the cart to proceed.";
                  $subject = "SSGC BO - Order Failed Due to out of stock product for Order Id : ".$cart->id;
                  if($admin_email){
                    Mail::to($admin_email)->send(new SendOrderFailedEmailAdmin($admin,$cart,$subject));
                  }
                  \Log::info($item_name." Product out of stock Product Id: ".$cart_item->product_id." Order Id :".$cart->id);
                  return redirect('order_error'); 
                }
              }
            }
          }else if($cart->order_type == 'digital_coupons'){
            //check whether coupon is active /publish and check whether coupon qty is available or not
            foreach ($cart->order_items as $key => $cart_item) {
                $item_name = '';
                $message = '';
                $is_active = $this->getAllActiveItemFromTypeAndId($cart->order_type, $cart_item->coupon_id);
                if(!$is_active){
                  $cart->order_status = 'failed';
                  $cart->save();
               //   $cart_item->coupon->increment('available_quantity',$cart_item->supplied_quantity);
                  $item_name = $cart_item->coupon->coupon->name;
                  $message = $item_name." is inactive now. please remove it from the cart to proceed.";
                  $subject = "SSGC BO - Order Failed Due to digital product inactive/unpublish for Order Id : ".$cart->id;
                  if($admin_email){
                    Mail::to($admin_email)->send(new SendOrderFailedEmailAdmin($admin,$cart,$subject));
                  }
                  \Log::info($item_name."Digital Product inactive/unpublish Product Id: ".$cart_item->coupon_id." Order Id :".$cart->order_id);
                  return redirect('order_error'); 
                  //return $this->sendError('',$message); exit;
                }
                if($is_active){
                    if($is_active->available_quantity < $cart_item->ordered_quantity) {
                        $cart->order_status = 'failed';
                        $cart->save();
                        $item_name = $cart_item->coupon->coupon->name;
                        $message = "Requested quantity for ". $item_name."  is not available. Available qty :".$is_active->available_quantity;
                        $subject = "SSGC BO - Order Failed Due to digital product out of stock for Order Id : ".$cart->id;
                        if($admin_email){
                          Mail::to($admin_email)->send(new SendOrderFailedEmailAdmin($admin,$cart,$subject));
                        }
                        \Log::info($item_name."Digital Product requested quantity not available for Product Id: ".$cart_item->coupon_id." Order Id :".$cart->id);
                        return redirect('order_error'); 
                       // return $this->sendError('',$message);
                    }

                    if($cart_item->coupon->coupon->end_date <= Carbon::now()->format('Y-m-d H:i')){
                        $item_name = $cart_item->coupon->coupon->name;
                        $message = "This coupon ". $item_name."  is expired.Please remove it from the cart.";
                         $subject = "SSGC BO - Order Failed Due to digital product expired for Order Id : ".$cart->id;
                        if($admin_email){
                          Mail::to($admin_email)->send(new SendOrderFailedEmailAdmin($admin,$cart,$subject));
                        }
                        \Log::info($item_name."Digital Product expired for Product Id: ".$cart_item->coupon_id." Order Id :".$cart->id);
                        return redirect('order_error'); 
                    }
                }
            }
          }  
          //check whether product/coupon is active /publish and check whether book is instock or outof stock ::start

          //only if digital coupon purchased
          if($cart->order_type == 'digital_coupons')
          {
            $this->assign_qr_codes($cart);
            $this->maintainReferralHistory((integer)$cart->redeemed_points, 'deducted', 'no_deduct',$user,'',$cart->id);
          }

        
          //###### Customer Order Placed Notification ######
          $title    = 'Order Placed Successfully';
         // $body     = "Congratulations! Your order ".$cart->order_id.", worth ".$cart->total_payable." Rs. has been placed successfully.";
           $body     = "Congratulations! Your order ".$cart->id.", worth ".$cart->total_payable." Rs. has been placed successfully.";

          if($cart->order_type == 'physical_books')
          {
            $slug = 'customer_order_placed';
          }
          else
          {
            $slug = 'customer_coupon_order_placed';
          }
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

	   //###### Send Order Placed Email ######
          if($user->email) {
              try
              {
                $subject = "SSGC- We are happy to announce your order confirmation!";
                Mail::to($user->email)->send(new SendOrderPlacedEmailCustomer($user,$cart,$subject));
              } catch(\Exception $e){
                //skip sending mail and redirect to success page
                // return redirect('order_payment_success');
                if($cart->order_type == 'physical_books')
                {
                  return redirect('order_payment_success');
                }
                else
                {
                  return redirect('order_payment_success?type=coupon');
                }
              }
          }
          //###### Send Order Placed Email ######
          // return redirect('order_payment_success');

          if($cart->order_type == 'physical_books')
          {
            return redirect('order_payment_success');
          }
          else
          {
            return redirect('order_payment_success?type=coupon');
          }
        } else {
          
          //to mark order failed and create duplicate order 
          $cart->order_status = 'failed';
          $cart->placed_at = date('Y-m-d H:i:s');
          $cart->is_cart = '0';
          $cart->is_payment_attempt = '2';
          $cart->payment_status = 'failed';
          $cart->save();

       
          $payment = Payment::where(['order_id' => $cart->id, 'user_id' => $user->id, 'payment_type' => 'payu'])->latest()->first();
          $payment = Payment::find($payment->id);
          $payment->tran_ref = $data['mihpayid'];
          $payment->api_response = json_encode($data);
          $payment->status = 'failed';
          $payment->save();   
          $id = $this->createDuplicateOrder($cart->id); 
  
          $cart = Order::where('id',$cart_id)->where('user_id',@$user_id)->where('is_cart','1')->first();
          
          if($cart)
          {
            if($cart->order_type == 'physical_books')
            {
              $this->mergeCart($cart_id);
            }
          }

          DB::commit();    
          return redirect('order_payment_error');
        }
        
      }catch(\Exception $e){
        \Log::info("Error From payu method :".$e->getMessage());
        //echo "<pre>"; print_r($e->getMessage()); exit;
        
          //to mark order failed and create duplicate order 
          $cart->order_status = 'failed';
          $cart->placed_at = date('Y-m-d H:i:s');
          $cart->is_cart = '0';
          $cart->is_payment_attempt = '2';
          $cart->payment_status = 'failed';
          $cart->save();

          $payment = Payment::where(['order_id' => $cart->id, 'user_id' => $user->id, 'payment_type' => 'payu'])->latest()->first();
          $payment = Payment::find($payment->id);
          $payment->tran_ref = $data['mihpayid'];
          $payment->api_response = json_encode($data);
          $payment->status = 'failed';
          $payment->save();   
          $id = $this->createDuplicateOrder($cart->id); 

        //###### Admin New Order failed ######
          $admin = User::where('user_type','admin')->first();
          if($admin){
              $title    = 'Customer Purchased New Product';
              $body     = "Customer purchased new product physical or digital product";
              $slug     = 'customer_order_failed';
              $this->sendNotification($admin,$title,$body,$slug,$cart,null);
          }
          //###### Admin New Order failed ######

          $user = User::find($user_id);
          if($user){
              $title    = 'Your Order has been failed';
              $body     = "Your order has been failed due to some reason";
              $slug     = 'customer_order_failed';
              $this->sendNotification($admin,$title,$body,$slug,$cart,null);
          }

          $cart = Order::where('id',$cart_id)->where('user_id',@$user_id)->where('is_cart','1')->first();
            if($cart)
            {
              if($cart->order_type == 'physical_books')
              {
                $this->mergeCart($cart_id);
              }
            }
        
        DB::commit();
        return redirect('order_payment_error');
      }
    }

    public function ccavenue_callback_order(Request $request, $user_id, $cart_id)
    {
      DB::beginTransaction();
      try{
        $working_key = Setting::get('ccavenue_working_key');//Shared by CCAVENUES
        $res = $this->decrypt($request->encResp,$working_key);
        $settings = Setting::pluck('value','name')->all();
        $admin_email = $settings['admin_email'];
        $user = User::find($user_id);
      
        if($res == null){
          $cart = Order::where('id',$cart_id)->where('user_id',@$user_id)->where('is_cart','1')->first();
          if($cart->order_type == 'physical_books')
          {
            $this->mergeCart($cart_id);
          }
          DB::commit();
          return redirect('order_payment_error');
        }
        //Sample Response
        // order_id=123654789&tracking_id=111523954988&bank_ref_no=null&order_status=Initiated&failure_message=&payment_mode=Credit Card&card_name=Visa&status_code=&status_message=card not supported¤cy=INR&amount=1.00&billing_name=Charli&billing_address=Room no 1101, near Railway station Ambad&billing_city=Indore&billing_state=MP&billing_zip=425001&billing_country=India&billing_tel=9876543210&billing_email=test@test.com&delivery_name=Charli&delivery_address=Room no 1101, near Railway station Ambad&delivery_city=Indore&delivery_state=MP&delivery_zip=425001&delivery_country=India&delivery_tel=9876543210&merchant_param1=additional Info&merchant_param2=additional Info&merchant_param3=additional Info&merchant_param4=additional Info&merchant_param5=additional Info&vault=N&offer_type=null&offer_code=null&discount_value=0.0&mer_amount=1.00&eci_value=&retry=null&response_code=&billing_notes=&trans_date=03/06/2022 13:34:42&bin_country=

        //Convert String Response To Array
        $res = explode('&',$res);
        $response = array();

        foreach ($res as $value) {
          $tmp = explode('=', $value);
          $response[$tmp[0]] = $tmp[1];
        }
       
        $cart = Order::where('id',$cart_id)->where('user_id',@$user_id)->first();
        
        if($response != null && $response['order_status'] == 'Success'){

          $user = User::find($user_id);
         // $cart = Order::where('id',$cart_id)->where('user_id',@$user->id)->where('is_cart','1')->first();
          $cart = Order::where('id',$cart_id)->where('user_id',@$user->id)->first();
          if(!$cart) {
            return redirect('order_payment_error');
          }
          $cart->transaction_id = $response['tracking_id'];

          $cart->order_status   = 'processing';
          if($cart->order_type == 'digital_coupons')
          {
            $cart->order_status   = 'completed';
          }
          
          $cart->placed_at      = date('Y-m-d H:i:s');
          $cart->is_cart = '0';
          $cart->payment_status = 'paid';
          $cart->is_payment_attempt = '2';
          // $this->generateInvoice($cart);
          $cart->save();

          $payment = Payment::where(['order_id'=>$cart->id, 'user_id' => $user->id, 'payment_type' => 'ccavenue'])->latest()->first();
          $payment->tran_ref = $response['tracking_id'];
          $payment->api_response = json_encode($response);
          $payment->status = 'paid';
          $payment->save();
          DB::commit();


          //check whether product is active /publish and check whether book is instock or outof stock :: start
          if($cart->order_type == 'physical_books') 
          {
            //check whether product is active /publish and check whether book is instock or outof stock
            foreach ($cart->order_items as $key => $cart_item) {
              $item_name = '';
              $message = '';
              $is_active = $this->getAllActiveItemFromTypeAndId($cart->order_type, $cart_item->product_id);
                    
              if(!$is_active){
                $cart->order_status = 'failed';
                $cart->save();
                $item_name = $cart_item->product->get_name();
                $message = $item_name." is inactive now. please remove it from the cart to proceed.";
                $subject = "SSGC BO - Order Failed Due to inactive product for Order Id : ".$cart->id;
                if($admin->email){
                  Mail::to($admin->email)->send(new SendOrderFailedEmailAdmin($admin,$cart,$subject));
                }
                \Log::info($item_name." Product out of stock Product Id: ".$cart->item_id." Order Id :".$cart->order_id);
                return redirect('order_error'); 
              }
              if($is_active) {
                if($cart_item->product->stock_status == 'out_of_stock') {
                  $cart->order_status = 'failed';
                  $cart->save();
                  $item_name = $cart_item->product->get_name();
                  $message = $item_name." is out of stock now. please remove it from the cart to proceed.";
                  $subject = "SSGC BO - Order Failed Due to  out of stock product for Order Id : ".$cart->id;
                  if($admin->email){
                    Mail::to($admin->email)->send(new SendOrderFailedEmailAdmin($admin,$cart,$subject));
                  }
                  \Log::info($item_name." Product out of stock Product Id: ".$cart_item->item_id." Order Id :".$cart->order_id);
                  return redirect('order_error'); 
                }

              }
            }
          }else if($cart->order_type == 'digital_coupons'){
            //check whether coupon is active /publish and check whether coupon qty is available or not
            foreach ($cart->order_items as $key => $cart_item) {
                $item_name = '';
                $message = '';
                $is_active = $this->getAllActiveItemFromTypeAndId($cart->order_type, $cart_item->coupon_id);
                if(!$is_active){
                  $cart->order_status = 'failed';
                  $cart->save();
                  $item_name = $cart_item->coupon->coupon->name;
                 // $cart_item->coupon->increment('available_quantity',$cart_item->supplied_quantity);
               
                  $message = $item_name." is inactive now. please remove it from the cart to proceed.";
                  $subject = "SSGC BO - Order Failed Due to digital product inactive/unpublish for Order Id : ".$cart->id;
                  if($admin_email){
                    Mail::to($admin_email)->send(new SendOrderFailedEmailAdmin($admin,$cart,$subject));
                  }
                  \Log::info($item_name."Digital Product inactive/unpublish Product Id: ".$cart_item->coupon_id." Order Id :".$cart->order_id);
                  return redirect('order_error'); 
                  //return $this->sendError('',$message); exit;
                }
                if($is_active){
                    if($is_active->available_quantity < $cart_item->ordered_quantity) {
                        $cart->order_status = 'failed';
                        $cart->save();
                        $item_name = $cart_item->coupon->coupon->name;
                        $message = "Requested quantity for ". $item_name."  is not available. Available qty :".$is_active->available_quantity;
                        $subject = "SSGC BO - Order Failed Due to digital product out of stock for Order Id : ".$cart->id;
                        if($admin_email){
                          Mail::to($admin_email)->send(new SendOrderFailedEmailAdmin($admin,$cart,$subject));
                        }
                        \Log::info($item_name."Digital Product requested quantity not available for Product Id: ".$cart_item->coupon_id." Order Id :".$cart->id);
                        return redirect('order_error'); 
                       // return $this->sendError('',$message);
                    }
                if($cart_item->coupon->coupon->end_date <= Carbon::now()->format('Y-m-d H:i')){
                    $item_name = $cart_item->coupon->coupon->name;
                    $message = "This coupon ". $item_name."  is expired.Please remove it from the cart.";
                    $subject = "SSGC BO - Order Failed Due to digital product expired for Order Id : ".$cart->id;
                    if($admin_email){
                      Mail::to($admin_email)->send(new SendOrderFailedEmailAdmin($admin,$cart,$subject));
                    }
                    \Log::info($item_name."Digital Product expired for Product Id: ".$cart_item->coupon_id." Order Id :".$cart->id);
                    return redirect('order_error'); 
                }
                }
            }
          }  
          //check whether product is active /publish and check whether book is instock or outof stock :: end
          
          //only if digital coupon purchased
          if($cart->order_type == 'digital_coupons')
          {
            $this->assign_qr_codes($cart);
            $this->maintainReferralHistory((integer)$cart->redeemed_points, 'deducted', 'no_deduct',$user,'',$cart->id);
          }

        
          //###### Customer Order Placed Notification ######
          $title    = 'Order Placed Successfully';
         // $body     = "Congratulations! Your order ".$cart->order_id.", worth ".$cart->total_payable." Rs. has been placed successfully.";
           $body     = "Congratulations! Your order ".$cart->id.", worth ".$cart->total_payable." Rs. has been placed successfully.";
          if($cart->order_type == 'physical_books')
          {
            $slug = 'customer_order_placed';
          }
          else
          {
            $slug = 'customer_coupon_order_placed';
          }
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

	  try
          {
            //###### Send Order Placed Email ######
            if($user->email) {
                $subject = "SSGC- We are happy to announce your order confirmation!";
                Mail::to($user->email)->send(new SendOrderPlacedEmailCustomer($user,$cart,$subject));
            }
            //###### Send Order Placed Email ######
          } catch(\Exception $e){
            //skip sending mail and redirect to success page
            // return redirect('order_payment_success');
            if($cart->order_type == 'physical_books')
            {
              return redirect('order_payment_success');
            }
            else
            {
              return redirect('order_payment_success?type=coupon');
            }
          }
           	
          // return redirect('order_payment_success');
          if($cart->order_type == 'physical_books')
          {
            return redirect('order_payment_success');
          }
          else
          {
            return redirect('order_payment_success?type=coupon');
          }

        }else{

          $cart->order_status = 'failed';
          $cart->placed_at = date('Y-m-d H:i:s');
          $cart->is_cart = '0';
          $cart->is_payment_attempt = '2';
          $cart->payment_status = 'failed';
          $cart->save();

          $payment = Payment::where(['order_id'=>$cart->id, 'user_id' => $user->id, 'payment_type' => 'ccavenue'])->latest()->first();
          $payment->tran_ref = $response['tracking_id'];
          $payment->api_response = json_encode($response);
          $payment->status = 'failed';
          $payment->save();   
          $id = $this->createDuplicateOrder($cart->id); 

          $cart = Order::where('id',$cart_id)->where('user_id',@$user_id)->where('is_cart','1')->first();
          if($cart)
          {
            if($cart->order_type == 'physical_books')
            {
              $this->mergeCart($cart_id);
            } 
          }
          
          DB::commit();
          return redirect('order_payment_error');
        }
      }catch(\Exception $e){
         \Log::info("Error From CCAvenue : ".$e->getMessage());
        $cart->order_status = 'failed';
        $cart->placed_at = date('Y-m-d H:i:s');
        $cart->is_cart = '0';
        $cart->is_payment_attempt = '2';
        $cart->payment_status = 'failed';
        $cart->save();
 
       
        $payment = Payment::where(['order_id'=>$cart->id, 'user_id' => $user->id, 'payment_type' => 'ccavenue'])->latest()->first();
        $payment->tran_ref = $response['tracking_id'];
        $payment->api_response = json_encode($response);
        $payment->status = 'failed';
        $payment->save();   
        $id = $this->createDuplicateOrder($cart->id); 

        $cart = Order::where('id',$cart_id)->where('user_id',@$user_id)->where('is_cart','1')->first();
        if($cart)
        {
          if($cart->order_type == 'physical_books')
          {
            $this->mergeCart($cart_id);
          }
        }
        DB::commit();
        return redirect('order_payment_error');
      }

    }

    public function order_payment_success() {
      $page_title = "Thanks for your Payment";
      $status_title = "Thank You!";
      $message = "Thank you! Your Order has been successfully placed. If order confirmation is not received via mail or msg. Please contact to customer support";
      return view('customer.payment_status', compact('page_title','status_title','message'));
    }

    public function order_payment_error() {
      $page_title = "Erorr in Payment";
      $status_title = "Error!";
      $message = "Payment failed, Please try again";
      return view('customer.payment_status', compact('page_title','status_title','message'));
    }

    public function order_error() {
      $page_title = "Erorr in Order";
      $status_title = "Error!";
      $message = "Order failed, Please try again";
      return view('customer.payment_status', compact('page_title','status_title','message'));
    }
}
