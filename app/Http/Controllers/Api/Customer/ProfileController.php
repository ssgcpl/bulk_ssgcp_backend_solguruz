<?php
namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Models\User;
use App\Models\City;
use App\Models\SmsVerification;
use App\Models\EmailVerification;
use App\Models\CompanyDocImage;
use Carbon\Carbon;
use Validator,DB,Notification;
use Illuminate\Validation\Rule;
use App\Http\Resources\Customer\UserResource;
use App\Models\Helpers\CommonHelper;
use App\Mail\SendVerificationEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Authy\AuthyApi;
use App\Models\ReferHistory;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @group Customer Endpoints
 *
 * Customer Apis
 */
class ProfileController extends BaseController
{
    use CommonHelper;

    /**
    * Profile: Profile Details
    *
    * @authenticated
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "My Profile",
        "data": {
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
    public function profile(Request $request)
    {
        $user = Auth::guard('api')->user();
        return $this->sendResponse(new UserResource($user), trans('profile.profile_details'));
    }

    /**
    * Profile: Update Personal Details
    *
    * @authenticated
    *
    * @bodyParam first_name string required max:50  First Name. Example:John
    * @bodyParam last_name string optional max:50  Last Name. Example:John
    * @bodyParam company_name string required Full name. Example:John
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "Profile details has been updated successfully",
        "data": {
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
    public function update_personal_details(Request $request)
    {
        $id = Auth::guard('api')->user()->id;
        $validator = Validator::make($request->all(), [
            'first_name'   => 'nullable|string|min:3|max:50',
            'last_name'    => 'nullable|string|min:3|max:50',
            'company_name' => 'nullable|string|min:3|max:50',
        ],[
          'first_name.min'      => 'The full name must be atleast 3 characters.',
          'first_name.max'      => 'The full name must not be greater than 50 characters.',
          'first_name.required' => 'The full name is required.',
        ]);
        if($validator->fails()){
            return $this->sendValidationError('', $validator->errors()->first());       
        }
        
        DB::beginTransaction();
        try{
            $user = Auth::guard('api')->user();     
            $updateArray = [
                'first_name'   => $request->first_name,
                'last_name'    => $request->last_name,
                'company_name' => $request->company_name,
            ];
            
            $user->fill($updateArray);
            if($user->save()){
                DB::commit();
                return $this->sendResponse(new UserResource($user), trans('profile.profile_update_success'));
            }else{
                DB::rollback();
                return $this->sendError('',trans('profile.profile_update_error'));
            }
        }catch(\Exception $e){
            DB::rollback();
            return $this->sendError('',$e->getMessage());           
        }
    }

    /**
    * Profile: Update Profile Image 
    *
    * @authenticated
    *
    * @bodyParam profile_image file required The image.
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "Profile details has been updated successfully",
        "data": {
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
    public function update_image(Request $request)
    {
        $id = Auth::guard('api')->user()->id;
        $validator = Validator::make($request->all(), [
            'profile_image' => 'required|image|mimes:png,jpg,jpeg,svg|max:10000',
        ]);
        if($validator->fails()){
            return $this->sendValidationError('', $validator->errors()->first());       
        }
        
        DB::beginTransaction();
        try{
            $user = Auth::guard('api')->user();
            $updateArray = array();
            if(isset($request->profile_image)){
                if(file_exists($user->profile_image)){
                  unlink($user->profile_image);
                }
              $path = $this->saveMedia($request->file('profile_image'),'profile');
              $updateArray['profile_image'] = $path;
            }
            $user->fill($updateArray);
            if($user->save()){
                DB::commit();
                return $this->sendResponse(new UserResource($user), trans('profile.profile_update_success'));
            }else{
                DB::rollback();
                return $this->sendError('',trans('profile.profile_update_error'));
            }
        }catch(\Exception $e){
            DB::rollback();
            return $this->sendError('',$e->getMessage());           
        }
    }

    /**
    * Profile: Update Company Docs & Images 
    *
    * @authenticated
    *
    * @bodyParam company_images[] file required Company Images.
    * @bodyParam company_documents[] file required Company Documents.
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "Profile details has been updated successfully",
        "data": {
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
    public function update_company_images(Request $request)
    {
        if(!isset($request->platform))
        {
            $validator = Validator::make($request->all(), [
                'company_images'      => 'required|array',
                'company_images.*'    => 'required|image|mimes:png,jpg,jpeg,svg|max:10000',
                'company_documents'   => 'required|array',
                'company_documents.*' => 'required|image|mimes:png,jpg,jpeg,svg|max:10000',
            ]);

            if($validator->fails()){
                return $this->sendValidationError('', $validator->errors()->first());       
            }
        }
        else
        {
            if(!$request->company_images)
            {
                return $this->sendError('', 'Company images field is required.'); 
            }

            if(!$request->company_documents)
            {
                return $this->sendError('', 'Company documents field is required.'); 
            }
        }
        
        DB::beginTransaction();
        try{
            $user = Auth::guard('api')->user();
            $updateArray = array();
            
            //uplaod & save company docs & image
            CompanyDocImage::where('company_id',$user->id)->delete();
            
            if(count($request->company_images)){
                if(isset($request->platform))
                {
                    if($request->platform == 'web')
                    {
                        foreach ($request->company_images as $base64File) {

                            // decode the base64 file
                            $file = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64File));
                            $filename = uniqid().'_'.time().'.png';
                            $path = 'uploads/media/'.$filename;
                            $success = file_put_contents(public_path().'/'.$path, $file);
                            CompanyDocImage::create(['company_id' => $user->id,'images'=>$path]);
                        }
                    }
                }
                else
                {
                    $files  =  $request->file('company_images');
                    foreach ($files as $file) { 
                        $path = $this->saveMedia($file,'profile');
                        CompanyDocImage::create(['company_id' => $user->id,'images'=>$path]);
                    }
                }
            }

            if(count($request->company_documents)){
                if(isset($request->platform))
                {
                    if($request->platform == 'web')
                    {
                        foreach ($request->company_documents as $base64File) {

                            // decode the base64 file
                            $file = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64File));

                            $filename = uniqid().'_'.time().'.png';
                            $path = 'uploads/media/'.$filename;
                            $success = file_put_contents(public_path().'/'.$path, $file);
                            CompanyDocImage::create(['company_id' => $user->id,'documents'=>$path]);
                        }
                    }
                }
                else
                {
                    $files  =  $request->file('company_documents');
                    foreach ($files as $file) { 
                        $path = $this->saveMedia($file,'profile');
                        CompanyDocImage::create(['company_id' => $user->id,'documents'=>$path]);
                    }
                }
                
            }

            DB::commit();
            return $this->sendResponse(new UserResource($user), trans('profile.profile_update_success'));
            
        }catch(\Exception $e){
            DB::rollback();
            return $this->sendError('',$e->getMessage());           
        }
    }

    /**
    * Profile: Update Mobile Number 
    *
    * @authenticated
    *
    * @bodyParam mobile_number string required  max:10  Mobile Number. Example:1234567890
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "OTP sent to your mobile number 1112223332",
        "data": {
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
    public function update_mobile_number(Request $request) {
        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|digits:10|unique:users,mobile_number',
            // 'country_id'    => 'required|exists:countries,id',
        ]);
        if($validator->fails()){
            return $this->sendValidationError('', $validator->errors()->first());       
        }
        $request->merge([
            'country_id' => 1,
        ]);
        $user = Auth::guard('api')->user();

        //send and store otp
        $otp = $this->genrateOtp();
        $country_code = $user->country_code;
        $mobile_number = $request->mobile_number;
        $res = $this->sendOtp($country_code,$mobile_number,$otp);


        return $this->sendResponse(new UserResource($user), trans('auth.otp_sent_mobile',['number'=>$request->mobile_number]));
    }

    /**
    * Profile: Verify Mobile Number 
    *
    * @authenticated
    *
    * @bodyParam mobile_number string required  max:10  Mobile Number. Example:123456789
    * @bodyParam otp string required max:4  OTP. Example:1234
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "Your mobile number has been updated successfully",
        "data": {
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
    public function verifyMobileNumber(Request $request)
    {
        $validator = Validator::make($request->all(), [
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

        $user = User::find(Auth::guard('api')->user()->id);

        $smsVerifcation = SmsVerification::where(['mobile_number' => $request->mobile_number,'status' => 'pending'])
                        ->latest() //show the latest if there are multiple
                        ->first();

        if($smsVerifcation == null){
            return $this->sendError($this->object,trans('auth.number_not_found'));
        }

        if($user->country_id != $request->country_id){
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

            $user->mobile_number = $request->mobile_number;
            $user->verified = '1';
            $user->save();

            $request["status"] = 'verified';
            $smsVerifcation->updateModel($request);
             
            DB::commit();
            return $this->sendResponse(new UserResource($user), trans('auth.mobile_changed'));
        } catch (Exception $e) {
            DB::rollback();
            return $this->sendError($this->object,$e->getMessage());
        }       
       
    }

    /**
    * Profile: Update Email Address 
    *
    * @authenticated
    *
    * @bodyParam email string optional max:190  Email. Example:John@gmail.com
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "OTP sent to your email testmmm1@mailinator.com",
        "data": {
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
    public function update_email(Request $request) {
        $user = Auth::guard('api')->user();
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:190|unique:users,email,'.$user->id.',id',
        ]);
        if($validator->fails()) {
            return $this->sendError($this->object, $validator->errors()->first());       
        }


        // Check Email Changed
        if($user->email_verified_at != null){
          if($user->email == $request->email){
            return $this->sendError('',trans('auth.same_email'));
          }
        }

        $user_email = User::where('email' , $request->email)->where('user_type','=','customer')->where('id','!=',$user->id)->first();

        $updateArray = $request->all();
        $user->fill($updateArray);
        if($user->save()){
            //send & store otp
            $otp = $this->genrateOtp();
            $email = $request->email;
            $res = $this->sendEmailOtp($email,$user,$otp);
            $user->email_verified_at = null;
            $user->save();
        }

        return $this->sendResponse(new UserResource($user), trans('auth.otp_sent_email',['email'=>$request->email]));
    }

    /*public function update_email(Request $request) {
        $user = Auth::guard('api')->user();
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:190|unique:users,email,'.$user->id,
        ]);
        if($validator->fails()) {
            return $this->sendError($this->object, $validator->errors()->first());       
        }


          // Check Email Changed 
          if($user->email == $request->email && $user->email_verified_at){
                return $this->sendError('',trans('auth.same_email'));
          }

          $user_email = User::where('email' , $request->email)->where('user_type','=','customer')->where('id','!=',$user->id)->first();

          $updateArray = $request->all();
          $user->fill($updateArray);
          if($user->save()){
          $res  = $user->sendEmailVerificationNotification();
                  $user->email_verified_at = null;
                  $user->save();
          }

          return $this->sendResponse(new UserResource($user), trans('auth.verification_link',['email'=>$request->email]));
    }*/
    
    /**
    * Profile: Resend Mobile  OTP 
    *
    * @authenticated
    *
    * @bodyParam mobile_number string required min:10 max:10  Mobile Number. Example:123456789
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "OTP sent to your mobile number 1112223331"
    }

    */
    public function resend_mobile_otp(Request $request)
    {
        DB::beginTransaction();
        try
            {
            $validator = Validator::make($request->all(), [
                'mobile_number' => 'required|digits:10',
                // 'country_id'    => 'required|exists:countries,id',
            ]);

            if($validator->fails()) {
                return $this->sendError($this->object, $validator->errors()->first());       
            }

            $request->merge([
                'country_id' => 1,
            ]);
            
            $user = User::find(Auth::guard('api')->user()->id);

            if($user->country_id != $request->country_id){
                return $this->sendError('', trans('auth.otp_wrong_number'));
            }

            $smsVerification = SmsVerification::where(['mobile_number' => $request->mobile_number])
                            ->latest() //show the latest if there are multiple
                            ->first();


            if(!$smsVerification)
            {
                return $this->sendResponse($this->object, trans('auth.number_or_email_not_found'));
            }

            if($smsVerification)
            {
                //resend otp to mobile number and update 
                $otp = $this->genrateOtp();
                $country_code = $user->country->country_code;
                $mobile_number = $request->mobile_number;
                $res = $this->sendOtp($country_code,$mobile_number,$otp,$smsVerification);
            }
            
            DB::commit();
            return $this->sendResponse('', trans('auth.otp_sent_mobile', ['number'=> $request->mobile_number]));
        }
        catch (\Exception $e)
        {   
            // echo "<pre>";print_r($e->getMessage());exit;
            DB::rollback();
            return $this->sendError('', trans('auth.api_error'));
        }
    }
    
    /**
    * Profile: Verify Email Address 
    *
    * @authenticated
    *
    * @bodyParam otp string required max:6  OTP. Example:1234
    * @bodyParam email string required   Email. Example:test@mail.com
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "Your email has been updated successfully",
        "data": {
            "id": "148",
            "first_name": "John",
            "last_name": "",
            "email": "testttwww@mailinator.com",
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
    public function verify_email(Request $request)
    {
        // echo "<pre>";print_r($request->all());exit;
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp'   => 'required|digits:6'
        ]);

        if($validator->fails()) {
            return $this->sendError($this->object, $validator->errors()->first());       
        }

        $user = User::find(Auth::user()->id);

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
            $user->verified = '1';
            $user->save();

            $request["status"] = 'verified';
            $emailVerifcation->updateModel($request);
             
            DB::commit();
            return $this->sendResponse(new UserResource($user), trans('auth.email_updated'));
        } catch (Exception $e) {
            DB::rollback();
            return $this->sendError($this->object,$e->getMessage());
        }       
       
    }

    /**
    * Profile: Resend Email  OTP 
    *
    * @authenticated
    *
    * @bodyParam email string required   Email. Example:test@mail.com
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
                'email'  => 'required|email',
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
            $user = Auth::guard('api')->user();
            $otp = $this->genrateOtp();
            $email = $request->email;
            $res = $this->sendEmailOtp($email,$user,$otp);
            DB::commit();

            return $this->sendResponse('', trans('auth.otp_sent_email', ['email'=> $request->email]));
        }
        catch (\Exception $e)
        {   
            DB::rollback();
            return $this->sendError('', trans('auth.otp_sent_error'));
        }
    }

    /**
    * Profile: Change Password 
    *
    * @authenticated
    *
    * @bodyParam old_password string required Old Password. Example:12345678
    * @bodyParam new_password string required New Password. Example:12345678
    * @bodyParam confirm_password string required Confirm Password. Example:12345678
    * 
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "Password updated successfully"
    }

    */
    public function change_password(Request $request)
    {
        $input = $request->all();
        $userid = Auth::guard('api')->user()->id;
        $rules = array(
          //  'old_password'     => 'required|min:8|max:16',
            'new_password'     => 'required|min:8|max:16',
            'confirm_password' => 'required|same:new_password',
        );
        $validator = Validator::make($input, $rules);
        if($validator->fails()){
            return $this->sendValidationError('', $validator->errors()->first());       
        } else {
            try {
                if (request('old_password')!='' && (Hash::check(request('old_password'), Auth::guard('api')->user()->password)) == false) {
                    $arr = $this->sendError('', trans('profile.old_password_wrong'));
                } else if ((Hash::check(request('new_password'), Auth::guard('api')->user()->password)) == true) {
                    $arr = $this->sendError('', trans('profile.pasword_same_as_current'));
                } else {
                    User::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);
                    $arr = $this->sendResponse('', trans('profile.password_success'));
                }
            } catch (\Exception $ex) {
                $arr = $this->sendError('', trans('profile.change_password_error'));
          }
      }
      return $arr;
    }

    /**
    * Profile: Earn Accounts
    *
    * @authenticated
    *
    * @bodyParam type string required history,earn,redeem
    * 
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "Data Found Successfully",
      "data": {
        "balance_points": "0",
        "data": [
            {
                "type": "Welcome Points",
                "date": "01 Jul, 2022",
                "points": "10"
            },
            {
                "type": "Redeem Points",
                "date": "01 Jul, 2022",
                "points": "-10"
            }
        ]
      }
    }
    */
    public function earn_accounts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:history,earn,redeem',
        ]);
        if($validator->fails()){
            return $this->sendValidationError('', $validator->errors()->first());       
        }
        try{
            $user = Auth::guard('api')->user();
            $referHistory = ReferHistory::where('customer_id', $user->id)->orderBy('created_at','DESC');

            $data = [];
            if($request->type == 'history'){
                foreach ($referHistory->get() as $key => $value) {
                    $sign = ($value->point_status == 'deducted') ? '-' : '';
                    $type = ($value->referrer_id === null) ? "Welcome Points" : "Earned Points";
                    if($value->point_status == 'deducted') {
                      $type = 'Redeem Points';
                    }
                    if($value->refunded == '1') {
                        $type = 'Refunded Points';
                    }


                    $order_id = (string)$value->order_id ?? '';
                    $customer_image = '';
                    $customer_name = '';
                    if($value->referrer_id)
                    {
                        $referrer_user  = User::find($value->referrer_id);
                        $customer_image = $referrer_user->profile_image ? asset($referrer_user->profile_image) : asset('customer_avtar.jpg');
                        $customer_name  = $referrer_user->first_name;
                    }

                    $wishlist_id = '';
                    if($value->wishlist_id)
                    {
                        $type = 'Wishlist Points';
                        $wishlist_id = (string)$value->wishlist_id;
                    }

                    $wish_return_id = '';
                    if($value->wish_return_id)
                    {
                        $type = 'Wish Return Points';
                        $wish_return_id = (string)$value->wish_return_id;
                    }

                    $data[] = [
                        'type'           => $type,
                        'date'           => (string)date('d M Y, h:i A', strtotime($value->created_at)),
                        'points'         => $sign.(string)(integer)$value->points,
                        'order_id'       => $order_id,
                        'customer_name'  => $customer_name,
                        'customer_image' => $customer_image,
                        'wishlist_id'    => $wishlist_id,
                        'wish_return_id' => $wish_return_id
                    ];
                }
            }
            if($request->type == 'earn'){
                foreach ($referHistory->where('point_status','added')->get() as $key => $value) 
                {
                    $type = ($value->referrer_id === null) ? "Welcome Points" : "Earned Points";
                    if($value->refunded == '1') {
                        $type = 'Refunded Points';
                    }
                    

                    $order_id = '';
                    $customer_image = '';
                    $customer_name = '';
                    if($value->referrer_id)
                    {
                        $referrer_user  = User::find($value->referrer_id);
                        $customer_image = $referrer_user->profile_image ? asset($referrer_user->profile_image) : asset('customer_avtar.jpg');
                        $customer_name  = $referrer_user->first_name;
                    }

                    $wishlist_id = '';
                    if($value->wishlist_id)
                    {
                        $type = 'Wishlist Points';
                        $wishlist_id = (string)$value->wishlist_id;
                    }

                    $wish_return_id = '';
                    if($value->wish_return_id)
                    {
                        $type = 'Wish Return Points';
                        $wish_return_id = (string)$value->wish_return_id;
                    }

                    $data[] = [
                        'type'           => $type,
                        'date'           => (string)date('d M Y, h:i A', strtotime($value->created_at)),
                        'points'         => (string)(integer)$value->points,
                        'order_id'       => $order_id,
                        'customer_name'  => $customer_name,
                        'customer_image' => $customer_image,
                        'wishlist_id'    => $wishlist_id,
                        'wish_return_id' => $wish_return_id
                    ];
                }
            }
            if($request->type == 'redeem'){
                foreach ($referHistory->where('point_status','deducted')->get() as $key => $value) 
                {
                    if($value->refunded == '1') {
                        $type = 'Refunded Points';
                    }

                    $order_id = (string)$value->order_id ?? '';
                    $customer_image = '';
                    $customer_name = '';
                    $wishlist_id = '';
                    $wish_return_id = '';

                    $data[] = [
                        'type'           => 'Redeem Points',
                        'date'           => (string)date('d M Y, h:i A', strtotime($value->created_at)),
                        'points'         => '-'.(integer)$value->points,
                        'order_id'       => $order_id,
                        'customer_name'  => $customer_name,
                        'customer_image' => $customer_image,
                        'wishlist_id'    => $wishlist_id,
                        'wish_return_id' => $wish_return_id
                    ];
                }
            }
            $response = [
                'balance_points' => (string)(integer)$user->points,
                'data' => $data
            ];
            return $this->sendResponse($response, trans('common.data_found'));
      }catch(\Exception $e) {
          return $this->sendError('',trans('common.something_went_wrong'));
      }
    }

}
