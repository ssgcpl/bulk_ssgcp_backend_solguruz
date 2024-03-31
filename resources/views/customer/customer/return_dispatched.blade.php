@extends('customer.layouts.app')

@section('content')
<div class="main-wapper">
   <div id="preloader" class="fade-out d-none"><img src="{{asset('web_assets/images/loader-img.svg')}}" alt=""></div>
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('web_home')}}">Home</a></li>
          <li class="breadcrumb-item active"> <a href="{{ Route::currentRouteName() }}">Return Dispatched</a>  </li>
        </ol> 
      </div>
  </section> 

  <section class="return-product">
    <div class="container">
      <div class="row align-items-center justify-content-center">
        <div class="col-xl-7 col-lg-7 col-md-12">
          <div class="section-title justify-content-center"><h2>Return Dispatched</h2></div>
          <input type="hidden" name="hdn_order_return_id" id="hdn_order_return_id" value="{{$id}}">
          <div class="order-list white-bg">
            <div class="head">
              <p><label>Order ID:</label><span id="order_id"></span></p>
              <div><span class="text-warning" id="status"></span></div>
            </div>
            <div class="details"> 
              <ul>
                <li><label>Return Date</label><span id="date"></span></li>
                <li><label>Total Return Qty</label><span id="return_qty"></span></li>
                <li><label>Total Price</label>₹<span id="total_sale_price"></span></li>
              </ul>
            </div>
          </div>
          <div class="return-pro-list white-bg" >   
            <h6>Added Items</h6> 
        
           <!--  <div class="list">
              <div class="secondary-color">Return Reason</div>
              <p>Lorem Ipsum is simply dummy</p>                
            </div>
            <div class="list">
              <div class="secondary-color">Description</div>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>                
            </div> -->
          </div>
          
    <!--       <div class="return-pro-list white-bg">            
            <div class="return-product">
              <div class="img"><img src="images/book/book3.png" alt=""></div>
              <div class="details">
                <h6 class="mb-4">AUTHORITY</h6>
                <div class="box">
                  <div class="list">
                    <div class="secondary-color">Return Qty</div>
                    <p>50</p>                
                  </div>
                  <div class="list">
                    <div class="secondary-color">Price</div>
                    <p>₹ 5,000</p> 
                  </div>
                </div> 
              </div>  
            </div> 
          </div>  
            -->
        </div>
      </div>
    </div>
  </section> 

</div>
@endsection