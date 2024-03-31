<?php

namespace App\Http\Controllers\Api\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use App\Models\Product;
use App\Models\WishReturn;
use App\Models\DealerRetailer;
use App\Models\Setting;
use App\Models\ReferHistory;
use App\Http\Resources\Customer\AllWishReurnListResource;
use App\Http\Resources\Customer\MyWishReturnListResource;
use App\Http\Resources\Customer\DealerWishReturnRequestResource;
use App\Http\Resources\Customer\DealerWishReturnRequestDetailsResource;
use Validator;
use Auth,DB;
use Carbon\Carbon;
use App\Models\Helpers\CommonHelper;

/**
* @group Customer Endpoints
*
* Customer Apis
*/
class WishReturnController extends BaseController
{
  use CommonHelper;
 
  /**
    * Wish Return: All Wish Return List
    * @authenticated
    * @bodyParam language string required Language (hindi,english). Example:hindi
    * @bodyParam category_id string optional Category_id (category_id or subcategory_id of any level). Example:3
    *
    * @responseFile scenario='success' storage/responses/all_wish_return_list.json
    * @responseFile scenario='error' storage/responses/error_array.json
  */
  public function all_wish_return_list(Request $request) {

      $validator = Validator::make($request->all(), [
        
        'category_id'  => 'nullable|exists:categories,id,is_live,1',
        'language'     => 'required|in:english,hindi',

      ]);

      if($validator->fails()){
        return $this->sendError($this->array, $validator->errors()->first());       
      }

      DB::beginTransaction();
      try{

        $user        = Auth::guard('api')->user();

        $all_category_ids = $this->get_all_child_categories($request->category_id);
      
        $data = Product::where(['is_live' => '1','status' => 'active','stock_status' => 'in_stock'])
          ->whereIn('language',['both',$request->language])
          ->whereIn('visible_to',['both',$user->user_type]);

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
            });
        }
        $data = $data->orderBy('created_at','desc')->paginate();
           
        if($data->count() > 0){
          
          foreach($data as $d){
            $d->lang = $request->language;
          }
          
          $response  = AllWishReurnListResource::Collection($data);

          return $this->sendPaginateResponse($response, trans('wish_return.wish_list_found'));

        }else{

          return $this->sendResponse($this->array,trans('wish_return.wish_list_not_found'));
        }
      }catch(\Exception $e){
       
        return $this->sendError($this->array,trans('common.api_error'));
      }
  }

  /**
    * Wish Return: My Wish Return List
    * @authenticated
    * 
    * @responseFile scenario='success' storage/responses/my_wish_return_list.json
    * @responseFile scenario='error' storage/responses/error_array.json
  */
  public function my_wish_return_list(Request $request) {

    DB::beginTransaction();
    try{

      $user   = Auth::guard('api')->user();

      $data = WishReturn::where('user_id',$user->id)
              ->orderBy('created_at','desc')->paginate();
         
      if($data->count() > 0){
        
        $response  = MyWishReturnListResource::Collection($data);

        return $this->sendPaginateResponse($response, trans('wish_return.wish_list_found'));

      }else{

        return $this->sendResponse($this->array,trans('wish_return.wish_list_not_found'));
      }
    }catch(\Exception $e){
     
      return $this->sendError($this->array,trans('common.api_error'));
    }
  }

  /**
    * Wish Return: Add to Wish Return
    * @authenticated
    * @bodyParam book_id string required Book Id . Example:1
    * @bodyParam dealer_id string optional from WishList: User's Dealer List. Example:3
    * @bodyParam description string optional Max:500 Description. Example:lorem ipsum
    * @bodyParam quantity string required Quantity. Example:3
    *
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "The item is added into the wish return list.",
    }
  */
  public function add_to_wish_return(Request $request) {

      $validator = Validator::make($request->all(), [
       
        'book_id' => 'required|exists:products,id,is_live,1,stock_status,in_stock,status,active',
        'quantity'      => 'required|numeric|min:1',
        'dealer_id'     => 'sometimes|nullable|exists:users,id,status,active,user_type,dealer,verified,1',
        'description'      => 'sometimes|nullable|max:500',
    
      ]);

      if($validator->fails()){
        return $this->sendError('', $validator->errors()->first());       
      }

      DB::beginTransaction();
      try{

        $user        = Auth::guard('api')->user();

        if($user->user_type == 'retailer'){

          $dealers = DealerRetailer::where('retailer_id',$user->id)
                ->whereHas('dealer',function($q){
                    $q->where(['status'=>'active', 'user_type' => 'dealer', 'verified' => '1']);
                })->count();

          if($dealers > 0){

            if($request->dealer_id == null){
              return $this->sendError('', trans('wish_list.dealer_required'));
            }
            
            $valid_dealer = DealerRetailer::where(['dealer_id' => $request->dealer_id,'retailer_id' => $user->id])->first();

            if(!$valid_dealer){
              return $this->sendError('', trans('wish_list.invalid_dealer'));
            }
          }

        }

        if($user->user_type == 'retailer'){

          $exist = WishReturn::where(['user_id' => $user->id,'product_id'=>$request->book_id,'dealer_id'=>$request->dealer_id])->first();
        }else{

          $exist = WishReturn::where(['user_id' => $user->id,'product_id'=>$request->book_id])->first();
        }

        if($exist){

          $last_date = $exist->created_at;
          $now = Carbon::now();
          $diff =$last_date->diffInDays($now);
          $limit  = Setting::get('wish_return_days_limit');

          if($diff < $limit){
            $days = $limit - $diff;
            return $this->sendError('', trans('wish_return.new_wish_return_limit',['days'=>$days]));
          } 
        }
        
        $wishlist_count = WishReturn::where('user_id', $user->id)->where('product_id', $request->book_id)->withTrashed()->get()->count();
        
        $data     = $request->all();
        $data['wish_return_qty'] = $data['quantity'];
        $data['user_id'] = $user->id;
        $data['user_type'] = $user->user_type;
        $data['product_id'] = $data['book_id'];
        $wishlist = new WishReturn();
        $wishlist->fill($data);

        if($wishlist->save()){

          // add points for first wishlist
          if($wishlist_count == 0)
          {
              //check if points not added already 
              $this->maintainReferralHistory((integer)Setting::get('wish_return_points'), 'added','',$user,'',null,null,null,$wishlist->id);
          }

          DB::commit();
          return $this->sendResponse('', trans('wish_return.item_added_to_wish_return'));

        }else{

          DB::rollback();
          return $this->sendError('', trans('wish_return.add_item_error'));
        }

      }catch(\Exception $e){
        DB::rollback();
        return $this->sendError('',trans('common.api_error'));
      }
  }

  /**
    * Wish Return: Edit Wish Return Item
    * @authenticated
    * 
    * @bodyParam wish_return_id number required Example:1
    * @bodyParam quantity number required Example:2
    * @bodyParam description string optional Max:500 Description. Example:lorem ipsum
    *
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "The wish return item is updated.",
    }
  */
  public function edit_wish_return_item(Request $request)
  { 
    $user = Auth::guard('api')->user();

    $validator=  Validator::make($request->all(),[
      'wish_return_id' => 'required|exists:wish_return,id,user_id,'.$user->id,
      'quantity'       => 'required|numeric|min:1',
       'description'      => 'sometimes|nullable|max:500',
    ]);

    if($validator->fails()) {
      return $this->sendError('', $validator->errors()->first());
    }

    DB::beginTransaction();
    try{

      $wishlist = WishReturn::find($request->wish_return_id);
      $wishlist->wish_return_qty = $request->quantity;
      $wishlist->description = $request->description;
      
      
      if($wishlist->save()){
        DB::commit();
        return $this->sendResponse('', trans('wish_return.item_updated'));
      }else{
        DB::rollback();
        return $this->sendError('',trans('wish_return.item_update_error'));
      }
    }catch(\Exception $e) {
      DB::rollback();
      return $this->sendError('',trans('common.api_error'));
    }
  }

  /**
    * Wish Return: Remove From Wish Return List
    * @authenticated
    * 
    * @bodyParam wish_return_id number required Example:1
    *
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "TThe wish return item is deleted.",
    }
  */
  public function remove_from_wishlist(Request $request)
  { 
    $user = Auth::guard('api')->user();

    $validator=  Validator::make($request->all(),[
      'wish_return_id' => 'required|exists:wish_return,id,user_id,'.$user->id,
    ]);

    if($validator->fails()) {
      return $this->sendError('', $validator->errors()->first());
    }

    DB::beginTransaction();
    try{

      $wishlist = WishReturn::find($request->wish_return_id);

      if($wishlist->delete()){
        DB::commit();
        return $this->sendResponse('', trans('wish_return.wish_return_deleted'));
      }else{
        DB::rollback();
        return $this->sendError('',trans('wish_return.wish_return_delete_error'));
      }
    }catch(\Exception $e) {
      DB::rollback();
      return $this->sendError('',trans('common.api_error'));
    }
  }

  /**
    * Wish Return: Wish Return Details
    * @authenticated
    * 
    * @bodyParam wish_return_id number required Example:1
    *
    * @responseFile scenario='success' storage/responses/wish_return_details.json
    * @responseFile scenario='error' storage/responses/error.json
  */
  public function wish_return_details(Request $request)
  { 
    $user = Auth::guard('api')->user();

    $validator=  Validator::make($request->all(),[
      'wish_return_id' => 'required|exists:wish_return,id,user_id,'.$user->id,
    ]);

    if($validator->fails()) {
      return $this->sendError($this->object, $validator->errors()->first());
    }

    DB::beginTransaction();
    try{

      $wishlist = WishReturn::find($request->wish_return_id);

      if($wishlist){
        DB::commit();
        return $this->sendResponse(new MyWishReturnListResource($wishlist), trans('wish_return.wish_list_found'));
      }else{
        DB::rollback();
        return $this->sendError($this->object,trans('wish_return.wish_list_not_found'));
      }
    }catch(\Exception $e) {
      DB::rollback();
      return $this->sendError($this->object,trans('common.api_error'));
    }
  }

  /**
    * Wish Return: Dealer's Retailer Wish Return Request
    * @authenticated
    * @bodyParam search string optional Search Query. Example:lorem
    * @responseFile scenario='success' storage/responses/dealer_wish_request.json
    * @responseFile scenario='error' storage/responses/error_array.json
  */
  public function dealer_wish_return_requests(Request $request) {

    $validator = Validator::make($request->all(), [
     
      'search' => 'sometimes|nullable|max:200',

    ]);

    if($validator->fails()){
      return $this->sendError($this->array, $validator->errors()->first());       
    }

    DB::beginTransaction();
    try{

      $user   = Auth::guard('api')->user();
      
      if($user->user_type != 'dealer'){
        return $this->sendError($this->array, trans('wish_list.only_for_dealer'));
      }
    
      $data = WishReturn::where('dealer_id',$user->id);
      if(isset($request->search) && $request->search != null){
        $search = $request->search;
        $data = $data->whereHas('product',function($q) use($search){
                        $q->where('name_english','like','%'.$search.'%')
                        ->orWhere('name_hindi','like','%'.$search.'%');
                    })->OrWhereHas('user',function($q) use($search){
                      $q->where('company_name','like','%'.$search.'%');
                    })->orWhere('wish_return_qty','like','%'.$search.'%');        
      }
       $data = $data->orderBy('created_at','desc')->paginate();
         
      if($data->count() > 0){
        
        $response  = DealerWishReturnRequestResource::Collection($data);

        return $this->sendPaginateResponse($response, trans('wish_return.wish_list_found'));

      }else{

        return $this->sendResponse($this->array,trans('wish_return.wish_list_not_found'));
      }
    }catch(\Exception $e){
     
      return $this->sendError($this->array,trans('common.api_error'));
    }
  }

  /**
    * Wish Return: Dealer's Retailer Wish Return Request Details
    * @authenticated
    * 
    * @bodyParam wish_return_id number required Example:1
    *
    * @responseFile scenario='success' storage/responses/dealer_wish_request_details.json
    * @responseFile scenario='error' storage/responses/error.json
  */
  public function dealer_wishlist_request_details(Request $request)
  { 
    $user = Auth::guard('api')->user();

    $validator=  Validator::make($request->all(),[
      'wish_return_id' => 'required|exists:wish_return,id,dealer_id,'.$user->id,
    ]);

    if($validator->fails()) {
      return $this->sendError($this->object, $validator->errors()->first());
    }

    DB::beginTransaction();
    try{

      $wishlist = WishReturn::find($request->wish_return_id);

      if($wishlist){
        DB::commit();
        return $this->sendResponse(new DealerWishReturnRequestDetailsResource($wishlist), trans('wish_return.wish_list_found'));
      }else{
        DB::rollback();
        return $this->sendError($this->object,trans('wish_return.wish_list_not_found'));
      }
    }catch(\Exception $e) {
      DB::rollback();
      return $this->sendError($this->object,trans('common.api_error'));
    }
  }

}
