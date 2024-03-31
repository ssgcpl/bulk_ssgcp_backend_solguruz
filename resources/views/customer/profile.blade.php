@extends('customer.layouts.app')

@section('content')

@section('css')
<style type="text/css">
    .common-upload .preview-img{
        flex-wrap: wrap !important;
    }
</style>
@endsection

<div id="preloader" class="fade-out d-none"><img src="{{asset('web_assets/images/loader-img.svg')}}" alt=""></div>

<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('web_home')}}">Home</a></li>          
          <li class="breadcrumb-item active"><a href="{{ route('customer.profile') }}">Profile</a></li>
        </ol> 
      </div>
  </section> 

  <section class="profile-page white-bg">     
    <div class="container">
      <div class="page-title"><h1>Profile</h1></div>
      <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-12 col-12">
          <div class="profile-outer">
            <div class="profile-img">
              <img src="" id="user_img" name="user_img" alt="">
              <form id="imageForm" enctype="multipart/form-data" method="post">
              <div class="file-input">
                  <input type="file" name="profile_image" id="profile_image" class="file-input-input" accept="*.png/*.jpg/*.jpeg">
                  <label class="file-input-label" for="profile_image"> <span><i class="fas fa-edit"></i></span></label>
              </div>
            </form>
            </div>
            <div class="profile-name" id="user_info">
            </div>
          </div>
          <div class="panel">
            <div class="head"><h5>Personal Details</h5> <a href="javascript:void(0)" id="edit_personal_info" class="theme-color" title="">Edit</a></div>
            <div class="panel-body">              
              <div class="item">
                <div class="title"><label class="secondary-color">Full Name</label></div>
                <p id="user_name"></p>
              </div>
              <div class="item">
                <div class="title"><label class="secondary-color">Company / Firm Name</label></div>
                <p id="comp_name"></p>
              </div>
            </div>
          </div>
          <div class="panel">
            <div class="head"><h5>Email ID & Mobile Number</h5></div>
            <div class="panel-body">              
              <div class="item">
                <div class="title">
                  <label class="secondary-color">Email ID</label><p><span id="verify_txt" class="text-success"></span><a href="javascript:void(0)"   id="verify_btn" class="theme-color d-none" title="">Verify</a>&nbsp; &nbsp;<a href="javascript:void(0)" id="edit_email" class="theme-color" title="">Edit</a></p>
                </div>
                <p id="Email"></p>
              </div>
              <div class="item">
                <div class="title">
                  <label class="secondary-color">Mobile Number</label><a href="javascript:void(0)" id="edit_mobile_number" class="theme-color" title="">Edit</a>
                </div>
                <input type="hidden"  id="hdn_mobile_number" name="hdn_mobile_number">
                <p id="mobile_number">+91 </p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-12 col-12">
          <div class="panel">
            <div class="head"><h5>Saved Addresses</h5> <a class="theme-color fa-lg d-inline d-none saved_address" href="javascript:void(0)" ><i class="icon-edit-address"></i></a></div>
            <div class="panel-body">              
               <div class="default-address" id="address_list"> 
               </div>
            </div>
          </div>
          <div class="panel">
            <div class="head"><h5>Images</h5> <a href="javascript:void(0)"  class="theme-color" id="edit_images" title="">Edit</a></div>
            <div class="panel-body">              
              <div class="common-upload">
                  <p><label>Company/Shop Image</label></p>
                  <div class="box" id="company_imgs">                        
                      
                  </div>

                  <p class="mt-3"><label>Shop Documents Image</label></p>
                  <div class="box" id="company_docs">                        
                       
                  </div>
              </div>
            </div>
          </div>

          <div class="change-password text-center">
            <a href="#change-password-modal" data-bs-toggle="modal" class="btn secondary-btn w-100">Change Password</a>
          </div>
        </div>
      </div>
    </div>
  </section>

</div>


<!-- Personal Details -->
<div class="modal fade personal-details" id="personal-details" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content"> 
      <div class="modal-header">
        <h5 class="modal-title">Edit Personal Details</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div> 
      <div class="modal-body">  
      <form id="personalInfoForm" method="post">       
        <div class="form-group mb-3">
          <label>Full Name</label>
          <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Full Name" required="true">
        </div>
        <div class="form-group mb-3">
          <label>Company / Firm Name</label>
          <input type="text" class="form-control" name="company_name" id="company_name" placeholder="Company / Firm Name" required="true">
        </div>
        <div class="button-list text-center">
          <input id="update_personal_info_btn" name="update_personal_info_btn"  type="submit" class="btn primary-btn">
        </div> 
        </form>         
      </div> 
    </div>
  </div>
</div>


<!-- Change Email Popup -->
<div class="modal fade change-email-modal" id="change-email" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content"> 
      <div class="modal-header">
        <h5 class="modal-title">Change Email ID</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div> 
      <div class="modal-body">
        <div class="text-center">
          <h6>Enter Email ID</h6>
          <p class="secondary-color">Enter new email ID here, then check your inbox for a link to otp</p>
        </div>         
        <div class="form-group mb-3">
          <label>Email ID</label>
          <input type="text" class="form-control" name="email_id" id="email_id" placeholder="Email ID">
        </div> 
        <div class="button-list text-center">
          <a href="javascript:void(0)" id="update_email_id" type="button" class="btn primary-btn">Submit</a>          
        </div>          
      </div> 
    </div>
  </div>
</div>

<!-- Phone Number Popup -->
<div class="modal fade phone-number-modal" id="phone-number-modal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content"> 
      <div class="modal-header">
        <h5 class="modal-title">Change Mobile Number</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div> 
      <div class="modal-body">
        <div class="text-center">
          <h6>Enter Mobile Number</h6>
          <p class="secondary-color">Enter New Mobile Number</p>
        </div>         
        <div class="form-group mb-3">
          <label>Mobile Number</label>
          <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="Mobile Number">
        </div> 
        <div class="button-list text-center">
          <a id="update_phone_number" name="update_phone_number" type="button" class="btn primary-btn">Submit</a>          
        </div>          
      </div> 
    </div>
  </div>
</div>

<!-- Edit Images / Documents Popup -->
<div class="modal fade edit-images-modal" id="edit-images-modal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content"> 
      <div class="modal-header">
        <h5 class="modal-title">Edit Images</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div> 
      <div class="modal-body">
        <div class="common-upload mb-3">
            <p><label>Add Company / Shop Images</label></p>
            <div class="box">                        
               <div id="company_images_div" class="preview-img"></div>
                <div class="upload-img">
                    <input type="file" name="file_input_img" id="file_input_img" class="file-input-input" accept=".png,.jpg,.jpeg,.gif" multiple>
                    <label class="file-input-label" for="file_input_img">
                        <img src="{{asset('web_assets/images/upload-comp-img.png')}}" id="comp_docs"  alt="" >
                        Add Image
                    </label>
                </div>
            </div>
        </div>
        <div class="common-upload mb-3">
            <p><label>Add Shop Documents Image</label></p>
            <div class="box">                        
               <div id="company_documents_div" class="preview-img"></div>
                <div class="upload-img">
                    <input type="file" name="file_input_img" id="file_input_doc" class="file-input-input" accept=".png,.jpg,.jpeg,.gif" multiple>
                    <label class="file-input-label" for="file_input_doc">
                        <img src="{{asset('web_assets/images/upload-doc.png')}}"  alt="" >
                        Add Image
                    </label>
                </div>
            </div>
        </div>
        <div class="button-list text-center">
          <a href="javascript:void(0)" id="update_docs_images" name="update_docs_images" type="button" class="btn primary-btn w-100">Save</a>          
        </div>          
      </div> 
    </div>
  </div>
</div>

<!-- Change Password Popup -->
<div class="modal fade change-password-modal" id="change-password-modal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content"> 
      <div class="modal-header">
        <h5 class="modal-title">Change Password</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div> 
      <div class="modal-body">
        <div class="text-center">          
          <p class="secondary-color">To change your password, enter your old and new password below.</p>
        </div>         
        <div class="form-group mb-3">
          <label>Old Password <span class="secondary-color">(Optional)</span></label>
           <div class="input-group">
          <input type="password" class="form-control" name="old_password" id="old_password" placeholder="Old Password" required="">
           <span class="input-group-text input-group-addon"><a href="javascript:void(0)"><i class="fa fa-lock password_icon"></i></a></span></div>
        </div>
        <div class="form-group mb-3">
          <label>New Password</label>
          <div class="input-group">
          <input type="password" class="form-control" name="new_password" id="new_password" placeholder="New Password" required="">
           <span class="input-group-text input-group-addon"><a href="javascript:void(0)"><i class="fa fa-lock new_password_icon"></i></a></span></div>
        </div>
        <div class="form-group mb-3">
          <label>Re-Enter New Password</label>
          <div class="input-group">
          <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Re-Enter New Password" required=""><span class="input-group-text input-group-addon"><a href="javascript:void(0)"><i class="fa fa-lock c_password_icon"></i></a></span></div>
        </div> 
        <div class="button-list text-center">
          <a href="javascript:void(0)" id="change_password_btn" type="button" class="btn primary-btn w-100">Submit</a>          
        </div>          
      </div> 
    </div>
  </div>
</div>

<!-- Add to Cart Again -->
<div class="modal fade order-again" id="order-again" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >Order Again</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xl-6"> 
            <div class="product-list-box">
                <div class="img"><a href="#" title=""><img src="images/book/book1.png" alt=""></a></div>
                <div class="detail">
                  <h6><a href="#" title="">Ganeral Mathematics</a></h6> 
                  <div class="out-of-stock">
                    <div class="sale-price">₹70<span>₹120</span></div> 
                    <div class="theme-red-color out">Out of stock</div>
                  </div>
                  <div class="qty-items">
                    <input type="button" value="-" class="qty-minus">
                    <input type="number" value="1" class="qty" min="0" max="10" >
                    <input type="button" value="+" class="qty-plus">
                  </div>                  
                </div>
              </div>
          </div>
          <div class="col-xl-6"> 
            <div class="product-list-box">
                <div class="img"><a href="#" title=""><img src="images/book/book2.png" alt=""></a></div>
                <div class="detail">
                  <h6><a href="#" title="">Ganeral Mathematics</a></h6> 
                  <div class="out-of-stock">
                    <div class="sale-price">₹70<span>₹120</span></div> 
                    <div class="theme-red-color out">Out of stock</div>
                  </div>
                  <div class="qty-items">
                    <input type="button" value="-" class="qty-minus">
                    <input type="number" value="1" class="qty" min="0" max="10" >
                    <input type="button" value="+" class="qty-plus">
                  </div> 
                  <button class="btn secondary-btn">Add to Wishlist</button>
                </div>
              </div>
          </div>
          <div class="col-xl-6"> 
            <div class="product-list-box">
                <div class="img"><a href="#" title=""><img src="images/book/book3.png" alt=""></a></div>
                <div class="detail">
                  <h6><a href="#" title="">Ganeral Mathematics</a></h6> 
                  <div class="out-of-stock">
                    <div class="sale-price">₹70<span>₹120</span></div> 
                    <div class="theme-red-color out">Out of stock</div>
                  </div>
                  <div class="qty-items">
                    <input type="button" value="-" class="qty-minus">
                    <input type="number" value="1" class="qty" min="0" max="10" >
                    <input type="button" value="+" class="qty-plus">
                  </div> 
                  <button class="btn secondary-btn">Add to Wishlist</button>
                </div>
              </div>
          </div>
          <div class="col-xl-6"> 
            <div class="product-list-box">
                <div class="img"><a href="#" title=""><img src="images/book/book4.png" alt=""></a></div>
                <div class="detail">
                  <h6><a href="#" title="">Ganeral Mathematics</a></h6> 
                  <div class="out-of-stock">
                    <div class="sale-price">₹70<span>₹120</span></div> 
                    <div class="theme-red-color out">Out of stock</div>
                  </div>
                  <div class="qty-items">
                    <input type="button" value="-" class="qty-minus">
                    <input type="number" value="1" class="qty" min="0" max="10" >
                    <input type="button" value="+" class="qty-plus">
                  </div> 
                  <button class="btn secondary-btn">Add to Wishlist</button>
                </div>
              </div>
          </div>
        </div> 
        <div class="button-list mt-2 text-center"><a href="my-cart.php" type="button" class=" btn primary-btn">Add to Cart</a></div>
      </div>      
    </div>
  </div>
</div> 

<!-- Mobile/Email OTP modal -->  
<div class="modal fade otp-modal" id="verify-otp-modal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content"> 
      <div class="modal-body">   
      <form id="MobileOtpForm" enctype="multipart/form-data" method="post">      
        @csrf
        <input type="hidden" name="phone_num" id="phone_num">
          <div class="otp-box">            
              <h3>Enter Code</h3>
             <!--  <p class="text-muted">Please enter 6 digit OTP to verify your Email Address</p>  -->
              <p class="text-muted">Enter the code, we sent to you registered Mobile Number.</p>             
              <div class="otp-input">
                <input type="text" id="otpcode1" name="otp_code1" onkeypress="javascript:return isNumber(event)" onkeyup="onMobileKeyUpEvent(1, event)" onfocus="onMobileFocusEvent(1)" maxlength="1" class="form-control">
                <input type="text" id="otpcode2" name="otp_code2" onkeypress="javascript:return isNumber(event)" onkeyup="onMobileKeyUpEvent(2, event)" onfocus="onMobileFocusEvent(2)" maxlength="1" class="form-control">
                <input type="text" id="otpcode3" name="otp_code3" onkeypress="javascript:return isNumber(event)" onkeyup="onMobileKeyUpEvent(3, event)" onfocus="onMobileFocusEvent(3)" maxlength="1" class="form-control">
                <input type="text" id="otpcode4" name="otp_code4" onkeypress="javascript:return isNumber(event)" onkeyup="onMobileKeyUpEvent(4, event)" onfocus="onMobileFocusEvent(4)" maxlength="1" class="form-control">
                <input type="text" id="otpcode5" name="otp_code5" onkeypress="javascript:return isNumber(event)" onkeyup="onMobileKeyUpEvent(5, event)" onfocus="onMobileFocusEvent(5)" maxlength="1" class="form-control">
                <input type="text" id="otpcode6" name="otp_code6" onkeypress="javascript:return isNumber(event)" onkeyup="onMobileKeyUpEvent(6, event)" onfocus="onMobileFocusEvent(6)" maxlength="1" class="form-control">
              </div>
              <div class="countdown">
                 <div class="countdown__time clr-primary font-family-semibold"></div>
              </div>
              <div class="resend d-none" ><p class="text-muted">Didn't receive a code? <br/> <a href="javascript:void(0);" id="resend_mobile_otp" class="theme-color">Resend</a></p></div>
              <a href="javascript:void(0)" id="verify_mobile_otp" class="btn primary-btn w-100" >Verify</a>
          </div>
        </form>
      </div> 
    </div>
  </div>
</div>


<!-- Mobile/Email OTP modal -->  
<div class="modal fade otp-modal" id="verify-email-otp-modal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content"> 
      <div class="modal-body">   
      <form id="EmailOtpForm" enctype="multipart/form-data" method="post">      
        @csrf
        <input type="hidden" name="updated_email_id" id="updated_email_id">
          <div class="otp-box">            
              <h3>Enter Code</h3>
             <!--  <p class="text-muted">Please enter 6 digit OTP to verify your Email Address</p>  -->
              <p class="text-muted">Enter the code, we sent to you in email.</p>             
              <div class="otp-input">
                <input type="text" id="otpcode_1" name="otp_code1" onkeypress="javascript:return isNumber(event)" onkeyup="onEmailKeyUpEvent(1, event)" onfocus="onEmailFocusEvent(1)" maxlength="1" class="form-control">
                <input type="text" id="otpcode_2" name="otp_code2" onkeypress="javascript:return isNumber(event)" onkeyup="onEmailKeyUpEvent(2, event)" onfocus="onEmailFocusEvent(2)" maxlength="1" class="form-control">
                <input type="text" id="otpcode_3" name="otp_code3" onkeypress="javascript:return isNumber(event)" onkeyup="onEmailKeyUpEvent(3, event)" onfocus="onEmailFocusEvent(3)" maxlength="1" class="form-control">
                <input type="text" id="otpcode_4" name="otp_code4" onkeypress="javascript:return isNumber(event)" onkeyup="onEmailKeyUpEvent(4, event)" onfocus="onEmailFocusEvent(4)" maxlength="1" class="form-control">
                <input type="text" id="otpcode_5" name="otp_code5" onkeypress="javascript:return isNumber(event)" onkeyup="onEmailKeyUpEvent(5, event)" onfocus="onEmailFocusEvent(5)" maxlength="1" class="form-control">
                <input type="text" id="otpcode_6" name="otp_code6" onkeypress="javascript:return isNumber(event)" onkeyup="onEmailKeyUpEvent(6, event)" onfocus="onEmailFocusEvent(6)" maxlength="1" class="form-control">
              </div>
              <div class="countdown">
                 <div class="countdown__time clr-primary font-family-semibold"></div>
              </div>
              <div class="resend d-none" ><p class="text-muted">Didn't receive a code? <br/> <a href="javascript:void(0);" id="resend_email_otp" class="theme-color">Resend</a></p></div>
              <a href="javascript:void(0)" id="verify_email_otp" class="btn primary-btn w-100" >Verify</a>
          </div>
        </form>
      </div> 
    </div>
  </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
   function getMobileOtpCodeElement(index) {
      return document.getElementById('otpcode' + index);
    }

   function onMobileKeyUpEvent(index, event) {
      const eventCode = event.which || event.keyCode;
      if (getMobileOtpCodeElement(index).value.length === 1) {
       if (index !== 6) {
        getMobileOtpCodeElement(index+ 1).focus();
       } else {
        getMobileOtpCodeElement(index).blur();
       }
      }
      if (eventCode === 8 && index !== 1) {
       getMobileOtpCodeElement(index - 1).focus();
      }
    }
    function onMobileFocusEvent(index) {
      for (item = 1; item < index; item++) {
       const currentElement = getMobileOtpCodeElement(item);
       if (!currentElement.value) {
          currentElement.focus();
          break;
       }
      }
    }


    function getEmailOtpCodeElement(index) {
      return document.getElementById('otpcode_' + index);
    }

   function onEmailKeyUpEvent(index, event) {
      const eventCode = event.which || event.keyCode;
      if (getEmailOtpCodeElement(index).value.length === 1) {
       if (index !== 6) {
        getEmailOtpCodeElement(index+ 1).focus();
       } else {
        getEmailOtpCodeElement(index).blur();
       }
      }
      if (eventCode === 8 && index !== 1) {
       getEmailOtpCodeElement(index - 1).focus();
      }
    }
    function onEmailFocusEvent(index) {
      for (item = 1; item < index; item++) {
       const currentElement = getEmailOtpCodeElement(item);
       if (!currentElement.value) {
          currentElement.focus();
          break;
       }
      }
    }

</script>
@endsection


