@extends('customer.layouts.app')

@section('content')
<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('web_home')}}">Home</a></li>          
          <li class="breadcrumb-item active"> <a href="{{ route('order_book_view', $order_item_id) }}" > Book Details </a> </li>
        </ol> 
      </div>
  </section> 

  <section class="product-detail white-bg">
    <div class="container">
      <div class="row">
        <input type="hidden" id="order_item_id" value="{{$order_item_id}}">
        <input type="hidden" id="book_id" value="{{$book_id}}">
        <div class="col-xl-5 col-lg-5 col-md-12">
          <div class="product-slider">
            <div class="pro-slide" id="book_images">
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
            <div class="qty-bulk" id="quantity">Qty.{{$ordered_quantity}}</div>
            <div class="item-list">
              <div class="list">
                <div class="secondary-color">Returnable Percentage(%):</div>
                <p class="green-color" id="returnable_qty"></p>                
              </div>
              <div class="list">
                <div class="secondary-color">Last Returnable Date:</div>
                <span class="theme-red-color" id="last_returnable_date"></span>
              </div>
            </div>
            <div class="desc">
              <h6 id="add_info_heading">Additional Information</h6>
              <p id="additional_info"></p> 
            </div>            
            <div class="mt-2" id="return_product_btn"></div>
          </div>
        </div>
      </div>
    </div>
  </section> 

</div>
@endsection