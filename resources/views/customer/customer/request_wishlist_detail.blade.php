@extends('customer.layouts.app')

@section('content')

<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('web_home')}}">Home</a></li>          
          <li class="breadcrumb-item active"><a href="{{ Route::currentRouteName() }}">Wish Order Detail</a>l</li>
        </ol> 
      </div>
  </section> 

  <section class="my-cart">     
    <div class="container">
      <div class="page-title"><h1>Wish Order Detail</h1></div>
      <input type="hidden" name="hdn_wishlist_request_id" ID="hdn_wishlist_request_id"  value="{{$wish_list_request_id}}">
      <div class="row">
        <div class="col-xl-7 col-lg-7 col-md-12 col-12">
          <div class="order-list">
            <div class="head">
              <p><label>Retailer Name:</label><span id="retailer_name"></span></p> 
              <!-- <div><span class="theme-color">Ongoing</span></div> -->
            </div>
            <div class="details"> 
              <div class="row">
                <div class="col-4">
                  <div class="list mb-2">
                    <div class="secondary-color">Date</div>
                    <p id="date"></p>                
                  </div>
                </div>
                <div class="col-4">
                  <div class="list mb-2">
                    <div class="secondary-color">Time</div>
                    <p id="time"></p>                
                  </div>
                </div>
                <div class="col-4">
                  <div class="list mb-2">
                    <div class="secondary-color">Wish Quantity</div>
                    <p><span id="wish_quantity"></span></p>                
                  </div>
                </div>
                <div class="col-4">
                  <div class="list mb-2">
                    <div class="secondary-color">Full Name</div>
                    <p id="full_name"></p>                
                  </div>
                </div>
                <div class="col-8">
                  <div class="list mb-2">
                    <div class="secondary-color">Phone Number</div>
                    <p>+91 <span id="contact_number"></span></p>                
                  </div>
                </div>
                <div class="col-12">
                  <div class="list mb-2">
                    <div class="secondary-color">Address</div>
                    <p id="address"></p>                
                  </div>
                </div>
              </div>
            </div>
          </div>
          
        </div>
        <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12">
          <h6>Wish List Items</h6>
          <div class="order-detail white-bg">                        
            <div class="box">
              <div class="img">
                <a href="order-detail-view.php"><img id="product_img" src="" alt=""></a> 
              </div>
              <div class="details">
                <div class="head">
                  <h5><a href="order-detail-view.php" title="" id="product_name"></a></h5>
                  <!-- <p class="secondary-color" id="product_name"></p>                 -->
                </div>
                <div class="price-qty">
                  <div class="sale-price">₹ <span id="sale_price"></span></div>
                  <div class="qty-bulk">Qty.<span id="qty"></span></div>
                </div>
               <!--  <div><span  class="secondary-color">Weight:</span> 2 K.G Per Book</div>  -->
              </div> 
            </div> 
          </div>
        </div>
      </div>
    </div>
  </section>

</div>

<!-- Add to Cart Again -->
<div class="modal fade order-again" id="order-again" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >Order Again</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xl-6"> 
            <div class="product-list-box">
                <div class="img"><a href="#" title=""><img src="images/book/book1.png" alt=""></a></div>
                <div class="detail">
                  <h6><a href="#" title="">Ganeral Mathematics</a></h6> 
                  <div class="out-of-stock">
                    <div class="sale-price">₹70<span>₹120</span></div> 
                    <div class="theme-red-color out">Out of stock</div>
                  </div>
                  <div class="qty-items">
                    <input type="button" value="-" class="qty-minus">
                    <input type="number" value="1" class="qty" min="0" max="10" >
                    <input type="button" value="+" class="qty-plus">
                  </div>                  
                </div>
              </div>
          </div>
          <div class="col-xl-6"> 
            <div class="product-list-box">
                <div class="img"><a href="#" title=""><img src="images/book/book2.png" alt=""></a></div>
                <div class="detail">
                  <h6><a href="#" title="">Ganeral Mathematics</a></h6> 
                  <div class="out-of-stock">
                    <div class="sale-price">₹70<span>₹120</span></div> 
                    <div class="theme-red-color out">Out of stock</div>
                  </div>
                  <div class="qty-items">
                    <input type="button" value="-" class="qty-minus">
                    <input type="number" value="1" class="qty" min="0" max="10" >
                    <input type="button" value="+" class="qty-plus">
                  </div> 
                  <button class="btn secondary-btn">Add to Wishlist</button>
                </div>
              </div>
          </div>
          <div class="col-xl-6"> 
            <div class="product-list-box">
                <div class="img"><a href="#" title=""><img src="images/book/book3.png" alt=""></a></div>
                <div class="detail">
                  <h6><a href="#" title="">Ganeral Mathematics</a></h6> 
                  <div class="out-of-stock">
                    <div class="sale-price">₹70<span>₹120</span></div> 
                    <div class="theme-red-color out">Out of stock</div>
                  </div>
                  <div class="qty-items">
                    <input type="button" value="-" class="qty-minus">
                    <input type="number" value="1" class="qty" min="0" max="10" >
                    <input type="button" value="+" class="qty-plus">
                  </div> 
                  <button class="btn secondary-btn">Add to Wishlist</button>
                </div>
              </div>
          </div>
          <div class="col-xl-6"> 
            <div class="product-list-box">
                <div class="img"><a href="#" title=""><img src="images/book/book4.png" alt=""></a></div>
                <div class="detail">
                  <h6><a href="#" title="">Ganeral Mathematics</a></h6> 
                  <div class="out-of-stock">
                    <div class="sale-price">₹70<span>₹120</span></div> 
                    <div class="theme-red-color out">Out of stock</div>
                  </div>
                  <div class="qty-items">
                    <input type="button" value="-" class="qty-minus">
                    <input type="number" value="1" class="qty" min="0" max="10" >
                    <input type="button" value="+" class="qty-plus">
                  </div> 
                  <button class="btn secondary-btn">Add to Wishlist</button>
                </div>
              </div>
          </div>
        </div> 
        <div class="button-list mt-2 text-center"><a href="my-cart.php" type="button" class=" btn primary-btn">Add to Cart</a></div>
      </div>      
    </div>
  </div>
</div> 
 
@endsection