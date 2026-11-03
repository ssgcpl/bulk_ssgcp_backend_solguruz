@extends('customer.layouts.app')

@section('content')

<div class="main-wapper">
    <section class="auth-page">
      <div class="auth-form">

        @error('general')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        @if(session('deleted'))
            {{-- Step 3: Done --}}
            <h3 class="title">Account Deleted</h3>
            <div class="alert alert-success">{{ trans('auth.account_deleted_successfully') }}</div>
            <p class="text-muted">You have been logged out of the app on all devices. You can create a new account any time using the SSGC Bulk Order app.</p>
            <a href="{{ route('web_home') }}" class="btn primary-btn w-100 mt-2">Go to Home</a>

        @elseif($mobile_number)
            {{-- Step 2: Verify OTP and confirm --}}
            <h3 class="title">Confirm Account Deletion</h3>
            <p class="text-muted">Enter the 6-digit OTP sent to <strong>{{ $mobile_number }}</strong>.</p>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('delete_account.submit') }}">
                @csrf
                <input type="hidden" name="action" value="confirm">
                <div class="mb-3">
                    <input type="text" name="otp" inputmode="numeric" maxlength="6" autocomplete="one-time-code"
                           class="form-control @error('otp') is-invalid @enderror" placeholder="Enter OTP" required>
                    @error('otp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="confirm" value="1" id="confirm"
                           class="form-check-input @error('confirm') is-invalid @enderror">
                    <label class="form-check-label" for="confirm">
                        I understand that my account will be permanently deleted and this cannot be undone.
                    </label>
                    @error('confirm')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-danger w-100 mb-3">Delete My Account</button>
            </form>

            <form method="POST" action="{{ route('delete_account.submit') }}" class="text-center">
                @csrf
                <input type="hidden" name="action" value="send_otp">
                <input type="hidden" name="mobile_number" value="{{ $mobile_number }}">
                @if($limit_reached_in > 0)
                    {{-- The last OTP was sent and is still valid; only further resends are blocked. --}}
                    <p class="text-muted mb-2" id="otp_limit_msg" data-wait="{{ $limit_reached_in }}">
                        OTP limit exceeded. Please try again after <span id="otp_limit_timer">{{ gmdate($limit_reached_in >= 3600 ? 'H:i:s' : 'i:s', $limit_reached_in) }}</span>.
                    </p>
                @else
                    <p class="text-muted mb-2">Didn't receive the code?</p>
                    <button type="submit" id="resend_otp_btn" class="btn btn-outline-secondary w-100 mb-2"
                            data-wait="{{ $resend_in }}" @if($resend_in > 0) disabled @endif>
                        Resend OTP<span id="resend_timer">@if($resend_in > 0) in {{ $resend_in }}s @endif</span>
                    </button>
                @endif
            </form>

            <form method="POST" action="{{ route('delete_account.submit') }}" class="text-center">
                @csrf
                <input type="hidden" name="action" value="cancel">
                <button type="submit" class="btn btn-link p-0 text-muted">Cancel</button>
            </form>

        @else
            {{-- Step 1: Enter mobile number --}}
            <h3 class="title">Delete Your Account</h3>
            <p class="text-muted">Enter the mobile number registered with your SSGC Bulk Order account. We will send you an OTP to confirm it's you.</p>

            <form method="POST" action="{{ route('delete_account.submit') }}">
                @csrf
                <input type="hidden" name="action" value="send_otp">
                <div class="mb-3">
                    <input type="tel" name="mobile_number" maxlength="10" value="{{ old('mobile_number') }}"
                           class="form-control @error('mobile_number') is-invalid @enderror" placeholder="Registered Mobile Number" required>
                    @error('mobile_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn primary-btn w-100">Send OTP</button>
            </form>
        @endif

        @unless(session('deleted'))
            <div class="mt-4 text-muted small">
                <p class="mb-1"><strong>What gets deleted</strong></p>
                <ul class="ps-3">
                    <li>Your profile and login access (web and app)</li>
                    <li>Registered devices and push notifications</li>
                </ul>
                <p class="mb-1"><strong>What we keep</strong></p>
                <ul class="ps-3">
                    <li>Order, invoice and payment records, as required for tax and legal compliance</li>
                </ul>
            </div>
        @endunless

      </div>
    </section>
</div>
@endsection

@section('js')
<script>
    // Ticks down the element's data-wait seconds, calling onTick(left) each second and onDone() at zero.
    function countdown(el, onTick, onDone) {
        if (!el) return;
        var left = parseInt(el.getAttribute('data-wait'), 10) || 0;
        if (left <= 0) return;
        var interval = setInterval(function () {
            if (--left <= 0) { clearInterval(interval); onDone(); } else { onTick(left); }
        }, 1000);
    }
    function pad(n) { return (n < 10 ? '0' : '') + n; }

    // Resend OTP: button stays disabled until the 1-minute timer ends.
    var resendBtn = document.getElementById('resend_otp_btn');
    countdown(resendBtn,
        function (left) { document.getElementById('resend_timer').textContent = ' in ' + left + 's'; },
        function () { resendBtn.disabled = false; document.getElementById('resend_timer').textContent = ''; });

    // Hourly OTP limit: live mm:ss countdown, then reload so the Resend button comes back.
    countdown(document.getElementById('otp_limit_msg'),
        function (left) {
            var h = Math.floor(left / 3600), m = Math.floor((left % 3600) / 60), s = left % 60;
            document.getElementById('otp_limit_timer').textContent = (h > 0 ? pad(h) + ':' : '') + pad(m) + ':' + pad(s);
        },
        function () { window.location.reload(); });
</script>
@if(session('deleted'))
<script>
    // Log the user out of the website as well, if they were signed in on this browser.
    try { localStorage.removeItem('user_token'); localStorage.removeItem('userid'); localStorage.removeItem('user_type'); } catch (e) {}
</script>
@endif
@endsection
