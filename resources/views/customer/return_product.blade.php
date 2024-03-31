@extends('customer.layouts.app')

@section('content')
<div id="preloader" class="fade-out d-none"><img src="{{asset('web_assets/images/loader-img.svg')}}" alt=""></div>
<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('web_home')}}">Home</a></li>          
          <li class="breadcrumb-item active"> <a href="{{ route('return_placed',$id) }}">Return Product</a>  </li>
        </ol> 
      </div>
  </section> 

  <section class="return-product">
    <div class="container">
      <div class="row align-items-center justify-content-center">
        <div class="col-xl-7 col-lg-7 col-md-12">
          <div class="section-title justify-content-center"><h2>Return Product</h2></div>
           <input type="hidden" name="hdn_order_return_id" id="hdn_order_return_id" value="{{$id}}">
          <div class="courier-details">
            <div class="head">Courier Details</div>
            <ul>
              <li>
                <div class="icon"><i class="fas fa-building"></i></div>
                <div class="detail">
                  <h6>Return Address</h6>
                  <p>{{\App\Models\Setting::get('order_return_address')}}</p>                  
                </div>
              </li>
              <li>
                <div class="icon"><i class="fas fa-phone-alt"></i></div>
                <div class="detail">
                  <h6>Receiver Contact number</h6>
                  <p><a href="#" title="">+91 {{\App\Models\Setting::get('order_return_contact_number')}}</a></p>                  
                </div>
              </li>
            </ul>
          </div>
          
          <div class="order-list white-bg">
            <div class="head">
              <p><label>Order ID:</label><span id="order_id"></span></p>
              <div><span class="theme-color" id="status"></span></div>
            </div>
            <div class="details"> 
              <ul>
                <li><label>Return Date</label><span id="date"></span></li>
                <li><label>Total Return Qty</label><span id="return_qty"></span></li>
                <li><label>Total Price</label>₹<span id="total_sale_price"></span></li>
              </ul>
            </div>
          </div>
          <div class="return-pro-list white-bg">   
            <h6>Added Items</h6> 
          
            <!-- <div class="list">
              <div class="secondary-color">Return Reason</div>
              <p>Lorem Ipsum is simply dummy</p>                
            </div>
            <div class="list">
              <div class="secondary-color">Description</div>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>                
            </div> -->
          </div>
        
          <div class="text-center mt-3"><a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#return-placed"  class="btn primary-btn dispatch" id="{{$id}}" title="">Next</a></div>
        </div>
      </div>
    </div>
  </section> 

</div>
@endsection