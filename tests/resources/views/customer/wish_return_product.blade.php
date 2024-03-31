@extends('customer.layouts.app')

@section('css')
<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>          
          <li class="breadcrumb-item active"> <a href="{{ Route::currentRouteName() }}">Wish Return product</a>  </li>
        </ol> 
      </div>
  </section> 

  <section class="wishlist-detail-modal">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-5 col-md-7 col-12">
          <div class="section-title justify-content-center"><h2>Wish Return product</h2></div> 
          <div class="create-wishlist bg-white">
              <div class="return-quantity">
                <p>Add Wish Return Quantity</p>
                <div class="qty-items">
                  <input type="button" value="-" class="qty-minus">
                  <input type="number" id="quantity" value="{{app('request')->input('qty') ?? 0}}" class="qty" min="0" max="10" >
                  <input type="button" value="+" class="qty-plus">
                </div>
              </div>
              <div class="wish-info mb-3 d-none" id="select_dealer_dropdown">
                <p>Select the Dealer</p>
                <div class="dealer" id="selected_dealer">
                  <a href="#" title="" data-bs-toggle="modal" data-bs-target="#select-dealer">Select Dealer <img src="{{asset('web_assets/images/right-arrow.svg')}}" alt=""></a>
                </div>
              </div>
              
              <div class="add-description">
                <div class="mb-3">
                  <label>Add Description</label>
                  <textarea class="form-control" id="description" rows="4" placeholder="Write here..."></textarea>
                </div>
              </div>
              <div class="text-center">                                
                <a href="javascript:void(0);" type="button" class="btn primary-btn" id="add_to_wish_return_btn">Add to Wish Return</a>
              </div> 
          </div>
        </div>
      </div>      
    </div>
  </section>
</div>
 
<!-- Wish List Detail Modal -->
<div class="modal fade select-dealer-modal" id="select-dealer" >
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select Dealer</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body" id="dealers_list">
      </div> 
      
    </div>
  </div>
</div> 

<!-- Success Modal  -->
<div class="fade modal delete-confirm" id="success-wish" >
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">        
          <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body"> 
        <div class="success-img">
          <lottie-player src="{{asset('web_assets/images/success.json')}}" background="transparent" speed="1" loop autoplay></lottie-player>
        </div>
        <h4 class="green-color">Success</h4>  
        <p>Your product has been successfully added in wish return.</p>        
      </div>     
    </div>
  </div>
</div>
@endsection