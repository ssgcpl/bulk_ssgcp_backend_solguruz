

<?php $__env->startSection('content'); ?>
<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?php echo e(route('web_home')); ?>">Home</a></li>          
          <li class="breadcrumb-item active"> <a href="<?php echo e(route('wish_return')); ?>"> Wish Return </a> </li>
        </ol> 
      </div>
  </section> 

  <section class="wishlist-return white-bg">
    <div class="container">
      <div class="section-title">
        <h2>Wish Return</h2>
      </div>
      <div class="custom-tab-box">                  
        <ul class="nav custom-nav-tabs" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <a class="nav-link active" id="all_wish_return" data-bs-toggle="tab" href="#tab11" role="tab" aria-controls="tab11" aria-selected="true">All</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="my_wish_return" data-bs-toggle="tab" href="#tab22" role="tab" aria-controls="tab22" aria-selected="false">My Wish Return</a>
          </li>          
        </ul>
        <div class="tab-content" id="myTabContent">

          <div class="tab-pane fade show active all_wish_return_tab" id="tab11" role="tabpanel" aria-labelledby="tab1">            
            <ul class="nav custom-nav-tabs" id="myTabLang" role="tablist">
              <li class="nav-item" role="presentation">
                <a class="nav-link active" id="hindi" data-bs-toggle="tab" href="#tab11_hindi" role="tab" aria-controls="tab11_hindi" aria-selected="true">हिन्दी</a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" id="english" data-bs-toggle="tab" href="#tab22_english" role="tab" aria-controls="tab22_english" aria-selected="false">English</a>
              </li>          
            </ul>
            <div class="tag-list">
                <ul class="selected_category">
                </ul>
            </div>
            <div class="tag-list" id="main_tag_list">
            </div>
            <div class="tag-list" id="tag_list">
              <ul class="sub-cate">
              </ul>
            </div>  
            <div class="tab-content" id="myTabContentLang">
              <div class="tab-pane fade show active hindi_tab" id="tab11_hindi" role="tabpanel" aria-labelledby="tab1">         
                <div class="row" id="all_wish_return_data_hindi">
                  
                  
                </div>
                <div id="no_data_hindi" class="no-data-found d-none">
                    <div class="box">
                      <img src="<?php echo e(asset('web_assets/images/empty-wishlist.svg')); ?>" alt="">
                      <h5>What! There is nothing on your Wishlist!</h5>
                      <p>You should browse items and add them to your Wishlist.</p>
                    </div>
                </div>
              </div>
              <div class="tab-pane fade english_tab" id="tab22_english" role="tabpanel" aria-labelledby="tab1">         
                <div class="row" id="all_wish_return_data_english">
                  
                </div>
                <div id="no_data_english" class="no-data-found d-none">
                    <div class="box">
                      <img src="<?php echo e(asset('web_assets/images/empty-wishlist.svg')); ?>" alt="">
                      <h5>What! There is nothing on your Wishlist!</h5>
                      <p>You should browse items and add them to your Wishlist.</p>
                    </div>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane fade my_wish_return_tab" id="tab22" role="tabpanel" aria-labelledby="tab2">
            <div class="row" id="my_wish_return_data">
              
            </div>
            <!-- <div id="no_data_my_wish_return" class="no-data-found d-none">
                <div class="box">
                        <img src="<?php echo e(asset('web_assets/images/no-purchase-yet.png')); ?>" alt="" />
                        <h5>Sorry! No Result Found :)</h5>
                        <p>We Couldn't Find What You're Looking For</p>
                </div>
            </div> -->
            <div id="no_data_my_wish_return" class="no-data-found d-none">
              <div class="box">
                <img src="<?php echo e(asset('web_assets/images/empty-wishlist.svg')); ?>" alt="">
                <h5>What! There is nothing on your Wishlist!</h5>
                <p>You should browse items and add them to your Wishlist.</p>
              </div>
            </div>
          </div>
        </div>
      </div> 
    </div>
  </section>
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
        <h6>Remove from Wish return</h6>
        <p>Are you sure you want to Remove from Wish return?</p>
         <div class="button-list text-center">
          <a href="#" type="button" class="btn secondary-btn me-1" data-bs-dismiss="modal">No</a>
          <input type="hidden" id="remove_wish_return_id" value="">
          <a href="#" type="button" class="btn primary-btn" id="remove_from_wish_return_btn">Yes</a>
        </div> 
      </div>      
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('customer.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/ssgc-bulk/resources/views/customer/wish_return.blade.php ENDPATH**/ ?>