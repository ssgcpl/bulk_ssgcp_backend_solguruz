

<?php $__env->startSection('css'); ?>
<style type="text/css">
  
  .wish-info .list .text img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    margin: 0 15px 0 0;
    border-radius: 5px;
  }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?php echo e(route('web_home')); ?>">Home</a></li>          
          <li class="breadcrumb-item active"> <a href="<?php echo e(Route::currentRouteName()); ?>"> Wish List </a> </li>
        </ol> 
      </div>
  </section> 

  <section class="wishlist-stock white-bg">
    <div class="container">
      <div class="section-title">
        <h2>Wish List</h2>
        <!-- <a href="book-list.php" title="">View All</a>         -->
      </div>
      
      <div class="custom-tab-box">                  
        <ul class="nav custom-nav-tabs" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <a class="nav-link active" id="out_of_stock_wishlist" data-bs-toggle="tab" href="#tab11" role="tab" aria-controls="tab11" aria-selected="true">All</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="my_wishlist" data-bs-toggle="tab" href="#tab22" role="tab" aria-controls="tab22" aria-selected="false">My Wish List</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="available" data-bs-toggle="tab" href="#tab33" role="tab" aria-controls="tab33" aria-selected="false">Available</a>
          </li>          
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="tab11" role="tabpanel" aria-labelledby="tab1">                      
            <div class="row" id="out_of_stock_wishlist_data">
            </div>
            <div class="no-data-found d-none">
              <div class="box">
                <img src="<?php echo e(asset('web_assets/images/empty-wishlist.svg')); ?>" alt="">
                <h5>What! There is nothing on your Wishlist!</h5>
                <p>You should browse items and add them to your Wishlist.</p>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="tab22" role="tabpanel" aria-labelledby="tab2">
            <div class="row" id="my_wishlist_data">
            </div>
            <div class="no-data-found d-none">
              <div class="box">
                <img src="<?php echo e(asset('web_assets/images/empty-wishlist.svg')); ?>" alt="">
                <h5>What! There is nothing on your Wishlist!</h5>
                <p>You should browse items and add them to your Wishlist.</p>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="tab33" role="tabpanel" aria-labelledby="tab3">
            <div class="row" id="available_data">
            </div>
            <div class="no-data-found d-none">
              <div class="box">
                <img src="<?php echo e(asset('web_assets/images/empty-wishlist.svg')); ?>" alt="">
                <h5>No Luck Yet!</h5>
                <p>Nothing from your wishlist is available now.</p>
              </div>
            </div>
          </div>
        </div>
      </div> 
    </div>
  </section>
 

</div>


<!-- Wish List Detail Modal -->
<div class="modal fade wishlist-detail-modal" id="wishlist-detail" >
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Wish List Detail</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <div class="product-list-box" id="book_info">
          
        </div>
        <div class="wish-info" id="wish-info">
          <div class="list">
            <div class="secondary-color">Product Quantity</div>
            <p id="wish_qty"></p>                
          </div>
        </div>
        <hr>
        <div class="wish-info" id="wish_dealers_list">
                
        </div>
        <div class="wish-button text-center">
          <div class="qty-items">
            <input type="button" value="-" class="qty-minus">
            <input type="number" value="" id="wish_qty_input" class="qty" min="0" >
            <input type="button" value="+" class="qty-plus">
          </div>
          <a href="javascript:void(0);" type="button" id="wish_update" data-id="" class="btn primary-btn update_qty" data-bs-dismiss="modal">Update</a>
        </div> 
      </div> 
    </div>
  </div>
</div> 

<!-- Wish List Detail Remove Modal -->
<div class="modal fade wishlist-detail-modal" id="available-wish-list" >
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Available Wish List Detail</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <div class="product-list-box" id="available_book_info">
          
        </div>
        <div class="wish-info" id="available-wish-info">
          <div class="list">
            <div class="secondary-color">Product Quantity</div>
            <p id="available_wish_qty"></p>                
          </div>
        </div>
        <hr>
        <div class="wish-info" id="available_wish_dealers_list">
                
        </div>
        <div class="wish-button text-center">
          <a href="" class="remove-btn remove_wishlist" id="wish_remove" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#confirm-remove" data-id="">Remove</a>
        </div> 
      </div> 
    </div>
  </div>
</div>
 

<!-- Confirm Remove Modal  -->
<div class="modal fade delete-confirm" id="confirm-remove" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <h5 class="modal-title" >Completed Test</h5> -->
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body text-center">        
        <h6>Remove from Wishlist</h6>
        <p>Are you sure you want to Remove from Wishlist?</p>
         <div class="button-list text-center">
          <a href="#" type="button" class="btn secondary-btn me-1" data-bs-dismiss="modal">No</a>
          <input type="hidden" id="remove_wishlist_id" value="">
          <a href="#" type="button" class="btn primary-btn" id="remove_from_wishlist_btn">Yes</a>
        </div> 
      </div>      
    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('customer.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/ssgc-bulk/resources/views/customer/wishlist.blade.php ENDPATH**/ ?>