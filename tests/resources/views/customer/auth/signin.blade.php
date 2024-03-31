@extends('customer.layouts.app')

@section('css')
<style type="text/css">
    .forgot {
        text-decoration: none !important;
    }
</style>
@endsection

@section('content')


<div class="main-wapper">
    <section class="auth-page">     
        <div class="auth-form">
            <h3 class="title">Sign in</h3>
            
            <div class="custom-tab-main">                  
                <ul class="nav custom-nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="tab1" data-bs-toggle="tab" href="#tab11" role="tab" aria-controls="tab11" aria-selected="true">With Password</a>
                  </li>
                  <li class="nav-item" role="presentation">
                    <a class="nav-link" id="tab2" data-bs-toggle="tab" href="#tab22" role="tab" aria-controls="tab22" aria-selected="false">With OTP</a>
                  </li>          
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="tab11" role="tabpanel" aria-labelledby="tab1">
                        <form id="loginPasswordForm">
                            <div class="form-group mb-3">
                                <input type="text" class="form-control" id="mobile_or_email" name="mobile_or_email" placeholder="Mobile Number/Email ID">
                            </div> 
                            <div class="form-group mb-3">
                            <div class="input-group">
                                <input  type="password" id="password" name="password" class="form-control" placeholder="Password"> <span class="input-group-text input-group-addon"><a href="javascript:void(0)"><i class="fa fa-lock password_icon"></i></a></span>
                            </div>
                            </div> 
                            <div class="login-with justify-content-end">                                
                                <a href="{{route('forgot_password')}}" title="" class="forgot">Forgot Password?</a>
                            </div>
                            <div class="signup-detail mt-4">
                                <p><a href="{{route('web_home')}}" class="clr-primary"><u>Guest User</u></a></p>
                            </div>
                            <div class="mb-3">           
                                <button type="button" id="login_with_password" name="login_with_password" class="btn primary-btn w-100">Sign in</button>
                            </div> 
                            <div class="signup-detail mt-4">
                                <p>Don’t have an account? <a href="{{route('signup')}}" class="clr-primary">Sign up</a></p>  
                                <!-- <p><a href="index.php" class="clr-primary">Guest User</a></p>   -->
                            </div>
                        </form>   
                    </div>
                    <div class="tab-pane fade" id="tab22" role="tabpanel" aria-labelledby="tab2">
                        <div class="form-group mb-3">
                            <input type="text" id="mobile_number" class="form-control" placeholder="Mobile Number">
                        </div>
                        <div class="mb-3">           
                            <button type="button" class="btn primary-btn w-100" id="login_with_otp">Get OTP</button>
                        </div> 

                        <div class="signup-detail mt-4">
                            <p>Don’t have an account? <a href="{{route('signup')}}" class="clr-primary">SIGN UP</a></p>  
                            <!-- <p><a href="index.php" class="clr-primary">Guest User</a></p>   -->
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </section>
</div>
@endsection

@section('js')
<script type="text/javascript">
    var input = document.querySelector(".countrycode");
    window.intlTelInput(input, {         
        utilsScript: "{{asset('web_assets/js/utils.js')}}",
    });
</script>
 @endsection
