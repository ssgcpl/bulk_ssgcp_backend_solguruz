@extends('customer.layouts.app')

@section('content')

<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('web_home')}}">Home</a></li>          
          <li class="breadcrumb-item active"> <a href="{{ Route::currentRouteName() }}">Return Product Cart</a> </li>
        </ol> 
      </div>
  </section> 

  <section class="return-product">
    <div class="container">
      <div class="no-data-found d-none">
        <div class="box">
          <img src="{{asset('web_assets/images/empty-cart.svg')}}" alt="">
          <h5>Whoops! You haven't added anything yet.</h5>
          <p>Go back and find some exciting stuff to add to your cart.</p>

          <!-- <a href="{{route('web_home')}}" title="" class="btn primary-btn">Go To Products</a> -->
        </div>
      </div>
      <div class="row align-items-center justify-content-center">
        <div class="col-xl-7 col-lg-7 col-md-12" id="return_products">
          
        </div>
      </div>
    </div>
  </section> 

</div>
@endsection