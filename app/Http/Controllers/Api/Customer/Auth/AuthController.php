<?php

namespace App\Http\Controllers\Api\Customer\Auth;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DeviceDetail;
use App\Models\CompanyDocImage;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SignupActivate;
use App\Models\Helpers\CommonHelper;
use Carbon\Carbon;
use App\Models\SmsVerification;
use App\Models\EmailVerification;
use App\Models\Setting;
use App\Models\Country;
use App\Models\ReferHistory;
use App\Http\Resources\Customer\UserResource;
use App\Http\Resources\Customer\CountryResource;
use Illuminate\Support\Facades\Password;
use DB;
use Validator;
use Notification;
use App\Mail\SendVerificationEmail;
use Illuminate\Support\Facades\Mail;


/**
 * @group Customer Endpoints
 *
 * Customer Apis
 */
class AuthController extends BaseController
{
    use CommonHelper;
    /**
    * Auth: Country List
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
            "flag": "http://localhost/ssgc-bulk-order-web/public/flags/india.png"
        }
    ]
}
    */
    public function country_list(){

        $countries = Country::where('status','active')->get();

        if($countries->count() > 0){

            return $this->sendResponse(CountryResource::collection($countries),'Country List Found');

        }else{

            return $this->sendError($this->array,trans('auth.country_list_empty'));
        }
    }

    /**
    * Auth: Signup
    *
    * @bodyParam first_name string required Full name. Example:John
    * @bodyParam profile_image file required Profile Image.
    * @bodyParam company_name string required Full name. Example:John
    * @bodyParam company_images[] file required Company Images.
    * @bodyParam company_documents[] file required Company Documents.
    * @bodyParam email string optional max:190 Email. Example:John@gmail.com
    * @bodyParam mobile_number string required max:10  Mobile Number. Example:1234567890
    * @bodyParam password string required min:8 max:16  Password. Example:John@123
    * @bodyParam referral_code string required min:8 max:8  Referral Code. Example:SSGC1234
    * @bodyParam device_type string required User's device type. Enums : iphone, android. Example:iphone
    * @bodyParam device_token string required User's device token. Example:abcd1234
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "OTP sent to your email test123@mailinator.com",
        "data": {
            "id": "10",
            "username": "test1234",
            "email": "test123@mailinator.com",
            "mobile_number": "1111111111",
            "bio": "test",
            "link": "https://www.mysite.com",
            "favorite_book": "test",
            "favorite_genre": "test",
            "title": "Author",
            "title_id": "1",
            "profile_image": "http://cloud1.kodyinfotech.com:7000/redwriter/public/uploads/media/53534670e278f062511d0b522b6372f0.jpg",
            "country": {
                "id": "1",
                "name": "USA",
                "code": "+1",
                "flag": "http://cloud1.kodyinfotech.com:7000/redwriter/public/flags/usa.png"
            },
            "state": {
                "id": "1",
                "country_id": "1",
                "country_name": "USA",
                "name": "California"
            },
            "city": {
                "id": "1",
                "state_id": "1",
                "state_name": "California",
                "name": "Los Angeles"
            },
            "email_verified": "1",
            "phone_verified": "1",
            "is_social_login": "normal",
            "friends": "0",
            "my_subscription": {
                "id": "1",
                "name": "Black",
                "type": "Public",
                "price": "100"
            }
        }
    }
    */
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'first_name'          => 'required|string|min:3|max:50',
            'company_name'        => 'required|string|min:3|max:50',
            'email'               => 'nullable|email|max:190',
            'mobile_number'       => 'required|digits:10',
            'password'            => 'required|min:8|max:16',
            'confirm_password'    => 'nullable|min:8|max:16|same:password',
            'referral_code'       => 'nullable|min:6|max:6',
            'profile_image'       => 'required|image|mimes:png,jpg,jpeg,svg|max:10000',
            'company_images'      => 'required|array',
            'company_images.*'    => 'required|image|mimes:png,jpg,jpeg,svg|max:10000',
            'company_documents'   => 'required|array',
            'company_documents.*' => 'required|image|mimes:png,jpg,jpeg,svg|max:10000',
            'device_token'        => 'required',
            'device_type'         => 'required|in:iphone,android,web',
        ],[
          'first_name.min'      => 'The full name must be atleast 3 characters.',
          'first_name.max'      => 'The full name must not be greater than 50 characters.',
          'first_name.required' => 'The full name is required.',
          'profile_image.image'=>'Please upload profile image',
          'company_images.required'=>'Please upload company/shop images',
          'company_documents.required'=>'Please upload shop documents',
        ]);

        if($validator->fails()) {

            return $this->sendError('', $validator->errors()->first());       
        }
        
        if(isset($request->email))
        {
            $user = User::where('email',$request->email)->first();
            if($user && ($user->email_verified_at != NULL || $user->verified == '1'))
            {
                return $this->sendError('', trans('auth.account_exists_with_this_email')); 
            }
        }
        

        $user = User::where('mobile_number',$request->mobile_number)->first();
        if($user && ($user->email_verified_at != NULL || $user->verified == '1'))
        {
            return $this->sendError('', trans('auth.account_exists_with_this_phone')); 
        }

        if($request->referral_code)
        {
            //check if referral code is valid
            $referrer_user = User::where('referral_code',$request->referral_code)->where('mobile_number','<>',$request->mobile_number)->where('email','<>',$request->email)->first();

            if(!$referrer_user)
            {
                return $this->sendError('',trans('auth.invalid_referral_code'));
            }

            if($referrer_user->verified != '1' && $referrer_user->email_verified_at == NULL)
            {
                return $this->sendError('',trans('auth.invalid_referral_code'));
            }
        }

        // Create New User
        $input = $request->all();
        
        if(!$user){
            $user = new User();
        }
        $country                  = Country::where('status','active')->first();
        $user->first_name         = $request->first_name;
        $user->company_name       = $request->company_name;
        $user->email              = $request->email;
        $user->mobile_number      = $request->mobile_number;
        $user->country_id         = $country->id;
        $user->user_type          = 'retailer';
        $user->status             = 'active';
        $user->password           = bcrypt($request->password);
        $user->referral_code      = $this->generateReferralCode();
        $user->referrer_id        = isset($referrer_user) ? $referrer_user->id : NULL;
        if($request->device_token === null){
            $user->registered_on = 'web';
        }

        if(isset($request->profile_image)){
            $path = $this->saveMedia($request->file('profile_image'),'profile');
            $user->profile_image = $path;
        }


        DB::beginTransaction();
        try {

            if($user->save()){ // Check if user data is saved
                
                //uplaod & save company docs & image
                CompanyDocImage::where('company_id',$user->id)->delete();
                
                if(count($request->company_images)){
                    $files  =  $request->file('company_images');
                    foreach ($files as $file) { 
                        $path = $this->saveMedia($file,'profile');
                        CompanyDocImage::create(['company_id' => $user->id,'images'=>$path]);
                    }
                }

                if(count($request->company_documents)){
                    $files  =  $request->file('company_documents');
                    foreach ($files as $file) { 
                        $path = $this->saveMedia($file,'profile');
                        CompanyDocImage::create(['company_id' => $user->id,'documents'=>$path]);
                    }
                }

                /*try
                {
                    //send and store email otp
                    $otp = $this->genrateOtp();
                    $email = $request->email;
                    $res = $this->sendEmailOtp($email,$user,$otp);
                } catch (\Exception $e) {
                  DB::rollback();
                  return $this->sendError('',trans('common.email_not_sent'));
                }  */
                

                //send and store mobile otp
                $country_code = $user->country->country_code;
                $otp = $this->genrateOtp();
                $res = $this->sendOtp($country_code,$request->mobile_number,$otp);

                if($res) {
                    // Save Device Details
                    if($request->device_token)
                    {
                        $data = ['device_token' => $request->device_token, 'device_type' => $request->device_type];
                        $createArray = array();
                        
                        foreach ($data as $key => $value) {
                            $createArray[$key] = $value;
                        }
                        $device_detail = DeviceDetail::where('user_id',$user->id)->first();
                        if($device_detail){
                            $device_detail->update($createArray);
                        } else {
                            $createArray['user_id'] = $user->id;
                            DeviceDetail::create($createArray);
                        }
                    }

                    DB::commit();

                    return $this->sendResponse(new UserResource($user), trans('auth.otp_sent_mobile', ['number'=> $request->mobile_number]));
                }
               
            }else{
                DB::rollback();
                return $this->sendError('',trans('auth.error'));
            }
        } catch (\Exception $e) {
          DB::rollback();
          return $this->sendError('',trans('common.something_went_wrong'));
        }   
    }


    /**
    * Auth: Mobile OTP Verify
    *
    * @bodyParam otp string required max:6  OTP. Example:123456
    * @bodyParam user_id string required User ID. Example:1
    * @bodyParam mobile_number string required  max:10  Mobile Number. Example:1234567890
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "Your Mobile number verified successfully",
        "data": {
            "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMjA4NmYxODJjOGZjNmNlYzI0ZDdjYzQ3NzlmYjFjY2FmZjcwNGE2NWZjMmQxNmY1OTNmNjUyY2FkM2YzYjk0M2ViZDg3ZmRmZGIxZTMwMzYiLCJpYXQiOjE2N...",
            "id": "9",
            "first_name": "John",
            "last_name": "",
            "email": "John@gmail.com",
            "mobile_number": "1234567890",
            "profile_image": "http://localhost/ssgc-web/public/customer_avtar.jpg",
            "country": {
                "id": "1",
                "name": "India",
                "code": "+91",
                "flag": "http://localhost/ssgc-web/public/flags/india.png"
            },
            "referral_code": "5X9PFTIR",
            "email_verified": "0",
            "phone_verified": "1",
            "is_social_login": "normal"
        }
    }
    */
    public function verify_otp(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'user_id'       => 'required|exists:users,id',
            'mobile_number' => 'required|digits:10',
            // 'country_id'    => 'required|exists:countries,id',
            'otp'           => 'required|digits:6'
        ]);

        if($validator->fails()) {
            return $this->sendError($this->object, $validator->errors()->first());       
        }
        $request->merge([
            'country_id' => 1,
        ]);
        $user = User::find($request->user_id);

        $smsVerifcation = SmsVerification::where(['mobile_number' => $request->mobile_number,'status' => 'pending'])
                        ->latest() //show the latest if there are multiple
                        ->first();

        if($smsVerifcation == null){
            return $this->sendError($this->object,trans('auth.number_not_found'));
        }

        if($user->country_id != $request->country_id){
            return $this->sendError('', trans('auth.otp_wrong_number'));
        }

        if($user->user_type != 'retailer' && $user->user_type != 'dealer'){
            return $this->sendError('', 'You are not valid customeree');
        }

        if($user->mobile_number != $request->mobile_number){
            return $this->sendError('', trans('auth.otp_wrong_number'));
        }

        if($request->otp != $smsVerifcation->code){
            return $this->sendError($this->object,trans('auth.otp_invalid_long'));
        }

        $otp_time_difference_in_minutes = $smsVerifcation->created_at->diffInMinutes(Carbon::now());

        //Checking OTP Code Expiry
        if($otp_time_difference_in_minutes > config('adminlte.otp_expiry_in_minutes')) {
            $request["status"] = 'expired';
            $request['code'] = $request['otp'];
            $smsVerifcation->updateModel($request);
            return $this->sendError($this->object,trans('auth.otp_expired_long'));
        }

      
        DB::beginTransaction();
        try {

            if($user->verified != '1')
            {
                    //maintainReferralHistory
                    $this->maintainReferralHistory((integer)Setting::get('welcome_points'), 'added','',$user);

                    //###### Admin New customer registered Notification ######
                    $admin = User::where('user_type','admin')->first();
                    if($admin){
                        $title    = 'New customer registered';
                        $body     = "Hurray! new customer registered";
                        $slug     = 'admin_customer_registerd';
                        $this->sendNotification($admin,$title,$body,$slug,$user,null);
                    }
                    //###### Admin New customer registered Notification ######

                     //###### New customer registered Notification ######
                    if($user){
                        $title    = 'You are registered successfully';
                        $body     = "Hurray! You have been successfully registered as a retailer";
                        $slug     = 'customer_registerd';
                        $this->sendNotification($user,$title,$body,$slug,$user,null);
                    }
                    //###### New customer registered Notification ######
            }

            $user->verified = "1";
            $user->save();

            $request["status"] = 'verified';
            $smsVerifcation->updateModel($request);
            
            $user->accessToken = $user->createToken(config('app.name'))->accessToken;
             
            DB::commit();

            return $this->sendResponse(new UserResource($user), trans('auth.mobile_verified'));

        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError($this->object,$e->getMessage());
        }       
       
    }


    

    /**
    * Auth: Login with OTP
    *
    * @bodyParam mobile_number string required  max:10  Mobile Number. Example:123456789
    * @bodyParam device_type string optional User's device type. Enums : iphone, android. Example:iphone
    * @bodyParam device_token string optional User's device token.Example:abcd1234
    * 
    * @response
    {
    "success": "1",
    "status": "200",
    "message": "Your Mobile number verified successfully",
    "data": {
        "token": "eyJ0eXAiOiJKV1QiLCJ...",
        "id": "1",
        "first_name": "Parth",
        "last_name": "Patel",
        "email": "parth.patel@kodytechnolab.com",
        "mobile_number": "972733248",
        "age": "25",
        "profile_image": "http://localhost/beauty-fly/public/images/customer_avtar.png",
        "country": {
            "id": "1",
            "name": "Saudi Arabia",
            "code": "+966",
            "flag": "http://localhost/beauty-fly/public/flags/saudi_arebia.png"
        },
        "latitude": "",
        "longitude": "",
        "email_verified": "0",
        "phone_verified": "1",
        "is_social_login": "0"
        }
    }
    */
    public function login_with_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|digits:10|exists:users,mobile_number',
            // 'country_id'         => 'required|exists:countries,id',
            'device_token'       => 'nullable',
            'device_type'        => 'nullable'
        ]);
       

        if($validator->fails()) {
            return $this->sendError('', $validator->errors()->first());       
        }

        $request->merge([
            'country_id' => 1,
        ]);
        if($request->mobile_number != null){

            $user = User::where('mobile_number',$request->mobile_number)->whereIn('user_type',['retailer','dealer'])->first();
            if(!$user)
            {
                return $this->sendError('',trans('auth.user_not_valid'));
            }

            // if($user->verified != '1')
            // {
            //     return $this->sendError('', trans('auth.phone_not_verified')); 
            // }

            if($user->status == 'blocked' || $user->status == 'inactive')
            {
                $admin_email = Setting::get('contact_email');
                return $this->sendError('',trans('auth.account_blocked',['contact' => $admin_email]));
            }
            $smsVerifcation = SmsVerification::where(['mobile_number' => $request->mobile_number])
                ->latest() //show the latest if there are multiple
                ->first();

            //send & store otp
            $otp = $this->genrateOtp();
            $country_code = $user->country->country_code;
            $mobile_number = $request->mobile_number;
            $res = $this->sendOtp($country_code,$mobile_number,$otp,$smsVerifcation);

           
            if($res) {
               
                // Save Device Details
                $data = $request->except('mobile_number','country_id');
                if($request->device_token)
                {
                    $createArray = array();
                    foreach ($data as $key => $value) {
                        $createArray[$key] = $value;
                    }
                    // $device_detail = DeviceDetail::where('user_id',$user->id)->first();

                    $device_exists = DeviceDetail::where([
                        'user_id' => $user->id,
                        'device_token' => $createArray['device_token']
                    ])->get()->count();

                    if($device_exists){
                        // $device_detail->update($createArray);
                    } else {
                        $createArray['user_id'] = $user->id;
                        DeviceDetail::create($createArray);
                    }
                }
                

                return $this->sendResponse(new UserResource($user), trans('auth.otp_sent_mobile', ['number'=> $request->mobile_number]));
            }


        }else{
            return $this->sendError('',trans('auth.failed_error'));
        }

    }

    /**
    * Auth: Login with password (email / mobile)
    *
    * @bodyParam email string optional Email(required without mobile number). Example:john@mail.com
    * @bodyParam mobile_number string optional  max:10  Mobile Number(required without email). Example:123456789
    * @bodyParam password string required min:8 max:16  Password. Example:12345678
    * @bodyParam device_type string optional User's device type. Enums : iphone, android. Example:iphone
    * @bodyParam device_token string optional User's device token.Example:abcd1234
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "User logged in successfully.",
        "data": {
            "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZDI3MDg1NTBhNjZkZjc5ZTQwZmI4MWQ0....",
            "id": "10",
            "first_name": "test",
            "last_name": "",
            "email": "tesssttt@mailinator.com",
            "mobile_number": "1112223331",
            "profile_image": "http://localhost/ssgc-web/public/customer_avtar.jpg",
            "country": {
                "id": "1",
                "name": "India",
                "code": "+91",
                "flag": "http://localhost/ssgc-web/public/flags/india.png"
            },
            "referral_code": "NRSW80WZ",
            "email_verified": "1",
            "phone_verified": "1",
            "is_social_login": "normal"
        }
    }
    */
    public function login_with_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'         => 'required_without:mobile_number|email|exists:users,email',
            'mobile_number' => 'required_without:email|digits:10',
            'password'      => 'required|min:8|max:16',
            'device_token'  => 'nullable',
            'device_type'   => 'nullable'
        ]);
       
        if($validator->fails()) {
            return $this->sendError('', $validator->errors()->first());       
        }

        if(isset($request->email))
        {

            $user = User::where('email',$request->email)->whereIn('user_type',['retailer','dealer'])->first();

            if(!$user)
            {
                return $this->sendError('',trans('auth.user_not_valid'));
            }
            
            if($user->email_verified_at == NULL)
            {
                //send & store otp
                /*$otp = $this->genrateOtp();
                $email = $request->email;
                $res = $this->sendEmailOtp($email,$user,$otp);*/

                return $this->sendException(new UserResource($user), trans('auth.email_not_verified')); 
            }

            $auth_check = Auth::attempt(['email' => $request->email, 'password' => $request->password]);
            // echo "<pre>";var_dump($auth_check);exit;
        }

        if(isset($request->mobile_number))
        {

            $user = User::where('mobile_number',$request->mobile_number)->whereIn('user_type',['retailer','dealer'])->first();

            if(!$user)
            {
                return $this->sendError('','This credentials does not match');
            }
            
            if($user->verified != '1')
            {
                //send & store otp
                /*$otp = $this->genrateOtp();
                $country_code = $user->country->country_code;
                $mobile_number = $request->mobile_number;
                $res = $this->sendOtp($country_code,$mobile_number,$otp);*/

                return $this->sendException(new UserResource($user), trans('auth.phone_not_verified')); 
            }

            $auth_check = Auth::attempt(['mobile_number' => $request->mobile_number, 'password' => $request->password]);
        }

        
        if($auth_check){
            if($user->status == 'blocked' || $user->status == 'inactive')
            {
                $admin_email = Setting::get('contact_email');
                return $this->sendError('',trans('auth.account_blocked',['contact' => $admin_email]));
            }
            // Save Device Details
            if($request->device_token)
            {
                $data = $request->except('email','password');
                $createArray = array();
                
                foreach ($data as $key => $value) {
                    $createArray[$key] = $value;
                }
                // $device_detail = DeviceDetail::where('user_id',$user->id)->first();

                $device_exists = DeviceDetail::where([
                    'user_id' => $user->id,
                    'device_token' => $request->device_token
                ])->get()->count();

                if($device_exists){
                    // $device_detail->update($createArray);
                } else {
                    $createArray['user_id'] = $user->id;
                    DeviceDetail::create($createArray);
                }
            }
            $user->accessToken = $user->createToken(config('app.name'))->accessToken;
            return $this->sendResponse(new UserResource($user), trans('auth.logged_in'));
        }
    
        return $this->sendError('',trans('auth.failed_error'));

    }

    /**
    * Auth: Logout
    *
    * @authenticated
    *
    * @bodyParam device_token string optional User's device token.Example:abcd1234
    *
    * @response
    * {
    *   "success": "1",
    *   "status": "200",
    *   "message": "You have logged-out successfully"
    * }
    */
    public function logout(Request $request){

        $validator = Validator::make($request->all(), [
            'device_token' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendValidationError('', $validator->errors()->first());
        }

        $user = Auth::guard('api')->user();

        if($user){
            DeviceDetail::where('device_token', $request->device_token)->delete();
            $user->token()->revoke();    
        }
        return $this->sendResponse('', trans('auth.logout_success'));
    }

    /**
    * Auth: Resend OTP
    *
    * @bodyParam user_id string required User ID. Example:1
    * @bodyParam mobile_number string required  max:10  Mobile Number. Example:123456789
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "OTP sent to your mobile number 9727332489"
    }
    */
    public function resend_otp(Request $request)
    {
        DB::beginTransaction();
        try{

            $validator = Validator::make($request->all(), [
                'user_id'        => 'required|exists:users,id',
                'mobile_number'  => 'required|digits:10',
                // 'country_id'    => 'required|exists:countries,id',
            ]);

            if($validator->fails()) {
                return $this->sendError($this->object, $validator->errors()->first());       
            }

            $request->merge([
                'country_id' => 1,
            ]);
            $user = User::find($request->user_id);

            if($user->country_id != $request->country_id){
                return $this->sendError('', trans('auth.otp_wrong_number'));
            }
           
            $smsVerifcation = SmsVerification::where(['mobile_number' => $request->mobile_number])
                            ->latest() //show the latest if there are multiple
                            ->first();

            if(!$smsVerifcation){
                return $this->sendResponse($this->object, trans('auth.number_not_found'));
            }

            //resend otp to mobile number and update 
            $otp = $this->genrateOtp();
            $country_code = $user->country->country_code;
            $mobile_number = $request->mobile_number;
            $res = $this->sendOtp($country_code,$mobile_number,$otp);
            DB::commit();
            if($res){
                return $this->sendResponse('', trans('auth.otp_sent_mobile', ['number'=> $request->mobile_number]));
            } else {
                return $this->sendError('',trans('auth.otp_sent_error'));
            }

        } catch (\Exception $e) {   
            DB::rollback();
            return $this->sendError('', trans('auth.otp_sent_error'));
        }
    }

    /**
    * Auth: Forgot Password
    *
    * @bodyParam send_in string required send in (values: mobile, email). Example:mobile
    * @bodyParam email string optional Email (required if send in email). Example:test@mail.com
    * @bodyParam mobile_number string optional  max:10  Mobile Number  (required if send in mobile). Example:123456789
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "OTP sent to your mobile number 1112223331"
    }

    */
    public function forgot_password(Request $request){

        $email_rules = 'nullable|string|email|max:99|exists:users';
        $mobile_number_rules = 'nullable|digits:10|exists:users';
        // $country_id_rules = 'nullable|exists:countries,id';
        if($request->send_in == 'email')
        {
            $email_rules = 'required|string|email|max:99|exists:users';
        }
        if($request->send_in == 'mobile')
        {
            $mobile_number_rules = 'required|digits:10|exists:users';
            // $country_id_rules    = 'required|exists:countries,id';
        }
        $validator = Validator::make($request->all(),[
            'send_in'       => 'required|in:mobile,email',
            'email'         => $email_rules,
            'mobile_number' => $mobile_number_rules,
            // 'country_id'    => $country_id_rules
        ]);

        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());       
        }
        $request->merge([
            'country_id' => 1,
        ]);
        try{
            $dataArray = $request->all();

            if($request->send_in == 'email')
            {
                $user = User::where('email', $request->email)->whereIn('user_type',['retailer','dealer'])->first();
                if(!$user){
                    return $this->sendError('', trans('auth.user_not_valid'));
                }

                if($user->email_verified_at == NULL)
                {
                    return $this->sendError('', trans('auth.email_not_verified'));
                }

                if(Password::sendResetLink(['email'=>$request->email])){
            
                    return $this->sendResponse('', trans('auth.password_reset'));

                }else{

                    return $this->sendError('',trans('auth.error'));
                }
                
            }

            if($request->send_in == 'mobile')
            {
                $user = User::where('mobile_number', $request->mobile_number)->whereIn('user_type',['retailer','dealer'])->first();
                if(!$user){
                    return $this->sendError('', trans('auth.user_not_valid'));
                }

                if($user->verified != '1'){
                    return $this->sendError('', trans('auth.phone_not_verified'));
                }

                //send and store otp
                $otp = $this->genrateOtp();
                $country_code = $user->country->country_code;
                $mobile_number = $request->mobile_number;
                $res = $this->sendOtp($country_code,$mobile_number,$otp);

                return $this->sendResponse('', trans("auth.otp_sent_mobile",['number'=>$request->mobile_number]));
            }
            
                
        }catch (\Exception $e) { 
          return $this->sendError('', trans('common.something_went_wrong')); 
        }
    }

    /**
    * Auth: Forgot Password Verify OTP
    *
    * @bodyParam mobile_number string optional  max:10  Mobile Number(required if send in mobile). Example:123456789
    * @bodyParam otp string required OTP. Example:1234
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "OTP has been verified successfully."
    }
    */
    public function forgot_password_verify_otp(Request $request)
    {
        DB::beginTransaction();
        try {
            
            $mobile_number_rules = 'required|digits:10|exists:users';
            $validator = Validator::make($request->all(), [
                'mobile_number' => $mobile_number_rules,
                'otp'           => 'required|digits:6'
            ]);

            if($validator->fails()) {
                return $this->sendError('', $validator->errors()->first());       
            }
            $request->merge([
                'country_id' => 1,
            ]);

            $smsVerification = SmsVerification::where(['mobile_number' => $request->mobile_number,'status' => 'pending'])
                            ->latest() //show the latest if there are multiple
                            ->first();
            if($smsVerification == null){
                return $this->sendError('',trans('auth.number_not_found'));
            }
            
            $otp_time_difference_in_minutes = $smsVerification->created_at->diffInMinutes(Carbon::now());

            if($request->otp != $smsVerification->code){
                return $this->sendError('',trans('auth.otp_invalid_long'));
            }

            //Checking OTP Code Expiry
            if($otp_time_difference_in_minutes > config('adminlte.otp_expiry_in_minutes')) {
                $request["status"] = 'expired';
                $request['code'] = $request['otp'];
                $smsVerification->updateModel($request);
                return $this->sendError('',trans('auth.otp_expired_long'));
            }

            $request["status"] = 'verified';
            $smsVerification->updateModel($request);
            
            DB::commit();
            return $this->sendResponse('', trans('auth.otp_verified'));
        } catch (Exception $e) {
            DB::rollback();
            return $this->sendError('',$e->getMessage());
        }       
       
    }
    /**
    * Auth: Forgot Password Resend OTP
    *
    * @bodyParam mobile_number string optional  max:10  Mobile Number(required if send in mobile). Example:123456789
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "OTP sent to your mobile number 9727332489"
    }
    */
    public function forgot_password_resend_otp(Request $request)
    {
        $mobile_number_rules = 'required|digits:10|exists:users';
        $validator = Validator::make($request->all(),[
            'mobile_number' => $mobile_number_rules,
        ]);

        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());       
        }

        $request->merge([
            'country_id' => 1,
        ]);
        
        try{
            $dataArray = $request->all();
            
            $user = User::where('mobile_number', $request->mobile_number)->first();
            if(!$user){
                return $this->sendError('', trans('auth.user_not_valid'));
            }

            if($user->verified != '1'){
                return $this->sendError('', trans('auth.phone_not_verified'));
            }

            //send and store otp
            $otp = $this->genrateOtp();
            $country_code = $user->country->country_code;
            $mobile_number = $request->mobile_number;
            $res = $this->sendOtp($country_code,$mobile_number,$otp);

            return $this->sendResponse('', trans("auth.otp_sent_mobile",['number'=>$request->mobile_number]));
        }catch (\Exception $e) { 
          return $this->sendError('', trans('common.something_went_wrong')); 
        }
    }
    /**
    * Auth: Reset Password
    *
    * @bodyParam otp string required OTP. Example:1234
    * @bodyParam mobile_number optional  max:10  Mobile NumberExample:123456789
    * @bodyParam password string required Password. Example:12345678
    * @bodyParam confirm_password string required Confirm Password. Example:12345678
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "Password Reset successfully."
    }
    */
    public function reset_password(Request $request){
        $validator = Validator::make($request->all(),[
            'otp'              => 'required|digits:6',
            'mobile_number'    => 'required_without:email|digits_between:8,15|exists:users',
            'password'         => 'required|min:8|max:16',
            'confirm_password' => 'required|min:8|max:16|same:password',
        ]);
        
        $user = User::where('mobile_number',$request->mobile_number)->first();
        $is_verified = SmsVerification::where(['mobile_number' => $request->mobile_number,'status' => 'verified'])
                        ->latest() //show the latest if there are multiple
                        ->first();
        
        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());       
        }
        
        //update password if otp verified
        if($is_verified)
        {
            $user->password = bcrypt($request->password);
            $user->save();
            return $this->sendResponse('',trans('auth.password_reset_success'));
        }
        else
        {
            return $this->sendResponse('',trans('auth.password_reset_failed'));
        }
        
    }

    /**
    * Auth: Update Device Token
    *
    * @authenticated
    *
    * @bodyParam device_type string required User's device type. Enums : iphone, android. Example:iphone
    * @bodyParam device_token string required User's device token. Example:abcd1234
    * 
    * @response {
    *    "success": "1",
    *    "status": "200",
    *    "message": "Device details updated."
    * }
    */
    public function update_device_token(Request $request) {

        $validator = Validator::make($request->all(), [
            'device_token' => 'required',
            'device_type'  => 'required|in:iphone,android',
        ]);

        if($validator->fails()) {
            return $this->sendError('', $validator->errors()->first());       
        }

        DB::beginTransaction();
        try{

            $user = Auth::guard('api')->user();
            $data = $request->all();
           
            if($this->saveDeviceDetails($data,$user)){
                
                DB::commit();
                return $this->sendResponse('',trans('auth.token_updated'));

            }else{

                DB::rollback();
                return $this->sendError('',trans('auth.api_error'));
            }
            
        }catch(\Exception $e){
            DB::rollback();
            return $this->sendError('',trans('auth.api_error'));
        }
       
    }

    /**
    * Auth: Verify Email OTP
    *
    * @bodyParam otp string required max:6  OTP. Example:1234
    * @bodyParam user_id string required User ID. Example:1
    * @bodyParam email string required   Email. Example:test@mail.com
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "Your email has been verified successfully",
        "data": {
            "token": "eyJ0eXAiOiJKV1QiL...",
            "id": "148",
            "first_name": "John",
            "last_name": "",
            "email": "aaawwwsss@mailinator.com",
            "mobile_number": "1234567804",
            "profile_image": "http://localhost/ssgc-bulk-order-web/public/uploads/media/Consumer_Woman-512_-_Copy_16811582359196.png",
            "country": {
                "id": "1",
                "name": "India",
                "code": "+91",
                "flag": "http://localhost/ssgc-bulk-order-web/public/flags/india.png"
            },
            "referral_code": "",
            "email_verified": "1",
            "phone_verified": "0",
            "is_social_login": "normal",
            "company_name": "John",
            "company_documents": [
                {
                    "id": "1196",
                    "document": "http://localhost/ssgc-bulk-order-web/public/uploads/media/Consumer_Woman-512_-_Copy_16811582353154.png"
                }
            ],
            "company_images": [
                {
                    "id": "1195",
                    "image": "http://localhost/ssgc-bulk-order-web/public/uploads/media/Consumer_Woman-512_-_Copy_16811582357164.png"
                }
            ],
            "user_type": "retailer"
        }
    }

    */
    public function verify_email_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'email'   => 'required|email',
            'otp'     => 'required|digits:6'
        ]);

        if($validator->fails()) {
            return $this->sendError($this->object, $validator->errors()->first());       
        }

        $user = User::find($request->user_id);

        $emailVerifcation = EmailVerification::where(['email' => $request->email,'status' => 'pending'])
                        ->latest() //show the latest if there are multiple
                        ->first();

        if($emailVerifcation == null){
            return $this->sendError($this->object,trans('auth.email_not_found'));
        }


        if($request->otp != $emailVerifcation->code){
            return $this->sendError($this->object,trans('auth.otp_invalid_long'));
        }



        $otp_time_difference_in_minutes = $emailVerifcation->created_at->diffInMinutes(Carbon::now());
        // echo "<pre>";print_r($otp_time_difference_in_minutes);exit;
        //Checking OTP Code Expiry
        if($otp_time_difference_in_minutes > config('adminlte.otp_expiry_in_minutes')) {
            $request["status"] = 'expired';
            $request['code'] = $request['otp'];
            $emailVerifcation->updateModel($request);
            return $this->sendError($this->object,trans('auth.otp_expired_long'));
        }

      
        DB::beginTransaction();
        try {

            $user->email = $request->email;
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->save();

            $request["status"] = 'verified';
            $emailVerifcation->updateModel($request);
             
            DB::commit();
            $user->accessToken = $user->createToken(config('app.name'))->accessToken;
            return $this->sendResponse(new UserResource($user), trans('auth.email_verified'));
        } catch (Exception $e) {
            DB::rollback();
            return $this->sendError($this->object,$e->getMessage());
        }       
       
    }

    /**
    * Auth: Resend Email OTP 
    *
    * @bodyParam user_id string required User ID. Example:1
    * @bodyParam email string required Email. Example:test@mail.com
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "OTP sent to your email ttt123@mailinator.com"
    }

    */
    public function resend_email_otp(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'email'   => 'required|email',
            ]);

            if($validator->fails()) {
                return $this->sendError($this->object, $validator->errors()->first());       
            }

            $emailVerifcation = EmailVerification::where(['email' => $request->email])
                            ->latest() //show the latest if there are multiple
                            ->first();

            if(!$emailVerifcation)
            {
                return $this->sendResponse($this->object, trans('auth.email_not_found'));
            }


            //send & store otp
            $user = User::find($request->user_id);
            $otp = $this->genrateOtp();
            $email = $request->email;
            try
            {   
                $res = $this->sendEmailOtp($email,$user,$otp);
            } catch (\Exception $e) {
              DB::rollback();
              return $this->sendError('',trans('common.email_not_sent'));
            }  
            DB::commit();

            return $this->sendResponse('', trans('auth.otp_sent_email', ['email'=> $request->email]));
        }
        catch (\Exception $e)
        {   
            DB::rollback();
            return $this->sendError('', trans('auth.otp_sent_error'));
        }
    }
}
