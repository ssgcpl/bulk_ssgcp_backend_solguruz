<?php

namespace App\Models\Helpers;

use Illuminate\Support\Facades\Storage;
use DB,PDF;
use Redirect;
use App\Models\User;
use App\Models\UserSubscription;
use App\Models\Setting;
use App\Models\DeviceDetail;
use App\Models\Country;
use App\Models\Payment;
use App\Models\Category;
use Edujugon\PushNotification\PushNotification;
use App\Mail\OtpEmail;
use App\Mail\SendCustomerMail;
use Illuminate\Support\Facades\Mail;
use App\Models\SmsVerification;
use App\Models\EmailVerification;
use App\Models\Notification;
use App\Models\Sms;
use App\Models\SubCoupon;
use App\Models\CouponMaster;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderReturn;
use App\Models\OrderReturnItem;
use App\Models\OrderAddress;
use App\Models\ReferHistory;
use App\Models\CouponQrCode;
use App\Models\OrderCouponQrCode;
use App\Models\Stock;
use App\Models\StockGro;
use App\Models\StockTransfer;
use App\Models\ProductCategory;
use App\Models\Product;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Carbon\Carbon;
use App\Notification\SendVerificationEmail;
// use Notification;
use DateTime;
use Auth;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use App\Jobs\GetOrderStatusFromPayu;
use App\Mail\SendOrderPlacedEmailCustomer;
use App\Mail\SendOrderFailedEmailAdmin;


trait CommonHelper
{

  public $image_path = 'uploads/media/';


  /**
  * Genrate Payment Client
  */
  public function genrateClient(){

      $settings = Setting::pluck('value', 'name')->all();
      if($settings['payment_environment'] == 'sandbox'){
        $clientId = $settings['paypal_test_client_id'];
        $clientSecret = $settings['paypal_test_client_secret'];
        $environment = new SandboxEnvironment($clientId, $clientSecret);
      }else{
        $clientId = $settings['paypal_client_id'];
        $clientSecret = $settings['paypal_client_secret'];
        $environment = new ProductionEnvironment($clientId, $clientSecret);
      }

      $client = new PayPalHttpClient($environment);

      // echo "<pre>";print_r($client);exit;
      return $client;
  }

  //Genrate OTP
  public function genrateOtp(){
    $code = mt_rand(100000,999999);
    //$code = 123456;
    return $code;
  }

  //Send OTP
  public function sendOtp($country_code,$mobile_number,$otp,$slug=''){

    DB::beginTransaction();
    try{

        //Delete Old OTP Entries to prevent DB table data flooding
        $exists = SmsVerification::where('mobile_number',$mobile_number)->get();
        if($exists->count() > 0){
          foreach ($exists as $exist) {

            $exist->delete();

          }
        }

        //Add Data into table
        $sms                = new SmsVerification();
        $sms->mobile_number = $mobile_number;
        $sms->code       = $otp;
        $sms->status     = 'pending';
        $sms->created_at = Carbon::now();
        $sms->save();

        //Prepare API Post Data
        $country_code = str_replace("+", "",$country_code);
        $data = new \stdClass();
        $data->sender = Setting::get('sms_gateway_sender');
        $data->mobiles = $country_code.$mobile_number;
        $data->otp = $otp;
        $data->flow_id = Setting::get('sms_gateway_otp_flow_id');


        $postdata = json_encode($data);

        //API URL
        $url = Setting::get('sms_gateway_url').'v5/flow';
        $authkey = Setting::get('sms_gateway_authkey');

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json',"authkey:".$authkey ));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

        // Get URL content
        $result = curl_exec($ch);
        $err = curl_error($ch);
        // close handle to release resources
        curl_close($ch);
        $result = json_decode($result);
        if($result->type == 'success'){
          //sms sent
          DB::commit();
          return true;
        }else{
          //sms failed
          DB::rollback();
          return false;
        }

        DB::commit();
        return true;

    } catch (\Exception|\GuzzleException $e){

      DB::rollback();
      return false;
    }
  }

  //Send OTP
  public function sendEmailOtp($email,$user,$otp,$subject="Verify Email Address"){


    DB::beginTransaction();
    try{

      //Delete Old OTP Entries to prevent DB table data flodding
      $exists = EmailVerification::where('email',$email)->get();

      if($exists->count() > 0){
        foreach ($exists as $exist) {
          $exist->delete();
        }
      }

      //Add Data into table

        $new_email             = new EmailVerification();
        $new_email->email      = $email;
        $new_email->code       = $otp;
        $new_email->status     = 'pending';
        $new_email->created_at = Carbon::now();
        $new_email->save();

        // $subject = 'Verify Email Address';
        Mail::to($email)->send(new OtpEmail($user,$otp,$subject));

        DB::commit();
        return true;

      } catch (\Exception $e){

        DB::rollback();
        return false;
      }
  }


  /**
   * Save different type of media into different folders
   */
  public function saveMedia($file,$type='')
  {
      //Laravel Image Saving
      $code            =  mt_rand(1000,9999);
      $media           =  $file;
      $filenameWithExt =  $media->getClientOriginalName();
      $filename        =  pathinfo($filenameWithExt, PATHINFO_FILENAME);
      $filename        =  str_replace(' ','_',$filename).'_'.time();
      $filename        =  substr($filename,0,40);
      $extension       =  $media->getClientOriginalExtension();

      $fileNameToStore =  $filename.$code.'.'.$extension;

      if($type == 'flag'){
        $save =  $media->move('flags',$fileNameToStore);
        $this->image_path = 'flags/';
        $path =  $this->image_path.$fileNameToStore;
      }else {
        $save =  $media->move('uploads/media',$fileNameToStore);
        $path =  $this->image_path.$fileNameToStore;
      }
      return $path;

  }
  /**
   * Save different type of media into different folders
   */
  public function copyMedia($file)
  {
      //Laravel Image Saving
      $code            = mt_rand(1000,9999);
      $filename        = 'dummy';
      $filename        =  str_replace(' ','_',$filename).'_'.time();
      $filename        =  substr($filename,0,40);
      $extension       =  substr($file, strpos($file, ".") + 1);
      $fileNameToStore =  $filename.$code.'.'.$extension;

      \File::copy(public_path($file), public_path('uploads/media/web_content/'.$fileNameToStore));

      $path =  'uploads/media/web_content/'.$fileNameToStore;

      return $path;
  }

  /**
   * Delete Media
   */
  public function deleteMedia($file){
    if(file_exists($file)){
      unlink($file);
      return true;
    } else {
      return true;
    }
  }

  /**
   *For Api Pagination
   */
  public static function countPageNumber($count,$limit){
    $page = ceil($count / $limit);
    return $page;
  }
  /**
  * Send Notification
  */


  public function sendNotification($user,$title,$body,$slug,$data=null,$url=null,$send_by=null){


      if($user == null){
        return true;
      }

      //Save notification in D
      $notify                 =  new Notification();
      $notify->user_id        =  $user->id;
      $notify->user_type      =  $user->user_type;
      $notify->title          =  $title;
      $notify->content        =  $body;
      $notify->slug           =  $slug;
      $notify->url            =  @$url;
      $notify->data_id        =  @$data->id;
      $notify->send_by        =  @$send_by;
      $notify->save();

      //Check for user's device details
      $devices = DeviceDetail::where('user_id',$user->id)->orderBy('created_at','DESC')->get();

      foreach ($devices as $device) {
        if(!empty($device)){

            $fcm_server_key = 'AAAA8-szx7Y:APA91bGfuYdF7JaRz7ah3BdlW7b5xqxlH5-z-OU3gicSUbL8nlNbv33IiqcpitpSrNfMr_P6iw-ZH7IdmFM_vEg8XXzO7IjmlXnBntm9M9awMrytCkC8JEat2HtSM5dIennnCTihD5eo';

            $message = [
              "to" => $device->device_token,
              "notification" => [
                  "body" => $body,
                  "title" => $title
              ],
              "data" => [
                  "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                  'type'         => $slug,
                  'type_id'      => @$data->id,
                  'url'          => @$url,
              ]
            ];

          /* NEW CODE FOR NOTIFICATION*/
          $push_notification_key = $fcm_server_key;
          $url = "https://fcm.googleapis.com/fcm/send";
          $header = array("authorization: key=" . $push_notification_key . "",
              "content-type: application/json"
          );

          $postdata = json_encode($message);

          $ch = curl_init();
          $timeout = 120;
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
          curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
          curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

          // Get URL content
          $result = curl_exec($ch);
          // close handle to release resources
          curl_close($ch);

          \Log::info('Notification result'.$user->id.'-->'.$result);

          // return true;

        }
      }



      return true;
  }

  /**
  * Send Email
  */
  public function sendEmail($user,$title,$body){

      if($user == null){
          return true;
      }

      Mail::to($user->email)->send(new MassEmail($user,$title,$body));
      return true;
  }

  public function sendMail($user,$body,$title){

      if($user == null){
          return true;
      }

      Mail::to($user->email)->send(new SendCustomerMail($user,$body,$title));
      return true;
  }

  public function sendSMS($user,$mobile_number,$message,$send_by,$slug='',$country_code='+91'){

      if($user == null){
        return true;
      }
    DB::beginTransaction();
    try{

      //Save SMS in DB
      $sms                 =  new Sms();
      $sms->user_id        =  $user->id;
      $sms->mobile_number  =  $mobile_number;
      $sms->message        =  $message;
      $sms->send_by        =  $send_by;
      $sms->status         = 'active';

      $sms->save();
       DB::commit();
          return true;

//       Prepare API Post Data
       /* $country_code = str_replace("+", "",$country_code);
        $data = new \stdClass();
        $data->sender = 'SSGCPL';
        $data->mobiles = $country_code.$mobile_number;
        //$data->otp = $otp;

        if($slug == '' || $slug=="send_otp"){
          $data->flow_id = '608aa4637b0af1203216e213';

        }else{
          $data->flow_id = '608aa4637b0af1203216e213';
        }

        $postdata = json_encode($data);*/

     //   API URL
      /*  $url = "https://api.msg91.com/api/v5/flow/";
        $authkey = '106360AYqjCGhBZnt6144a796P1';

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json',"authkey:".$authkey ));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

        // Get URL content
        $result = curl_exec($ch);
        $err = curl_error($ch);
        // close handle to release resources
        curl_close($ch);
        $result = json_decode($result);

        if($result->type == 'success'){

          //sms sent
          DB::commit();
          return true;

        }else{

          //sms failed
          DB::rollback();
          return false;

        }*/
    } catch (\Exception|\GuzzleException $e){

      DB::rollback();
      return false;
    }

}


public function sendNotifications($user,$title,$body){

      if($user == null){
        return true;
      }

      //Save notification in DB
      $notify                 =  new Notification();
      $notify->user_id        =  $user->id;
      $notify->user_type      =  $user->user_type;
      $notify->title          =  $title;
      $notify->content        =  $body;


      $notify->save();
}
  /**
     * Save images from external URL
     *
     * @param  file  $image
     *
     * @return image model
   */
  public function saveImageFromUrl($url, $featured = null)
  {
        // Get file info and validate
        $file_headers = get_headers($url, TRUE);
        $pathinfo = pathinfo($url);
        // $size = getimagesize($url);

        if ($file_headers === false) return; // when server not found

        $extension = 'jpg';

        // Get the original file
        // echo "<pre>";print_r($url);exit;
        $file_content = file_get_contents($url);
        // Make path and upload
        if (!is_dir('uploads/profile_images/')) // Make the directory if not exist
        {
            mkdir('uploads/profile_images/', 0777, true);
        }
        $path = 'uploads/profile_images/' . uniqid() . '.' . $extension;
        // echo "<pre>";print_r($path);exit;
        $res = file_put_contents(public_path($path), $file_content);
        return $path;
  }

  public static function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
  }

  //for Customer Web
  public static function saved_addresses() {
    $user = Auth::guard('customer')->user();
    $addresses = $user->addresses;
    return $addresses;
  }


  //For Customer Web
  public static function substrwords($text, $maxchar, $end='...') {
    if (strlen($text) > $maxchar || $text == '') {

        $output = substr($text, 0, $maxchar);
        // return $output;
        $output .= $end;
    }
    else {
        $output = $text;
    }
    return $output;
  }

  //Save/Update Device Details
  public function saveDeviceDetails($data, User $user){

    $createArray = array();

    foreach ($data as $key => $value) {
        $createArray[$key] = $value;
    }
    $device_detail = DeviceDetail::where('user_id',$user->id)->first();

    if($device_detail){
        $device_detail->update($createArray);
    }else {
        $createArray['user_id'] = $user->id;
        DeviceDetail::create($createArray);
    }

    return true;
  }

  // inactivate expired subsriptions
  public function inactivate_expired_subscriptions() {
    $user = Auth::guard('api')->user();
    $subscriptions = UserSubscription::where(['user_id' => $user->id, 'status' => 'active'])->get();
    foreach ($subscriptions as $subscription) {
      if($subscription->expiry_date != NULL) {
        if(strtotime($subscription->expiry_date) < strtotime(date('Y-m-d'))) {
          $subscription->status = 'inactive';
          $subscription->save();
        }
      }
    }
  }


  //get_subscription
  public function get_subscription($user, $package, $package_type = "") {
      DB::beginTransaction();
      $this->inactivate_expired_subscriptions(); //inactivate expired subsriptions

      $already_subscribed = UserSubscription::where(['user_id' => $user->id, 'subscription_package_id' => $package->id, 'status' => 'active'])->latest()->first();

      $past_subscription = UserSubscription::where(['user_id' => $user->id, 'status' => 'active'])->first();

      if($past_subscription) {
        if($package->id < $past_subscription->subscription_package_id) {
          return ['type' => 'error', 'message' => trans('subscriptions.cant_upgrade'), 'url' => ''];
        }
      }

      if($already_subscribed) {
        return ['type' => 'error', 'message' => trans('subscriptions.already_subscribed'), 'url' => ''];
      } else {


        $price = $package->price;
        $price_per = $package->price_per;
        $validity = $package->validity;
        $expiry_date = date('Y-m-d', strtotime("+".$package->validity." ".$package->price_per."s", strtotime(date('Y-m-d'))));

        $user_subscription = UserSubscription::create([
          'user_id'                 => $user->id,
          'subscription_package_id' => $package->id,
          'start_date'              => date('Y-m-d'),
          'expiry_date'             => $expiry_date,
          'price'                   => $price,
          'price_per'               => $price_per,
          'validity'                => $validity,
          'status'                  => 'inactive'
        ]);


        if($package->type == 'paid' || $package->price > 0)
        {
            DB::commit();
            return ['type' => 'error', 'message' => 'This subscription is paid. Please proceed for checkout.', 'url' => ''];
        }
        else
        {
            // inactivate other subscriptions
            UserSubscription::where('subscription_package_id','<>',$package->id)->where('user_id',$user->id)->update(['status' => 'inactive']);

            $user_subscription->status = 'active';
            $user_subscription->save();

            DB::commit();
            return ['type' => 'success', 'message' => trans('subscriptions.subscribed_success',['package_name'=>$package->name])];

        }


      }

  }


  public function generateUniqueSrt($size = 6)
  {
      $characters = implode(range(0, 9));
      $uniqueStr = '';
      for($i=0; $i<$size; $i++) {
          $uniqueStr .= $characters[mt_rand(0, strlen($characters) - 1)];
      }

      return $uniqueStr;
  }
  public function generateReferralCode()
  {
      $code = $this->generateUniqueSrt();
      $code_exist = User::where('referral_code',$code)->first();
      if($code_exist)
      {
          $this->generateReferralCode();
      }
      return $code;
  }

  /**
  * Send Notification
  */
  public function sendChatNotification($from_user_id, $to_user_id){
      $user = User::where(['user_type'=>'customer','id'=>$to_user_id])->first();
      if($user == null){
        return false;
      }

      $from_user_name = User::find($from_user_id)->first_name;
      $title = "New message";
      $content = "You have a new message from ".$from_user_name;
      $slug = 'new_message_received';


      //Save notification in DB
      $createArray              = array();
      $createArray['user_id']   = $user->id;
      $createArray['user_type'] = 'customer';
      $createArray['title']     = $title;
      $createArray['content']   = $content;
      $createArray['slug']      = $slug;
      $createArray['data_id']   = $from_user_id;

      $notify = Notification::create($createArray);

      //Check for user's device details
      $devices = DeviceDetail::where('user_id',$user->id)->orderBy('created_at','DESC')->get();
      //Check if Device details available
      foreach ($devices as $device) {
        if(!empty($device)){


          $fcm_server_key = 'AAAAeUvwau0:APA91bGWYLY4Tl_MOKPbpbf3H4ZlOnhs5XGu8oKz_T9c6mFdOpx7d4RRZLxcsmSKboSDXbcY2hl7ORUri-JpDXYfFGkLSKJaaFm_C1WIHi7wrubp-MjZONnHr-Yz0bSxZKe0j9CLS4V7';

          $message = [
            "to" => $device->device_token,
            "notification" => [
                "body" => $content,
                "title" => $title
            ],
            "data" => [
                "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                'title'        => $title,
                'body'         => $content,
                'slug'         => $slug,
                'data_id'      => $from_user_id,
            ]
          ];

          /* NEW CODE FOR NOTIFICATION*/
          $push_notification_key = $fcm_server_key;
          $url = "https://fcm.googleapis.com/fcm/send";
          $header = array("authorization: key=" . $push_notification_key . "",
              "content-type: application/json"
          );

          $postdata = json_encode($message);

          $ch = curl_init();
          $timeout = 120;
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
          curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
          curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

          // Get URL content
          $result = curl_exec($ch);
          // close handle to release resources
          curl_close($ch);

          /*\Log::info($slug);
          \Log::info($notify->id);
          \Log::info($device->device_token);
          \Log::info($user->id);
          \Log::info($result);
          \Log::info($user_id);
          \Log::info($hiring_id);
          \Log::info("=====================================");*/

          // return true;

        }
      }


      return true;
  }

  //Get all child categories of any category
  public function get_all_child_categories($category_id = ""){

    /*if(isset($category_id)){
      $master_categories = Category::where('id',$category_id)->get();
    }else{
      $master_categories = Category::where('is_live','1')->whereNull('parent_id')->orderBy('id','asc')->get();
    }*/
    if(isset($category_id) && $category_id !=''){
      $master_categories = Category::where('id',$category_id)->where('is_live','1')->where('status','active')->get();
    }else{
      $master_categories = Category::where('is_live','1')->whereNull('parent_id')->where('status','active')->orderBy('id','asc')->get();
     }

    $response = [];
    $all_level_cat_ids = [];

    foreach ($master_categories as $m_cat) {
      $all_level_cat_ids[$m_cat->id][] = $m_cat->id;
      //Check if there are child categories
      if(count($m_cat->childs_active()))
      {
        $all_level_cat_ids = $this->get_all_sub_cat_ids($all_level_cat_ids, $m_cat, $m_cat->id);
      }

    }

    return $all_level_cat_ids;
  }

  //Loop through each child categories to get their sub-child categories
  public function get_all_sub_cat_ids($all_level_cat_ids, $m_cat, $m_cat_id)
  {

        if(count($m_cat->childs_active()))
        {
          foreach ($m_cat->childs_active() as $m_cat1) {
            $all_level_cat_ids[$m_cat_id][] = $m_cat1->id;

            if(count($m_cat1->childs_active()))
            {

              $all_level_cat_ids = $this->get_all_sub_cat_ids($all_level_cat_ids,$m_cat1, $m_cat_id);

            }
          }
        }

        return $all_level_cat_ids;

  }
  //update cart calculation
  public function updateCartCalc($order_id, $action = "")
  {
      $cart             = Order::find($order_id);
      $user             = User::find($cart->user_id);
      $cart_items       = $cart->order_items;
      $total_mrp        = 0;
      $total_sale_price = 0;
      $discount_on_mrp  = 0;
      $coupon_discount  = 0;
      $total_payable    = 0;
      $total_weight     = 0;

      foreach ($cart_items as $item) {
          $product = $item->product;
          $coupon = $item->coupon;
          // update order items as per latest updated product prices

          if($cart->order_type == 'physical_books')
          {
            $item_mrp = $product->mrp;
            $item_sale_price = $product->get_price($user);
          }
          else
          {
            $item_mrp = $coupon->mrp;
            $item_sale_price = $coupon->get_price($user);
          }
          $item_total_mrp        = $item_mrp * $item->supplied_quantity;
          $item_total_sale_price = $item_sale_price * $item->supplied_quantity;
         /* $item_total_mrp        = $item->mrp * $item->supplied_quantity;
          $item_total_sale_price = $item->sale_price * $item->supplied_quantity;*/

          $item->mrp              = $item_mrp;
          $item->sale_price       = $item_sale_price;
          $item->total_mrp        = $item_total_mrp;
          $item->total_sale_price = $item_total_sale_price;
          $item->total            = $total_mrp + $total_sale_price;
 
          // update order master as per latest updated product prices
          if($cart->order_type == 'physical_books')
          {
            $total_weight     += $item->product->weight * $item->supplied_quantity;
          }
          $total_mrp        += $item_total_mrp;
          $total_sale_price += $item_total_sale_price;
          $discount_on_mrp  += ($item_total_mrp - $item_total_sale_price);
          $total_payable    += $item_total_sale_price;
          $item->save();
      }


        
      $delivery_charges = $this->get_delivery_charges($total_sale_price);
      if($cart->order_type == 'digital_coupons')
      {
        $delivery_charges = 0;
      }

      if($user->user_type == 'dealer' && $cart->is_cart == '1'){
        $delivery_charges = 0;
      }
      else if($cart->user_type == 'dealer' && $cart->is_cart == '0'){
        $delivery_charges = $cart->delivery_charges;
      }

      $cart->total_mrp        = $total_mrp;
      $cart->total_weight     = $total_weight;
      $cart->total_sale_price = $total_sale_price;
      $cart->discount_on_mrp  = $discount_on_mrp;
      $cart->delivery_charges = $delivery_charges;

      $cart->total_payable = $total_payable - $cart->redeemed_points_discount + $delivery_charges;
      $cart->save();
      return $cart;
  }

  public function get_delivery_charges($total_sale_price){
    $minimum_order_value            = Setting::get('amount_limit');
    $delivery_charges               = Setting::get('delivery_charges');
    $is_delivery_charges_applicable = Setting::get('is_delivery_charges_applicable');
    if($is_delivery_charges_applicable == 'on')
    {
      //if delivery charges applicable but order value greater than minimum order value
      if($total_sale_price > $minimum_order_value)
      {
          $delivery_charges = 0;
      }
    }
    else
    {
      //if delivery charges not applicable
      $delivery_charges = 0;
    }

    return $delivery_charges;
  }

  public function get_payment_methods($user_type)
  {
    $payment_methods = [];

    if($user_type == 'retailer') {
      $payment_methods = [
        'payu','ccavenue'
      ];
    }
    return $payment_methods;
  }

  public function sendOrderStatusSMS($country_code='+91',$mobile_number,$order,$slug=''){
    try{
        //Prepare API Post Data
        $country_code = str_replace("+", "",$country_code);
        $data = new \stdClass();
        $data->sender = Setting::get('sms_gateway_sender');
        $data->mobiles = $country_code.$mobile_number;
        $data->order_id = $order->id;
        $data->customer = $order->customer->first_name ? $order->customer->first_name.' '.$order->customer->last_name : 'Customer';

        if($slug == 'order_placed'){
          $data->flow_id = Setting::get('sms_gateway_order_placed_flow_id');
        }

        if($slug == 'order_delivered'){
          $data->flow_id = Setting::get('sms_gateway_order_delivered_flow_id');
        }

        $postdata = json_encode($data);
        // echo $postdata;die;
        //API URL
        $url = Setting::get('sms_gateway_url').'v5/flow';
        $authkey = Setting::get('sms_gateway_authkey');

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json',"authkey:".$authkey ));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

        // // Get URL content
        $result = curl_exec($ch);
        \Log::info('sendOrderStatusSMS result'.$result);
        // print_r($result);die;
        $err = curl_error($ch);
        // close handle to release resources
        curl_close($ch);
        $result = json_decode($result);
        // print_r($result);die;

        if($result->type == 'success'){
          return true;
        }else{
          return false;
        }

        return true;

    } catch (\Exception|\GuzzleException $e){
      return false;
    }
  }
  //Update return cart calculation
  public function updateReturnCartCalc($order_return_id)
  {

    $cart             = OrderReturn::find($order_return_id);
    $cart_items       = $cart->order_items;
    $total_sale_price = 0;
    $total_mrp = 0;
    $total_quantity    = 0;
    $total_weight = 0;
    foreach ($cart_items as $item) {

      $total_sale_price += $item->total_sale_price;
      $total_mrp += $item->total_mrp;
      $total_quantity  += $item->total_quantity;
      $total_weight += ($item->product->weight * $item->total_quantity);
    }
  
    $cart->total_sale_price = $total_sale_price;
    $cart->total_mrp = $total_mrp;
    $cart->total_quantity  = $total_quantity;
    $cart->total_weight  = $total_weight;

    $cart->save();
    return $cart;
  }

  public function itemAddedToCartChecker($item_type,$item_id,$user=null) {

    if(!$user){
      $user = Auth::guard('api')->user();
    }


    if($user) {
      if($item_type == 'order'){
        $order_ids = Order::where('user_id',$user->id)->where('is_cart','1')->pluck('id');
        $cart_item = OrderItem::whereIn('order_id',$order_ids)
            ->where('product_id',$item_id)->first();

        if($cart_item) {

          return $cart_item;
        }
      }else if($item_type == 'coupon_order'){
        $order_ids = Order::where('user_id',$user->id)->where('is_cart','1')->pluck('id');
        $cart_item = OrderItem::whereIn('order_id',$order_ids)
            ->where('coupon_id',$item_id)->first();

        if($cart_item) {

          return $cart_item;
        }
      }else{
        $order_ids = OrderReturn::where('user_id',$user->id)->where('is_cart','1')->pluck('id');
        $cart_item = OrderReturnItem::whereIn('order_return_id',$order_ids)
            ->where('order_item_id',$item_id)->first();

        if($cart_item) {

          return $cart_item;
        }
      }


    }
    return false;
  }

    public function get_product_stock($id){

            $total_data = Stock::select('id','pof_qty','ecm_qty')
                        ->where('product_id', $id)
                        ->get()->toArray();
            $stock_ids = [];
            $total_pof = '0'; $total_gro ='0'; $total_ecm = '0';

            foreach ($total_data as $val) {
                $stock_ids[]  = $val['id'];
                $total_ecm += $val['ecm_qty'];
                $total_pof += $val['pof_qty'];
            }
            $total_gro_data  = StockGro::select(
                            DB::raw("sum(gro_qty) as gro_total_qty"),
                          )->whereIn('stock_id', $stock_ids)
                           ->first();
            $total_gro = intval($total_gro_data->gro_total_qty);
           // $order = Order::where('is_cart','1')->first(); 
            $order = Order::WhereHas('order_item',function($q) use($id){
                              $q->where('product_id',$id);
                            })->where('is_cart','1')->first();
            $order_id = '';
            if($order){ $order_id = $order->id;}
            $order_supplied = OrderItem::select(
                          DB::raw("sum(supplied_quantity) as order_supplied"),
                          )->where('product_id', $id)
                           ->whereNotIn('order_id',[$order_id])
                           ->first()->toArray();
            $order_supplied = $order_supplied['order_supplied'];
            $return_go_down = 0;
            $balance_outside = ($total_pof + $total_ecm) - $total_gro;
            $balance_inside =  $total_gro - $order_supplied;
            $total_balance = ($balance_inside + $balance_outside+$return_go_down);
            return $total_balance;
    }


  public function updateReturnCartCalculation($order_return_id)
  {

    $cart             = OrderReturn::find($order_return_id);
    $cart_items       = $cart->order_items;
    $total_mrp        = 0; 
    $total_sale_price = 0;
    $total_quantity    = 0;
    $total_accepted_qty    = 0;
    $total_rejected_qty    = 0;
    $total_accepted_sale_price  = 0;
    $total_weight = 0;
    foreach ($cart_items as $item) {

      $total_mrp   += ($item->accepted_quantity) ? $item->mrp * $item->accepted_quantity : $item->mrp* $item->total_quantity;
     // $total_sale_price += $item->total_sale_price;
      $total_sale_price += ($item->accepted_quantity) ? $item->sale_price * $item->accepted_quantity : $item->sale_price * $item->total_quantity;
      $total_quantity  += $item->total_quantity;
      $total_weight += ($item->accepted_quantity) ?$item->product->weight * $item->accepted_quantity : $item->product->weight * $item->total_quantity;
      $total_accepted_sale_price += ($item->accepted_quantity) ? $item->accepted_quantity * $item->sale_price : $item->sale_price * $item->total_quantity;
      $total_accepted_qty += $item->accepted_quantity;
      if($item->rejected_quantity < '0'){
       $item->rejected_quantity = '0'; 
      }
      $total_rejected_qty += $item->rejected_quantity;
    }

    $cart->total_mrp = $total_mrp;
    $cart->total_weight = $total_weight;
    $cart->total_sale_price = $total_sale_price;
    $cart->accepted_sale_price = $total_accepted_sale_price;
    $cart->total_quantity  = $total_quantity;
    $cart->accepted_quantity = $total_accepted_qty;
    $cart->rejected_quantity = $total_rejected_qty;
    $cart->save();
    return $cart;
  }
/*
    public function get_total_weight($product){
      $total_weight = '';
      foreach ($products as $product) {
        $total_weight += $product->weight;
      }
      return $total_weight;
    }  */

    //Create duplicate cart when order failed
    public function createDuplicateOrder($order_id){
      $order = Order::find($order_id);
      $cart  = Order::where('user_id',$order->user_id)->where('is_cart','1')->first();
      if(!$cart){
        $newOrder = new Order();
	      $newOrder->order_id = $this->generateOrderId();
        $newOrder->user_id  = $order->user_id;
        $newOrder->user_type  = $order->user_type;
        $newOrder->total_mrp  = $order->total_mrp;
        $newOrder->total_sale_price  = $order->total_sale_price;
        $newOrder->total_weight  = $order->total_weight;
        $newOrder->discount_on_mrp  = $order->discount_on_mrp;
        $newOrder->redeemed_points  = $order->redeemed_points;
        $newOrder->redeemed_points_discount  = $order->redeemed_points_discount;
        $newOrder->delivery_charges  = $order->delivery_charges;
        $newOrder->total_payable  = $order->total_payable;
        $newOrder->delivery_charges  = $order->delivery_charges;
        $newOrder->total_payable  = $order->total_payable;
        $newOrder->order_type  = $order->order_type;
        $newOrder->print_status  = $order->print_status;
        $newOrder->bundles  = $order->bundles;
        $newOrder->cancel_reason_id  = $order->cancel_reason_id;
        $newOrder->cancel_comment  = $order->cancel_comment;
        $newOrder->order_status = 'pending';
        $newOrder->payment_status = 'pending';
        $newOrder->is_cart = '1';
        $newOrder->created_at = Carbon::now();
        $newOrder->placed_at = NULL;
        $newOrder->payment_type = NULL;
        $newOrder->save();

        $allOrders = OrderItem::where('order_id',$order_id)->get();
        foreach ($allOrders as $key => $value) {
          $oldItem = OrderItem::find($value->id);
          $newItem = $oldItem->replicate()->fill([
                            'order_id' => $newOrder->id
                        ]);
          $newItem->save();
        }

        if($newOrder->order_type == 'physical_books') {
          $allOrderAddresses = OrderAddress::where('order_id',$order_id)->get();
          foreach ($allOrderAddresses as $key => $value) {
            $oldAddress = OrderAddress::find($value->id);
            $newAddress = $oldAddress->replicate()->fill([
                              'order_id' => $newOrder->id
                          ]);
            $newAddress->save();
          }
        }
      }
      else {
        $newOrder = Order::find($cart->id);
        $oldOrderItems = OrderItem::where('order_id',$order_id)->get();
        foreach ($oldOrderItems as $key => $value) {
          $oldItem  = OrderItem::find($value->id);
          if($newOrder->order_type == 'physical_books') {
          $cartItem = OrderItem::where('id',$cart->id)->where('product_id',$oldItem->product_id)->first();
          }else {
            $cartItem = OrderItem::where('id',$cart->id)->where('coupon_id',$oldItem->coupon_id)->first();
          }
          if($cartItem){
            if($cartItem->order_type == 'physical_books'){
              $quantity         = $cartItem->quantity + $value->quantity;
              $mrp              = $cartItem->mrp;
              $sale_price       = $cartItem->sale_price;
              $total_mrp        = $mrp*$quantity;
              $total_sale_price = $sale_price*$quantity;
              $total            = $total_sale_price;
             
              $order_item_data = [
                'order_id'             => $cart->id,
                'product_id'           => $cartItem->product_id,
                'book_name'            => $cartItem->book_name,
                'book_sub_heading'     => $cartItem->book_sub_heading,
                'book_description'     => $cartItem->book_description,
                'book_additional_info' => $cartItem->book_additional_info,
                'mrp'                  => $mrp,
                'sale_price'           => $cartItem->item_sale_price,
                'total_mrp'            => $total_mrp,
                'total_sale_price'     => $total_sale_price,
                'total'                => $total,
                'ordered_quantity'     => $quantity,
                'supplied_quantity'    => $quantity,
                'language'             => $cartItem->language
              ];
            }
            else if($cartItem->order_type == 'digital_coupons'){
              $quantity = $cartItem->quantity;
              $mrp              = $cartItem->mrp;
              $total_mrp        = $mrp*$quantity;
              $total_sale_price = $item_sale_price*$quantity;
              $total            = $total_sale_price;
              $order_item_data = [
                'order_id'          => $cart->id,
                'coupon_id'         => $cartItem->coupon_id,
                'mrp'               => $mrp,
                'sale_price'        => $item_sale_price,
                'total_mrp'         => $total_mrp,
                'total_sale_price'  => $total_sale_price,
                'total'             => $total,
                'ordered_quantity'  => $quantity,
                'supplied_quantity' => $quantity,
              ];
            }
            $cartItem->update($order_item_data);
          }else {
            $newItem  = $oldItem->replicate()->fill([
                              'order_id' => $cart->id
                        ]);
            $newItem->save();  
          }
        }
        if($newOrder->order_type == 'physical_books') {
          $allOrderAddresses = OrderAddress::where('order_id',$order_id)->get();
          foreach ($allOrderAddresses as $key => $value) {
            $oldAddress = OrderAddress::find($value->id);
            $newAddress = $oldAddress->replicate()->fill([
                              'order_id' => $newOrder->id
                          ]);
            $newAddress->save();
          }
       }
       $this->updateCartCalc($cart->id,'no_redeem_action');
      }
      return $newOrder->id;
    }

    //merge existing and new carts when checkout fails
    public function mergeCart($cart_id)
    {
      return $existing_cart = Order::find($cart_id);
      $user_id = $existing_cart->user_id;
      $new_cart = Order::where('user_id',$user_id)->where('is_cart','1')->where('id','!=',$existing_cart->id)->first();
      if($new_cart)
      {
        foreach($new_cart->order_items as $new_cart_item){
          $new_cart_item->order_id = $existing_cart->id;
          $new_cart_item->save();
        }

        $existing_cart =  $this->updateCartCalc($existing_cart->id);
        $new_cart->delete();
      }
      return $existing_cart;
    }

    // maintainReferralHistory
    public function maintainReferralHistory($points, $operation, $entry = '', $user = null, $refunded='',$order_id = null, $referrer_id = null, $wishlist_id = null, $wish_return_id = null)
    {
      $points = (integer)$points;
      if($points > 0){
        if($operation == 'added' || $operation == 'deducted'){

          if($user === null) {
            $user = Auth::guard('api')->user();
          }

          if($user) {
            if($operation == 'added'){
              $user->points = $user->points + $points;
              $user->save();  
            }
            if($operation == 'deducted'){
              if($entry != 'no_deduct') {
                $user->points = $user->points - $points;
                $user->save(); 
              }
            }
            if($entry == '' || $entry == 'no_deduct') {
              $refer_history = ReferHistory::create([
                'customer_id'    => $user->id,
                'order_id'       => $order_id,
                'wishlist_id'    => $wishlist_id,
                'wish_return_id' => $wish_return_id,
                'referrer_id'    => $referrer_id,
                'points'         => $points,
                'point_status'   => $operation,
                'status'         => 'active',
                'refunded'       => ($refunded == '1') ? '1': '0',
              ]);


            }
          }
        }
        return true;
      }
    }

    //assign qr codes
    public function assign_qr_codes($cart)
    {
      foreach ($cart->order_items as $order_item) {
        $assigned_qr_code_ids = OrderCouponQrCode::pluck('coupon_qr_code_id')->toArray();
        $qr_codes = CouponQrCode::where('coupon_master_id',$order_item->coupon->coupon_master_id)->whereNotIn('id',$assigned_qr_code_ids)->orderBy('id','asc')->get()->take($order_item->supplied_quantity);

        foreach ($qr_codes as $qr_code) {
          OrderCouponQrCode::create([
            'order_item_id' => $order_item->id,
            'coupon_qr_code_id' => $qr_code->id
          ]);
        }

        //update available quantity
        $order_item->coupon->decrement('available_quantity',$order_item->supplied_quantity);
      }
      return true;
    }

    // get_redeemed_points_data
    public function get_redeemed_points_data($order_id) {
        $user = Auth::guard('api')->user();
        $cart = Order::find($order_id);
        $redeemed_points = $redeemed_points_discount = 0;
        if($cart) {
            $total_sale_price = $cart->total_sale_price;
            $user_points = (integer)$user->points;
            $required_points = ($total_sale_price)*Setting::get('points_per_rs');
            if($user_points > $required_points) {
                $redeemed_points = $required_points;
            }else{
                $redeemed_points = $user_points;
            }
            $redeemed_points_discount =  $redeemed_points/Setting::get('points_per_rs');
          
        }
        return [
          'redeemed_points'          => $redeemed_points, 
          'redeemed_points_discount' => $redeemed_points_discount, 
          'required_points'          => $required_points, 
        ];
    }

    public function get_root_category($category_id) {
      $category = Category::where('id',$category_id)->where(['status' => 'active', 'is_live' => '1'])->first();
      
      if($category){
        if($category->parent_id) {
          /*echo '<br><br>cat id: '.$category->id;
          echo '<br>prnt cat id: '.$category->parent_id;*/
          $category = Category::where('id',$category->parent_id)->first();
          $this->get_root_category($category->id);
        } else {
          return $category->id;
        }
      } else {
        return false;
      }
    }

  public function generateInvoice($order){
    $data[] = $this->generateInvoiceData($order);    
    $dir = public_path('uploads/invoices');
    if (! is_dir($dir))
        mkdir($dir, 0777, true);
    
    $data_array = $data;
    //return $data_array;
    $config = ['instanceConfigurator' => function($mpdf) {
                  $mpdf->SetWatermarkText('DRAFT');
                  $mpdf->showWatermarkText = true;
              }];
    //$pdf = PDF::loadView('invoice', ['data_array' => $data]);
    $pdf = PDF::loadHtml(view('invoice', ['data_array' => $data]),$config);
    $name = 'uploads/invoices'.'/'. $order->order_id .'.pdf';
    //return $pdf->stream($name);
    $pdf->save($name);
    $order->invoice = $name;
    $order->save();
  }

  public function generateInvoiceData($order){
          $order_items = [];
          $billing_address = [];
          $shipping_address = [];
          if($order->order_type == 'physical_books'){
          $billing_address = [
             'company_name' => $order->billing_address->company_name,
             'customer_name'=>$order->billing_address->customer_name,
              'address' => $order->billing_address->house_no.', '.$order->billing_address->street.', '.$order->billing_address->landmark.', '.$order->billing_address->area.', '.$order->billing_address->city .'-'.$order->billing_address->postal_code ,
              'state' => $order->billing_address->state ,
              'mobile' => $order->billing_address->contact_number,
              'email' => $order->billing_address->email];
          $shipping_address = [
             'company_name' => $order->shipping_address->company_name,
             'customer_name'=>$order->shipping_address->customer_name,
              'address' => $order->shipping_address->house_no.', '.$order->shipping_address->street.', '.$order->shipping_address->landmark.', '.$order->shipping_address->area.', '.$order->shipping_address->city .'-'.$order->shipping_address->postal_code ,
              'state' => $order->shipping_address->state ,
              'mobile' => $order->shipping_address->contact_number,
              'email' => $order->shipping_address->email];
          }

          if($order->payment_type == ''){
            $payment_type = 'Offline';
          } else {
            $payment_type = $order->payment_type;
          } 
         
          foreach ($order->order_items as $ik=>$item) {
             if($order->order_type == 'physical_books'){
                $product_name = $item->product->get_name();
                $weight = number_format($item->product->weight,'2','.',',');
                $total_weight = number_format($item->product->weight * $item->supplied_quantity,'2','.',',');
              } else {
                $product_name = $item->coupon->get_name();
                $weight = 'N/A';
                $total_weight = 'N/A';
              } 
            $order_items[] = [
                  'id' => $ik+1,
                  'name' => $product_name,
                  'quantity' => $item->supplied_quantity,
                  'mrp' => $item->mrp,
                  'rate' =>$item->sale_price,
                  'total' =>$item->sale_price * $item->supplied_quantity,
                  'weight' => $weight,
                  'total_weight'=>$total_weight,
                ];
          }
          $data = [
            'invoice_no' => $order->order_id,'order_type'=>$order->order_type,'dated' => date('M d, Y',strtotime($order->created_at)),
            'order_id' => $order->id,'user_name'=>$order->user->first_name." ".$order->user->last_name,'billing_address' => $billing_address,'shipping_address'=>$shipping_address,
            'order_items' => $order_items,'total_weight'=>number_format($order->total_weight,'2','.',','),'bundles'=>$order->bundles,'payment_type'=>$payment_type,'total_amount'=> $order->total_payable,'total_sale_price'=>$order->total_sale_price,'delivery_charges'=>$order->delivery_charges,
       ];
      return $data;  
    }

    public function generateOrderReturnInvoiceData($order_return){
          $order_return_items = [];
          if($order_return->user->delivery_address) {
          $billing_address = [
             'company_name' => $order_return->user->delivery_address->company_name,
             'customer_name'=>$order_return->user->first_name." ".$order_return->user->last_name,
              'address' => $order_return->user->delivery_address->house_no.', '.$order_return->user->delivery_address->street.', '.$order_return->user->delivery_address->landmark.', '.$order_return->user->delivery_address->area.', '.$order_return->user->delivery_address->city .'-'.$order_return->user->delivery_address->postal_code ,
              'state' => $order_return->user->delivery_address->state ,
              'mobile' => $order_return->user->delivery_address->contact_number,
              'email' => $order_return->user->delivery_address->email];
          }
          else {
            $billing_address = '';
          }    
          if($order_return->payment_type == ''){
            $payment_type = 'Offline';
          } else {
            $payment_type = $order_return->payment_type;
          }   
          foreach ($order_return->order_items as $ik=>$item) {
            $order_return_items[] = [
                  'id' => $ik+1,
                  'name' => $item->product->get_name(),
                  'quantity' => $item->accepted_quantity,
                  'mrp' => $item->mrp,
                  'rate' =>$item->sale_price,
                  'total' =>$item->sale_price * $item->accepted_quantity,
                  'weight' => number_format($item->product->weight,'2','.',','),
                  'total_weight'=>$item->product->weight * $item->accepted_quantity,
                ];
          }
          $data = [
            'invoice_no' => $order_return->id,
            'dated' => date('M d, Y',strtotime($order_return->created_at)),
            'order_return_id' => $order_return->id,
            'user_name'=>$order_return->user->first_name." ".$order_return->user->last_name,
            'billing_address' => $billing_address,
            'shipping_address'=>$billing_address,
            'order_items' => $order_return_items,
            'total_weight'=>number_format($order_return->total_weight,'2','.',','),
          //  'bundles'=>$order_return->bundles,
            'payment_type'=>$payment_type,
            'total_amount'=> $order_return->accepted_sale_price,
            'total_sale_price'=>$order_return->total_sale_price,
        
       ];
    return $data;  
    }


    public function getStockData($product_id){
          $data = array();
          $total_data = Stock::select('id','pof_qty','ecm_qty')
                        ->where('product_id', $product_id)
                        ->get()->toArray();
              $total_ecm = 0;
              $total_pof = 0;  
              $stock_ids = array();
            foreach ($total_data as $val) {
                $stock_ids[]  = $val['id'];   
                $total_ecm += $val['ecm_qty'];
                $total_pof += $val['pof_qty'];                 
            }              
            $total_gro_data  = StockGro::select(
                            DB::raw("sum(gro_qty) as gro_total_qty"),
                          )->whereIn('stock_id', $stock_ids)
                           ->first(); 
          $return_gro = StockTransfer::select(DB::raw("sum(gto_in_qty) as gtoin_total_qty"))->where('product_id',$product_id)->first();
          //$total_gro = intval($total_gro_data->gro_total_qty)+$return_gro->gtoin_total_qty;
          $total_gro = intval($total_gro_data->gro_total_qty);
         /* $order = Order::WhereHas('order_item',function($q) use($product_id){
                        $q->where('product_id',$product_id);
                    })->where('is_cart','1')->first();
          $order_id = '';
          if($order){
            $order_id = $order->id;
          }*/
          $order_supplied = OrderItem::select(
                          DB::raw("sum(supplied_quantity) as order_supplied"),
                          )->where('product_id', $product_id)
                           //->whereNotIn('order_id',[$order_id])
                            ->whereHas('order',function($q1){
                            $q1->whereIn('order_status',['shipped','completed']);
                           })
                           ->first()->toArray();
          $order_supplied = $order_supplied['order_supplied'];
          $scrap_data = StockTransfer::select(
                          DB::raw("sum(scrap_qty) as scrap_qty"),
                          )->where('product_id', $product_id)
                           ->first()->toArray(); 

                           /* $order_return = OrderReturn::WhereHas('order_return_item', 
                            function($q) use($product_id){
                              $q->where('product_id',$product_id);
                            })->where('is_cart','1')->first();
            $order_return_id = '';
            if($order_return){ $order_return_id = $order_return->id;}*/
            $return_go_downs =  OrderReturnItem::select(
                          DB::raw("sum(accepted_quantity) as returned_qty"),
                          )->where('product_id', $product_id)
                          // ->whereNotIn('order_return_id',[$order_return_id])
                           ->whereNotNull('accepted_quantity')
                           ->whereHas('order_return',function($q1){
                            $q1->where('order_status','accepted');
                           })
                           ->first()->toArray();
            $gro_to_return = StockTransfer::select(DB::raw("sum(gto_out_qty) as gtoout_total_qty"))->where('product_id',$product_id)->first();
            $stockTransfer    =   StockTransfer::where('product_id',$product_id)->first();

            // $return_go_down =   $return_go_down['returned_qty'] + $gro_to_return->gtoout_total_qty;
            $scrap_qty = intval($scrap_data['scrap_qty']);
            //$return_go_down =   ($return_go_downs['returned_qty'] + $gro_to_return->gtoout_total_qty) - ($return_gro->gtoin_total_qty + $scrap_qty);
            $return_go_down =  $return_go_downs['returned_qty'];
            //$total_gro = $total_gro - $gro_to_return->gtoout_total_qty;
            //$balance_inside =  $total_gro - $order_supplied;
            $balance_inside =  $total_gro - $order_supplied + $return_gro->gtoin_total_qty - $gro_to_return->gtoout_total_qty;
            $balance_outside = ($total_pof + $total_ecm) - $total_gro;
            $total_balance  =($balance_inside + $balance_outside +$return_go_down);
            $actual_westage = $total_balance + ($gro_to_return->gtoout_total_qty - $return_gro->gtoin_total_qty);
            $actual_westage_inside =  $actual_westage - $scrap_qty;
            $actual_sale = $order_supplied - $return_go_down;
            $actual_go_down_stock = $return_go_down - $return_gro->gtoin_total_qty+ $gro_to_return->gtoout_total_qty;
                $data =  ['total_pof'  => $total_pof,
                'total_ecm' => $total_ecm,
                'total_gro' => $total_gro,
                'order_supplied' => $order_supplied,
                'return_go_down' => $return_go_down,
                'balance_outside' => $balance_outside,
                'balance_inside' => $balance_inside,
                'total_balance' => $total_balance,
                'scrap_qty'=> $scrap_qty ,
                'actual_westage' => $actual_westage,
                'actual_westage_inside' => $actual_westage_inside,
                'actual_sale' => $actual_sale,
                'gto_in_qty' => ($return_gro->gtoin_total_qty) ??  '-',
                'gto_out_qty'=> ($gro_to_return->gtoout_total_qty) ??  '-',
                'actual_go_down_stock' =>$actual_go_down_stock,
              ];
              return $data;
               /*'gto_in_qty' => ($stockTransfer->gto_in_qty) ??  '-',
                'gto_out_qty'=> ($stockTransfer->gto_out_qty) ??  '-'
              ];
                return $data;*/
    }

    public function isProductExist($business_category_id,$category_id,$layout,$lang){
      $category = Category::find($category_id);
      $childs = array();
      /*  if(count($category->childs_active())) {
        foreach ($category->childs_active() as $key => $value) {
          $allChilds = $this->get_all_child_categories($value->id);
          foreach ($childs as $k => $v) {
            array_push($childs,$value->id);
          }
        }
      }*/
      array_push($childs,$category_id);
      if($layout == 'digital_coupons'){
         $count = SubCoupon::whereHas('coupon', function($q1){
                    $q1->where('is_live', '1')
                    ->where('is_deleted','0')
                    ->where('end_date', '>=',Carbon::now()->format('Y-m-d h:i') );
                })->whereHas('categories',function($q) use($business_category_id,$childs){
                    $q->whereIn('category_id',$childs);
                  })->where('available_quantity','>','0')->where(['business_category_id'=>$business_category_id,'status' => 'active'])->count();
      }else {
        $user = Auth::guard('api')->user();
        if($business_category_id !=''){
          if($user) {
            $count = Product::whereHas('categories',function($q) use($business_category_id,$childs,$lang)        {
                    $q->whereIn('category_id',$childs);
                  })->where(['business_category_id'=>$business_category_id,'is_live' => '1','status' => 'active','stock_status' => 'in_stock'])->whereIn('visible_to',['both',$user->user_type])->whereIn('language',['both',strtolower($lang)])->count();
             }else {
            $count = Product::whereHas('categories',function($q) use($business_category_id,$childs,$lang)        {
                    $q->whereIn('category_id',$childs);
                  })->where(['business_category_id'=>$business_category_id,'is_live' => '1','status' => 'active','stock_status' => 'in_stock'])->whereIn('visible_to',['retailer','both'])->whereIn('language',['both',strtolower($lang)])->count();
          }
        }else {
          if($user){
            $count = Product::whereHas('categories',function($q) use($childs,$lang)   {
                    $q->whereIn('category_id',$childs);
                  })->where(['is_live' => '1','status' => 'active','stock_status' => 'in_stock'])->whereIn('visible_to',['both',$user->user_type])->whereIn('language',['both',strtolower($lang)])->count();
          }else {
            $count = Product::whereHas('categories',function($q) use($childs,$lang)        {
                    $q->whereIn('category_id',$childs);
                  })->where(['is_live' => '1','status' => 'active','stock_status' => 'in_stock'])->whereIn('visible_to',['retailer','both'])->whereIn('language',['both',strtolower($lang)])->count();  
          }
        }
       }
       return $count;
    }

  public function getAllActiveItemFromTypeAndId($order_type, $item_id) {
    switch ($order_type) {
      case "physical_books":
        $item = Product::where(['id'=>$item_id, 'status'=>'active','is_live'=>'1'])->first();
        break;
      case "digital_coupons":
        $item = SubCoupon::where(['id'=>$item_id, 'status'=>'active'])->first();
        break;
      default:
        $item = Product::where(['id'=>$item_id, 'status'=>'active','is_live'=>'1'])->first();
    }
    return $item;
  }

  public function isItemAvailableForUser($item_id,$user_id){
    $user = User::find($user_id);
    $is_available = OrderItem::whereHas('product',function($q) use($item_id,$user){
                    $q->where('id',$item_id)
                      ->whereIn('visible_to',['both',$user->user_type]);
                    })->where('product_id',$item_id)->whereNull('coupon_id')->whereHas('order',function($q1) use($user) { $q1->where('user_id',$user->id); })->count();
    return $is_available;
  }

  public function generateOrderId() {
    $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code    = substr(str_shuffle($permitted_chars), 0, 8);
    if(Order::where('order_id',$code)->first()){
      generateOrderId(); // call recursivly
    }
    return $code; 
  }

  public function closePaymentAttempt($user_id){
    $cart = Order::where('user_id',$user_id)->where('is_payment_attempt','1')->where('is_cart','1')->latest()->first();
    if($cart){
        $job = new GetOrderStatusFromPayu($cart->id);
        \Log::info("job dispatched : ");
        dispatch($job);
        return true; 
    }else {
      return false;
    }
  }

  public function markOrderStatusAsFailed($order_id,$user,$data=null,$is_user_cancelled){
        $cart = Order::find($order_id);
        //to mark order failed and create duplicate order 
        if($cart) {
          $cart->order_status = 'failed';
          $cart->placed_at = date('Y-m-d H:i:s');
          $cart->is_cart = '0';
          $cart->is_payment_attempt = '2';
          $cart->payment_status = 'failed';
          $cart->save();
         
          $payment = Payment::where(['order_id' => $cart->id, 'user_id' => $user->id, 'payment_type' => 'payu'])->latest()->first();
          if($payment){
            $payment = Payment::find($payment->id);
            $payment->tran_ref = @$data['mihpayid'];
            $payment->api_response = json_encode(@$data);
            // update this flag when transaction cancelled by iser
            $payment->is_user_cancelled = $is_user_cancelled;
            $payment->status = 'failed';
            $payment->save();   
            $id = $this->createDuplicateOrder($cart->id); 
            \Log::info("duplicate order".$id);
            $cart = Order::where('id',$cart->id)->where('user_id',@$user->id)->where('is_cart','1')->first();
                    
            if($cart){
              if($cart->order_type == 'physical_books')
              {
                $this->mergeCart($order_id);
              }
            }
            return true;  
          }else {
            return false;
          }
          
        }else {
          return false;
        }
  }

  public function verifyPaymentForPayu($cart){
        $user = User::find($cart->user_id);
        //$payment = Payment::where('order_id',$cart->id)->latest()->first();
        //$payment_id = $payment->id;
        $settings = Setting::pluck('value','name')->all();
        if($settings['payu_mode'] == 'sandbox'){
            $payment_url = $settings['payu_sandbox_url'].'_payment';
            $key = $settings['payu_sandbox_key'];
            $salt = $settings['payu_sandbox_salt'];
            $url = "https://test.payu.in/merchant/postservice?form=2";
        } else {
            $payment_url = $settings['payu_production_url'].'_payment';
            $key = $settings['payu_live_key'];
            $salt = $settings['payu_live_salt'];
            $url = "https://info.payu.in/merchant/postservice?form=2";
        }
        //For multiple transaction id we can add multiple txnid by adding | in between
        $txnid = $cart->order_id;
              //Genrate Hash Token
        $hash_string = $key.'|verify_payment|'.$txnid.'|'.$salt;
        $hash = hash('sha512', $hash_string);
        $req = curl_init($url);
        curl_setopt($req, CURLOPT_URL, $url);
        curl_setopt($req, CURLOPT_POST, true);
        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
        $headers = array( "Content-Type: application/x-www-form-urlencoded");
        curl_setopt($req, CURLOPT_HTTPHEADER, $headers);
        $data = "key=".$key."&command=verify_payment&var1=".$txnid."&hash=".$hash;
        \Log::info("Payload " . $url.$data);       
        curl_setopt($req, CURLOPT_POSTFIELDS, $data);
        $resp = curl_exec($req);
        curl_close($req);
        $response = json_decode($resp,true);
        \Log::info("Data".$resp);
        if($resp != ''){
          $data = $response['transaction_details'][$txnid];
          return $data;
        }else {
          return false;
        }
  }

  public function markOrderStatusAsSuccess($cart_id,$user,$data){
          $cart = Order::findOrfail($cart_id);   
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

                //###### Send Order Placed Email ######
                if($user->email) {
                    try
                    {
                      $subject = "SSGC- We are happy to announce your order confirmation!";
                      Mail::to($user->email)->send(new SendOrderPlacedEmailCustomer($user,$cart,$subject));
                    } catch(\Exception $e){
                      //skip sending mail and redirect to success page
                    }
                }
                //###### Send Order Placed Email ######

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
              }
}