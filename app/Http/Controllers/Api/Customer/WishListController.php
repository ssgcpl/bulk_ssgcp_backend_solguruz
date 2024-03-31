<?php

namespace App\Http\Controllers\Api\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use App\Models\Product;
use App\Models\WishList;
use App\Models\WishListDealer;
use App\Models\DealerRetailer;
use App\Models\Setting;
use App\Models\ReferHistory;
use App\Http\Resources\Customer\OutOfOrderWishlistResource;
use App\Http\Resources\Customer\RetailerWishlistResource;
use App\Http\Resources\Customer\RetailerDealerlistResource;
use App\Http\Resources\Customer\WishlistDetailsResource;
use App\Http\Resources\Customer\DealerWishlistRequestResource;
use App\Http\Resources\Customer\DealerWishlistRequestDetailsResource;
use Validator;
use Auth,DB;
use Carbon\Carbon;
use App\Models\Helpers\CommonHelper;

/**
* @group Customer Endpoints
*
* Customer Apis
*/
class WishListController extends BaseController
{
  use CommonHelper;
 
  /**
    * WishList: Out Of Stock Wish List
    * @authenticated
    *
    * @responseFile scenario='success' storage/responses/out_of_order_wishlist.json
    * @responseFile scenario='error' storage/responses/error_array.json
  */
  public function out_of_stock_wishlist(Request $request) {

      DB::beginTransaction();
      try{

        $user = Auth::guard('api')->user();
      
        $data = Product::where(['is_live' => '1','status' => 'active','stock_status' => 'out_of_stock'])->whereIn('visible_to',['both',$user->user_type]);
                // ->whereHas('wishlist',function($q)use($user){
                //   $q->where('user_id','!=',$user->id);
                // })
        // check if category is active/publish
        $data = $data->whereHas('categories',function($q) {
                      $q->whereHas('category',function($q1){
                        $q1->where('is_live','1')
                           ->where('status','active')
                           ->whereNull('parent_id');
                      });
                  });
        $data = $data->orderBy('created_at','desc')->paginate();
           
        if($data->count() > 0){
          
          foreach($data as $d){
            $wish = WishList::where(['product_id'=>$d->id,'user_id'=>$user->id])->first();
            if($wish){
              $d->in_wishlist = '1';
              $d->quantity = $wish->wish_product_qty;
              $d->wish_list_id = $wish->id;
            }
          }
          
          $response  = OutOfOrderWishlistResource::Collection($data);

          return $this->sendPaginateResponse($response, trans('wish_list.wish_list_found'));

        }else{

          return $this->sendResponse($this->array,trans('wish_list.wish_list_not_found'));
        }
      }catch(\Exception $e){
       
        return $this->sendError($this->array,trans('common.api_error'));
      }
  }

  /**
    * WishList: Wish List(My Wishlist,Available)
    * @authenticated
    * @bodyParam type string required Wishlist type enum:my_wishlist,available . Example:my_wishlist
    * @responseFile scenario='success' storage/responses/retailer_wishlist.json
    * @responseFile scenario='error' storage/responses/error_array.json
  */
  public function retailer_wishlist(Request $request) {

    $validator = Validator::make($request->all(), [
     
      'type' => 'required|in:my_wishlist,available',

    ]);

    if($validator->fails()){
      return $this->sendError($this->array, $validator->errors()->first());       
    }

    DB::beginTransaction();
    try{

      $user   = Auth::guard('api')->user();
      $type   = $request->type;
    
      $data = WishList::where('user_id',$user->id);

      if($type == 'my_wishlist'){
        $data = $data->whereHas('product',function($q){
                    $q->where('stock_status','out_of_stock');
                });
      }else{
        $data = $data->whereHas('product',function($q){
                    $q->where('stock_status','in_stock');
                });
      }
              
      $data = $data->orderBy('created_at','desc')->paginate();
         
      if($data->count() > 0){
        
        $response  = RetailerWishlistResource::Collection($data);

        return $this->sendPaginateResponse($response, trans('wish_list.wish_list_found'));

      }else{

        return $this->sendResponse($this->array,trans('wish_list.wish_list_not_found'));
      }
    }catch(\Exception $e){
     
      return $this->sendError($this->array,trans('common.api_error'));
    }
  }

  /**
    * WishList: User's Dealer List
    * @authenticated
    *
    * @responseFile scenario='success' storage/responses/retailer_dealer_list.json
    * @responseFile scenario='error' storage/responses/error_array.json
  */
  public function retailer_dealer_list(Request $request) {


    DB::beginTransaction();
    try{

      $user   = Auth::guard('api')->user();
      $type   = $request->type;
    
      $data   = DealerRetailer::where('retailer_id',$user->id)
                ->whereHas('dealer',function($q){
                    $q->where(['status'=>'active', 'user_type' => 'dealer', 'verified' => '1']);
                })
                ->orderBy('created_at','desc')->paginate();

      if($data->count() > 0){
        
        $response  = RetailerDealerlistResource::Collection($data);

        return $this->sendPaginateResponse($response, trans('wish_list.dealers_found'));

      }else{

        return $this->sendResponse($this->array,trans('wish_list.dealers_not_found'));
      }
    }catch(\Exception $e){
     
      return $this->sendError($this->array,trans('common.api_error'));
    }
  }

  /**
    * WishList: Add to Wish List
    * @authenticated
    * @bodyParam book_id string required Book Id . Example:1
    * @bodyParam dealers[] array optional from WishList: User's Dealer List. Example:[3,4]
    * @bodyParam quantity string required Quantity. Example:3
    *
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "The item is added into the wishlist.",
    }
  */
  public function add_to_wishlist(Request $request) {

      $validator = Validator::make($request->all(), [
       
        'book_id' => 'required|exists:products,id,is_live,1,stock_status,out_of_stock',
        'quantity'      => 'required|numeric|min:1',
        'dealers'   => 'sometimes|array',
        'dealers.*'     => 'sometimes|nullable|exists:users,id,status,active,user_type,dealer,verified,1',
    
      ]);

      if($validator->fails()){
        return $this->sendError('', $validator->errors()->first());       
      }

      DB::beginTransaction();
      try{

        $user = Auth::guard('api')->user();

        if($user->user_type == 'retailer'){

          $dealers = DealerRetailer::where('retailer_id',$user->id)
                ->whereHas('dealer',function($q){
                    $q->where(['status'=>'active', 'user_type' => 'dealer', 'verified' => '1']);
                })->count();

          if($dealers > 0){
            if(!isset($request->dealers)||count($request->dealers) < 0 ){
              return $this->sendError('', trans('wish_list.dealer_required'));
            }

            $valid_dealer = DealerRetailer::where('retailer_id',$user->id)
              ->whereIn('dealer_id',$request->dealers)->count();

            if($valid_dealer != count($request->dealers)){
              return $this->sendError('', trans('wish_list.invalid_dealer'));
            }
          }
        }


        $exist = WishList::where(['user_id' => $user->id,'product_id'=>$request->book_id])->first();

        if($exist){
          $last_date = $exist->created_at;
          $now = Carbon::now();
          $diff =$last_date->diffInDays($now);
          $limit  = Setting::get('wishlist_days_limit');

          if($diff < $limit){
            $days = $limit - $diff;
            return $this->sendError('', trans('wish_list.new_wish_limit',['days'=>$days]));
          } 
        }

        // check wish list count is passed book id is unique
        $wishlist_count = WishList::where('user_id', $user->id)->where('product_id', $request->book_id)->withTrashed()->get()->count();

        $data     = $request->all();
        $data['wish_product_qty'] = $data['quantity'];
        $data['user_id'] = $user->id;
        $data['user_type'] = $user->user_type;
        $data['product_id'] = $data['book_id'];
        $wishlist = new WishList();
        $wishlist->fill($data);

        if($wishlist->save()){

          if(isset($request->dealers) && count($request->dealers) > 0){
            foreach ($request->dealers as $dealer) {
              $wish_dealer = new WishListDealer();
              $wish_dealer->wish_list_id = $wishlist->id;
              $wish_dealer->dealer_id = $dealer;
              $wish_dealer->user_id = $user->id;
              $wish_dealer->save();
            }
          }
          // check if points not added already 
          if($wishlist_count == 0)
          {
              $this->maintainReferralHistory((integer)Setting::get('wishlist_points'), 'added','',$user,'',null,null,$wishlist->id);
          }

          DB::commit();
          return $this->sendResponse('', trans('wish_list.item_added_to_wishlist'));

        }else{

          DB::rollback();
          return $this->sendError('', trans('wish_list.add_item_error'));
        }

      }catch(\Exception $e){
        DB::rollback();
        return $this->sendError('',trans('common.api_error'));
      }
  }

  /**
    * WishList: Update Quantity
    * @authenticated
    * 
    * @bodyParam wish_list_id number required Example:1
    * @bodyParam quantity number required Example:2
    *
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "The item quantity is updated.",
    }
  */
  public function update_quantity(Request $request)
  { 
    $user = Auth::guard('api')->user();

    $validator=  Validator::make($request->all(),[
      'wish_list_id' => 'required|exists:wish_list,id,user_id,'.$user->id,
      'quantity'     => 'required|numeric|min:1',
    ]);

    if($validator->fails()) {
      return $this->sendError('', $validator->errors()->first());
    }

    DB::beginTransaction();
    try{

      $wishlist = WishList::find($request->wish_list_id);

      if($wishlist->product != null && $wishlist->product->stock_status != 'out_of_stock'){
        return $this->sendError('', trans('wish_list.item_in_stock'));
      }

      $wishlist->wish_product_qty = $request->quantity;
        
      if($wishlist->save()){
        DB::commit();
        return $this->sendResponse('', trans('wish_list.quantity_updated'));
      }else{
        DB::rollback();
        return $this->sendError('',trans('wish_list.quantity_update_error'));
      }
    }catch(\Exception $e) {
      DB::rollback();
      return $this->sendError('',trans('common.api_error'));
    }
  }

  /**
    * WishList: Remove From Wishlist
    * @authenticated
    * 
    * @bodyParam wish_list_id number required Example:1
    *
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "The item is removed from the wishlist.",
    }
  */
  public function remove_from_wishlist(Request $request)
  { 
    $user = Auth::guard('api')->user();

    $validator=  Validator::make($request->all(),[
      'wish_list_id' => 'required|exists:wish_list,id,user_id,'.$user->id,
    ]);

    if($validator->fails()) {
      return $this->sendError('', $validator->errors()->first());
    }

    DB::beginTransaction();
    try{

      $wishlist = WishList::find($request->wish_list_id);

      if($wishlist->delete()){
        DB::commit();
        return $this->sendResponse('', trans('wish_list.wishlist_deleted'));
      }else{
        DB::rollback();
        return $this->sendError('',trans('wish_list.wishlist_delete_error'));
      }
    }catch(\Exception $e) {
      DB::rollback();
      return $this->sendError('',trans('common.api_error'));
    }
  }

  /**
    * WishList: WishList Details
    * @authenticated
    * 
    * @bodyParam wish_list_id number required Example:1
    *
    * @responseFile scenario='success' storage/responses/wishlist_details.json
    * @responseFile scenario='error' storage/responses/error.json
  */
  public function wishlist_details(Request $request)
  { 
    $user = Auth::guard('api')->user();

    $validator=  Validator::make($request->all(),[
      'wish_list_id' => 'required|exists:wish_list,id,user_id,'.$user->id,
    ]);

    if($validator->fails()) {
      return $this->sendError($this->object, $validator->errors()->first());
    }

    DB::beginTransaction();
    try{

      $wishlist = WishList::find($request->wish_list_id);

      if($wishlist){
        DB::commit();
        return $this->sendResponse(new WishlistDetailsResource($wishlist), trans('wish_list.wish_list_found'));
      }else{
        DB::rollback();
        return $this->sendError($this->object,trans('wish_list.wish_list_not_found'));
      }
    }catch(\Exception $e) {
      DB::rollback();
      return $this->sendError($this->object,trans('common.api_error'));
    }
  }

  /**
    * WishList: Dealer's Retailer Wishlist Request
    * @authenticated
    * @bodyParam search string optional Search Query. Example:lorem
    * @responseFile scenario='success' storage/responses/dealer_wish_request.json
    * @responseFile scenario='error' storage/responses/error_array.json
  */
  public function dealer_wishlist_requests(Request $request) {

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
    
      $data = WishListDealer::where('dealer_id',$user->id);

      if(isset($request->search) && $request->search != null){
        $search = $request->search;
        $data = $data->whereHas('wish_list',function($q) use($search){
                    $q->whereHas('product',function($qu) use($search){
                        $qu->where('name_english','like','%'.$search.'%')
                        ->orWhere('name_hindi','like','%'.$search.'%');
                    })->orWhereHas('user',function($que) use($search){
                      $que->where('company_name','like','%'.$search.'%');
                    })->orWhere('wish_product_qty','like','%'.$search.'%');
                })->where('dealer_id',$user->id);
      }
              
      $data = $data->orderBy('created_at','desc')->paginate();
         
      if($data->count() > 0){
        
        $response  = DealerWishlistRequestResource::Collection($data);

        return $this->sendPaginateResponse($response, trans('wish_list.wish_list_found'));

      }else{

        return $this->sendResponse($this->array,trans('wish_list.wish_list_not_found'));
      }
    }catch(\Exception $e){
     
      return $this->sendError($this->array,trans('common.api_error'));
    }
  }

  /**
    * WishList: Dealer's Retailer Wishlist Request Details
    * @authenticated
    * 
    * @bodyParam wish_list_id number required Example:1
    *
    * @responseFile scenario='success' storage/responses/dealer_wish_request_details.json
    * @responseFile scenario='error' storage/responses/error.json
  */
  public function dealer_wishlist_request_details(Request $request)
  { 
    $user = Auth::guard('api')->user();

    $validator=  Validator::make($request->all(),[
      'wish_list_request_id' => 'required|exists:wish_list_dealers,id,dealer_id,'.$user->id,
    ]);

    if($validator->fails()) {
      return $this->sendError($this->object, $validator->errors()->first());
    }

    DB::beginTransaction();
    try{

      $wishlist = WishListDealer::find($request->wish_list_request_id);

      if($wishlist){
        DB::commit();
        return $this->sendResponse(new DealerWishlistRequestDetailsResource($wishlist), trans('wish_list.wish_list_found'));
      }else{
        DB::rollback();
        return $this->sendError($this->object,trans('wish_list.wish_list_not_found'));
      }
    }catch(\Exception $e) {
      DB::rollback();
      return $this->sendError($this->object,trans('common.api_error'));
    }
  }

  

  

  

  

  

}
