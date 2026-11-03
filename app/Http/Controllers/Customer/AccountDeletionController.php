<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\DeviceDetail;
use App\Models\Helpers\CommonHelper;
use App\Models\SmsVerification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;

/**
 * Public "Delete Account" page (Google Play / App Store data-deletion policy).
 * Lets a customer delete their account from the browser, verified by mobile OTP,
 * without logging in to the app.
 */
class AccountDeletionController extends Controller
{
    use CommonHelper;

    const SESSION_KEY = 'account_deletion_mobile';

    // OTP limits per mobile number
    const RESEND_COOLDOWN_SECONDS = 60;   // wait between two OTPs
    const MAX_OTP_PER_WINDOW      = 3;    // max OTPs ...
    const OTP_WINDOW_SECONDS      = 3600; // ... per hour

    const MAX_REQUESTS_PER_IP_PER_MINUTE = 10;
    const MAX_WRONG_OTP_ATTEMPTS         = 5;  // per OTP; then the OTP is invalidated

    public function index(Request $request)
    {
        $page_title = 'Delete Account';
        $mobile_number = $request->session()->get(self::SESSION_KEY);

        $resend_in = 0;           // seconds until "Resend OTP" is enabled
        $limit_reached_in = 0;    // seconds until a new OTP can be requested (0 = resends still available)
        if ($mobile_number) {
            $hourlyKey = $this->hourlyKey($mobile_number);
            $resend_in = $this->cooldownRemaining($mobile_number);
            if (RateLimiter::tooManyAttempts($hourlyKey, self::MAX_OTP_PER_WINDOW)) {
                $limit_reached_in = RateLimiter::availableIn($hourlyKey);
            }
        }

        return view('customer.delete_account', compact('page_title', 'mobile_number', 'resend_in', 'limit_reached_in'));
    }

    /**
     * Single POST endpoint for every step, so the browser always stays on /delete-account.
     */
    public function handle(Request $request)
    {
        // Basic per-IP protection (friendly message instead of a 429 page)
        $ipKey = 'delete-account-ip:' . $request->ip();
        if (RateLimiter::tooManyAttempts($ipKey, self::MAX_REQUESTS_PER_IP_PER_MINUTE)) {
            $wait = $this->formatWait(RateLimiter::availableIn($ipKey));
            return $this->backToPage()->withErrors(['general' => "Too many requests. Please try again after {$wait}."]);
        }
        RateLimiter::hit($ipKey, 60);

        switch ($request->input('action')) {
            case 'send_otp':
                return $this->send_otp($request);
            case 'confirm':
                return $this->confirm($request);
            default: // 'cancel'
                $request->session()->forget(self::SESSION_KEY);
                return $this->backToPage();
        }
    }

    private function send_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|digits:10',
        ]);
        if ($validator->fails()) {
            return $this->backToPage()->withInput()->withErrors($validator);
        }

        $user = $this->findDeletableUser($request->mobile_number);
        if (!$user) {
            return $this->backToPage()->withInput()->withErrors([
                'mobile_number' => 'No active account is registered with this mobile number.',
            ]);
        }

        $mobile_number = $user->mobile_number;

        // 1 minute wait between OTPs
        $wait = $this->cooldownRemaining($mobile_number);
        if ($wait > 0) {
            return $this->backToPage()->withInput()->withErrors(['general' => "Please wait {$wait} seconds before requesting a new OTP."]);
        }

        // Max 3 OTPs per hour
        $hourlyKey = $this->hourlyKey($mobile_number);
        if (RateLimiter::tooManyAttempts($hourlyKey, self::MAX_OTP_PER_WINDOW)) {
            $wait = $this->formatWait(RateLimiter::availableIn($hourlyKey));
            return $this->backToPage()->withInput()->withErrors(['general' => "OTP limit exceeded. Please try again after {$wait}."]);
        }

        $country_code = optional($user->country)->country_code ?: '+91';
        $sent = $this->sendOtp($country_code, $mobile_number, $this->genrateOtp());

        if (!$sent) {
            return $this->backToPage()->withInput()->withErrors([
                'mobile_number' => trans('auth.otp_sent_error'),
            ]);
        }

        // Count only OTPs that were actually sent
        RateLimiter::hit($hourlyKey, self::OTP_WINDOW_SECONDS);
        RateLimiter::hit($this->cooldownKey($mobile_number), self::RESEND_COOLDOWN_SECONDS);
        RateLimiter::clear($this->failKey($mobile_number)); // fresh OTP => fresh wrong-attempt counter

        $request->session()->put(self::SESSION_KEY, $mobile_number);

        return $this->backToPage()
            ->with('success', trans('auth.otp_sent_mobile', ['number' => $mobile_number]));
    }

    private function confirm(Request $request)
    {
        $mobile_number = $request->session()->get(self::SESSION_KEY);
        if (!$mobile_number) {
            return $this->backToPage();
        }

        $validator = Validator::make($request->all(), [
            'otp'     => 'required|digits:6',
            'confirm' => 'accepted',
        ], [
            'confirm.accepted' => 'Please confirm that you understand your account will be permanently deleted.',
        ]);
        if ($validator->fails()) {
            return $this->backToPage()->withErrors($validator);
        }

        $user = $this->findDeletableUser($mobile_number);
        if (!$user) {
            $request->session()->forget(self::SESSION_KEY);
            return $this->backToPage()->withErrors([
                'mobile_number' => 'No active account is registered with this mobile number.',
            ]);
        }

        $sms = SmsVerification::where(['mobile_number' => $mobile_number, 'status' => 'pending'])
            ->latest()
            ->first();

        if (!$sms) {
            return $this->backToPage()->withErrors(['otp' => 'OTP has expired. Please request a new OTP.']);
        }

        // Brute-force protection: limit wrong guesses per mobile number (independent of IP/session)
        $failKey = $this->failKey($mobile_number);
        if (!hash_equals((string) $sms->code, (string) $request->otp)) {
            RateLimiter::hit($failKey, self::OTP_WINDOW_SECONDS);
            if (RateLimiter::tooManyAttempts($failKey, self::MAX_WRONG_OTP_ATTEMPTS)) {
                $sms->update(['status' => 'expired']);
                RateLimiter::clear($failKey);
                return $this->backToPage()->withErrors(['otp' => 'Too many wrong attempts. This OTP is no longer valid, please request a new OTP.']);
            }
            return $this->backToPage()->withErrors(['otp' => trans('auth.otp_invalid_long')]);
        }

        if ($sms->created_at->diffInMinutes(Carbon::now()) > config('adminlte.otp_expiry_in_minutes')) {
            $sms->update(['status' => 'expired']);
            return $this->backToPage()->withErrors(['otp' => trans('auth.otp_expired_long')]);
        }

        DB::transaction(function () use ($user, $sms) {
            $user->status = 'deleted';
            $user->save();

            // Stop push notifications and invalidate every app login (Passport).
            DeviceDetail::where('user_id', $user->id)->delete();
            $user->tokens()->update(['revoked' => true]);

            $sms->update(['status' => 'verified']);
        });

        Log::info('Account deleted via public delete-account page', [
            'user_id' => $user->id,
            'ip'      => $request->ip(),
        ]);

        $request->session()->forget(self::SESSION_KEY);

        return $this->backToPage()->with('deleted', true);
    }

    /**
     * Every step returns to the same URL: /delete-account
     */
    private function backToPage()
    {
        return redirect()->route('delete_account');
    }

    private function hourlyKey($mobile_number)
    {
        return 'delete-account-otp-hourly:' . $mobile_number;
    }

    private function failKey($mobile_number)
    {
        return 'delete-account-otp-fail:' . $mobile_number;
    }

    private function cooldownKey($mobile_number)
    {
        return 'delete-account-otp-cooldown:' . $mobile_number;
    }

    private function cooldownRemaining($mobile_number)
    {
        $key = $this->cooldownKey($mobile_number);
        return RateLimiter::tooManyAttempts($key, 1) ? RateLimiter::availableIn($key) : 0;
    }

    /**
     * 3540 => "59 minutes", 60 => "1 minute", 45 => "45 seconds"
     */
    private function formatWait($seconds)
    {
        $seconds = max(1, (int) $seconds);
        if ($seconds < 60) {
            return $seconds . ($seconds == 1 ? ' second' : ' seconds');
        }
        $minutes = (int) ceil($seconds / 60);
        return $minutes . ($minutes == 1 ? ' minute' : ' minutes');
    }

    private function findDeletableUser($mobile_number)
    {
        return User::where('mobile_number', $mobile_number)
            ->whereIn('user_type', ['retailer', 'dealer'])
            ->where('status', '!=', 'deleted')
            ->first();
    }
}
