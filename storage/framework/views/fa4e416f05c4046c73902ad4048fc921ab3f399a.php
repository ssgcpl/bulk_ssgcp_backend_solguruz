

<?php $__env->startSection('content'); ?>

<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?php echo e(route('web_home')); ?>">Home</a></li>          
          <li class="breadcrumb-item active"> <a href="<?php echo e(Route::currentRouteName()); ?>"> Return Accepted</a> </li>
        </ol> 
      </div>
  </section> 

  <section class="return-product white-bg">
    <div class="container">
      <div class="section-title">
        <h2>Return Products</h2>
        <a href="#" title="" data-bs-toggle="modal" data-bs-target="#filter-modal" id="return_filter"><img src="<?php echo e(asset('web_assets/images/filter.svg')); ?>" alt="" /></a>        
      </div> 
      <div class="row" id="return_products_list">
      
      </div> 
      <div id="no_data" class="no-data-found d-none">
        <div class="box">
          <img src="<?php echo e(asset('web_assets/images/empty-history.svg')); ?>" alt="">
          <h5>There is nothing here</h5>
          <p>Start ordering to create order history now!</p>
        </div>
      </div>
    </div>
  </section> 

</div>


<!-- Filter Return Product -->
<div class="modal fade filter-modal" id="filter-modal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >FILTER</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body"> 
        <h6 class="title">Type</h6>
        <div class="mb-3">
          <div class="common-check">
            <label class="checkbox"><span>Dispatched</span>
              <input type="checkbox" name="order_type" value="dispatched"><span class="checkmark"></span>
            </label> 
          </div>  
          <div class="common-check">
            <label class="checkbox"><span>In Review</span>
              <input type="checkbox" name="order_type" value="in_review"><span class="checkmark"></span>
            </label> 
          </div>  
          <div class="common-check">
            <label class="checkbox"><span>Accepted</span>
              <input type="checkbox" name="order_type" value="accepted"><span class="checkmark"></span>
            </label> 
          </div>
          <div class="common-check">
            <label class="checkbox"><span>Return Placed</span>
              <input type="checkbox" name="order_type" value="return_placed"><span class="checkmark"></span>
            </label> 
          </div>
          <div class="common-check">
            <label class="checkbox"><span>Rejected</span>
              <input type="checkbox" name="order_type" value="rejected"><span class="checkmark"></span>
            </label> 
          </div> 
        </div> 
      </div> 
      <div class="custom-footer text-center"> 
        <button type="button" id="clear_all" class="btn secondary-btn me-1"><i class="far fa-times-circle me-1"></i>Clear All</button>
        <button type="button" id="apply_filter" class="btn primary-btn ms-1"><i class="far fa-check-circle me-1"></i>Apply</button>
      </div>      
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('customer.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/ssgc-bulk/resources/views/customer/return_products.blade.php ENDPATH**/ ?>