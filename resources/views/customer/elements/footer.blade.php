<footer class="footer">
  <div class="container">
    <div class="row">
      <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">  
        <div class="about-info">
          <div class="footer-logo"><img src="{{asset('web_assets/images/logo.svg')}}" alt=""></div>
          <ul>
            <li><a target="_blank" id="whatsapp"><i class="fab fa-whatsapp"></i></a></li>            
	    <li><a target="_blank" id="telegram"><i class="fab fa-telegram"></i></a></li>	    
            <li><a target="_blank" id="facebook"><i class="fab fa-facebook-f"></i></a></li>
            <li><a target="_blank" id="instagram"><i class="fab fa-instagram"></i></a></li>
            <li><a target="_blank" id="twitter"><img src="{{asset('web_assets/images/x-icon.svg')}}" alt=""></a></li>
          </ul> 
        </div>
      </div>
      <div class="col-xl-1 col-lg-1 col-md-1 col-sm-12"></div>
      <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
        <h3 class="footer-title">Useful Links</h3>
        <div class="footer-link">
          <ul>
              <li><a href="{{route('about_us')}}">About Us</a></li>              
              <li><a href="{{route('terms_and_condition')}}">Terms & Condition</a></li>                
              <li><a href="{{route('privacy_policy')}}">Privacy Policy</a></li>
              <li><a href="{{route('contact_us')}}">Contact Us</a></li>
          </ul>
        </div>
      </div>  
      <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
          <h3 class="footer-title">Contact Information</h3>
          <div class="contact-info">
            <ul>
                <li>
                    <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="details">{{\App\Models\Setting::get('contact_address')}}</div>
                </li>
                <li>
                    <div class="icon"><i class="fas fa-envelope"></i></div>
                    <div class="details"><a href="mailto:{{\App\Models\Setting::get('contact_email')}}" title="">{{\App\Models\Setting::get('contact_email')}}</a></div>
                </li>
                <li>
                    <div class="icon"><i class="fas fa-phone-alt"></i></div>
                    <div class="details"><a href="tel:{{\App\Models\Setting::get('customer_care_no')}}" title="">+1 {{\App\Models\Setting::get('customer_care_no')}}</a></div>
                </li>
              </ul>
          </div>
      </div>
      <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12">
        <h3 class="footer-title">Download App</h3>
        <div class="download-app">
          <a href="#" title=""><img src="{{asset('web_assets/images/app-store.svg')}}" alt=""></a>
          <a href="#" title=""><img src="{{asset('web_assets/images/google-play.svg')}}" alt=""></a>
        </div>
      </div>
    </div> 
  </div>  
  <div class="footer-bottom">        
    <div class="copyright-footer">&copy; <script>document.write(new Date().getFullYear())</script> ssgc . All rights reserved.</div>        
  </div>
</footer>


<!-- OTP Modal -->
<div class="modal fade otp-modal" id="otp-modal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content"> 
      <div class="modal-body">   
      <form id="loginOtpForm" enctype="multipart/form-data" method="post">      
        @csrf
        <input type="hidden" name="user_id" id="user_id">
        <input type="hidden" name="phone" id="phone">
          <div class="otp-box">            
              <h3>Enter Code</h3>
              <!-- <p class="text-muted">Please enter 6 digit OTP to verify your Email Address</p> -->
              <p class="text-muted">Enter the code, we sent to you registered Mobile Number.</p>              
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
              <div class="resend d-none"><p class="text-muted">Didn't receive a code? <br/> <a href="javascript:void(0);" class="theme-color">Resend</a></p></div>
              <a href="javascript:void(0)" id="verify_otp" class="btn primary-btn w-100" >Verify</a>
          </div>
        </form>
      </div> 
    </div>
  </div>
</div>

<!-- Save Address -->
<div class="modal fade save-address" id="save-address" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >Saved Addresses</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <div class="d-flex justify-content-end mb-1">
          <a href="javascript:void(0)" class="theme-color text-decoration-underline add_new_address" title="">Add New</a>
        </div>
        <div class="default-address" id="addresses_list"> 
        </div>
        <div class="button-list">
          <input type="hidden" id="type" value=""> <!-- billing, shipping   -->    
          <a href="javascript:void(0)" id="select_address" type="button" class="w-100 btn primary-btn">Done</a>
        </div>
      </div>      
    </div>
  </div>
</div>

<!-- Add Address -->
<div class="modal fade add-address" id="add-address" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="address_title"></h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <h5 class="title">Personal Details</h5>
       <form id="saveAddressForm" method="post" enctype="multipart/form-data">
        <input type="hidden" id="address_id" name="address_id">
        <div class="row">
          <div class="col-xl-6 col-md-6 col-12">
            <div class="form-group mb-3">
              <label>Full Name</label>
              <input type="text" id="contact_name" name="contact_name" class="form-control" placeholder="Full Name" >
            </div>
          </div>
          <div class="col-xl-6 col-md-6 col-12">
            <div class="form-group mb-3">
              <label>Company Name</label>
              <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Name">
            </div>
          </div>
          <div class="col-xl-6 col-md-6 col-12">
            <div class="form-group mb-3">
              <label>Phone Number</label>
              <input type="tel" id="contact_number" name="contact_number" class="form-control" placeholder="Phone Number" maxlength="10" >
            </div>
          </div>
          <div class="col-xl-6 col-md-6 col-12">
            <div class="form-group mb-3">
              <label>Email ID</label>
              <input type="email" id="email" name="email" class="form-control" placeholder="Email ID">
            </div>
          </div>  
        </div>
        <h5 class="title">Address Details</h5>

        <div class="row">
          <div class="col-xl-6 col-md-6 col-12">
            <div class="form-group mb-3">
              <label>State</label>
              <select name="state_id" id="state_id" class="form-select" aria-label="Default select example">
              </select>          
            </div>
          </div>
          <div class="col-xl-6 col-md-6 col-12">
            <div class="form-group mb-3">
              <label>City</label>
              <select name="city_id" id="city_id" class="form-select" aria-label="Default select example">
              </select>          
            </div>
          </div>
          <div class="col-xl-6 col-md-6 col-12">
            <div class="form-group mb-3">
              <label>Pincode</label>
              <select name="postcode_id" id="postcode_id" class="form-select" aria-label="Default select example">
              </select>               
            </div>
          </div>
          <div class="col-xl-6 col-md-6 col-12">
            <div class="form-group mb-3">
              <label>Area</label>
              <input type="text" id="area" name="area" class="form-control" placeholder="Area">
            </div>
          </div>
        </div> 

        <h5 class="title">Location Details</h5>
        <div class="row">
          <div class="col-xl-12 col-md-12 col-12">
            <div class="form-group mb-3">
              <label>House/Street No.</label>
              <input type="text" id="house_no" name="house_no" class="form-control" placeholder="House/Street No">
            </div>
          </div>
          <div class="col-xl-6 col-md-6 col-12">
            <div class="form-group mb-3">
              <label>Building/Street Name</label>
              <input type="text" id="street" name="street" class="form-control" placeholder="Building/Street Name">
            </div>
          </div>
          <div class="col-xl-6 col-md-6 col-12">
            <div class="form-group mb-3">
              <label>Landmark</label>
              <input type="text" id="landmark" name="landmark" class="form-control" placeholder="Landmark">
            </div>
          </div>
        </div> 
         
        <h5 class="title">Save As</h5>
        <div class="radio-list mb-3">
            <input type="radio" class="btn-check address_type" value="Home" name="options" id="option1" autocomplete="off" checked>
            <label class="me-2 btn btn-outline-secondary" for="option1"><i class="fas fa-home"></i> Home</label>  
            <input type="radio" class="btn-check address_type" value="Office" name="options" id="option2" autocomplete="off">
            <label class="me-2 btn btn-outline-secondary" for="option2"><i class="fas fa-building"></i> Office</label>
            <input type="radio" class="btn-check address_type" value="Other" name="options"  id="option3" autocomplete="off">
            <label class="me-2 btn btn-outline-secondary" for="option3"><i class="fas fa-map-pin"></i> Other</label>
        </div>
        <div class="form-check form-switch mb-3">
          <label class="form-check-label" for="flexSwitchCheckDefault">Delivery Address</label>
          <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" name="is_delivery_address">
        </div>
        <div class="button-list">            
          <input id="save_address_btn" name="save_address_btn" type="submit" class="w-100 btn primary-btn" value="SAVE">  
        </div>
      </form>
      </div>      
    </div>
  </div>
</div>

<!-- Delete Modal  -->
<div class="modal fade delete-confirm" id="delete-confirm" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <h5 class="modal-title" >Completed Test</h5> -->
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body text-center">
        <input type="hidden" id="id" name="id">
        <!-- <div class="img"><img src="images/right-arrow.svg" alt=""></div> -->
        <p>Are you sure you want to Delete?</p>
         <div class="button-list text-center">
          <a href="javascript:void(0)" type="button" class="btn secondary-btn me-1" data-bs-dismiss="modal">No</a>
          <a href="javascript:void(0)" type="button" id="remove_item" class="btn primary-btn">Yes</a>
        </div> 
      </div>      
    </div>
  </div>
</div>

<!-- Clear All  -->
<div class="modal fade clear-all-confirm" id="clear-all-confirm" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <h5 class="modal-title" >Completed Test</h5> -->
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body text-center">
        <input type="hidden" id="id" name="id">
        <!-- <div class="img"><img src="images/right-arrow.svg" alt=""></div> -->
        <p>Are you sure you want to clear all notifcations?</p>
         <div class="button-list text-center">
          <a href="javascript:void(0)" type="button" class="btn secondary-btn me-1" data-bs-dismiss="modal">No</a>
          <a href="javascript:void(0)" type="button" id="clear_all_btn" class="btn primary-btn">Yes</a>
        </div> 
      </div>      
    </div>
  </div>
</div>

<!-- Place Order -->
<div id="place-order" class="fade modal delete-confirm">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">        
         
      </div>
      <div class="modal-body"> 
        <div class="success-img">
          <lottie-player src="{{asset('web_assets/images/success.json')}}" background="transparent" speed="1" loop autoplay></lottie-player>
        </div>
        <h4 class="green-color">Success</h4>  
        <p>Your order has been successfully Placed</p>     
        <div class="button-list text-center">
          <a href="{{route('my_orders')}}" id="redirect_url" type="button" class="btn primary-btn">Ok</a>
        </div>    
      </div>     
    </div>
  </div>
</div>

<!-- Login confirmation -->
<div id="sign_in_modal" class="fade modal delete-confirm">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">        
         
      </div>
      <div class="modal-body"> 
        <h4 style="color: #33A1E1;">You Need To Sign In First</h4>  
        <p>Create an account within seconds for an uninterrupted experience on the app.</p>     
        <div class="button-list text-center">
          <a href="{{route('signin')}}" id="redirect_url" type="button" class="btn primary-btn">Sign in</a>
        </div>  
        <div class="signup-detail mt-4">
            <p>Don’t have an account? <a href="{{route('signup')}}" style="color: #33A1E1;" >Sign Up</a></p>  
            <!-- <p><a href="index.php" class="clr-primary">Guest User</a></p>   -->
        </div>
      </div>     
    </div>
  </div>
</div>



<!-- Ticket Send by customer success -->
<div id="ticket-send" class="fade modal delete-confirm">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">        
      </div>
      <div class="modal-body"> 
        <div class="success-img">
          <lottie-player src="{{asset('web_assets/images/success.json')}}" background="transparent" speed="1" loop autoplay></lottie-player>
        </div>
        <h4 class="green-color">Success</h4>  
        <p>Your request has been submitted</p>     
       </div>     
    </div>
  </div>
</div>


<!-- Return Product Return Placed -->
<div class="modal fade return-product" id="return-placed" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >Return Product</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <form id="orderReturnDispatchForm" method="post" enctype="multipart/form-data">
           <input type="hidden" name="order_return_id" id="order_return_id" >
        <div class="form-group">
          <div class="mb-3">
             <!--  <label>Courier service name</label> -->
              <input type="text" class="form-control" name="courier_name" id="courier_name" placeholder="Courier service name">
          </div>
          <div class="mb-3">
            <!--   <label>Tracking number</label> -->
              <input type="text" class="form-control" name="tracking_number" id="tracking_number" placeholder="Tracking number">
          </div> 
          <div class="common-upload  mb-3">
            <p><label>Add receipt image</label></p> 
            <div class="box">                        
                 <div class="preview-img" id="receipt_image_preview">
                   <!--  <div class="img">
                        <img src="images/profile.png" alt="">
                        <a href="#" class="close">×</a>  
                        1.0_image                                  
                    </div>  -->
                </div>
                <div class="upload-img">
                    <input type="file" name="receipt_image" id="receipt_image" class="file-input-input">
                    <label class="file-input-label" for="receipt_image">
                        <img src="{{asset('web_assets/images/uplod-file.png')}}" alt="">
                        Add Image
                    </label>
                </div>
            </div>
          </div>
        </div> 
        <div class="button-list"><button type="submit" id="return_dispatch" class="w-100 btn primary-btn">Return Dispatched</button></div>
      </form>
      </div>      
    </div>
  </div>
</div> 


<!-- Return Prduct -->
<div class="modal fade return-prduct-modal" id="return-prduct" >
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Return Product</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <div class="book-price-weight" id="return_book_price_weight">
          
        </div>        
        <div class="qty-big text-center">
          <p>Add Return Quantity</p>
          <div class="qty-items">
            <input type="button" value="-" class="qty-minus">
            <input type="number" value="0" class="qty return_qty" min="0">
            <input type="button" value="+" class="qty-plus">
          </div>
        </div>
        <!-- <div class="mb-3">
            <label>Add Description</label>
            <textarea class="form-control" placeholder="Add Description" rows="4"></textarea>            
        </div> -->
        <div class="button-list mt-2 text-center">
          <input type="hidden" id="order_item_id" value="">
          <a href="" type="button" data-bs-dismiss="modal" class="btn primary-btn add_to_return_cart">Add to Return Cart</a>
        </div>
      </div>      
    </div>
  </div>
</div> 

<!-- Return Place Order -->
<div id="return-place-success" class="fade modal delete-confirm">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">        
          <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body"> 
        <div class="success-img">
          <lottie-player src="{{asset('web_assets/images/success.json')}}" background="transparent" speed="1" loop autoplay></lottie-player>
        </div>
        <h4 class="green-color">Success</h4>  
        <p>Your item has been added successfully</p>
        <div class="button-list">        
          <a href="{{route('make_my_return')}}" class="btn secondary-btn">Add More</a>
          <a href="{{route('return_cart')}}" class="btn primary-btn">Go to Return Cart</a>
        </div>
      </div>     
    </div>
  </div>
</div>


<!-- Logout Modal  -->
<div class="modal fade delete-confirm" id="logout-modal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <h5 class="modal-title" >Completed Test</h5> -->
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body text-center">        
        <p>Are you sure you want to Logout?</p>
         <div class="button-list text-center">
          <a href="javascript:void(0)" type="button" class="btn secondary-btn me-1" data-bs-dismiss="modal">No</a>
          <a href="javascript:void(0)" id="logout_btn" type="button" class="btn primary-btn">Yes</a>
        </div> 
      </div>      
    </div>
  </div>
</div>



<!-- Welcome page after signup --->
<div id="welcome_page" class="fade modal green-color delete-confirm" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">        
      </div>
      <div class="modal-body"> 
        <div class="success-img">
          <lottie-player src="{{asset('web_assets/images/success.json')}}" background="transparent" speed="1" loop autoplay></lottie-player>
        </div>
        <h4>Welcome</h4>  
        <p>Congratulations! Your account has been verified</p>     
        <div class="button-list text-center">
          <a href="{{route('web_home')}}" type="button" class="btn primary-btn">Continue</a>
        </div>    
      </div>     
    </div>
  </div>
</div>

<!-- Add to cart modal on detail page --->
<div id="add_to_cart_modal" class="fade modal delete-confirm" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">        
      </div>
      <div class="modal-body"> 
        <div class="success-img">
          <lottie-player src="{{asset('web_assets/images/success.json')}}" background="transparent" speed="1" loop autoplay></lottie-player>
        </div>
        <h4 class="green-color">Success</h4>  
        <p>Your Item has been added successfully</p>     
        <div class="button-list text-center">
          <a href="{{route('my_cart')}}" type="button" class="btn secondary-btn">Go To Cart</a>
          <a id="add_more_btn_detail" type="button" class="btn primary-btn">Add More</a>
        </div>    
      </div>     
    </div>
  </div>
</div>


<div id="usertype_change_modal" class="fade modal delete-confirm">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">        
          <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body"> 
        <div class="success-img">
          <lottie-player src="{{asset('web_assets/images/error-user.json')}}" background="transparent" speed="1" loop autoplay></lottie-player>
        </div>
        <h4 class="green-color">User Type Changed</h4>  
        <p>Your user type has been changed,  kindly go to cart and verify your order to continue.</p>

        <a href="{{route('my_cart')}}" class="btn primary-btn">Go To Cart</a>        
      </div>     
    </div>
  </div>
</div>

<!-- Share Now -->
<div class="modal fade share-popup" id="share-popup" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Share</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <div class="share-option">
       
          <ul>
            <li><a href="" id="refer_earn_facebook" target="_blank"><img src="{{asset('web_assets/images/facebook-share.svg')}}"><p>Facebook</p></a></li>
            <li><a href="" id="refer_earn_twitter" target="_blank"><img src="{{asset('web_assets/images/twitter.svg')}}"><p>Twitter</p></a></li>
            <li><a href="" id="refer_earn_wtapp" target="_blank"><img src="{{asset('web_assets/images/whatsapp.svg')}}"><p>Whatsapp</p></a></li>
            <li><a href="" id="refer_earn_instagram" target="_blank"><img src="{{asset('web_assets/images/instagram.svg')}}"><p>Instagram</p></a></li>
          </ul> 
        </div>
      </div>      
    </div>
  </div>
</div>

<!-- Order Cancel Modal  -->
<div class="modal fade" id="order-cancel-confirm" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <h5 class="modal-title" >Completed Test</h5> -->
      <!--   <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button> -->
      </div>
      <div class="modal-body text-center">
        <!-- <div class="img"><img src="images/right-arrow.svg" alt=""></div> -->
        <p>Are you sure you want to cancel the transaction?<br>
        OR <br> you need to wait for {{ floor(\App\Models\Setting::get('payu_job_delay_in_seconds'))/60}} minute to confirm this transaction status.</p>
         <div class="button-list text-center">
          <a id="no_btn" type="button" class="btn secondary-btn me-1">No</a>
          <a type="button" id="cancel_transaction" class="btn primary-btn">Yes</a>
        </div> 
      </div>      
    </div>
  </div>
</div>
