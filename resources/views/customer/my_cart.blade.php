@extends('customer.layouts.app')

@section('content')

<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('web_home')}}">Home</a></li>  
          <li class="breadcrumb-item active"><a href="{{ Route::currentRouteName() }}"> My Cart </a> </li>
        </ol> 
      </div>
  </section> 

  <section class="my-cart">     
    <div class="container">
      <div class="page-title"><h1>My Cart</h1></div>
        <div class="no-data-found d-none">
          <div class="box">
            <img src="{{asset('web_assets/images/empty-cart.svg')}}" alt="">
            <h5>Whoops! You haven't added anything yet.</h5>
            <p>Go back and find some exciting stuff to add to your cart.</p>

            <a href="{{route('web_home')}}" title="" class="btn primary-btn">Go To Products</a>
          </div>
        </div>

        <div class="row" id="cart_data">
          <div class="col-xl-8 col-lg-8 col-md-12 col-12" id="cart_items">
            
          </div>
          <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
            <div class="sticky-cart">
              <div class="cart-summary white-bg"> 
               
              </div>
              <div class="summary-action">
                <!-- <a href="{{route('latest_digital_coupons')}}" class="btn secondary-btn me-1">Add More</a>
                <a href="{{route('coupons_checkout')}}" class="btn primary-btn ms-1"><i class="icon-bag me-2"></i>Checkout</a> -->

                <a href="{{route('search')}}?type=books" id="add_more_btn" class="btn secondary-btn me-1">Add more</a>
                <a href="javascript:void(0)" id="checkout_btn" class="btn primary-btn ms-1"><i class="icon-bag me-2"></i>Checkout</a>
              </div>
            </div>
          </div>
        </div>
    </div>
  </section>

</div>
@endsection