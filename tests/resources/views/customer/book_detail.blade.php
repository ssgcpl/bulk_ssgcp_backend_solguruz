<!DOCTYPE html>
<html lang="en">
@extends('customer.layouts.app')

@section('content')
<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('web_home')}}">Home</a></li>          
          <li class="breadcrumb-item active"> <a href="{{ route('book_detail', @$book_id) }}"> Book Details </a> </li>
        </ol> 
      </div>
  </section> 

  <section class="product-detail white-bg">
    <div class="container">
      <input type="hidden" id="book_id" value="{{$book_id}}">
      <div class="row">
        <div class="col-xl-5 col-lg-5 col-md-12">
          <div class="product-slider">
            <div class="pro-slide" id="book_images">
              
            </div>
            <div class="button-list" id="buttons">
              
            </div>
          </div>
        </div>
        <div class="col-xl-7 col-lg-7 col-md-12">
          <div class="product-content">
            <h1 id="name"></h1>          
            <p class="secondary-color" id="sub_heading"></p>
            <div class="book-price-weight">
              
            </div>
            <div class="desc">
              <h6 id="desc_heading">Description</h6>
              <p id="description"> </p>
            </div>
            <div class="last-returnable">              
              <div class="img">
                <lottie-player src="{{asset('web_assets/images/last-date.json')}}" background="transparent" speed="1" loop autoplay></lottie-player>
              </div>
              <div class="text">
                <h6>Last Returnable Date:</h6>
                <p id="last_returnable"></p>
              </div>
            </div>
            <div class="desc">
              <h6 id="add_info_heading">Additional Information</h6>
              <p id="additional_info"></p> 
            </div>            
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <section class="related-product white-bg">
    <div class="container">
      <div class="section-title"><h2>Related Product</h2></div>
      <div class="related-slider" id="related_products">
         
      </div>
    </div>
  </section>

</div>
@endsection
