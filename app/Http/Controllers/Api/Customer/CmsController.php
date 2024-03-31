<?php

namespace App\Http\Controllers\Api\Customer;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Models\Cms;
use App\Models\Setting;
use Auth;

/**
 * @group Customer Endpoints
 *
 * Customer Apis
 */

class CmsController extends BaseController
{	

  /**
  * Hamburger Menu: CMS
  *
  * 
  * @response
  {
    "success": "1",
    "status": "200",
    "message": "CMS Pages",
    "data": {
        "terms_page_url": "http://localhost/ssgc-web/public/api/customer/terms_and_conditions",
        "about_us": "http://localhost/ssgc-web/public/api/customer/about_us",
        "privacy_policy": "http://localhost/ssgc-web/public/api/customer/privacy_policy"
    }
  }
  */
	public function cms_page(){  
    $response                      = array ();
    $response['terms_page_url']    = route('cms.customer_terms');
    $response['about_us']           = route('cms.about_us');
    $response['privacy_policy']     = route('cms.privacy_policy');
    
    if($response != null){
      return $this->sendResponse($response, trans('cms.cms_success'));
    } else {
      return $this->sendResponse($this->object,trans('cms.cms_error'));
    }
  }


  public function customer_terms_conditions_url(){
    $cms = Cms::where('slug','terms_conditions')->first();
    return view('admin.cms.view',compact('cms'));
  }

  public function privacy_policy_url(){
    $cms = Cms::where('slug','privacy_policy')->first();
    return view('admin.cms.view',compact('cms'));
  }

  public function about_us_url(){
    $cms = Cms::where('slug','about_us')->first();
    return view('admin.cms.view',compact('cms'));    
  }


  /**
  * Hamburger Menu: Refer & Earn
  * @authenticated
  * 
  * @response
  {
    "success": "1",
    "status": "200",
    "message": "Data found",
    "data": {
        "referaal_code": "12345678",
        "title": "You Will Get 1000 Points On Your First Referral",
        "content": "This is content for Refer and Earn, Lorem ipsum dollar sit amet. Lorem ipsum dollar sit amet. Lorem ipsum dollar sit amet. Lorem ipsum dollar sit amet. Lorem ipsum dollar sit amet. Lorem ipsum dollar sit amet. Lorem ipsum dollar sit amet. Lorem ipsum dollar sit amet. Lorem ipsum dollar sit amet."
    }
  }
  */
  public function refer_and_earn() {
    $user = Auth::guard('api')->user();
    $referral_code = ($user) ? $user->referral_code : "" ;
    $content = Cms::where('slug','refer_and_earn')->first();
    $response = [
            'referral_code' => $referral_code, 
            'title' => 'You Will Get '.Setting::get('refer_points').' Points For The Referral', 
            'content' => @$content->content, 
        ];
    return $this->sendResponse($response, trans('common.data_found'));
  }

}