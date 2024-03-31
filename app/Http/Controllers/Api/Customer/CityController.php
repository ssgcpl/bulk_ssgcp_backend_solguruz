<?php

namespace App\Http\Controllers\Api\Customer;
use App\Http\Controllers\Api\BaseController;
use App\Models\City;
use App\Models\PostCode;
use App\Http\Resources\Customer\CityResource;
use App\Http\Resources\Customer\PostcodeResource;
use Illuminate\Http\Request;
use DB,Validator,Auth;

/**
 * @group Customer Endpoints
 *
 * Customer Apis
 */
class CityController extends BaseController
{
    /**
    * Master: Cities
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

    public function index($state_id = '') {
      if(!$state_id || $state_id == ''){
        return $this->sendError('',trans('cities.country_required'));
      }
    	$cities = City::where('state_id',$state_id)->where('status','active')->get();
    	$cities_data = CityResource::collection($cities);
      if($cities_data){
        if(count($cities_data) > 0) {
          return $this->sendResponse($cities_data,trans('cities.cities_found'));
        } else {
          return $this->sendResponse($cities_data,trans('cities.cities_not_found')); 
        }
      }else{
        return $this->sendError('',trans('common.something_went_wrong')); 
      }
    }


    /**
    * Master: Postcodes
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
    public function postcodes($city_id = '') {
      if(!$city_id || $city_id == ''){
        return $this->sendError('',trans('cities.country_required'));
      }
      $postcodes = PostCode::where('city_id',$city_id)->get();
      $postcodes_data = PostcodeResource::collection($postcodes);
      if($postcodes_data){
        if(count($postcodes_data) > 0) {
          return $this->sendResponse($postcodes_data,trans('cities.postcodes_found'));
        } else {
          return $this->sendResponse($postcodes_data,trans('cities.postcodes_not_found')); 
        }
      }else{
        return $this->sendError('',trans('common.something_went_wrong')); 
      }
    }
}
