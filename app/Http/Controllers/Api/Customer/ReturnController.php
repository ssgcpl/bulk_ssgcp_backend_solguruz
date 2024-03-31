<?php

namespace App\Http\Controllers\Api\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\OrderReturn;
use App\Models\OrderReturnItem;
use App\Http\Resources\Customer\ReturnBookListResource;
use App\Http\Resources\Customer\ReturnCartResource;
use App\Http\Resources\Customer\ReturnOrderListResource;
use App\Http\Resources\Customer\ReturnOrderDetailsResource;
use Validator;
use Auth,DB;
use Carbon\Carbon;
use App\Models\Helpers\CommonHelper;

/**
* @group Customer Endpoints
*
* Customer Apis
*/
class ReturnController extends BaseController
{
  use CommonHelper;
 
  /**
    * Return: Make My Return List
    * @authenticated
    * @bodyParam language string required Language (hindi,english). Example:hindi
    * @bodyParam category_id string optional Category_id (category_id or subcategory_id of any level). Example:3
    *
    * @responseFile scenario='success' storage/responses/make_my_return_list.json
    * @responseFile scenario='error' storage/responses/error_array.json
  */
  public function make_my_return_list(Request $request) {

      $validator = Validator::make($request->all(), [
       
        'category_id' => 'nullable|exists:categories,id,is_live,1',
        'language'    => 'required|in:english,hindi',
    
      ]);

      if($validator->fails()){
        return $this->sendError($this->array, $validator->errors()->first());       
      }

      DB::beginTransaction();
      try{

        $user        = Auth::guard('api')->user();
        $now         = Carbon::now();
       
        $all_category_ids = $this->get_all_child_categories(@$request->category_id);
        // echo "<pre>";print_r($all_category_ids);exit;
        $data = OrderItem::where('is_returned','0')
                      ->whereIn('language',['both',$request->language])
                      ->whereHas('order',function($que) use($user){
                          $que->where(['user_id'=> $user->id,'order_status'=>'completed']);
                        })->whereHas('product',function($q) use($now,$all_category_ids,$request){
                          $q->whereNull('last_returnable_date')
                            ->orWhere('last_returnable_date','>=',$now);
                            // ->whereIn('language',['both',$request->language]);
                            if($request->category_id)
                            {
                              $q->whereHas('categories',function($q1) use($all_category_ids){
                                $q1->whereIn('category_id',$all_category_ids);
                              });
                            }
                        })
                        ->orderBy('created_at','desc')->paginate();

        if($data->count() > 0){
          
          foreach($data as $d){
            $d->lang = $request->language;
            $cart = $this->itemAddedToCartChecker('order_return',$d->id);
            if($cart){
              $d->added_to_cart = '1';
            }
          }
          
          $response  = ReturnBookListResource::Collection($data);
          return $this->sendPaginateResponse($response, trans('products.return_book_list_success'));

        }else{

          return $this->sendResponse($this->array,trans('products.return_book_list_empty'));
        }
      }catch(\Exception $e){
       
        return $this->sendError($this->array,trans('common.api_error'));
      }
  }

  /**
    * Return: Add to return cart
    * @authenticated
    * @bodyParam order_item_id string required id from Make My Return List API . Example:1
    * @bodyParam quantity string required Quantity. Example:3
    *
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "The product is added into the return cart.",
    }
  */
  public function add_to_cart(Request $request) {

      $validator = Validator::make($request->all(), [
       
        'order_item_id' => 'required|exists:order_items,id',
        'quantity'      => 'required|numeric|min:1',
    
      ]);

      if($validator->fails()){
        return $this->sendError('', $validator->errors()->first());       
      }

      DB::beginTransaction();
      try{

        $user        = Auth::guard('api')->user();
        $now         = Carbon::now();

        $item  = OrderItem::find($request->order_item_id);

        if($item->is_returned == '1'){
          return $this->sendError('',trans('order_return.already_returned')); 
        }

        if($item->order->order_status != 'completed'){
          return $this->sendError('',trans('order_return.order_not_completed')); 
        }

        //Check if user is available
        if($user->verified != '1' && $user->email_verified_at == NULL) {
          return $this->sendError('',trans('orders_api.user_not_verified')); 
        }

        if($user->status != 'active') {
          return $this->sendError('',trans('orders_api.user_not_active')); 
        }

        //Check if item already in the cart
        $cart = OrderReturn::where('user_id',$user->id)->where('is_cart','1')->first();
        if($cart) {
          $cart_item = OrderReturnItem::where('order_item_id',$request->order_item_id)
                ->where('order_return_id',$cart->id)
                ->first();

          if($cart_item) {
            return $this->sendError('', trans('order_return.already_added_in_cart'));
          }   
        }
       
        $item_sale_price = $item->sale_price;
        $item_mrp        = $item->mrp;
        $product         = $item->product;
        $quantity        = $request->quantity;

        if($product->last_returnable_qty != null && $quantity > $product->get_max_return_quantity($item->supplied_quantity)){
          return $this->sendError('',trans('order_return.quantity_above_return_limit')); 
        }

        if($quantity > $item->supplied_quantity){
          return $this->sendError('',trans('order_return.quantity_above_purchase_limit')); 
        }
          
        $total_sale_price = $cart ? $cart->total_sale_price + ($item_sale_price*$quantity) : ($item_sale_price*$quantity);
        $total_mrp = $cart ? $cart->total_mrp + ($item_mrp*$quantity) : ($item_mrp*$quantity);
        $total_quantity =  $cart ? $cart->total_quantity + $quantity : $quantity;
        
      
  
        $data = [
            'user_id'          => $user->id,
            'requested_as'     => $user->user_type,
            'total_sale_price' => $total_sale_price,
            'total_mrp'        => $total_mrp,
            'total_quantity'   => $total_quantity,
            'is_cart'          => '1'
        ];

        //Create cart or update cart if exist
        if(!$cart)
        {
            $cart = new OrderReturn();
        }
        
        $cart->fill($data);
        
        if($cart->save()){

          //Add cart items
          $return_item  = new OrderReturnItem();
          $return_item->order_return_id = $cart->id;
          $return_item->order_item_id   = $item->id;
          $return_item->product_id = $product->id;
          $return_item->requested_as = $user->user_type;
          $return_item->mrp = $item->mrp;
          $return_item->total_mrp = $item->mrp * $quantity;
          $return_item->sale_price = $item->sale_price;
          $return_item->total_sale_price = $item->sale_price * $quantity;
          $return_item->total_quantity = $quantity;

          if($return_item->save()){
            DB::commit();
            $cart = $this->updateReturnCartCalc($cart->id);
            return $this->sendResponse('', trans('order_return.item_added_to_cart'));
          }else{
            DB::rollback();
            return $this->sendError('', trans('order_return.add_item_error'));
          }

        }else{
          DB::rollback();
          return $this->sendError('', trans('order_return.cart_create_error'));
        }
      }catch(\Exception $e){
        DB::rollback();
        return $this->sendError('',trans('common.api_error'));
      }
  }

  /**
    * Return: Update Quantity
    * @authenticated
    * 
    * @bodyParam return_item_id number required
    * @bodyParam quantity number required 
    *
    * @responseFile scenario="success" storage/responses/return_cart.json
    * @responseFile scenario="error" storage/responses/error_array.json
    * @response scenario="cart_empty"{
          "success": "1",
          "status": "202",
          "message": "Return cart is empty",
          "data": []
      }
  */
  public function update_quantity(Request $request)
  {
    $validator=  Validator::make($request->all(),[
      'return_item_id' => 'required|exists:order_return_items,id',
      'quantity'     => 'required|numeric|min:0',
    ]);

    if($validator->fails()) {
      return $this->sendError($this->array, $validator->errors()->first());
    }

    DB::beginTransaction();
    try{
        $return_item = OrderReturnItem::find($request->return_item_id);
        $product = $return_item->product;
        $cart = $return_item->order_return;
        $user = Auth::guard('api')->user();

        if($cart->user_id != $user->id) {
          return $this->sendError($this->array,trans('order_return.item_not_found'));
        }

        if($cart->is_cart != '1') {
          return $this->sendError($this->array,trans('order_return.cart_not_found')); 
        }

        $quantity        = $request->quantity;

        if($product->last_returnable_qty != null && $quantity > $product->get_max_return_quantity($return_item->order_item->supplied_quantity)){
          return $this->sendError($this->array,trans('order_return.quantity_above_return_limit')); 
        }

        if($quantity > $return_item->order_item->supplied_quantity){
          return $this->sendError($this->array,trans('order_return.quantity_above_purchase_limit')); 
        }

        if($quantity == 0) {

          $return_item->delete();
          DB::commit();

          //Delete cart if all items are removed from the cart
          if($cart->order_items->count() > 0){
            $cart = $this->updateReturnCartCalc($cart->id);
            return $this->sendResponse(ReturnCartResource::collection($cart->order_items), trans('order_return.item_removed'));
          }else{
            $cart->delete();
            return $this->sendException($this->array, trans('order_return.cart_cleared'));
          }
              
        }

        //Update quantity & price
        $return_item->total_quantity = $quantity;
        $return_item->total_sale_price = $return_item->sale_price * $quantity;
        $return_item->total_mrp = $return_item->mrp * $quantity;

        if($return_item->save()){
          DB::commit();
          $cart = $this->updateReturnCartCalc($cart->id);
          return $this->sendResponse(ReturnCartResource::collection($cart->order_items), trans('orders_api.quantity_updated'));
        }else{
          DB::rollback();
          return $this->sendError($this->array,trans('order_return.quantity_update_error'));
        }
    }catch(\Exception $e) {
      DB::rollback();
      return $this->sendError($this->array,trans('common.api_error'));
    }
  }

  /**
    * Return: My Return Cart
    * @authenticated
    * 
    * @responseFile scenario='success' storage/responses/return_cart.json
    * @responseFile scenario='error' storage/responses/error_array.json
  */
  public function my_cart()
  {
    try{

      $user = Auth::guard('api')->user();
      $cart = OrderReturn::where('user_id',$user->id)->where('is_cart','1')->first();

      if(!$cart) {
        return $this->sendError($this->array,trans('order_return.cart_is_empty')); 
      }

      $cart = $this->updateReturnCartCalc($cart->id);

      return $this->sendResponse(ReturnCartResource::collection($cart->order_items),trans('common.data_found'));

    }catch(\Exception $e) {
      return $this->sendError($this->array,trans('common.api_error'));
    }
  }

  /**
    * Return: Place Order Return
    * @authenticated
    * 
    * @bodyParam return_items array required array of return_item_id from Return: My Return Cart Example:[1,2]
    *
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "Order returned successfully.",
    }
   
  */
  public function place_order_return(Request $request)
  { 
    $user = Auth::guard('api')->user();
    $order_return = OrderReturn::where(['user_id'=>$user->id,'is_cart'=>'1'])->first();

    if(!$order_return){
      return $this->sendError('',trans('order_return.cart_not_found'));
    }

    if($order_return->order_status != 'return_placed'){
      return $this->sendError('',trans('order_return.cart_not_found'));
    }

    $validator=  Validator::make($request->all(),[
      'return_items'   => 'array|required',
      'return_items.*' => 'required|exists:order_return_items,id,order_return_id,'.$order_return->id,
    ]);

    if($validator->fails()) {
      return $this->sendError('', $validator->errors()->first());
    }

    DB::beginTransaction();
    try{
        $return_items = OrderReturnItem::whereIn('id',$request->return_items)->get();

        //Create new return cart for not selected items
        if($order_return->order_items->count() > $return_items->count()){
         
          $cart = new OrderReturn();
          $data = [
            'user_id'          => $user->id,
            'requested_as'     => $user->user_type,
            'total_sale_price' => '0',
            'total_mrp'        => '0',
            'total_quantity'   => '0',
            'is_cart'          => '1'
          ];

          $cart->fill($data);

          if($cart->save()){

            $items = OrderReturnItem::where('order_return_id',$order_return->id)
                      ->whereNotIn('id',$request->return_items)
                      ->get();

            foreach($items as $item){
              $item->order_return_id = $cart->id;
              $item->save();
            }

            DB::commit();

            $cart = $this->updateReturnCartCalc($cart->id);
            $order_return =  $this->updateReturnCartCalc($order_return->id);

            $order_return->is_cart = '0';
            $order_return->returned_at = Carbon::now();

            foreach ($order_return->order_items as $item) {
              $o_item = $item->order_item;
              $o_item->is_returned = '1';
              $o_item->save();
            }

            if($order_return->save()){
              
              return $this->sendResponse('', trans('order_return.order_return_success'));
            }else{
              DB::rollback();
              return $this->sendError('',trans('order_return.order_return_error'));
            }

          }else{
            DB::rollback();
            return $this->sendError('',trans('order_return.new_cart_error'));
          }

        }else{

          $order_return->is_cart = '0';
          $order_return->returned_at = Carbon::now();

          foreach ($order_return->order_items as $item) {
            $o_item = $item->order_item;
            $o_item->is_returned = '1';
            $o_item->save();
          }

          if($order_return->save()){
            DB::commit();
            return $this->sendResponse('', trans('order_return.order_return_success'));
          }else{
            DB::rollback();
            return $this->sendError('',trans('order_return.order_return_error'));
          }
        }
    }catch(\Exception $e) {
      DB::rollback();
      return $this->sendError('',trans('common.api_error'));
    }
  }

  /**
    * Return: Return Product List
    * @authenticated
    *
    * @bodyParam status array required enum:return_placed,dispatched,in_review,rejected,accepted Example:return_placed,dispatched,in_review,rejected,accepted
    *
    * @responseFile scenario='success' storage/responses/return_order_list.json
    * @responseFile scenario='error' storage/responses/error_array.json
  */
  public function return_orders_list(Request $request) {

      $validator = Validator::make($request->all(), [
        
        'status' => 'sometimes|nullable|array',
        'status.*' => 'sometimes|in:return_placed,dispatched,in_review,rejected,accepted',
        
      ]);

      if($validator->fails()){
        return $this->sendError($this->array, $validator->errors()->first());       
      }

      DB::beginTransaction();
      try{

        $user  = Auth::guard('api')->user();
        $now   = Carbon::now();
       
        $data = OrderReturn::where(['user_id'=>$user->id,'is_cart'=> '0'])->whereNull('added_by');

        if(isset($request->status) && count($request->status) > 0){
          $data = $data->whereIn('order_status',$request->status);
        }
        
        $data =  $data->orderBy('returned_at','desc')->paginate();

        if($data->count() > 0){
          
          $response  = ReturnOrderListResource::Collection($data);

          return $this->sendPaginateResponse($response, trans('order_return.return_order_list_success'));

        }else{

          return $this->sendResponse($this->array,trans('order_return.return_order_list_empty'));
        }
      }catch(\Exception $e){
       
        return $this->sendError($this->array,trans('common.api_error'));
      }
  }

   /**
    * Return: Dispatch Order Return
    * @authenticated
    * 
    * @bodyParam order_return_id string required order_return_id from Return: Return Product List Example:1
    * @bodyParam courier_name string required max:150 Courier service name Example:loremkaka
    * @bodyParam tracking_number string required max:100 Tracking number Example:123
    * @bodyParam receipt_image string required mimes:png,jpg,jpeg,svg max:10mb Receipt Image  Example:1
    *
    * @responseFile scenario='success' storage/responses/return_order_details.json
    * @responseFile scenario='error' storage/responses/error.json
   
  */
  public function dispatch_order_return(Request $request)
  { 
    $user = Auth::guard('api')->user();

    $validator=  Validator::make($request->all(),[
      'order_return_id'   => 'required|exists:order_returns,id,is_cart,0,order_status,return_placed,user_id,'.$user->id,
     // 'courier_name' => 'required|string|alpha_dash|max:150',
       'courier_name' => 'required|string|max:150',
      'tracking_number' => 'required|string|alpha_dash|max:100',
      'receipt_image' => 'required|image|mimes:png,jpg,jpeg,svg|max:10000',
    ]);

    if($validator->fails()) {
      return $this->sendError($this->object, $validator->errors()->first());
    }

    DB::beginTransaction();
    try{
        
        $order_return = OrderReturn::find($request->order_return_id);

        if(isset($request->receipt_image)){
          $order_return->receipt_image = $this->saveMedia($request->file('receipt_image'));
        }

        $order_return->courier_name = $request->courier_name;
        $order_return->tracking_number = $request->tracking_number;
        $order_return->order_status = 'dispatched';
        
        if($order_return->save()){
          DB::commit();
          return $this->sendResponse(new ReturnOrderDetailsResource($order_return), trans('order_return.order_return_dispatch_success'));
        }else{
          DB::rollback();
          return $this->sendError($this->object,trans('order_return.order_return_dispatch_error'));
        }
        
    }catch(\Exception $e) {
      DB::rollback();
      return $this->sendError($this->object,trans('common.api_error'));
    }
  }

  /**
    * Return: Order Return Details
    * @authenticated
    * 
    * @bodyParam order_return_id string required order_return_id from Return: Return Product List Example:1
    *
    * @responseFile scenario='success' storage/responses/return_order_details.json
    * @responseFile scenario='error' storage/responses/error.json
   
  */
  public function order_return_details(Request $request)
  { 
    $user = Auth::guard('api')->user();

    $validator=  Validator::make($request->all(),[
      'order_return_id'   => 'required|exists:order_returns,id,is_cart,0,user_id,'.$user->id,
    ]);

    if($validator->fails()) {
      return $this->sendError($this->object, $validator->errors()->first());
    }

    DB::beginTransaction();
    try{
        
        $order_return = OrderReturn::find($request->order_return_id);

        if($order_return){
         
          return $this->sendResponse(new ReturnOrderDetailsResource($order_return), trans('order_return.order_return_details'));
        }else{
         
          return $this->sendError($this->object,trans('common.api_error'));
        }
    }catch(\Exception $e) {
     
      return $this->sendError($this->object,trans('common.api_error'));
    }
  }

}
