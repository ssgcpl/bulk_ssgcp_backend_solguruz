<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Helpers\CommonHelper;
use App\Models\Helpers\PaymentHelper;
use App\Mail\SendOrderPlacedEmailCustomer;
use App\Mail\SendOrderFailedEmailAdmin;
use App\Mail\PasswordUpdateMail;
use Illuminate\Support\Facades\Mail;
use App\Jobs\TestJob;
use App\Jobs\GetOrderStatusFromCCAvenue;
use DB;

class HomeController extends Controller
{
    use CommonHelper;
    use PaymentHelper;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        $this->middleware('auth', ['except' => ['webhookForPayu','webhookForCCAvenue','testJob','GetOrderStatusFromCCAvenue','fixUserMigration','fixProductMigration']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      return view('home');
    }

    public function testJob()
    {
      \Log::info("test job");
      $job = (new TestJob());
      dispatch($job);
      $job1 = (new TestJob())->delay(120);
      dispatch($job1);
      $job2 = (new GetOrderStatusFromCCAvenue());
      dispatch($job2);
      return "Done";
    }

    public function webhookForPayu(Request $request)
    {
        //$response =  '{"mihpayid":"17380967851","mode":"UPI","status":"'.$status.'","key":"JZ3ygG","txnid":"'.$order_id.'","amount":"30.00","addedon":"2023-05-18 16:22:44","productinfo":"Purchase items for Order ID  id 1000131326","firstname":"mukesh","lastname":"dabra","address1":null,"address2":null,"city":null,"state":null,"country":null,"zipcode":null,"email":"ronak.soni@kodytechnolab.com","phone":"9690725608","udf1":null,"udf2":null,"udf3":null,"udf4":null,"udf5":null,"udf6":null,"udf7":null,"udf8":null,"udf9":null,"udf10":null,"card_token":null,"card_no":null,"field0":null,"field1":"mdabra219-1@oksbi","field2":"313816","field3":"mdabra219-1@oksbi","field4":"MUKESH KUMAR SO SHARMA NAND","field5":"samsamayikghatnachakra-5297212.payu@indus","field6":"INDBFBF698453DC73166E053F87C180AB13","field7":"APPROVED OR COMPLETED SUCCESSFULLY|00","field8":null,"field9":"Success|Completed Using Callback","payment_source":"payu","PG_TYPE":"UPI-PG","error":"E000","error_Message":"No Error","net_amount_debit":"30","discount":"0.00","offer_key":null,"offer_availed":null,"unmappedstatus":"captured","hash":"c8777ea50ba4e78785a4660cf35d1aa2341ccf5a0e767127e064a7a64909fbed298dd6e18e40b759d3fec8f30089e5cce6e28200230af6b0fb8446311096b746","bank_ref_no":"313848601632","bank_ref_num":"313848601632","bankcode":"TEZ","surl":"https:\/\/www.samsamayikghatnachakra.com\/payu_callback_order\/365657\/157629","curl":"https:\/\/www.samsamayikghatnachakra.com\/payu_callback_order\/365657\/157629","furl":"https:\/\/www.samsamayikghatnachakra.com\/payu_callback_order\/365657\/157629","meCode":"{\"pgMerchantId\":\"INDB000004531915\"}"}';   
       $data = $request->all();
        //$data = json_decode($request->all(),true);
        if(!$data){
          return "No Data Found"; 
        }
        $user = User::where('email',$data['email'])->first();
        $cart = Order::where('order_id',$data['txnid'])->where('is_payment_attempt','1')->where('user_id',@$user->id)->first();
        $settings = Setting::pluck('value','name')->all();
        $admin_email = $settings['admin_email'];
        if(!$cart) {
            return "Cart not found";
        }

        try {
              $user = User::find($cart->user_id);
              $payment = Payment::where('order_id',$cart->id)->first();
              $payment_id = $payment->id;
              if($data['status'] == 'success'){
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
          //$cart = Order::where('id',$id)->where('user_id',@$user_id)->where('is_cart','1')->first();
          
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

            $user = User::find($user->id);
            if($user){
                $title    = 'Your Order has been failed';
                $body     = "Your order has been failed due to some reason";
                $slug     = 'customer_order_failed';
                $this->sendNotification($admin,$title,$body,$slug,$cart,null);
            }

            $cart = Order::where('id',$cart->id)->where('user_id',@$user->id)->where('is_cart','1')->first();
              if($cart)
              {
                if($cart->order_type == 'physical_books')
                {
                  $this->mergeCart($cart->id);
                }
              }
          
          DB::commit();
          return redirect('order_payment_error');
        }
        return true;
    }

   public function webhookForCCAvenue(Request $request)
    {
       
         //$response = '{"encResp":"c430d030354da4502ab5a375a70165e95e1648efe3e96cb802e5d2829139f01bdecfa0f929f39b774a30f2502c2182a8ee5ca68b081e84ef896a10b491999b16a4fc3523c3fbd33106399aa27accd91b9c7b33fb0a92f47ae5fd4fbe71d014c3fbc3ac64c1c5e023fde33139ef7bc6efe37eee59ac35c8bb43c2f3a077409de1bf1a3299ed696a1ac0104af26837c5c933e7986fb8cfb7db3f2b1fe12667edc78cc4635087a7fff57fb98e4218efa978291eae7e3bf2dae1eb8b4371fd651839d32aec3e30eb3b0058a708a90bb86e73e3e96316e6bfd079aa02ebeb632b52b8cc2b0586e196eaf5dcb0e9364c3a5c355a1988b309b05916bb2fb13b5bf7a0fcc74132f2212472bcd76ba2d1776692235ef487b8a1110bd420b107dd5b534d6af47dce99910e9fb3c9f944a6cac350a10f0adf4c14a2d94f5e1b97e6b53aee3d8a718d22e12fcd1f63629c9b3c45641f0dc2960c7e7695461c0e371f795269a85d35919a7b9af4389a60f338306479b58628002af37236a187a06e1f881c32234471ceb03e08405d82404702ae3ede65ee3a3144154ef5a7f06bf436cc3b48b217b0f1f0d60c8c7d93678f60ece237649baab1eee566a9c20acc8ed8d9271b22c1513ef97711c0c63e5a4f47e7a7c3babb0d64b912ab7f71417168707dd38e3d89ba22ae6240c24b5e6adaaf8d5ae416674df739e0563640ce825d46324ec848939f206fcf1d37fd379db2953fe2f2f17f58df3459409bce808bc6cf777658a32320c4fa912c7ca673a3a7b6ce17eb2c0a10653d9298a90f4e98b292ed0adf29ff8022164216f2143e6c6af8b65639bd8165edde53b29f8b1c1802894bfb208fda1948dbbcdd3a944dcfc5aa14049debe072054b5964827c54115fe55735b5f9dbf8aaa7f4e3dd9d70cc0e86d04ad82cccf6080a06bae2d56f0f82ba9ca42ae7009885e0c2a7f9cf00840e9344402d012c647f2d06e26b38773341f1c079fe0b32931eef1c8c5c73b7fb6560f9c15cd6b4ede231b0f99c5d8dfd3fb06e2f6d6da8e2aa8245184b619baaf6ae1688d2c3a91c9be7613a70fb113ab06659a7b116564937f2071935b3ee9c9bacd5ceb2aa5c702f903d338c28e133c37ad06e28561e0d8261c892e97091cae3349e43e9f8df820a54e5d0da1dcbb753710cab32f9","order_id":"1969"}';
       
        $data = json_encode($request->all(),true); 
        if($request->all() == []){
          return "No Response Found";
        } 
          //$data    = json_decode($response,true);
          $cart_id = $data['order_id'];
          \Log::info("Data here : ".$data['order_id']);
          try{
          $working_key = Setting::get('ccavenue_working_key');//Shared by CCAVENUES
          $res = $this->decrypt($data['encResp'],$working_key);
          $settings = Setting::pluck('value','name')->all();
          $admin_email = $settings['admin_email'];

          if($res == null){
            $cart = Order::where('id',$cart_id)->where('user_id',@$user_id)->where('is_cart','1')->first();
            if($cart->order_type == 'physical_books')
            {
              $this->mergeCart($cart_id);
            }
            DB::commit();
          }
          $cart = Order::where('id',$cart_id)->where('is_payment_attempt','1')->first();
          if(!$cart) {
              \Log::info("Cart Not Found");
              return "No Cart Found";
          }
          $user = User::find($cart->user_id);
       
         
          //Convert String Response To Array
          $res = explode('&',$res);
          $response = array();

          foreach ($res as $value) {
            $tmp = explode('=', $value);
            $response[$tmp[0]] = $tmp[1];
          }
          
            if($response != null && $response['order_status'] == 'Success'){
             
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
                
                }
            //###### Customer Order Placed Notification ######
            $title    = 'Order Placed Successfully';
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
          }
          }
          catch(\Exception $e){
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
          }
          return true;
    }


    public function fixUserMigration()
    {
        $users = User::whereIn('user_type',['retailer','dealer'])->where('is_pwd_converted','0')->latest()->limit(100)->get();
        foreach ($users as $user) {
          $user->email_verified_at = date('Y-m-d H:i:s');
          $password = "Ghatna@12";
          $user->password = bcrypt($password);
          $user->is_pwd_converted = '1';
          $user->referral_code = $this->generateReferralCode();
          $user->points = 5000;
          $user->save();
          // echo "<pre>"; print_r($user); exit;
          $email = "ssgc_bo_test_user@mailinator.com";
          // Mail::to($email)->send(new PasswordUpdateMail($user,$password)); 
        }
        return 'Success';
    }

    public function fixProductMigration()
    {
        $products = Product::whereNull('last_returnable_date')->orWhereNull('last_returnable_qty')->latest()->limit(100)->get();
        foreach ($products as $products) {
          $products->last_returnable_date = '2024-12-31';
          $products->last_returnable_qty = '100';
          $products->save();
        }
        return 'Success';
    }

    public function generatePassword(){
      $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
      return substr(str_shuffle($str_result), 0, 8);
    }
}
