<?php
namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\State;
use App\Models\City;
use App\Models\Country;
use App\Models\Address;
use App\Models\PostCode;
use Validator,DB;
use App\Http\Resources\Customer\AddressResource;
use App\Models\Helpers\CommonHelper;
use Authy\AuthyApi;

/**
 * @group Customer Endpoints
 *
 * Customer Apis
 */

class AddressController extends BaseController
{
	/**
    * Addresses : Address List
    *
    * @authenticated
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "Address List",
        "data": [
            {
                "id": "2",
                "contact_name": "test",
                "contact_number": "1111112222",
                "state_id": "1",
                "state_name": "Gujarat",
                "city_id": "1",
                "city_name": "Ahmedabad",
                "postcode_id": "1",
                "postcode": "380026",
                "area": "satellite",
                "house_no": "20",
                "street": "test",
                "landmark": "test",
                "address_type": "Home"
            }
        ]
    }
    */
    public function index(Request $request){
    	$addressList = Address::where('user_id',Auth::guard('api')->user()->id)->orderBy('id','desc')->get();
        return $this->sendResponse(AddressResource::collection($addressList), trans('addresses.address_list'));
    }

    /**
    * Addresses : Add Address
    *
    * @bodyParam contact_name string required Contact name. Example:test
    * @bodyParam company_name string required Company name. Example:test
    * @bodyParam contact_number string required Contact number. Example:1231231231
    * @bodyParam email string required   Email. Example:test@mail.com
    * @bodyParam state_id string State id. Example:Gujarat
    * @bodyParam state string
    * @bodyParam city_id string City id. Example:1
    * @bodyParam city string 
    * @bodyParam postcode_id string PostCode id. Example:1
    * @bodyParam postcode string
    * @bodyParam area string required Area. Example:test
    * @bodyParam house_no string required House / Street No. Example:11
    * @bodyParam street string required Street. Example:xxx
    * @bodyParam landmark string required Landmark. Example:xxx
    * @bodyParam address_type string required Address Type(Home,Office,Other). Example:Home
    * @bodyParam is_delivery_address string required Is Delivery Address (Default Address) Enum(yes,no). Example:yes
    *
    * @authenticated
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "Address has been added successfully",
        "data": [
            {
                "id": "2",
                "contact_name": "test",
                "contact_number": "1111112222",
                "state_id": "1",
                "state_name": "Gujarat",
                "city_id": "1",
                "city_name": "Ahmedabad",
                "postcode_id": "1",
                "postcode": "380026",
                "area": "satellite",
                "house_no": "20",
                "street": "test",
                "landmark": "test",
                "address_type": "Home"
            }
        ]
    }

    */
    public function store(Request $request){
    	try {
    		$validator = Validator::make($request->all(),[
	            'contact_name'        => 'required|max:100',
                'company_name'        => 'required|max:100',
                'contact_number'      => 'required|digits:10',
                'email'               => 'required|email',
                'state_id'            => 'required|exists:states,id',
                'state'               => 'nullable|min:2|max:50',
                'city_id'             => 'required|exists:cities,id',
                'city'                => 'nullable|min:2|max:50',
                'postcode_id'         => 'required|exists:postcodes,id',
                'postcode'            => 'nullable|min:6|max:6',
                'area'                => 'required|max:100',
                'house_no'            => 'required|max:100',
                'street'              => 'required|max:100',
                'landmark'            => 'required|max:100',
                'address_type'        => 'required|in:Home,Office,Other',
                'is_delivery_address' => 'required|in:yes,no'

	        ], [
                'contact_name.required' => 'Full name field is required!',
                'contact_number.required' => 'Phone number field is required!',
                'postcode_id.required'=>'Pincode field is required',
                'city_id.required'=>'City field is required',
                'state_id.required'=>'State field is required',
            ]);

            if($validator->fails()){
              return $this->sendValidationError('',$validator->errors()->first());
            }
            
          /*  if($request->state_id === null && $request->state === null) {
                return $this->sendError('','State field is required');
            }
            if($request->city_id === null && $request->city === null) {
                return $this->sendError('','City field is required');
            }
            if($request->postcode_id === null && $request->postcode === null) {
                return $this->sendError('','Pincode field is required');
            }*/
            $user_id = Auth::guard('api')->user()->id;
            $postcode = PostCode::find($request->postcode_id);

            if($postcode) {
                $request->merge([
                    'user_id' => $user_id,
                    'state' => $postcode->city->state->name,
                    'city' => $postcode->city->name,
                    'postcode' => $postcode->postcode
                ]);    
            } else {
                $state = ($request->state_id) ? State::find($request->state_id)->name : $request->state;
                $city = ($request->city_id) ? City::find($request->city_id)->name : $request->city;
                $m_postcode = ($request->postcode_id) ? City::find($request->postcode_id)->name : $request->postcode;
    	        $request->merge([
                    'user_id' => $user_id,
                    'state' => $state,
                    'city' => $city,
                    'postcode' => $m_postcode
                ]);
            }
            // echo "<pre>";print_r($request->all());exit;
	        
	        

	        $insert = Address::create($request->all());

            // mark other as non default
            if($request->is_delivery_address == 'yes')
            {
                Address::where('id','!=',$insert->id)->where('user_id',$user_id)->update(['is_delivery_address'=>'no']);
            }

	        $addressList = Address::where('user_id',$user_id)->orderBy('id','desc')->get();
	        return $this->sendResponse(AddressResource::collection($addressList), trans('addresses.address_added'));
    	} catch(\Exception $e) {
    		return $this->sendError('',trans('common.something_went_wrong'));
    	}
    }
    /**
    * Addresses : Edit Address
    *
    * @bodyParam address_id string required Address ID. Example:1
    *
    * @authenticated
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "Address Details",
        "data": {
            "id": "1",
            "contact_name": "test",
            "contact_number": "1111112222",
            "state_id": "1",
            "state_name": "Gujarat",
            "city_id": "1",
            "city_name": "Ahmedabad",
            "postcode_id": "1",
            "postcode": "380026",
            "area": "satellite",
            "house_no": "20",
            "street": "test",
            "landmark": "test",
            "address_type": "Home"
        }
    }
    */
    public function edit(Request $request){
    	try {
	    	$validator = Validator::make($request->all(),[
	          'address_id' => 'required|exists:addresses,id',
	        ]);
	        if($validator->fails()){
	          return $this->sendValidationError('',$validator->errors()->first());
	        }

	    	$address = Address::find($request->address_id);
	    	if(!empty($address)){
	    		return $this->sendResponse(new AddressResource($address), trans('addresses.address_details'));
	    	} else {
	    		return $this->sendError('', trans('addresses.address_not_found'));
	    	}
	    } catch(\Exception $e) {
    		return $this->sendError('',trans('common.something_went_wrong'));
    	}
    }
    /**
    * Addresses : Update Address
    *
    * @bodyParam address_id string required Address ID. Example:1
    * @bodyParam contact_name string required Contact name. Example:test
    * @bodyParam company_name string required Company name. Example:test
    * @bodyParam contact_number string required Contact number. Example:1231231231
    * @bodyParam email string required   Email. Example:test@mail.com
    * @bodyParam state_id string State id. Example:Gujarat
    * @bodyParam state string
    * @bodyParam city_id string City id. Example:1
    * @bodyParam city string 
    * @bodyParam postcode_id string PostCode id. Example:1
    * @bodyParam postcode string
    * @bodyParam area string required Area. Example:test
    * @bodyParam house_no string required House / Street No. Example:11
    * @bodyParam street string required Street. Example:xxx
    * @bodyParam landmark string required Landmark. Example:xxx
    * @bodyParam address_type string required Address Type(Home,Office,Other). Example:Home
    * @bodyParam is_delivery_address string required Is Delivery Address (Default Address) Enum(yes,no). Example:yes
    *
    * @authenticated
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "Address has been updated successfully",
        "data": [
            {
                "id": "1",
                "contact_name": "sssss",
                "contact_number": "2222222222",
                "state_id": "2",
                "state_name": "Gujarat",
                "city_id": "3",
                "city_name": "Ahmedabad",
                "postcode_id": "3",
                "postcode": "380026",
                "area": "Bandra",
                "house_no": "40",
                "street": "khaugali",
                "landmark": "Taj Hotel",
                "address_type": "Home"
            }
        ]
    }
    */
    public function update(Request $request) {
    	try {
	    	$address = Address::find($request->address_id);
	    	if(empty($address)){
	    		return $this->sendError('', trans('addresses.address_not_found'));
	    	}

	    	$validator = Validator::make($request->all(),[
	          /*	'contact_name'        => 'required|max:100',
                'company_name'        => 'required|max:100',
                'contact_number'      => 'required|digits:10',
                'email'               => 'required|email',
                'state_id'            => 'nullable|exists:states,id',
                'state'               => 'nullable|min:2|max:50',
                'city_id'             => 'nullable|exists:cities,id',
                'city'                => 'nullable|min:2|max:50',
                'postcode_id'         => 'nullable|exists:postcodes,id',
                'postcode'            => 'nullable|min:6|max:6',
                'area'                => 'required|max:100',
                'house_no'            => 'required|max:100',
                'street'              => 'required|max:100',
                'landmark'            => 'required|max:100',
                'address_type'        => 'required|in:Home,Office,Other',
                'is_delivery_address' => 'required|in:yes,no'*/
                'contact_name'        => 'nullable|max:100',
                'company_name'        => 'nullable|max:100',
                'contact_number'      => 'nullable|digits:10',
                'email'               => 'nullable|email',
                'state_id'            => 'nullable|exists:states,id',
                'state'               => 'nullable|min:2|max:50',
                'city_id'             => 'nullable|exists:cities,id',
                'city'                => 'nullable|min:2|max:50',
                'postcode_id'         => 'nullable|exists:postcodes,id',
                'postcode'            => 'nullable|min:6|max:6',
                'area'                => 'nullable|max:100',
                'house_no'            => 'nullable|max:100',
                'street'              => 'nullable|max:100',
                'landmark'            => 'nullable|max:100',
                'address_type'        => 'nullable|in:Home,Office,Other',
                'is_delivery_address' => 'nullable|in:yes,no'

	        ]);

	        if($validator->fails()){
	          return $this->sendValidationError('',$validator->errors()->first());
	        }

           /* if($request->state_id === null && $request->state === null) {
                return $this->sendError('','State field is required');
            }
            if($request->city_id === null && $request->city === null) {
                return $this->sendError('','City field is required');
            }
            if($request->postcode_id === null && $request->postcode === null) {
                return $this->sendError('','PostCode field is required');
            }*/

	    	$data = $request->except(['address_id']);
            $postcode = PostCode::find($request->postcode_id);

            $user = Auth::guard('api')->user();
            if($postcode){
                $data['state'] = $postcode->city->state->name;
                $data['city'] = $postcode->city->name;
                $data['postcode'] = $postcode->postcode;
            }else{
                $state = ($request->state_id) ? State::find($request->state_id)->name : $request->state;
                $city = ($request->city_id) ? City::find($request->city_id)->name : $request->city;
                $m_postcode = ($request->postcode_id) ? City::find($request->postcode_id)->name : $request->postcode;
                $data['state'] = $state;
                $data['city'] = $city;
                $data['postcode'] = $m_postcode;
                $data['postcode_id'] = @$request->postcode_id;
            }

            // print_r($data);die;

	        $address->update($data);

            // mark other as non default
            if($request->is_delivery_address == 'yes')
            {
                Address::where('id','!=',$request->address_id)->where('user_id',$user->id)->update(['is_delivery_address'=>'no']);
            }

	        $addressList = Address::where('user_id',Auth::guard('api')->user()->id)->orderBy('id','desc')->get();
	        return $this->sendResponse(AddressResource::collection($addressList), trans('addresses.address_updated'));
	    } catch(\Exception $e) {
            echo "<pre>";print_r($e->getMessage());exit;
	    	return $this->sendError("",trans('common.something_went_wrong'));
	    }
    }
    /**
    * Addresses : Delete Address
    *
    * @bodyParam address_id string required Address ID. Example:1
    *
    * @authenticated
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "Address has been deleted successfully",
        "data": [
            {
                "id": "4",
                "contact_name": "test",
                "contact_number": "1111112222",
                "state_id": "1",
                "state_name": "Gujarat",
                "city_id": "1",
                "city_name": "Ahmedabad",
                "postcode_id": "1",
                "postcode": "380026",
                "area": "Amraiwadi",
                "house_no": "20",
                "street": "test",
                "landmark": "ssasa",
                "address_type": "Home"
            }
        ]
    }
    */
    public function delete(Request $request){
    	try{    		
	        $validator = Validator::make($request->all(),[
	          'address_id' => 'required|exists:addresses,id',
	        ]);
	        if($validator->fails()){
	          return $this->sendValidationError('',$validator->errors()->first());
	        }

	        Address::find($request->address_id)->delete();

	        return $this->sendResponse(AddressResource::collection(Auth::guard('api')->user()->addresses), trans('addresses.address_deleted'));
    	} catch(\Exception $e) {
    		return $this->sendError('',trans('common.something_went_wrong'));
    	}
    }
}