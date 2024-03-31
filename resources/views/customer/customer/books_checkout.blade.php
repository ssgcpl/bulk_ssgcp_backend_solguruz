@extends('customer.layouts.app')

@section('content')
<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('web_home')}}">Home</a></li>          
          <li class="breadcrumb-item active">Checkout</li>
        </ol> 
      </div>
  </section> 

  <section class="my-cart">     
    <div class="container">
      <div class="page-title"><h1>Checkout</h1></div>
      <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-12 col-12">
          <div class="cart-items" id="cart_items">
            
          </div>

          <div class="default-address"> 
            <div class="same-billing white-bg"> 
              <h6>Billing Address <a href="javascript:void(0);" title="" id="" class="theme-color d-none edit_address" ><i class="icon-edit-address"></i></a></h6>
              <input type="hidden" id="billing_address_id" value="">             
              <label id="default_address">
                
              </label> 
              <div id="add_address"></div>                         
            </div>

            <div class="same-billing white-bg">
              <h6>Shipping Address <a href="javascript:void(0);" title="" data-bs-toggle="modal" data-bs-target="#add-address" id="" class="theme-color d-none edit_shipping_address" ><i class="icon-edit-address"></i></a></h6>
              <input type="hidden" id="shipping_address_id" value="">
              <div class="link" id="shipping_btns">
                <a href="javascript:void(0);" class="theme-color" title="" id="same_as_billing">Same As Billing Address</a> 
                <a href="#" class="theme-color change_shipping" title="">Add New</a>
              </div>
              <label id="shipping_address">
                
              </label> 
              <div class="text-center" id="change_address"></div>  
            </div>

            <div class="same-billing white-bg" id="payment_method">
              <h6>Payment</h6>
                <input type="radio" name="payment_method" value="ccavenue"> <span class="checkmark">CC Avenue</span>
                <br>
                <input type="radio" name="payment_method" value="payu"> <span class="checkmark">Payu</span>
              
            </div> 

          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
          <div class="sticky-cart">
            <div class="cart-summary white-bg"> 
                        
            </div>
            <div class="summary-action">              
              <a href="javascript:void(0);" class="btn primary-btn" id="place_order" >Place Order</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</div>
@endsection


