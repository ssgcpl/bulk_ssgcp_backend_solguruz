<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VendorType;
use App\Models\User;
use App\Models\VendorProfile;
use App\Models\EmailVerification;
use App\Models\SmsVerification;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Redirect;
use Illuminate\Support\Facades\Input;
use Auth,Hash;
use Carbon\Carbon;
use Validator;
use Notification;
use App\Models\Country;
use App\Models\Helpers\CommonHelper;
use Illuminate\Validation\Rule;
use App\Notifications\SendVerificationEmail;


class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('permission:vendor_profile-edit', ['only' => ['update_vendor_profile','vendor_profile']]);
        
    }
    use CommonHelper;
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile () {
        $page_title = trans('common.profile');
        $id   = Auth::user()->id;
        $admin     = User::where(['id'=>$id])->first();
        return view('profile',compact('admin','page_title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_profile (Request $request) {
        $id = Auth::user()->id;
        $admin = User::find($id);
        $user = User::find($id);
        $this->validate($request,[
            'name'  =>  'required|string|min:3|max:50',
            'email' =>  'required|email|max:255|unique:users,email,'.$id,
            'mobile_number' => 'required|digits:10|numeric|unique:users,mobile_number,'.$id,
            'profile_image' => 'image|mimes:png,jpg,jpeg,svg|max:10000',
        ]);

        if(isset($request->new_password) && $request->new_password != "")
        {
            $this->validate($request,[
                'name'  =>  'required|string|min:3|max:50',
                'email' =>  'required|email|max:255|unique:users,email,'.$id,
                'mobile_number' => 'required|digits:10|numeric|unique:users,mobile_number,'.$id,
                'old_password' => $request->old_password != null ?'sometimes|min:8|max:14':'',
                'new_password' => $request->new_password != null ?'sometimes|min:8|max:14':'',
                'confirm_password'  => $request->new_password != null ?
                'same:new_password|min:8|max:14':'',
                'profile_image' => 'image|mimes:png,jpg,jpeg,svg|max:10000',
            ]);
        }

        DB::beginTransaction();
        try{

            $updateArray = $request->all();
        if(isset($updateArray['old_password']) && $updateArray['old_password'] != null){

            $match = Hash::check($updateArray['old_password'],$admin->password);
            if(!$match){
                return redirect()->back()->withInput($updateArray)->with('error','Invalid old password');
            }

            if($updateArray['new_password'] == null){
                return redirect()->back()->withInput($updateArray)->with('error','Please enter a new password');
            }

            if($updateArray['confirm_password'] == null){
                return redirect()->back()->withInput($updateArray)->with('error','Please confirm a new password');
            }

            if($updateArray['new_password'] != $updateArray['confirm_password']){
                return redirect()->back()->withInput($updateArray)->with('error','The confirmed password must be same as new password');
            }

            $password = Hash::make($updateArray['new_password']);
            $updateArray['password'] = $password;
        }
        else
        {
            if((isset($updateArray['new_password']) && $updateArray['new_password'] != null) || (isset($updateArray['confirm_password']) && $updateArray['confirm_password'] != null) )
            {
                return redirect()->back()->withInput($updateArray)->with('error','Please enter old password to update the new password');
            }
        }

            if($admin->profile_image != null){

                $this->deleteMedia($admin->profile_image);
            }

            $updateArray['profile_image'] = $this->saveMedia($request->profile_image);
            
            $updateArray['first_name']  = $request->name;
            if(!empty($request->new_password)){
                $updateArray['password'] = Hash::make($request->new_password);
            }

            $admin->fill($updateArray);
            if($admin->save()){
            /*if($admin->user_type != 'admin' && $user->email != $request->email){
                
                    $admin->sendEmailVerificationNotification();
                    $admin->email_verified_at = null;
                    $admin->save();
                    return redirect()->route('verification.notice')->with('success', '');
            }*/
                DB::commit();
                return redirect()->back()->with('success', trans('users.profile_update_success'));
            }
            else{

                DB::rollback();
                return redirect()->route('profile')->with('error', trans('users.profile_update_fail'));
            }
        }catch(\Exception $e){

            DB::rollback();
            return redirect()->route('profile')->with('error', $e->getMessage());
        }

    }


    public function admin_update(Request $request)
    {
        $data  = $request->all();
        $user  = Auth::user();
        $admin = User::where(['id' => $user->id,'user_type'=>'admin'])->first();
      

        if(empty($admin)){
          return redirect()->route('home')->with('error','Something went wrong');  
        }

        $validator = $request->validate([
            'name'                 => 'required|max:50',
            //'email'                => ['required','email','max:255',Rule::unique('users','email')->ignore($admin->id)],
            //'mobile_number'        => ['required','numeric','digits:10',Rule::unique('users','mobile_number')->ignore($admin->id)],
            'profile_image'      => 'sometimes|nullable|image|max:10000',
            'old_password'         => 'sometimes|nullable|min:8|max:15',
            'new_password'         => 'sometimes|nullable|min:8|max:15',
            'confirm_password'     => 'sometimes|nullable|min:8|max:15',
            
        ]);

        $data = $request->all();

        if(isset($data['old_password']) && $data['old_password'] != null){

            $match = Hash::check($data['old_password'],$admin->password);

            if(!$match){
                return redirect()->back()->withInput($data)->with('error','Invalid old password');
            }

            if($data['new_password'] == null){
                return redirect()->back()->withInput($data)->with('error','Please enter a new password');
            }

            if($data['confirm_password'] == null){
                return redirect()->back()->withInput($data)->with('error','Please confirm a new password');
            }

            if($data['new_password'] != $data['confirm_password']){
                return redirect()->back()->withInput($data)->with('error','The confirmed password must be same as new password');
            }

            $password = Hash::make($data['new_password']);
            $data['password'] = $password;
        }
        else
        {
            if((isset($data['new_password']) && $data['new_password'] != null) || (isset($data['confirm_password']) && $data['confirm_password'] != null) )
            {
                return redirect()->back()->withInput($data)->with('error','Please enter old password to update the new password');
            }
        }

        if(isset($data['profile_image']) && $data['profile_image'] != null){
            $this->deleteMedia($admin->profile_image);
            $data['profile_image'] = $this->saveMedia($data['profile_image']);
        }
        $data['first_name']  = $request->name;
        if($admin->update($data)){
            return redirect()->route('profile',[$admin])->with('success','Admin Details Updated');
        }else{
            return redirect()->route('home')->with('error','Something went wrong');
        }
    }


    public function update_email(Request $request) 
      {

        $validator = Validator::make($request->all(), [
            'email'         => 'required|email|unique:users|max:100',
        ]);
        if($validator->fails()){
            return ['error' => $validator->errors()->first()];           
        }

        //send and store otp
        $otp = $this->genrateOtp();
       // $otp = mt_rand(100000,999999);
        $emailVerification = new EmailVerification();
        $emailVerification->email = $request->email;
        $emailVerification->code = $otp;
        $emailVerification->status = 'pending';
        $emailVerification->created_at = Carbon::now();
        $emailVerification->save();

        $user = Auth::user();
        $verification_code = $otp;
        $res = Notification::route('mail', trim($request->email))->notify(new SendVerificationEmail($verification_code,$user));
        return [  
                  'success' => [
                   //'data' => new UserResource($user),
                    'data' => $user,
                    'email'   => $request->email,
                    'message' => trans('auth.otp_sent_email',['email'=>$request->email])
                  ]
        ];
    }
    

    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|min:3|max:100',
            'otp'   => 'required|min:6|max:6',
        ]);

        if($validator->fails()) {
            return ['error' => $validator->errors()->first()];    
            // return $this->sendError($this->object, $validator->errors()->first());       
        }

        $user = User::find(Auth::user()->id);

        $emailVerification = EmailVerification::where(['email' => $request->email,'status' => 'pending'])
                        ->latest() //show the latest if there are multiple
                        ->first();

        if($emailVerification == null){
            // return $this->sendError($this->object,trans('auth.email_not_found'));
            return ['error' => trans('auth.email_not_found')];    
        }

        if($request->otp != $emailVerification->code){
            // return $this->sendError($this->object,trans('auth.otp_invalid_long'));
            return ['error' => trans('auth.otp_invalid_long')];    

        }

        $otp_time_difference_in_minutes = $emailVerification->created_at->diffInMinutes(Carbon::now());
      // return $otp_time_difference_in_minutes."/".$emailVerification->created_at->diffInMinutes(Carbon::now());
        //Checking OTP Code Expiry
        if($otp_time_difference_in_minutes >= config('adminlte.otp_expiry_in_minutes')) {
            $request["status"] = 'expired';
            $request['code'] = $request['otp'];
            $emailVerification->updateModel($request);
            return ['error' => trans('auth.otp_expired_long')];    

          //  return $this->sendError($this->object,trans('auth.otp_expired_long'));
        }
      
        DB::beginTransaction();
        try {

            $user->email = $request->email;
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->save();

            $request["status"] = 'verified';
            $emailVerification->updateModel($request);
             
            DB::commit();

            // return $this->sendResponse(new UserResource($user), trans('auth.email_updated'));
            return [  
                  'success' => [
                  //  'data'    => new UserResource($user),
                      'data'    => $user,
                    'message' => trans('auth.email_updated'),
                  ]
            ];

        } catch (Exception $e) {
            DB::rollback();
            // return $this->sendError($this->object,$e->getMessage());
            return ['error' => $e->getMessage()];    

        }           
    }


    public function update_mobile_number(Request $request) {

        /*if(strlen($request->mobile_number) > 9){
           if($request->mobile_number[0] != 0){
                return  ['error' =>  trans('auth.please_add_zero_first_digit')];
            }        
        }*/

        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|unique:users,mobile_number,NULL,id,user_type,admin|digits_between:9,10',
         //   'country_id'    => 'required|exists:countries,id',
        ]);
        if($validator->fails()){
            return ['error' => $validator->errors()->first()];       
        }

       // $user = Auth::guard('customer')->user();
        $user = User::find(Auth::user()->id);
        //send and store otp
        $otp = $this->genrateOtp();
        
        if(!$user->country){
            $country_code = '+91';
        }else {
            $country_code = $user->country->country_code;
        }
        $mobile_number = $request->mobile_number;
        $res = $this->sendOtp($country_code,$mobile_number,$otp);

        $smsVerifcation = new SmsVerification();
        $smsVerifcation->mobile_number = $request->mobile_number;
        $smsVerifcation->code = $otp;
        $smsVerifcation->status = 'pending';
        $smsVerifcation->created_at = Carbon::now();
        $res = $smsVerifcation->save();

      //   $user->mobile_number = $request->mobile_number;
       //  $user->verified = '0';
        // $user->save();
         //$user->token()->revoke();  
        if($res)
        {
            return [  
                      'success' => [
                     // 'data' => new UserResource($user),
                      'data' => $user,
                      'mobile_number'  => $request->mobile_number,
                      'message' => trans('auth.otp_sent_mobile', ['number'=> $request->mobile_number])
                      ]
            ];
        }
        else
        {
            return  ['error' => $this->object,trans('auth.otp_sent_error')];
        }
        
    }

    /**
     * Verify Mobile Number via OTP
     * @return \Illuminate\Http\Response
     */
    public function verifyMobileNumber(Request $request){
        /*if(strlen($request->mobile_number) > 9){
           if($request->mobile_number[0] != 0){
                return  ['error' =>  trans('auth.please_add_zero_first_digit')];
            }        
        }*/
        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|digits_between:9,10',
           // 'country_id'    => 'required|exists:countries,id',
            'otp'           => 'required|min:6|max:6'
        ]);

        if($validator->fails()) {
            return  ['error' => $validator->errors()->first()];       
        }
        $user = User::find(Auth::user()->id);

        $smsVerifcation = SmsVerification::where(['mobile_number' => $request->mobile_number,'status' => 'pending'])
                        ->latest() //show the latest if there are multiple
                        ->first();

        if($smsVerifcation == null){
            return  ['error' => trans('auth.number_not_found')];
        }
/*
        if($user->country_id != $request->country_id){
            return  ['error' =>  trans('auth.otp_wrong_number')];
        }*/

        if($request->otp != $smsVerifcation->code){
            //return  ['error' => $this->object,trans('auth.otp_invalid_long')];
            return  ['error' => trans('auth.otp_invalid_long')];

        }

        $otp_time_difference_in_minutes = $smsVerifcation->created_at->diffInMinutes(Carbon::now());

        //Checking OTP Code Expiry
        if($otp_time_difference_in_minutes >= config('adminlte.otp_expiry_in_minutes')) {
            $request["status"] = 'expired';
            $request['code'] = $request['otp'];
            $smsVerifcation->updateModel($request);
          //  return  ['error' => $this->object,trans('auth.otp_expired_long')];
              return  ['error' => trans('auth.otp_expired_long')];
        }

        DB::beginTransaction();
        try {

            $user->mobile_number = $request->mobile_number;
            $user->verified = '1';
            $user->save();

            $request["status"] = 'verified';
            $smsVerifcation->updateModel($request);
             
            DB::commit();
            return [ 
                      'success' => [
                       // 'data' => new UserResource($user),
                        'data' => $user,
                        'message' => trans('auth.mobile_changed')
                      ]
            ];
        } catch (Exception $e) {
            DB::rollback();
            return  ['error' =>$e->getMessage()];
        }       
       
    }

    public function resend_email_otp(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'email'  => 'required|email',
            ]);

            if($validator->fails()) {
                // return $this->sendError($this->object, $validator->errors()->first());
                return ['error' => $validator->errors()->first()];    

            }

            $emailVerifcation = EmailVerification::where(['email' => $request->email])
                            ->latest() //show the latest if there are multiple
                            ->first();

            if(!$emailVerifcation)
            {
                // return $this->sendResponse($this->object, trans('auth.email_not_found'));
                return ['error' => trans('auth.email_not_found')];    

            }

      /*  $otp_time_difference_in_minutes = $emailVerifcation->created_at->diffInMinutes(Carbon::now());
        //Checking OTP Code Expiry
        if($otp_time_difference_in_minutes < config('adminlte.otp_expiry_in_minutes')) {
         return ['error' => 'old otp is still valid.. please use it'];

        }*/


            //resend otp to email and update 
            $otp = $this->genrateOtp();
            $emailVerifcation->code   = $otp;
            $emailVerifcation->status = 'pending';
            $emailVerifcation->created_at = Carbon::now();
            $emailVerifcation->save();
            DB::commit();
            $user = Auth::user();
            $verification_code = $otp;
            $res = Notification::route('mail', trim($request->email))->notify(new SendVerificationEmail($verification_code,$user));
            // return $this->sendResponse('', trans('auth.otp_sent_email', ['email'=> $request->email]));
            return [  
                  'success' => [
                    'data' => "",
                    'message' => trans('auth.otp_sent_email', ['email'=> $request->email]),
                  ]
            ];
        }
        catch (\Exception $e)
        {   
            DB::rollback();
            $code = $e->getCode();
            $message = $e->getMessage();
            $response = array('code'=>$code,'message'=>$message);
            // return $this->sendError('', trans('auth.otp_sent_error', $response));
            return ['error' => trans('auth.otp_sent_error', $response)];    

        }
    }

    public function resend_mobile_otp(Request $request)
    {
        DB::beginTransaction();
        // try {
            $validator = Validator::make($request->all(), [
                'mobile'  => 'required',
            ]);

            if($validator->fails()) {
                // return $this->sendError($this->object, $validator->errors()->first());
                return ['error' => $validator->errors()->first()];    
             }


          
            $smsVerifcation = SmsVerification::where(['mobile_number' => $request->mobile,'status' => 'pending'])
                        ->latest() //show the latest if there are multiple
                        ->first();

            if($smsVerifcation == null){
              $smsVerifcation = new SmsVerification();
            }

        // $otp_time_difference_in_minutes = $smsVerifcation->created_at->diffInMinutes(Carbon::now());
         $otp_time_difference_in_minutes = $smsVerifcation->created_at->diffInMinutes(Carbon::now());
        //return $otp_time_difference_in_minutes."/".config('adminlte.otp_expiry_in_minutes');
            //Checking OTP Code Expiry
        if($otp_time_difference_in_minutes < config('adminlte.otp_expiry_in_minutes')) {

         return ['error' => 'old otp is still valid.. please use it','time'=>$otp_time_difference_in_minutes];
        }


            //resend otp to mobile
            $otp = $this->genrateOtp();
          
            $smsVerifcation->mobile_number = $request->mobile;
            $smsVerifcation->code   = $otp;
            $smsVerifcation->status = 'pending';
            $smsVerifcation->created_at = Carbon::now();
            $smsVerifcation->save();
            DB::commit();
            $user = Auth::user();
            $verification_code = $otp;
                if(!$user->country){
                    $country_code = '+91';
                }else {
                    $country_code = $user->country->country_code;
                }
                $mobile_number = $request->mobile;
                $res = $this->sendOtp($country_code,$mobile_number,$otp);
            // $res = Notification::route('mail', trim($request->mobile))->notify(new SendVerificationSms($verification_code,$user));
            // return $this->sendResponse('', trans('auth.otp_sent_email', ['email'=> $request->email]));
            return [  
                  'success' => [
                    'data' => $request->mobile,
                    'message' => trans('auth.otp_sent_mobile',['number'=> $request->mobile]),
                  ]
            ];
        // }
        // catch (\Exception $e)
        // {   
        //     DB::rollback();
        //     $code = $e->getCode();
        //     $message = $e->getMessage();
        //     $response = array('code'=>$code,'message'=>$message);
        //     // return $this->sendError('', trans('auth.otp_sent_error', $response));
        //     return ['error' => trans('auth.otp_sent_error', $response)];    

        // }
    }


}