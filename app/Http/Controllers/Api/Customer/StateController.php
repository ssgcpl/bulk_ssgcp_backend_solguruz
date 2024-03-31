<?php

namespace App\Http\Controllers\Api\Customer;
use App\Http\Controllers\Api\BaseController;
use App\Models\State;
use App\Models\Country;
use App\Http\Resources\Customer\StateResource;
use Illuminate\Http\Request;
use DB,Validator,Auth;

/**
 * @group Customer Endpoints
 *
 * Customer Apis
 */
class StateController extends BaseController
{
    
    /**
    * Master: States
    *
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "Country List Found",
        "data": [
            {
                "id": "1",
                "name": "India",
                "code": "+91",
                "flag": "http://localhost/ssgc-web/public/flags/india.png"
            }
        ]
    }
    */
    public function index($country_id = '') {
      $country_id = Country::whereIn('id',[1])->first()->id;
    	$states = State::where(['country_id' => $country_id, 'status' => 'active'])->whereHas('city')->get();
    	$states_data = StateResource::collection($states);
      if($states_data){
        if(count($states_data) > 0) {
          return $this->sendResponse($states_data,trans('states.states_found'));
        } else {
          return $this->sendResponse($states_data,trans('states.states_not_found')); 
        }
      }else{
        return $this->sendError('',trans('common.something_went_wrong')); 
      }
    }
}
