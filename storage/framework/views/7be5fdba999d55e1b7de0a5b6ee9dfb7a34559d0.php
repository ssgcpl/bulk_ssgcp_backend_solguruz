

<?php $__env->startSection('css'); ?>
<style type="text/css">
  section.notification-page a.link-color { color:black!important;  }
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="main-wapper">
   
  <section class="inner-page-banner">
      <div class="container"> 
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?php echo e(route('web_home')); ?>">Home</a></li>          
          <li class="breadcrumb-item active"> <a href="<?php echo e(Route::currentRouteName()); ?>">Notifications</a> </li>
        </ol> 
      </div>
  </section> 
  <section class="notification-page white-bg">     
    <div class="container"> 
      <div class="page-title"><h1>Notification</h1> <a href="#clear-all-confirm" data-bs-toggle="modal" title="" id="clear_all">CLEAR ALL</a></div>

      <div class="no-data-found d-none">
        <div class="box">
          <img src="<?php echo e(asset('web_assets/images/empty-notification.svg')); ?>" alt="">
          <h5>No Notification Right Now</h5>
          <p>Stay tuned! Notifications about your activity will show up here.</p>
        </div>
      </div>
      
      <ul class="notification-list" id="notifications_data">
        
      </ul>
    </div>
  </section>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('customer.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/ssgc-bulk/resources/views/customer/notifications.blade.php ENDPATH**/ ?>