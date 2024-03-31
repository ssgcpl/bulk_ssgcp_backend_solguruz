<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'admin/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

     public function login(Request $request)
    {

        $this->validateLogin($request);


        // restricting user from login when he is deactivated by admin :: CODE ADDED BY RONAK :: START 

         $user = User::where('email', $request->email)->first();
         if(!$user) {
              throw ValidationException::withMessages([
                $this->username() => [trans('auth.failed',['contact'=>'admin'])],
                ]);
         }
          $role = Role::where('name',$user->user_type)->where('status','active')->first();
          if(!$role){
                  throw ValidationException::withMessages([
                  $this->username() => [trans('auth.role_blocked',['contact'=>'admin'])],
                  ]);  
          }
          if($user && $user->status =='inactive' ) {
                throw ValidationException::withMessages([
                $this->username() => [trans('auth.account_blocked',['contact'=>'admin'])],
                ]);
          }

        // restricting user from login when he is deactivated by admin  :: CODE ADDED BY RONAK :: END

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request, true)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password','block','0');
    }
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect('admin/login');
    }
}
