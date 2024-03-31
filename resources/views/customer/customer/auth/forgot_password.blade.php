@extends('customer.layouts.app')

@section('content')

<div class="main-wapper">
    <section class="auth-page">     
      <div class="auth-form">
          <h3 class="title ">Forgot Password</h3>
          <p class="text-muted">Please Select The Option From Below Where You Want To Reset your password</p>
          <form>
            <div class="forgot-option"> 
                <label class="radio-box">
                    Send In Mobile
                    <input type="radio" name="sendoption" id="mobile_chk" checked="checked"><span class="checkmark"></span>
                </label>
                <label class="radio-box">
                    Send In Email
                    <input type="radio" name="sendoption" id="email_chk" ><span class="checkmark"></span>
                </label>
            </div>

            <div class="mb-3">
                <input name="mobile" id="mobile"  type="tel" class="form-control" placeholder="Phone">
            </div> 
            <div class="mb-3">
                <input  type="email" id="email" name="email" class="form-control d-none" placeholder="Email">
            </div>  

            <div class="mb-3">           
                <button type="button" class="btn primary-btn w-100" id="reset_password">Reset Password</button>
            </div> 

            <div class="signup-detail mt-4">
                <p>Don’t have an account? <a href="{{route('signup')}}" class="clr-primary">SIGN UP</a></p>  
            </div>

          </form>        
      </div>
    </section>
</div>

<!-- Forgot Password OTP Modal -->
<div class="modal fade otp-modal" id="forgot-password-otp-modal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content"> 
      <div class="modal-body">   
      <form id="loginOtpForm" enctype="multipart/form-data" method="post">      
        @csrf
        <input type="hidden" name="phone_no" id="phone_no">
          <div class="otp-box">            
              <h3>Enter OTP</h3>
              <p class="text-muted" id="heading_txt">Enter the code, we sent to your Mobile Number</p>              
              <div class="otp-input">
                <input type="text" id="otp_code1" name="otp_code1" onkeypress="javascript:return isNumber(event)" onkeyup="onKeyUpEvent(1, event)" onfocus="onFocusEvent(1)" maxlength="1" class="form-control">
                <input type="text" id="otp_code2" name="otp_code2" onkeypress="javascript:return isNumber(event)" onkeyup="onKeyUpEvent(2, event)" onfocus="onFocusEvent(2)" maxlength="1" class="form-control">
                <input type="text" id="otp_code3" name="otp_code3" onkeypress="javascript:return isNumber(event)" onkeyup="onKeyUpEvent(3, event)" onfocus="onFocusEvent(3)" maxlength="1" class="form-control">
                <input type="text" id="otp_code4" name="otp_code4" onkeypress="javascript:return isNumber(event)" onkeyup="onKeyUpEvent(4, event)" onfocus="onFocusEvent(4)" maxlength="1" class="form-control">
                <input type="text" id="otp_code5" name="otp_code5" onkeypress="javascript:return isNumber(event)" onkeyup="onKeyUpEvent(5, event)" onfocus="onFocusEvent(5)" maxlength="1" class="form-control">
                <input type="text" id="otp_code6" name="otp_code6" onkeypress="javascript:return isNumber(event)" onkeyup="onKeyUpEvent(6, event)" onfocus="onFocusEvent(6)" maxlength="1" class="form-control">
              </div>
              <div class="countdown">
                 <div class="countdown__time clr-primary font-family-semibold"></div>
              </div>
              <div class="resend d-none" id="resend_fpassword"><p class="text-muted">Didn't receive a code? <br/> <a href="javascript:void(0);" class="theme-color">Resend</a></p></div>
              <a href="javascript:void(0)" id="verify_fpassword_otp" class="btn primary-btn w-100" >Verify</a>
          </div>
        </form>
      </div> 
    </div>
  </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $(document).ready(function(){
        $("input[name=sendoption]").on('click',function(){
            var selected = $(this).attr('id');
            if(selected == 'email_chk'){
                $("#mobile").addClass('d-none');
                $("#email").removeClass('d-none');
            }
            else if(selected == 'mobile_chk'){
                $("#mobile").removeClass('d-none');
                $("#email").addClass('d-none');
            }
        })
    })
</script>
@endsection