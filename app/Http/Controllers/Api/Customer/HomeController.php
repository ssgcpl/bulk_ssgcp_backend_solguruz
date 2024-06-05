<?php

namespace App\Http\Controllers\Api\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\BusinessCategory;
use App\Models\Banner;
use App\Models\TeamMember;
use App\Models\CouponMaster;
use App\Models\User;
use App\Models\Country;
use App\Models\CouponQrCode;
use App\Models\Setting;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\OrderReturn;
use App\Models\OrderReturnItem;
use App\Models\SubCoupon;
use App\Http\Resources\Customer\BannerResource;
use App\Http\Resources\Customer\BusinessCategoryResource;
use App\Http\Resources\Customer\TeamMemberResource;
use App\Http\Resources\Customer\CouponInfoResource;
use App\Http\Resources\Customer\DealerResource;
use App\Http\Resources\Customer\HomeSearchResource;
use App\Http\Resources\Customer\ReturnBookListResource;
use App\Http\Resources\Customer\PreviousOrderResource;
use App\Http\Resources\Customer\TrendingBookResource;
use Validator;
use Auth,DB;
use Carbon\Carbon;
use App\Models\Helpers\CommonHelper;

/**
 * @group Customer Endpoints
 *
 * Customer Apis
 */

class HomeController extends BaseController
{ 
  use CommonHelper;
  /**
  * Home: Business Categories
  *
  *
  * @response
  {
    "success": "1",
    "status": "200",
    "message": "Data Found Successfully",
    "data": [
        {
            "id": "1",
            "name": "Books",
            "type": "books",
            "url": "",
            "image": "http://cloud1.kodyinfotech.com:7000/ssgc/public/uploads/media/0bc1554ec2c118bff175ab17e332c6e0.png"
        },
        {
            "id": "2",
            "name": "E- Books",
            "type": "e_books",
            "url": "",
            "image": "http://cloud1.kodyinfotech.com:7000/ssgc/public/uploads/media/84a4a640eff66b428b089c933a0edd05.png"
        }
    ]
  }
  */
  public function business_categories() {
      $business_categories = BusinessCategory::where(['status'=>'active', 'is_live' => '1'])->orderBy('display_order','asc')->get();
      if(count($business_categories)) {
          return $this->sendResponse(BusinessCategoryResource::Collection($business_categories),trans('common.data_found'));
      } else {
          return $this->sendResponse([],trans('common.no_data'));
      }
  }

  /**
  * Master: Dealers List
  *
  *
  * @response
  {
    "success": "1",
    "status": "200",
    "message": "Data Found Successfully",
    "data": [
        {
            "id": "1",
            "name": "Books",
            "type": "books",
            "url": "",
            "image": "http://cloud1.kodyinfotech.com:7000/ssgc/public/uploads/media/0bc1554ec2c118bff175ab17e332c6e0.png"
        },
        {
            "id": "2",
            "name": "E- Books",
            "type": "e_books",
            "url": "",
            "image": "http://cloud1.kodyinfotech.com:7000/ssgc/public/uploads/media/84a4a640eff66b428b089c933a0edd05.png"
        }
    ]
  }
  */
  public function dealers() {
      $dealers = User::where(['status'=>'active', 'user_type' => 'dealer', 'verified' => '1'])->paginate();
      if(count($dealers)) {
          return $this->sendPaginateResponse(DealerResource::Collection($dealers),trans('common.data_found'));
      } else {
          return $this->sendResponse([],trans('common.no_data'));
      }
  }

  /**
  * Home: Contact Us Info
  * @response
  {
    "success": "1",
    "status": "200",
    "message": "Data Found Successfully",
    "data": {
        "contact_email": "1.0.0",
        "contact_mobile": "soft",
    }
  }
  */
  public function contact_us_info()
  {
    try{
      $country = Country::where('status','active')->first();
      $contact_info = [
        "country_code"   => $country->country_code,
        "contact_email"  => Setting::get('contact_email'),
        "contact_mobile" => Setting::get('customer_care_no')
      ];
      return $this->sendResponse($contact_info, trans('common.data_found'));
    } catch(\Exception $e) {
        DB::rollback();
        return $this->sendError('',trans('common.something_went_wrong'));
    }
  }
  /**
  * Home: App version
  * @response
  {
    "success": "1",
    "status": "200",
    "message": "Data Found Successfully",
    "data": {
        "android_version": "1.0.0",
        "android_version_update_type": "soft",
        "iphone_version": "1.0.0",
        "iphone_version_update_type": "soft"
    }
  }
  */
  public function app_version() {
      try{
        $version = [
          "android_version" => Setting::get('android_app_version'),
          "android_version_update_type" => Setting::get('android_app_version_update_type'),
          "iphone_version" => Setting::get('apple_app_version'),
          "iphone_version_update_type" => Setting::get('apple_app_version_update_type'),
        ];
        return $this->sendResponse($version, trans('common.data_found'));
      } catch(\Exception $e) {
          DB::rollback();
          return $this->sendError('',trans('common.something_went_wrong'));
      }  
    }
  /**
  * For Backend Use
  */
  public function bulk_coupons($url='')
  {
      DB::beginTransaction();
      try{
          if($url ==''){
              $baseurl= config('adminlte.APP_BASE_URL');
              $url = $baseurl.'api/customer/bulk_coupons?page=1';
          }else{
              $url = $url;
          }
          $response = $this->get_bulk_coupons($url);
          $response = json_decode($response, true);


          $current_page = $current_page = $response['meta']['current_page'];
          $last_page = $response['meta']['last_page'];
          // print_r($response);die;
          foreach ($response['data'] as $key=>$value ){
              if($value['key'] != 'ssgc_1212'){
                  return $this->sendError('',trans('common.something_went_wrong'));
              }
              $coupon_master = CouponMaster::where('coupon_id', $value['id'])->first();

              if (!$coupon_master) {
                  $coupon_master = CouponMaster::create([
                      "coupon_id" => $value['id'],
                      "type"      => $value['type']  ,
                      "name"      => $value['name'],
                      "item_type" => $value['item_type'],
                      "item_name" => $value['item_name'],
                      "start_date" => $value['start_date'],
                      "end_date" => $value['end_date'],
                      "usage_limit" => $value['usage_limit'],
                      "quantity" => $value['quantity'],
                      "discount" => $value['discount'] ?$value['discount']:'',
                      "description" => $value['description'],
                      "is_live" => $value['is_live'],
                      "status" => $value['status'],
                      "state" => $value['state'],
                  ]);
                  // return($coupon_master);die;
                  $qr_codes = $value['qr_codes'];
                  // Make directory not if available
                  if (!is_dir('uploads/qr_codes/')) {
                      mkdir('uploads/qr_codes/',0777, true);
                  }
                  $app_url = config('adminlte.APP_BASE_URL');
                  foreach ($qr_codes as $qr) {

                      $src = $app_url.''.$qr['qr_code'];
                      $destination_path = '/uploads/qr_codes/'.basename($src);
                      $dest = public_path($destination_path);
                      // echo $src;die;
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

          }
          if($current_page != $last_page){
              $current_page = $current_page+1;
              $baseurl= config('adminlte.APP_BASE_URL');
              $url = $baseurl.'api/customer/bulk_coupons?page='.$current_page;
             $this->bulk_coupons($url);
          }

      DB::commit();
      return $this->sendResponse('', trans('coupons.added'));
      } catch(\Exception $e) {
          return $e->getMessage();
          DB::rollback();
          return $this->sendError('',trans('common.something_went_wrong'));
      }
  }

  /**
  * Home: Search
  *
  * @bodyParam type string required search type enum:books,coupons Example:books
  * @bodyParam search_string string required search data  Example:lorem ipsum
  * @responseFile scenario='success' storage/responses/home_search_list.json
  * @responseFile scenario='error' storage/responses/error_array.json
  */
  public function home_search(Request $request) {

      $validator = Validator::make($request->all(), [
        'type'  => 'required|in:books,coupons',
        'search_string'  => 'sometimes|nullable|max:50',
      ]);

      if($validator->fails()){
        return $this->sendError($this->array, $validator->errors()->first());       
      }

      DB::beginTransaction();
      try{

        $user = Auth::guard('api')->user();

        if($request->type == 'books'){
          $data = Product::where(['is_live' => '1','status' => 'active','stock_status'=>'in_stock']);

          //Check if user is logged in and show data according to the account type
          if($user){
            $data = $data->whereIn('visible_to',['both',$user->user_type]);
          }else {
             $data = $data->whereIn('visible_to',['both','retailer']);
          }

          // check if category is active/publish
          $data = $data->whereHas('categories',function($q) {
                      $q->whereHas('category',function($q1){
                        $q1->where('is_live','1')
                          ->where('status','active')
                          ->whereNull('parent_id');
                      });
                  });

          if(isset($request->search_string) && $request->search_string != null){
            $data = $data->where(function($q) use($request){
                        $q->where('name_english','like','%'.$request->search_string.'%')
                        ->orWhere('name_hindi','like','%'.$request->search_string.'%');
                      });
          }

       

        }else{
          $data = SubCoupon::where('available_quantity','>','0');

          if(isset($request->search_string) && $request->search_string != null){
            $data = $data->whereHas('coupon', function ($q) use($request) {
                $q->where('is_live', '1')
                ->where('is_deleted','0')
                ->where('end_date', '>=',Carbon::now())
                ->where('name','like','%'.$request->search_string.'%');
            })->where('status','active');
           
          }else{
            $data = $data->whereHas('coupon', function ($q) use($request) {
                $q->where('is_live', '1')
                ->where('is_deleted','0')
                ->where('end_date', '>=',Carbon::now());
            })->where('status','active');
          }
        }

        $data = $data->orderBy('created_at','desc')->paginate();

        if($data->count() > 0){
          
            foreach($data as $d){

              $d->display_name = $d->get_name();
              $d->search_type = $request->type;
              
              if($request->type == 'books'){
                $d->sale_price = $d->retailer_sale_price;
              }
              else
              {
                $d->sale_price = $d->sale_price;
              }

              if($request->type == 'books'){
                  
                  if($request->search_string != '')
                  {
                    if (str_contains(strtolower($d->name_hindi), strtolower($request->search_string))) { 
                      $d->language = 'hindi';
                      $d->display_name = $d->name_hindi;
                    }
                    else
                    {
                      $d->language = 'english';
                      $d->display_name = $d->name_english;
                    }
                  }
              }
              if(isset($user)){
                
                $d->sale_price = $d->get_price($user);
                
                if($request->type == 'books'){

                  $cart = $this->itemAddedToCartChecker('order',$d->id,$user);
                  if($cart){
                    $d->added_to_cart = '1';
                    $d->quantity = $cart->supplied_quantity;
                    $d->cart_item_id = $cart->id;
                  }
                }else{
                 
                  $cart = $this->itemAddedToCartChecker('coupon_order',$d->id,$user);
                  if($cart){
                    $d->added_to_cart = '1';
                    $d->quantity = $cart->supplied_quantity;
                    $d->cart_item_id = $cart->id;
                    
                  }
                }
                
              }
            }
          
          
          $response   = HomeSearchResource::Collection($data);
          return $this->sendPaginateResponse($response, trans('common.data_found'));
          
          
        }else{

          return $this->sendResponse($this->array,trans('common.no_data_found'));
        }
      }catch(\Exception $e){
       
        return $this->sendError($this->array,trans('common.api_error'));
      }
  }

  /**
  * Home: Make My Return
  * @authenticated
  *
  * @responseFile scenario='success' storage/responses/home_make_my_return.json
  * @responseFile scenario='error' storage/responses/error_array.json
  */
  public function make_my_return(Request $request) {

    DB::beginTransaction();
    try{

      $user = Auth::user();
      $now = Carbon::now();
      $returnable = OrderItem::where('is_returned','0')
                    ->whereIn('language',['hindi','english'])
                    ->whereHas('order',function($q) use($user){
                        $q->where(['user_id'=> $user->id,'order_status'=>'completed']);
                      })->whereHas('product',function($q) use($now){
                        $q->whereNull('last_returnable_date')
                          ->orWhere('last_returnable_date','>=',$now);
                      })
                      ->orderBy('created_at','desc')
                      ->get();
      if($returnable->count() > 0){
        
        foreach($returnable as $d){
          $cart = $this->itemAddedToCartChecker('order_return',$d->id);
          if($cart){
            $d->added_to_cart = '1';
          }
        }
      }

      /* $returned = OrderReturnItem::whereHas('order_return',function($q)use($user){
                $q->where(['user_id'=>$user->id,'is_cart'=> '0']);
              })->orderBy('created_at','desc')->limit(4)->get();
      */
      $returned = OrderReturn::query()->whereNull('added_by')->where(['user_id'=>$user->id,'is_cart'=> '0'])->orderBy('created_at','desc')->limit(4)->get();        
      $response = new \stdClass();

      $response->returnable_books = $returnable->count() > 0 ? ReturnBookListResource::collection($returnable) : array();
      $response->previous_orders = $returned->count() > 0 ? PreviousOrderResource::collection($returned) : array();

      return $this->sendResponse($response, trans('common.data_found'));
       
    }catch(\Exception $e){
     
      return $this->sendError($this->object,trans('common.api_error'));
    }
  }

  /**
  * Home: Trending Books List
  *
  * @responseFile scenario='success' storage/responses/trending_book_list.json
  * @responseFile scenario='error' storage/responses/error_array.json
  */
  public function trending_book_list(Request $request) {

    DB::beginTransaction();
    try{

      $user = Auth::guard('api')->user();

      $start_date = Carbon::today()->subDays(7);
      $end_date = Carbon::now();
      
      $datas = OrderItem::whereHas('order',function($q)use($start_date,$end_date){
          $q->where('payment_status','paid')
          ->whereBetween('created_at',[$start_date,$end_date]);
        });

      if($user){
        $datas = $datas->whereHas('product',function($q)use($user){
          $q->where('stock_status','in_stock')
            ->where('status','active')
            ->where('is_live','1')
            ->whereIn('visible_to',['both',$user->user_type]);
        });
      }else {
         $datas = $datas->whereHas('product',function($q)use($user){
           $q->where('stock_status','in_stock')
             ->where('status','active')
             ->where('is_live','1')
             ->whereIn('visible_to',['both','retailer']);
        });
      }

      $datas = $datas->select(array(
            DB::raw('product_id as product_id'),
            DB::raw('COUNT(product_id) as product_count')
        ))->groupBy('product_id')
          ->orderBy('product_count','desc')
          ->orderBy('product_id','desc')
          ->wherenotNull('product_id')
          ->paginate(20);

      if($datas->count() > 0){
        
          foreach($datas as $data){
            $d = $data->product;
            $d->price = $d->retailer_sale_price;
            if(isset($user)){

              $d->price = $d->get_price($user);
              $cart = $this->itemAddedToCartChecker('order',$d->id,$user);
              if($cart){
                $d->added_to_cart = '1';
                $d->quantity = $cart->supplied_quantity;
                $d->cart_item_id = $cart->id;
              }
            }
          
          }
        
        $response  = TrendingBookResource::Collection($datas);
        return $this->sendPaginateResponse($response, trans('common.data_found'));
        
      }else{

        return $this->sendResponse($this->array,trans('common.no_data_found'));
      }
    }catch(Exception $e){
     
      return $this->sendError($this->array,trans('common.api_error'));
    }
  }

  /**
  * Home: Business Category Sections Data
  * @responseFile scenario='success' storage/responses/business_category_section_data.json
  * @responseFile scenario='error' storage/responses/error_array.json
  */
  public function business_category_section_data(Request $request) {

    DB::beginTransaction();
    try{

      $user = Auth::guard('api')->user();

      $categories = BusinessCategory::where(['is_live'=>'1','status'=>'active'])->get();
     
      $response = array();
      foreach($categories as $cat){

        $object = new \stdClass();
        $object->business_category_id = (string)$cat->id;
        $object->category_name = (string)$cat->category_name;
        $object->type = (string)$cat->layout;

        if($cat->layout == 'books'){

          $data = Product::where(['is_live' => '1','status' => 'active','stock_status' => 'in_stock'])->where('business_category_id',$cat->id);

          //Check if user is logged in and show data according to the account type
          // if($user){
          //   $data = $data->whereIn('visible_to',['both',$user->user_type]);
          // }else {
          //   $data = $data->whereIn('visible_to',['both','retailer']);
          // }

          // check if category is active/publish
          $data = $data->whereHas('categories',function($q) {
                      $q->whereHas('category',function($q1){
                        $q1->where('is_live','1')
                          ->where('status','active')
                          ->whereNull('parent_id');
                      });
                  });

          $data = $data->orderBy('republished_at','desc')->limit(20)->get();
        }else{

          $data = SubCoupon::where('available_quantity','>','0')
                  ->where('business_category_id',$cat->id)
                  ->where('status','active')
                  ->whereHas('coupon', function ($q){
                    $q->where('is_live', '1')
                    ->where('is_deleted','0')
                    ->where('end_date', '>=',Carbon::now()->format('Y-m-d h:i'));
                   })->orderBy('created_at','desc')->limit(20)->get();
          }


        $list = array();
        $container = array();
         

        if($data->count() > 0){

        
          foreach($data as $key => $value){

            $value->display_name = $value->get_name();
            
            if($cat->layout == 'books'){
              $value->sale_price = (string)$value->retailer_sale_price;
            }

            if($user){
              
              $value->sale_price = $value->get_price($user);
              
              if($cat->layout == 'books'){
               
                $cart = $this->itemAddedToCartChecker('order',$value->id,$user);
                if($cart){
                  $value->added_to_cart = '1';
                  $value->quantity = $cart->supplied_quantity;
                  $value->cart_item_id = $cart->id;
                  $value->sale_price = $value->get_price($user);

                }
              }else{
               
                $cart = $this->itemAddedToCartChecker('coupon_order',$value->id,$user);
                if($cart){
                  $value->added_to_cart = '1';
                  $value->quantity = $cart->supplied_quantity;
                  $value->cart_item_id = $cart->id;
                  
                }
              }
              
            }

            if($key <= 5){
              $list[] = $value;
            }else{
              $container[] = $value;
            }
          }
        
        $object->list = HomeSearchResource::collection($list);
        $object->container = HomeSearchResource::collection($container);
        $response[] = $object;

        
        }
      }
    
      if(count($response) > 0){
        
        return $this->sendResponse($response, trans('common.data_found'));
        
      }else{

        return $this->sendResponse($this->array,trans('common.no_data_found'));
      }
    }catch(\Exception $e){
     
      return $this->sendError($this->array,trans('common.api_error'));
    }
  }

  /**
  * For Backend Use
  */
  private function get_bulk_coupons($url){
      $header = array(
          "content-type: application/json"
      );

      // $postdata = json_encode($data);
      $ch = curl_init();
      $timeout = 120;
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
      // curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

      // Get URL content
      $response = curl_exec($ch);
      return $response;
      // print_r($response);die;
      // close handle to release resources
      curl_close($ch);
  }

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

   public function getSocialMediaLink(){
    $settings = Setting::pluck('value', 'name')->all();
    $data = new \stdClass();
    $data->facebook_url = $settings['facebook_url'];
    $data->twitter_url = $settings['twitter_url'];
    $data->instagram_url = $settings['instagram_url'];
    $data->telegram_url = $settings['telegram_url'];
    $data->whatsapp_url = $settings['whatsapp_url'];

    return $this->sendResponse($data, trans('common.data_found'));

  }
}
