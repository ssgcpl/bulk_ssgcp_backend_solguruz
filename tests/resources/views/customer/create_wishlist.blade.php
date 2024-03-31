@extends('customer.layouts.app')

@section('css')
<style type="text/css">
  .wish-button .primary-btn{
    width: 100%;
  }
  .wish-info .list .text img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    margin: 0 15px 0 0;
    border-radius: 5px;
}
</style>
@endsection
@section('content')
<div class="main-wapper">
  <input type="hidden" id="book_id" value="{{$book_id}}">
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('web_home')}}">Home</a></li>          
          <li class="breadcrumb-item active">Create Wishlist</li>
        </ol> 
      </div>
  </section> 

  <section class="wishlist-detail-modal">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-5 col-md-7 col-12">
          <div class="section-title justify-content-center"><h2>Create Wishlist</h2></div> 
          <div class="create-wishlist bg-white">
              <div class="product-list-box" id="book_details">
                
              </div>
              <div class="wish-info d-none" id="select_dealer_dropdown">
                <p>Select the Dealer</p>
                <div class="dealer">
                  <a href="javascript:void(0);" title="" data-bs-toggle="modal" data-bs-target="#select-dealer">Select Dealer <img src="{{asset('web_assets/images/right-arrow.svg')}}" alt=""></a>
                </div>
              </div>

              <div class="wish-info d-none" id="selected_dealers_list">
                
              </div>
              <a href="javascript:void(0);" class="d-none" id="add_more_btn" title="" data-bs-toggle="modal" data-bs-target="#select-dealer">Add More Dealers</a>

              <div class="wish-button text-center">
                <div class="qty-items me-1">
                  <input type="button" value="-" class="qty-minus">
                  <input type="number" value="{{app('request')->input('qty') ?? 0}}" id="quantity" class="qty" min="0" >
                  <input type="button" value="+" class="qty-plus">
                </div>
                <a href="javascript:void(0);" type="button" id="add_to_wishlist_btn" class=" btn primary-btn ms-1">Add to Wishlist</a>
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
          <!-- <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button> -->
      </div>
      <div class="modal-body"> 
        <div class="success-img">
          <lottie-player src="{{asset('web_assets/images/success.json')}}" background="transparent" speed="1" loop autoplay></lottie-player>
        </div>
        <h4 class="green-color">Success</h4>  
        <p>Your product has been successfully added in wishlist.</p>        
      </div>     
    </div>
  </div>
</div>

@endsection