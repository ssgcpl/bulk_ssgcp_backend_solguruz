@extends('customer.layouts.app')

@section('content')


<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('web_home')}}">Home</a></li>          
          <li class="breadcrumb-item active"><a href="{{ route('digital_coupon_detail_purchased', $id) }}" > Digital Coupon Order Detail  </a></li>
        </ol> 
      </div>
  </section> 

  <section class="digital-coupon">
    <div class="container">
      <div class="section-title">
        <h2>Digital Coupon Order Detail</h2>
        <!-- <a href="#" title="" data-bs-toggle="modal" data-bs-target="#filter-modal"><img src="images/filter.svg" alt=""></a> -->
      </div>
      <div class="row">
          <input type="hidden" id="hdn_order_id" name="hdn_order_id"  value="{{$id}}">
        <div class="col-xl-6 col-lg-6 col-md-12 col-12"> 
          <div class="order-list">
            <div class="head">
              <p><label>Purchased ID:</label><span id="order_id"></span></p>
              <div><span class="theme-color" id="status"></span></div>
            </div>
            <div class="details"> 
              <ul>
                <li><label>Purchased Date</label><span id="order_date"></span></li>
                <li><label>Coupon Amount</label>₹ <span id="amount"></span></li>
                <li><label>Purchased Time</label><span id="order_time"></span></li>
              </ul>
            </div>
          </div>
          <div  id="coupon_items">
          </div>
        </div>

        <div class="col-xl-6 col-lg-6 col-md-12 col-12"> 
          <div class="coupon-listing white-bg">
            <div class="custom-tab-box">                  
              <ul class="nav custom-nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <a class="nav-link active" id="tab1" data-bs-toggle="tab" href="#tab11" role="tab" aria-controls="tab11" aria-selected="true">Available Coupons (<span id="available_count"></span>)</a>
                </li>
                <li class="nav-item" role="presentation">
                  <a class="nav-link" id="tab2" data-bs-toggle="tab" href="#tab22" role="tab" aria-controls="tab22" aria-selected="false">Sold Coupons (<span id="sold_count"></span>)</a>
                </li>          
              </ul>
            </div>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="tab11" role="tabpanel" aria-labelledby="tab1">
                <div class="coupon-list">
                  <ul class="list" id="available_coupons_list">
                 <!--    <li>
                      <div class="box">
                        <a href="#" class="btn code">SSGC8745 <img src="images/coupon-code.svg"></a>
                        <div class="btn redeemed">Expired</div>                        
                      </div>
                    </li>
                    <li>
                      <div class="box">
                        <a href="#" class="btn code">SSGC8745 <img src="images/coupon-code.svg"></a>
                        <div class="btn redeemed">Expired</div>
                      </div>
                    </li>
                    <li>
                      <div class="box">
                        <a href="#" class="btn code">SSGC8745 <img src="images/coupon-code.svg"></a>
                        <div class="btn redeemed">Expired</div>
                      </div>
                    </li>
                    <li>
                      <div class="box">
                        <a href="#" class="btn code">SSGC8745 <img src="images/coupon-code.svg"></a>
                        <div class="btn redeemed">Expired</div>
                      </div>
                    </li> -->
                  </ul>
                </div>
              </div>
              <div class="tab-pane fade" id="tab22" role="tabpanel" aria-labelledby="tab2">
                <div class="coupon-list">
                  <ul class="list" id="sold_coupons_list">
                 <!--    <li>
                      <div class="info">
                        <div class="list">
                          <div class="secondary-color">Full Name</div>
                          <p>Andrew Smith</p>                
                        </div>
                        <div class="list">
                          <div class="secondary-color">Mobile Number</div>
                          <p>+91 9755 319 215</p>                
                        </div>
                        <div class="list">
                          <div class="secondary-color">Price</div>
                          <p>₹ 85</p>                
                        </div>
                      </div>
                      <div class="box">
                        <a href="#" class="btn code red">SSGC8745 <img src="images/coupon-code.svg"></a>
                        <div class="btn redeemed">Redeemed</div>
                      </div>
                    </li>
                    <li>
                      <div class="info">
                        <div class="list">
                          <div class="secondary-color">Full Name</div>
                          <p>Andrew Smith</p>                
                        </div>
                        <div class="list">
                          <div class="secondary-color">Mobile Number</div>
                          <p>+91 9755 319 215</p>                
                        </div>
                        <div class="list">
                          <div class="secondary-color">Price</div>
                          <p>₹ 85</p>                
                        </div>
                      </div>
                      <div class="box">
                        <a href="#" class="btn code">SSGC8745 <img src="images/coupon-code.svg"></a>
                        <a href="#" class="btn primary-btn">Share <i class="icon-share ms-2"></i></a>
                      </div>
                    </li>  -->
                  </ul>
                </div>
              </div>
            </div>

          </div>
        </div>
          
      </div>  
    </div>
  </section>
 

</div>

<!-- Filter Digital Coupon -->
<div class="modal fade qr-code-modal" id="qrcode-modal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <h5 class="modal-title" >QR CODE</h5> -->
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <p>E-Book Coupon</p> 
        <h6 class="modal-title" >QR CODE</h6> 
        <div class="img"><img src="" id="qr_image" alt="image"></div>
        <div class="coupon-code">
          <a href="javascript:void(0)" class="btn code" id="qr_code"></a>
        </div>
      </div> 
      <div class="custom-footer text-center">         
        <a href="javascript:void(0)"  class="btn primary-btn sale_coupon">Sale Coupon</a>
      </div>      
    </div>
  </div>
</div>



<!-- Add Customer Details -->
<div class="modal fade add-customer-detail" id="add-customer-modal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >Add Customer Detail</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <form id="saleCouponForm" method="post" enctype="multipart/form-data">
      <div class="modal-body">
        <input type="hidden" name="qr_id" id="qr_id" >
        <div class="mb-3">
          <label>Full Name</label>
          <input type="text" id="customer_name" name="customer_name" class="form-control" placeholder="Full Name" required="">
        </div>
        <div class="mb-3">
          <label>Mobile Number</label>
          <input type="text" id="customer_contact" class="form-control" placeholder="Mobile Number" name="customer_contact" required="">
        </div>
        <div class="mb-3">
          <label>Price</label>
          <input type="text" class="form-control" id="sale_price" name="sale_price" placeholder="Price" name="" required="">
        </div>
       </div> 
      <div class="custom-footer text-center">         
        <button type="submit" id="sale_coupon_btn" class="btn primary-btn">Submit & Share <i class="icon-share ms-2"></i></button>
      </div>      
    </div>
    </form>
   
  </div>
</div>

@endsection