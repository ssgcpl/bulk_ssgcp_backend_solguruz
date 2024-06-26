<?php

namespace App\Http\Controllers\Api\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\BusinessCategory;
use App\Models\RelatedProduct;
use App\Http\Resources\Customer\BookResource;
use App\Http\Resources\Customer\BookDetailResource;
use Validator;
use Auth,DB;
use App\Models\Helpers\CommonHelper;

/**
* @group Customer Endpoints
*
* Customer Apis
*/
class ProductController extends BaseController
{
  use CommonHelper;
 
  /**
  * Books: Book list
  *
  * @bodyParam business_category_id numeric required from id from business_categories API Example:1
  * @bodyParam language string required Language (hindi,english). Example:hindi
  * @bodyParam category_id string optional Category_id (category_id or subcategory_id of any level). Example:3
  * @bodyParam user_id numeric optional User id if logged in API Example:1
  * @responseFile scenario='success' storage/responses/book_list.json
  * @responseFile scenario='error' storage/responses/error_array.json
  */
  public function books_list(Request $request) {

      $validator = Validator::make($request->all(), [
        'business_category_id'    => 'required|exists:business_categories,id,is_live,1,status,active',
        'category_id'  => 'nullable|exists:categories,id,is_live,1',
        'language' => 'required|in:english,hindi,all',
        'user_id' => 'sometimes|nullable|exists:users,id',
        'current_user_type' => 'nullable|in:retailer,dealer,both',
      ]);
      if($validator->fails()){
        return $this->sendError($this->array, $validator->errors()->first());       
      }

      DB::beginTransaction();
      try{

        $data = Product::where(['business_category_id'=>$request->business_category_id,'is_live' => '1','status' => 'active','stock_status' => 'in_stock'])
          ->whereIn('language',['both',$request->language]);
          if($request->language == 'all'){
            $data = Product::where(['business_category_id'=>$request->business_category_id,'is_live' => '1','status' => 'active','stock_status' => 'in_stock'])
            ->whereIn('language',['both','hindi','english']);
          }
         
        //Check if user is logged in and show data according to the account type
        /*if(isset($request->user_id) && $request->user_id != null){
          $user = User::find($request->user_id);
          $data = $data->whereIn('visible_to',['both',$user->user_type]);
        }else {
          $data = $data->whereIn('visible_to',['both','retailer']);
        }*/
        $user = User::find($request->user_id);

        if(isset($request->current_user_type) && $request->current_user_type != null ){
          if($request->current_user_type == 'both'){
            $data = $data->whereIn('visible_to',['retailer','dealer','both']);
          }else{
            $data = $data->whereIn('visible_to',[$request->current_user_type,'both']);
          }
        }
        
        // check if category is active/publish
        $data = $data->whereHas('categories',function($q) {
                      $q->whereHas('category',function($q1){
                        $q1->where('is_live','1')
                           ->where('status','active')
                           ->whereNull('parent_id');
                      });
                  });

        if(isset($request->category_id) && $request->category_id != '')
        {
          $all_category_ids = $this->get_all_child_categories($request->category_id);
          $data = $data->whereHas('categories',function($q) use($all_category_ids){
                    $q->whereIn('category_id',$all_category_ids);
                    /*$q->whereHas('category',function($q1){
                        $q1->where('is_live','1')
                           ->where('status','active');
                    });*/
                    //->where('is_live','1')
                    //->where('status','active');
                  });
        }
       

        $data = $data->orderBy('republished_at','desc')->paginate();
        if($data->count() > 0){
          
            foreach($data as $d){
              $d->lang = $request->language;
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
          

          $response       = BookResource::Collection($data);

          return $this->sendPaginateResponse($response, trans('products.book_list_success'));

        }else{

          return $this->sendResponse($this->array,trans('products.book_list_empty'));
        }
      }catch(\Exception $e){
       
        return $this->sendError($this->array,trans('common.api_error'));
      }
  }


  /**
  * Books: Book Details
  *
  * @authenticated
  *
  * @bodyParam book_id string required Book Id. Example:1
  * @bodyParam user_id numeric optional User id if logged in API Example:1
  * @bodyParam language string required Language (hindi,english). Example:hindi
  *
  * @responseFile scenario='success' storage/responses/book_details.json
  * @responseFile scenario='error' storage/responses/error.json
  */
  public function book_details(Request $request) {

      DB::beginTransaction();
       try{

        $validator = Validator::make($request->all(), [
          'book_id'  => 'required|exists:products,id,is_live,1,status,active',
          'user_id'  => 'sometimes|nullable|exists:users,id',
          'language' => 'nullable|in:english,hindi,all'
        ]);

        if($validator->fails()){
          return $this->sendError($this->object, $validator->errors()->first());       
        }

        $language = $request->language;

        $book = Product::where('id',$request->book_id);
        // if(isset($request->user_id) && $request->user_id != null){
        //   $user = User::find($request->user_id);
        //   $book = $book->whereIn('visible_to',['both',$user->user_type]);
        // }else {
        //   $book = $book->whereIn('visible_to',['both','retailer']);
        // }

        // check if category of product is active /publish
        $book = $book->whereHas('categories',function($q) {
                  $q->whereHas('category',function($q1){
                    $q1->where('is_live','1')
                      ->where('status','active');
                  });
                });
        
        $book = $book->first();
        if(!$book)
        {
          return $this->sendError($this->object,'Book Not Found');
        }
        
        if(!$language)
        {
          if($book->language == 'both')
          {
            //set default lang as hindi if book available in both languages
            $language = 'hindi';
          }
          else
          {
            $language = $book->language;
          }
        }
        
        //Check if user is logged in and show data according to the account type
        $book->price = $book->retailer_sale_price;
        if(isset($request->user_id) && $request->user_id != null){
          $user = User::find($request->user_id);
          $book->price = $book->get_price($user);
          $book->lang = $language;
          $cart = $this->itemAddedToCartChecker('order',$book->id,$user);
          if($cart){
            $book->added_to_cart = '1';
            $book->quantity = $cart->supplied_quantity;
            $book->cart_item_id = $cart->id;
          }
        }

        //Related books data
        $rel = array();

        if(count($book->related_products_data) > 0){
          $related_prod_ids = RelatedProduct::where('product_id',$book->id)->get()->pluck('related_product_id')->toArray();
          $related_products = Product::withCount('order_items')
            ->whereIn('id',$related_prod_ids)
            ->where('status','active')
            ->where('is_live','1')
            ->where('stock_status','in_stock')
            ->whereIn('language',['both',$language])
            ->whereHas('order_items',function($q){
              $q->whereHas('order',function($que){
                $que->whereIn('order_status',['on_hold','processing','shipped','completed']);
              });
            });
            if(isset($request->user_id) && $request->user_id != null){
                $user = User::find($request->user_id);
                $related_products = $related_products->whereIn('visible_to',['both',$user->user_type]);
              }else {
                $related_products = $related_products->whereIn('visible_to',['both','retailer']);
              }
              $related_products = $related_products->orderBy('order_items_count', 'desc')->get()->take(6);
          foreach ($related_products as $product) {
            $product->price = $product->retailer_sale_price;
            if(isset($user)){
              $product->price = $product->get_price($user);
            }
            $rel[] = $product;
          }
        }
        else
        {
          //get same category books, if related books data not found 
          $same_pro_cat_ids = ProductCategory::where('product_id',$request->book_id)->orderBy('id','desc')->get()->pluck('category_id');
          $product_ids = ProductCategory::whereIn('category_id',$same_pro_cat_ids)->groupBy('product_id')->orderBy('id','desc')->get()->pluck('product_id')->toArray();

          $related_products_data = Product::withCount('order_items')
            ->whereIn('id',$product_ids)
            ->where('id','!=',$request->book_id)
            ->where(['is_live'=>'1','status'=>'active'])
            ->where('stock_status','in_stock')
            ->whereIn('language',['both',$language])
            ->whereHas('order_items',function($q){
              $q->whereHas('order',function($que){
                $que->whereIn('order_status',['on_hold','processing','shipped','completed']);
              });
            });
              if(isset($request->user_id) && $request->user_id != null){
                $user = User::find($request->user_id);
                $related_products_data = $related_products_data->whereIn('visible_to',['both',$user->user_type]);
              }else {
                $related_products_data = $related_products_data->whereIn('visible_to',['both','retailer']);
              }
              $related_products_data = $related_products_data->orderBy('order_items_count', 'desc')->get()->take(6);
          foreach ($related_products_data as $product) {
            $product->price = $product->retailer_sale_price;
            if(isset($user)){
              $product->price = $product->get_price($user);
            }
            $rel[] = $product;
          }
        }

        $book->rel = $rel;
        
        return $this->sendResponse(new BookDetailResource($book),trans('products.book_details'));

      }catch(\Exception $e){
        return $this->sendError($this->object,trans('common.api_error'));
      } 
  }



}
