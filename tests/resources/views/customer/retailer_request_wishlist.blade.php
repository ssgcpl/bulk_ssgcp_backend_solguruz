@extends('customer.layouts.app')

@section('content')

<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('web_home')}}">Home</a></li>          
          <li class="breadcrumb-item">Retailer Request</li>
          <li class="breadcrumb-item active"><a href="{{ Route::currentRouteName() }}">Wish Request</a> </li>
        </ol> 
      </div>
  </section> 

  <section class="order-placed-page white-bg">
    <div class="container"> 
      <div class="page-title"><h1>Wish List</h1></div>
       
      <div class="search-field">
        <div class="box">                  
          <input type="search" class="form-control searchbox" name="" placeholder="Search here...">
          <button class="search-btn"><img src="{{asset('web_assets/images/search.svg')}}" alt=""></button>
        </div>
      </div> 
      <div class="row" id="request_wishlist">
      </div> 
       
        <div id="no_data" class="no-data-found d-none">
                <div class="box">
                        <img src="{{ asset('web_assets/images/no-purchase-yet.png') }}" alt="" />
                        <h5>Sorry! No Result Found :)</h5>
                        <p>We Couldn't Find What You're Looking For</p>
                </div>
            </div>
  </div>
   
    </div> 
  </section>
</div>
@endsection