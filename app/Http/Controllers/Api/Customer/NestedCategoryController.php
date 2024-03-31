<?php

namespace App\Http\Controllers\Api\Customer;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\QuestionCategory;
use App\Models\BusinessCategory;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderItem;
use App\Http\Resources\Customer\NestedCategoryResource;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use App\Models\Helpers\CommonHelper;
use DB;
use Carbon\Carbon;
use Validator;
use App\Notifications\CustomerTicket;
use Notification;

/**
* @group Customer Endpoints
*
* Customer Apis
*/

class NestedCategoryController extends BaseController
{ 
  use CommonHelper;

  /**
    * Nested Categories: List
    *
    * @authenticated 
    * 
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "Data Found Successfully",
      "data": [
            {
                "category_id": "1",
                "category_name": "Master 1",
                "sub_cat": [
                    {
                        "category_id": "2",
                        "category_name": "L2 11",
                        "sub_cat": [
                            {
                                "category_id": "4",
                                "category_name": "L3 111",
                                "sub_cat": [
                                    {
                                        "category_id": "5",
                                        "category_name": "L4 1111",
                                        "sub_cat": [
                                            {
                                                "category_id": "7",
                                                "category_name": "L5 11111",
                                                "sub_cat": [
                                                    {
                                                        "category_id": "8",
                                                        "category_name": "L6 111111",
                                                        "sub_cat": [
                                                            {
                                                                "category_id": "9",
                                                                "category_name": "L7 1111111",
                                                                "sub_cat": []
                                                            }
                                                        ]
                                                    }
                                                ]
                                            }
                                        ]
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        "category_id": "3",
                        "category_name": "L2 12",
                        "sub_cat": []
                    },
                    {
                        "category_id": "33",
                        "category_name": "NewCategory",
                        "sub_cat": []
                    },
                    {
                        "category_id": "34",
                        "category_name": "Abc",
                        "sub_cat": []
                    }
                ]
            },
            {
                "category_id": "6",
                "category_name": "Master 2",
                "sub_cat": []
            }
        ]
    }
  */
  public function nested_categories($business_category_id = ""){
      $layout = '';
      $lang = 'hindi';
      if(isset($_GET['lang']) && $_GET['lang'] != ''){
              $lang = $_GET['lang'];
      }
      if($business_category_id != "") {
          $business_category = BusinessCategory::where(['id'=>$business_category_id, 'is_live'=>'1', 'status' => 'active'])->first();
          if(!$business_category) {
            return $this->sendError('',trans('common.no_data'));
          }
          
          if(!$business_category) {
            return $this->sendError('',trans('common.no_data'));
          }

          $related_categories = $this->related_categories($business_category->layout,$lang);

          $layout = $business_category->layout;
          $root_categories = [];
          foreach ($related_categories as $rcat) {
            $is_product_exist = $this->isProductExist($business_category_id,$rcat,$layout,$lang);
            if($is_product_exist > 0){
              $root = $this->get_root_category($rcat);
              if($root){
                if(!in_array($root, $root_categories)) {
                  array_push($root_categories, $root);
                }
              }
            }
          }
          $root_categories = array_filter($root_categories);
          $categories = Category::where('is_live','1')->whereIn('id',$root_categories)->where('status','active')->whereNull('parent_id')->orderBy('id','asc')->get();
      } else {
        $categories = \App\Models\ProductCategory::whereHas('product', function($q) use ($lang) {
                                                    $q->where('language',[strtolower($lang),'both'])
                                                      ->where('status','active')
                                                      ->where('is_live','1'); 
                                                })->pluck('category_id');
          $categories = Category::whereIn('id',$categories)->where('is_live','1')->where('status','active')->whereNull('parent_id')->orderBy('id','asc')->get();
          $exist_categories = [];
          foreach ($categories as $cat) {
            $is_product_exist = $this->isProductExist('',$cat['id'],'',$lang);
            if($is_product_exist > 0){
                  $exist_categories[] = $cat['id'];
                }
          }
          $categories =  Category::where('is_live','1')->whereIn('id',$exist_categories)->where('status','active')->whereNull('parent_id')->orderBy('id','asc')->get();
            
      }
     //  echo "<pre>";print_r($categories);exit;
      $cat_response = array();
      if(count($categories)) {

          foreach ($categories as $kl1 => $cat_lv1) 
          {
              $cat_response[$kl1]['category_id'] = (string)$cat_lv1->id;
              $cat_response[$kl1]['category_name'] = $cat_lv1->category_name;
              $cat_response[$kl1]["sub_cat"] = array();

              if(count($cat_lv1->childs_active()))
              {
                foreach ($cat_lv1->childs_active() as $kl2 => $cat_lv2) {
                    $cat_response[$kl1]["sub_cat"][$kl2]['category_id'] = (string)$cat_lv2->id;
                    $cat_response[$kl1]["sub_cat"][$kl2]['category_name'] = $cat_lv2->category_name;
                    $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'] = array();

                    if(count($cat_lv2->childs_active()))
                    {
                      foreach ($cat_lv2->childs_active() as $kl3 => $cat_lv3) {
                        $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['category_id'] = (string)$cat_lv3->id;
                        $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['category_name'] = $cat_lv3->category_name;
                        $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'] = array();

                        if(count($cat_lv3->childs_active()))
                        {
                          foreach ($cat_lv3->childs_active() as $kl4 => $cat_lv4) {
                            $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'][$kl4]['category_id'] = (string)$cat_lv4->id;
                            $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'][$kl4]['category_name'] = $cat_lv4->category_name;
                            $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'][$kl4]['sub_cat'] = array();

                            if(count($cat_lv4->childs_active()))
                            {
                              foreach ($cat_lv4->childs_active() as $kl5 => $cat_lv5) {
                                $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'][$kl4]['sub_cat'][$kl5]['category_id'] = (string)$cat_lv5->id;
                                $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'][$kl4]['sub_cat'][$kl5]['category_name'] = $cat_lv5->category_name;
                                $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'][$kl4]['sub_cat'][$kl5]['sub_cat'] = array();

                                if(count($cat_lv5->childs_active()))
                                {
                                  foreach ($cat_lv5->childs_active() as $kl6 => $cat_lv6) {
                                    $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'][$kl4]['sub_cat'][$kl5]['sub_cat'][$kl6]['category_id'] = (string)$cat_lv6->id;
                                    $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'][$kl4]['sub_cat'][$kl5]['sub_cat'][$kl6]['category_name'] = $cat_lv6->category_name;
                                    $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'][$kl4]['sub_cat'][$kl5]['sub_cat'][$kl6]['sub_cat'] = array();

                                    if(count($cat_lv6->childs_active()))
                                    {
                                      foreach ($cat_lv6->childs_active() as $kl7 => $cat_lv7) {
                                        $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'][$kl4]['sub_cat'][$kl5]['sub_cat'][$kl6]['sub_cat'][$kl7]['category_id'] = (string)$cat_lv7->id;
                                        $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'][$kl4]['sub_cat'][$kl5]['sub_cat'][$kl6]['sub_cat'][$kl7]['category_name'] = $cat_lv7->category_name;
                                        $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'][$kl4]['sub_cat'][$kl5]['sub_cat'][$kl6]['sub_cat'][$kl7]['sub_cat'] = array();
                                      }
                                    }
                                  }
                                }
                              }
                            }
                          }
                        }
                      }
                    }
                }
              }
          }
          return $this->sendResponse($cat_response,trans('common.data_found'));
      }else {
        return $this->sendPaginateResponse('',trans('common.no_data')); 
      }
      
  }

  public function nested_categories_return(){
      $language = 'hindi';
      if(isset($_GET['lang']) && $_GET['lang'] != ''){
              $language = $_GET['lang'];
      }
     $user  = Auth::guard('api')->user();
     $now         = Carbon::now();
     $all_category_ids = $this->get_all_child_categories();
     $itemIds = OrderItem::where('is_returned','0')
                      ->whereIn('language',['both',$language])
                      ->whereHas('order',function($que) use($user){
                          $que->where(['user_id'=> $user->id,'order_status'=>'completed']);
                        })->whereHas('product',function($q) use($now,$all_category_ids){
                          $q->whereNull('last_returnable_date')
                            ->orWhere('last_returnable_date','>=',$now);
                            // ->whereIn('language',['both',$request->language]);
                              $q->whereHas('categories',function($q1) use($all_category_ids) {
                              //  $q1->whereIn('category_id',$all_category_ids);
                              });
                        })->pluck('product_id');
         $categories = \App\Models\ProductCategory::whereIn('product_id',$itemIds)->pluck('category_id');
         $categories = Category::whereIn('id',$categories)->where('is_live','1')->where('status','active')->whereNull('parent_id')->orderBy('id','asc')->get();
          $exist_categories = [];
          foreach ($categories as $cat) {
           $exist_categories[] = $cat['id'];
          
          }
          $categories =  Category::where('is_live','1')->whereIn('id',$exist_categories)->where('status','active')->whereNull('parent_id')->orderBy('id','asc')->get();
            
     //  echo "<pre>";print_r($categories);exit;
      $cat_response = array();
      if(count($categories)) {

          foreach ($categories as $kl1 => $cat_lv1) 
          {
              $cat_response[$kl1]['category_id'] = (string)$cat_lv1->id;
              $cat_response[$kl1]['category_name'] = $cat_lv1->category_name;
              $cat_response[$kl1]["sub_cat"] = array();

              if(count($cat_lv1->childs_active()))
              {
                foreach ($cat_lv1->childs_active() as $kl2 => $cat_lv2) {
                    $cat_response[$kl1]["sub_cat"][$kl2]['category_id'] = (string)$cat_lv2->id;
                    $cat_response[$kl1]["sub_cat"][$kl2]['category_name'] = $cat_lv2->category_name;
                    $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'] = array();

                    if(count($cat_lv2->childs_active()))
                    {
                      foreach ($cat_lv2->childs_active() as $kl3 => $cat_lv3) {
                        $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['category_id'] = (string)$cat_lv3->id;
                        $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['category_name'] = $cat_lv3->category_name;
                        $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'] = array();

                        if(count($cat_lv3->childs_active()))
                        {
                          foreach ($cat_lv3->childs_active() as $kl4 => $cat_lv4) {
                            $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'][$kl4]['category_id'] = (string)$cat_lv4->id;
                            $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'][$kl4]['category_name'] = $cat_lv4->category_name;
                            $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'][$kl4]['sub_cat'] = array();

                            if(count($cat_lv4->childs_active()))
                            {
                              foreach ($cat_lv4->childs_active() as $kl5 => $cat_lv5) {
                                $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'][$kl4]['sub_cat'][$kl5]['category_id'] = (string)$cat_lv5->id;
                                $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'][$kl4]['sub_cat'][$kl5]['category_name'] = $cat_lv5->category_name;
                                $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'][$kl4]['sub_cat'][$kl5]['sub_cat'] = array();

                                if(count($cat_lv5->childs_active()))
                                {
                                  foreach ($cat_lv5->childs_active() as $kl6 => $cat_lv6) {
                                    $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'][$kl4]['sub_cat'][$kl5]['sub_cat'][$kl6]['category_id'] = (string)$cat_lv6->id;
                                    $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'][$kl4]['sub_cat'][$kl5]['sub_cat'][$kl6]['category_name'] = $cat_lv6->category_name;
                                    $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'][$kl4]['sub_cat'][$kl5]['sub_cat'][$kl6]['sub_cat'] = array();

                                    if(count($cat_lv6->childs_active()))
                                    {
                                      foreach ($cat_lv6->childs_active() as $kl7 => $cat_lv7) {
                                        $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'][$kl4]['sub_cat'][$kl5]['sub_cat'][$kl6]['sub_cat'][$kl7]['category_id'] = (string)$cat_lv7->id;
                                        $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'][$kl4]['sub_cat'][$kl5]['sub_cat'][$kl6]['sub_cat'][$kl7]['category_name'] = $cat_lv7->category_name;
                                        $cat_response[$kl1]["sub_cat"][$kl2]['sub_cat'][$kl3]['sub_cat'][$kl4]['sub_cat'][$kl5]['sub_cat'][$kl6]['sub_cat'][$kl7]['sub_cat'] = array();
                                      }
                                    }
                                  }
                                }
                              }
                            }
                          }
                        }
                      }
                    }
                }
              }
          }
          return $this->sendResponse($cat_response,trans('common.data_found'));
      }else {
        return $this->sendPaginateResponse('',trans('common.no_data')); 
      }
      
  }


  public function related_categories($layout,$lang) {

    switch ($layout) {
      case 'books':
        $categories = \App\Models\ProductCategory::whereHas('product', function($q) use ($lang) {
                                                    $q->whereIn('language',[strtolower($lang),'both'])
                                                      ->where('status','active')
                                                      ->where('is_live','1'); 
                                                })->groupBy('category_id')->pluck('category_id');
        break;
      case 'my_return':
        $categories = \App\Models\ProductCategory::whereHas('product', function($q) use ($lang) {
                                                    $q->where('language',[strtolower($lang),'both'])
                                                      ->where('status','active')
                                                      ->where('is_live','1'); 
                                                })->groupBy('category_id')->pluck('category_id');
        break;
      case 'digital_coupons':
        $categories = \App\Models\SubCouponCategory::groupBy('category_id')->pluck('category_id');
        break;
      default:
        $categories = \App\Models\ProductCategory::whereHas('product', function($q) use ($lang) {
                                                    $q->where('language',[strtolower($lang),'both'])
                                                      ->where('status','active')
                                                      ->where('is_live','1'); 
                                                })->groupBy('category_id')->pluck('category_id');

        break;
    }
    return $categories;
  }
  

}




