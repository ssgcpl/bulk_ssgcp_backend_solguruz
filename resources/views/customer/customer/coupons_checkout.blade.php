@extends('customer.layouts.app')

@section('content')

<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('web_home')}}">Home</a></li>
          <li class="breadcrumb-item active">Coupons Checkout</li>
        </ol> 
      </div>
  </section> 

  <section class="my-cart">     
    <div class="container">
      <div class="page-title"><h1>Coupons Checkout</h1></div>
      <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-12 col-12" >
          <div class="cart-list white-bg"  id="cart_items">
          </div>
          <div class="erned-point white-bg my-3" id="earned_points_div">
            <p>You have total earned points: <span class="green-color" id="earned_points"></span></p>
            <div class="common-check">
                <label class="checkbox">Use your coin points <span class="green-color" id="points"></span>
                   <input type="checkbox" id="redeem"><span class="checkmark"></span>
                </label>
            </div>            
          </div>
          <div class="erned-point white-bg my-3" id="payment_method">
            <h6>Payment</h6>
          </div>
    </div>
        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
          <div class="sticky-cart">
            <div class="cart-summary white-bg"> 
            </div>
            <div class="summary-action">              
              <a id="pay_now" class="btn primary-btn ms-1"></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</div>
@endsection