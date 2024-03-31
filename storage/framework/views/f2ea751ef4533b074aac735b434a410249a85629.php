

<?php $__env->startSection('content'); ?>
<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?php echo e(route('web_home')); ?>">Home</a></li>          
          <li class="breadcrumb-item active"> <a href="<?php echo e(route('order_details', $order_id)); ?>"> Detail </a> </li>
        </ol> 
      </div>
  </section> 

  <section class="my-cart">     
    <div class="container">
      <div class="page-title"><h1>Order Detail</h1></div>
      <input type="hidden" id="order_id" value="<?php echo e($order_id); ?>">
      <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-12 col-12">
          <div class="order-list" id="order_list">
            
          </div>
          <h5>Added Items</h5>
          <div class="order-detail white-bg" id="order_items">
            
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
          <div class="sticky-cart">
            <div class="cart-summary white-bg"> 
                        
            </div> 
            <div class="summary-action">              
              <a href="#" class="btn primary-btn" data-bs-toggle="modal" data-bs-target="#order-again" id="order_again">Order Again</a>
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
        <div class="row" id="buy_order_items">
          
        </div>
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
          <lottie-player src="<?php echo e(asset('web_assets/images/success.json')); ?>" background="transparent" speed="1" loop autoplay></lottie-player>
        </div>
        <h4 class="green-color">Success</h4>  
        <p>Your product has been successfully added in return cart.</p>        
      </div>     
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('customer.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/ssgc-bulk/resources/views/customer/order_details.blade.php ENDPATH**/ ?>