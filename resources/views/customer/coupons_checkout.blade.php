@extends('customer.layouts.app')

@section('content')

<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('web_home')}}">Home</a></li>
          <li class="breadcrumb-item active"><a href="{{ route('coupons_checkout')}}">Coupons Checkout</a></li>
        </ol> 
      </div>
  </section> 

  <section class="my-cart">     
    <div class="container">
      <div class="page-title"><h1>Coupons Checkout</h1></div>
      <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-12 col-12" >
          <div class="cart-list white-bg"  id="cart_items">
          </div>
          <div class="erned-point white-bg my-3" id="earned_points_div">
            <p>You have total earned points: <span class="green-color" id="earned_points"></span></p>
            <div class="common-check">
                <label class="checkbox">Use your coin points <span class="green-color" id="points"></span>
                   <input type="checkbox" id="redeem"><span class="checkmark"></span>
                </label>
            </div>            
          </div>
          <div class="default-address my-3">
            <div class="same-billing white-bg">
              <h6>Billing Address <a href="javascript:void(0);" title="" id="" class="theme-color d-none edit_address" ><i class="icon-edit-address"></i></a></h6>
              <input type="hidden" id="billing_address_id" value="">
              <label id="default_address">

              </label>
              <div id="add_address"></div>
            </div>

            <div class="same-billing white-bg">
              <h6>Shipping Address (Optional) <a href="javascript:void(0);" title="" id="" class="theme-color d-none edit_shipping_address" ><i class="icon-edit-address"></i></a> <a href="javascript:void(0);" title="Remove" id="remove_shipping" class="theme-color d-none"><i class="fa fa-trash"></i></a></h6>
              <input type="hidden" id="shipping_address_id" value="">
              <div class="link" id="shipping_btns">
                <a href="javascript:void(0);" class="theme-color" title="" id="same_as_billing">Same As Billing Address</a>
                <a href="#" class="theme-color change_shipping" title="">Add New</a>
              </div>
              <label id="shipping_address">

              </label>
              <div class="text-center" id="change_address"></div>
            </div>
          </div>
          <div class="erned-point white-bg my-3" id="payment_method">
            <h6>Payment</h6>
          </div>
    </div>
        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
          <div class="sticky-cart">
            <div class="cart-summary white-bg"> 
            </div>
            <div class="summary-action">              
              <a id="pay_now" class="btn primary-btn ms-1"></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</div>
@endsection
@section('js')

<script type="text/javascript">
    // Script to disable back button the page
    function preventBack() {
      console.log(performance.getEntriesByType("navigation")[0]);
      var type = performance.getEntriesByType("navigation")[0].type;
      var payout = localStorage.getItem("payout");
      if(type == 'back_forward' && payout =='payu') 
      {
        $(window).load(function(){
          $('#order-cancel-confirm').modal('show');
        });
      }
      localStorage.removeItem('payout');
    } 
    setTimeout("preventBack()", 0); 
    window.onunload = function () { null }; 
 
</script>
@endsection