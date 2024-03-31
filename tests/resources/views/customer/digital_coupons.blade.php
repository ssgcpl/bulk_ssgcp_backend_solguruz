@extends('customer.layouts.app')

@section('content')

<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('web_home')}}">Home</a></li>          
          <li class="breadcrumb-item active"> <a href="{{ route('digital_coupons_list')}}"> Digital Coupons  </a> </li>
        </ol> 
      </div>
  </section> 

  <section class="digital-coupon white-bg">
    <div class="container">
      <div class="section-title">
        <h2>My Digital Coupons</h2>
        <a href="javascript:void(0)" id="coupon_filter" title="" data-bs-toggle="modal" data-bs-target="#filter-modal"><img src="{{asset('web_assets/images/filter.svg')}}" alt=""></a>
      </div>
      <div class="no-data-found">
        <div class="box">
          <img src="{{asset('web_assets/images/empty-coupon.svg')}}" alt="">
          <h5>No Coupon Found</h5>
          <p>You haven't bought any coupons so far.</p>
        </div>
      </div>
      <div class="row" id="digital_coupons_list">
       
      </div>  
    </div>
  </section>
</div>
<!-- Filter Digital Coupon -->
<div class="modal fade filter-modal" id="filter-modal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >FILTER</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body"> 
        <h6 class="title">Status</h6>
        <div class="mb-3">
          <div class="common-radio">
            <label class="radio-box">                    
              <input type="checkbox" name="order_status" value="under_process"><span class="checkmark"></span>
              <div class="text">Under Process</div>
            </label>  
          </div>
          <div class="common-radio">
            <label class="radio-box">                    
              <input type="checkbox" name="order_status" value="completed"><span class="checkmark"></span>
              <div class="text">Completed</div>
            </label>  
          </div>
           <div class="common-radio">
            <label class="radio-box">                    
              <input type="checkbox" name="order_status"  value="cancelled"><span class="checkmark"></span>
              <div class="text">Cancelled</div>
            </label> 
          </div>
           <div class="common-radio">
            <label class="radio-box">                    
              <input type="checkbox" name="order_status"  value="refunded"><span class="checkmark"></span>
              <div class="text">Refunded</div>
            </label> 
          </div>
           <div class="common-radio">
            <label class="radio-box">                    
              <input type="checkbox" name="order_status"  value="failed"><span class="checkmark"></span>
              <div class="text">Failed</div>
            </label> 
          </div>
        </div> 
        <h6 class="title">Type</h6>
        <div class="mb-3"> 
           <div class="common-check">
            <label class="checkbox"><span>Book</span>
              <input type="checkbox"  name="order_type" value="books"><span class="checkmark"></span>
            </label> 
          </div>
          <div class="common-check">
            <label class="checkbox"><span>E - Book</span>
              <input type="checkbox"  name="order_type" value="e_books"><span class="checkmark"></span>
            </label> 
          </div>
          <div class="common-check">
            <label class="checkbox"><span>Video Lectures</span>
              <input type="checkbox"  name="order_type" value="videos"><span class="checkmark"></span>
            </label> 
          </div> 
          <div class="common-check">
            <label class="checkbox"><span>Online Test</span>
              <input type="checkbox"  name="order_type" value="tests"><span class="checkmark"></span>
            </label> 
          </div>
          <div class="common-check">
            <label class="checkbox"><span>Courses</span>
              <input type="checkbox"  name="order_type" value="courses"><span class="checkmark"></span>
            </label> 
          </div>  
          <div class="common-check">
            <label class="checkbox"><span>Packages</span>
              <input type="checkbox"  name="order_type" value="packages"><span class="checkmark"></span>
            </label> 
          </div> 
         <!--  <div class="common-check">
            <label class="checkbox"><span>Daily Quiz</span>
              <input type="checkbox"  name="order_type" value="quizzes"><span class="checkmark"></span>
            </label> 
          </div>  -->
        </div> 
      </div> 
      <div class="custom-footer text-center"> 
        <button type="button" id="clear_all" class="btn secondary-btn me-1"><i class="far fa-times-circle me-1"></i>Clear All</button>
        <button type="button" id="apply_filter_btn" class="btn primary-btn ms-1"><i class="far fa-check-circle me-1"></i>Apply</button>
      </div>      
    </div>
  </div>
</div>

@endsection