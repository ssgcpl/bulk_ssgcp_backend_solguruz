@extends('customer.layouts.app')

@section('content')
<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('web_home')}}">Home</a></li>          
          <li class="breadcrumb-item active"><a href="{{ Route::currentRouteName() }}"> Trending Books </a></li>
        </ol> 
      </div>
  </section> 

  <section class="shop-our-book white-bg">
    <div class="container">
      <div class="section-title">
        <h2>Trending Books</h2>
      </div>
      <div class="row" id="trending_books">
        
      </div> 
      <div id="no_data" class="no-data-found d-none">
          <div class="box">
                  <img src="{{ asset('web_assets/images/no-purchase-yet.png') }}" alt="" />
                  <h5>Sorry! No Result Found :)</h5>
                  <p>We Couldn't Find What You're Looking For</p>
          </div>
      </div>
    </div>
  </section>
 

</div>
@endsection