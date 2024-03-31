<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Profile;
use App\Models\Country;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use DB;
use Spatie\Permission\Models\Role;
use Auth;
use App\Models\Helpers\CommonHelper;


class RegisterController extends Controller
{
  use CommonHelper;
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


     public function register(Request $request)
    {
          $validator= $request->validate([
              'full_name'       => 'required|string|min:2|max:100',
              'mobile_number'   => 'required|numeric|digits:10|unique:users,mobile_number',
              'email'           => 'required|string|email|max:255|unique:users',
              'password'        => 'required|string|same:password_confirmation|min:8|max:14',    
          ]);
          // [
          //     'password.regex'  => 'Password Must Contain Upper-case, Lower-case, Number and Special characters Like (~!@#$%^&*()_+=-?.',
          // ]);  

        DB::beginTransaction();

        if(isset($request->full_name)){
          $first_name = $request->full_name;
        }
        $userArray = [
            'first_name'     => $first_name,
            'email'          => $request->email,
            'mobile_number'  => $request->mobile_number,
            'registered_on'  => 'web',
            'status'         => 'active',
            'user_type'         => 'vendor',
            'password'       => Hash::make($request->password),
         //   'country_id'  => Country::where('country_code','+1')->first()->id,
        ];
        $user = User::create($userArray);
        $role = Role::where('name','customer')->first();
        $user->assignRole([$role->id]);

        $vendorArray = [
            'user_id'          => $user->id,
            'user_type'        => 'vendor',
            'store_descrition:en' => '',
            'store_descrition:ar' =>'',
        ];
          $admin_user     = User::where(['user_type' => 'admin'])->first();
          $title    = trans('notify.new_service_provider_registered');
          $body     = trans('notify.new_service_provider_registered_body', [
              'vendor_name'   => $user->first_name,
          ]);
          $slug     = 'new_service_provider_registered';
          $this->sendNotification($admin_user,$title,$body,$slug);


         DB::commit();
          Auth::login($user);
          return redirect()->route('home')->with('message',trans('auth.registered_successfully'));
        
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'min:3', 'max:255'],
            'last_name' => ['required', 'string','min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'mobile_number' => 12345678,
            'user_type' => 'admin',
            'password' => Hash::make($data['password']),
        ]);
    }
}
