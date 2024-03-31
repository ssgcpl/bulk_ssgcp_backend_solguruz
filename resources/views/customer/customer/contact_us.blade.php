@extends('customer.layouts.app')

@section('content')

<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('web_home')}}">Home</a></li>          
          <li class="breadcrumb-item active">Contact Us</li>
        </ol> 
      </div>
  </section> 



  <section class="contact-page white-bg">
    <div class="contact-banner">     
      <div class="container">
        <div class="page-title text-white justify-content-center"><h1>CONTACT US</h1></div> 
        <div class="img-box"> 
          <img src="{{asset('web_assets/images/contact-us.svg')}}" alt="">
        </div>
      </div>
    </div>

    <div class="container-xl">      
      <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-12">
          <div class="contact-info"> 
            <h5 class="">In case of any query, Feel free to contact us</h5>
            <ul>
              <li>
                <div class="box">
                  <div class="img"><a href="tel:{{\App\Models\Setting::get('customer_care_no')}}" title=""><i class="icon-call-big"></i></a></div>
                  <div class="detail">
                    <div class="secondary-color">Support Phone Number</div>
                    <p class="mb-0"><a href="tel:{{\App\Models\Setting::get('customer_care_no')}}" title="">+91 {{\App\Models\Setting::get('customer_care_no')}}</a></p>
                  </div>
                </div>
              </li>
              <li>
                <div class="box">
                  <div class="img"><a href="mailto:{{\App\Models\Setting::get('contact_email')}}" title=""><i class="icon-send"></i></a></div>                  
                  <div class="detail">
                    <div class="secondary-color">Support Email Address</div>
                    <p class="mb-0"><a href="mailto:{{\App\Models\Setting::get('contact_email')}}" title="">{{\App\Models\Setting::get('contact_email')}}</a></p>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-xl-8 col-lg-8 col-md-12">
          <div class="contact-form">
            <h5>Contact Support</h5>            
            <form id="contactSupportForm" method="post" enctype="multipart/form-data">
              <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-12 col-12">
                  <div class="form-group mb-3">
                    <label>Full Name<span class="required-star">*</span></label>
                    <input type="text" id="full_name" class="form-control" placeholder="Full Name" name="full_name" required="">
                  </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-12 col-12">
                  <div class="form-group mb-3">
                    <label>Mobile Number<span class="required-star">*</span></label>
                    <input type="text" maxlength="10" class="form-control" placeholder="Mobile Number" id="mobile_number" name="mobile_number" required="">
                  </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-12 col-12">
                  <div class="form-group mb-3">
                    <label>Email ID<span class="required-star">*</span></label>
                    <input type="email" class="form-control" placeholder="Email ID" id="email" name="email" required="">
                  </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-12 col-12">
                  <div class="form-group mb-3">          
                    <label>Select Reason<span class="required-star">*</span></label>
                    <select name="reason_id" id="reason_id" class="form-select" aria-label="Default select example" required>
                    </select>
                  </div>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                  <div class="form-group mb-3">
                    <label>Description<span class="required-star">*</span></label>
                    <textarea class="form-control" id="message" name="message" placeholder="Write here..." required></textarea>
                  </div>
                </div>
              </div> 
              <div class="text-center mb-3">
                  <button type="submit" id="submit_btn"  class="w-100 btn primary-btn">Submit</button><br><br>

                   <a href="{{route('ticket_history')}}" title="" class="theme-color text-decoration-underline" id="ticket_history_link">View support history</a>
              </div>
              </form>
             
          </div>
        </div>
      </div>
    </div>
  </section>
 

</div>
@endsection
