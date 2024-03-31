

<?php $__env->startSection('content'); ?>
<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?php echo e(route('web_home')); ?>">Home</a></li>          
          <li class="breadcrumb-item active"><a href="<?php echo e(Route::currentRouteName()); ?>">My Order </a> </li>
        </ol> 
      </div>
  </section> 

  <section class="my-order white-bg">
    <div class="container">
      <div class="section-title">
        <h2>My Order</h2>
        <a href="#" title="" data-bs-toggle="modal" data-bs-target="#filter-modal"><img src="<?php echo e(asset('web_assets/images/filter.svg')); ?>" alt="" /></a>        
      </div> 
      <div class="custom-tab-box">                  
        <ul class="nav custom-nav-tabs" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <a class="nav-link active" id="upcoming" data-bs-toggle="tab" href="#tab11" role="tab" aria-controls="tab11" aria-selected="true">Upcoming</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="past" data-bs-toggle="tab" href="#tab22" role="tab" aria-controls="tab22" aria-selected="false">Past</a>
          </li>          
        </ul>
      </div>
      <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="tab11" role="tabpanel" aria-labelledby="tab1">
            <div class="row" id="upcoming_orders">
            </div>
            <div id="no_data_upcoming" class="no-data-found d-none">
              <div class="box">
                <img src="<?php echo e(asset('web_assets/images/empty-history.svg')); ?>" alt="">
                <h5>There is nothing here</h5>
                <p>Start ordering to create order history now!</p>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="tab22" role="tabpanel" aria-labelledby="tab2">
            <div class="row" id="past_orders">
            </div>
            <div id="no_data_past" class="no-data-found d-none">
              <div class="box">
                <img src="<?php echo e(asset('web_assets/images/empty-history.svg')); ?>" alt="">
                <h5>There is nothing here</h5>
                <p>Start ordering to create order history now!</p>
              </div>
            </div>
          </div>
      </div>
    </div>
  </section> 

</div>

<!-- Filter My Order Page -->
<div class="modal fade filter-modal" id="filter-modal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >FILTER</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body"> 
        <h6 class="title">Status</h6>
        <div class="mb-3" id="order_status_div">
        
        </div> 
      </div> 
      <div class="custom-footer text-center"> 
        <button type="button" id="clear_all" class="btn secondary-btn me-1"><i class="far fa-times-circle me-1"></i>Clear All</button>
        <button type="button" id="apply_filter_btn" class="btn primary-btn ms-1"><i class="far fa-check-circle me-1"></i>Apply</button>
      </div>      
    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('customer.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/ssgc-bulk/resources/views/customer/my_orders.blade.php ENDPATH**/ ?>