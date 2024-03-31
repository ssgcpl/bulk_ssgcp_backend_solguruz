<?php

namespace App\Http\Controllers\Api\Customer\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\FollowerFollowing;

class VerificationController extends BaseController {

    public function __construct() {
        $this->middleware('auth:api')->except(['verify']);
    }

    /**
     * Verify email
     *
     * @param $user_id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function verify($user_id, Request $request) {
        if (! $request->hasValidSignature()) {
            Auth::logout();
            return redirect('/admin/login')->with('error',trans('auth.verification_link_expired_or_invalid'));
        }

        $user = User::findOrFail($user_id);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
        // $user->email = $request->email;
        // $user->save();
        return redirect(url('/admin/login'))->with('success',trans('auth.email_verified_successfully'));
    }

    /**
     * Resend email verification link
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function resend() {
        if (auth()->user()->hasVerifiedEmail()) {
            return $this->respondBadRequest(254);
        }

        auth()->user()->sendEmailVerificationNotification();

        return $this->respondWithMessage("Email verification link sent on your email id");
    }
}
