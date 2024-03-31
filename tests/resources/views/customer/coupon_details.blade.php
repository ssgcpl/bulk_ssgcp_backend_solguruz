@extends('customer.layouts.app')

@section('content')

<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('web_home')}}">Home</a></li>          
          <li class="breadcrumb-item active">  <a href="{{ route('digital_coupon_details', $id) }}" > Digital Coupon  Detail  </a></li>
        </ol> 
      </div>
  </section> 

  <section class="product-detail white-bg">
    <div class="container">
      <div class="row">
        <input type="hidden" name="hdn_sub_coupon_id" id="hdn_sub_coupon_id" value="{{$id}}">
        <div class="col-xl-5 col-lg-5 col-md-12">
          <div class="product-slider">
           <div id="slider" class="pro-slide">
           </div>
            <div class="button-list" id="buttons">
              <div class="qty-items">
                <input type="button" value="-" class="qty-minus">
                <input type="number" value="" id="quantity" class="qty" min="0" max="10" >
                <input type="button" value="+" class="qty-plus">
              </div>
           
            </div>
          </div>
        </div>
        <div class="col-xl-7 col-lg-7 col-md-12">
          <div class="product-content">
            <h1 id="heading"></h1>          
            <!-- <p class="secondary-color" id="description"></p> -->
            <div class="book-price-weight">
              <ul>
                <li><label><img class="rupee" src="{{asset('web_assets/images/book-price.svg')}}" alt=""> Item Price</label><div class="price"></div></li>                
                <li><label>Item Type</label><p id="item_type"></p></li>
              </ul>
            </div>
            <div class="desc">
              <h6 id="desc_heading">Description</h6>
              <p id="description"> <!-- <a href="javascript:void(0)" class="theme-color" title="">Read More</a> --></p>
            </div>
            <div class="theme-red-color">Expiry Date: <span id="expiry_date"></span></div>      
          </div>
        </div>
      </div>
    </div>
  </section> 
</div>
@endsection