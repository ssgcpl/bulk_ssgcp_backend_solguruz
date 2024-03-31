@extends('customer.layouts.app')

@section('content')

<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('web_home')}}">Home</a></li>          
          <li class="breadcrumb-item active"> <a href="{{ route('latest_digital_coupons', @$business_category->id) }}"> {{@$business_category->category_name}} </a> </li>
        </ol> 
      </div>
  </section> 

  <section class="shop-our-book white-bg">
    <div class="container">
      <div class="section-title">
        <h2>{{@$business_category->category_name}}</h2>
        <input type="hidden" id="business_category_id" value="{{@$business_category->id}}">
      </div>
      <div class="tag-list">
          <ul class="selected_category">
          </ul>
      </div>
      <div class="tag-list" id="main_tag_list">
      </div>
      <div class="tag-list" id="tag_list">
        <ul class="sub-cate">
        </ul>
      </div>
    
    <div class="row" id="digital_coupons_list">
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