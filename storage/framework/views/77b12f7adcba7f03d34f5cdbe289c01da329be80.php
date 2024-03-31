

<?php $__env->startSection('content'); ?>

<?php $__env->startSection('css'); ?>
<style type="text/css">
    .common-upload .preview-img{
        flex-wrap: wrap !important;
    }
</style>
<?php $__env->stopSection(); ?>

<div id="preloader" class="fade-out d-none"><img src="<?php echo e(asset('web_assets/images/loader-img.svg')); ?>" alt=""></div>
<div class="main-wapper">
    <section class="auth-page">
        <div class="auth-form signup">
            <h3 class="title">Create Account</h3>          
            <form id="signupForm" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                        <div class="mb-3">
                            <label>Full Name</label>
                            <input  type="text" id="first_name" name="first_name" class="form-control" placeholder="Full Name" required/>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                        <div class="mb-3">
                            <label>Company / Firm Name</label>
                            <input  type="text"  id="company_name" name="company_name" class="form-control" placeholder="Company / Firm Name" required/>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                        <div class="mb-3">
                            <label>Email ID</label>
                            <input  type="email"  id="email" name ="email" class="form-control" placeholder="Email ID" required/>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                        <div class="mb-3">
                            <label>Mobile Number</label>
                            <input type="tel" class="form-control" id="mobile_number" name="mobile_number" maxlength="10" title="Enter 10 digits only" pattern="/^[0-9]{10}+$/" placeholder="Mobile Number" required/>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                        <div class="mb-3">
                            <label>Password</label>
                            <div class="input-group">
                            <input  type="password" id="password"  name="password" class="form-control" placeholder="Password" required minlength="8" maxlength="16" />
                            <span class="input-group-text input-group-addon"><a href="javascript:void(0)"><i class="fa fa-lock password_icon"></i></a></span>
                        </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                        <div class="mb-3">
                            <label>Confirm Password</label>
                            <div class="input-group">
                            <input  type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm Password" minlength="8" maxlength="16" required /><span class="input-group-text input-group-addon"><a href="javascript:void(0)"><i class="fa fa-lock c_password_icon"></i></a></span></div>    
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                        <div class="mb-3">
                            <label>Enter Referral Code</label>
                            <input  type="text" class="form-control" id="referral_code" name="referral_code" placeholder="Enter Referral Code">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                        <div class="form-group mb-3">       
                            <div class="common-upload">
                                <p><label>Add Profile Image</label></p>
                                <div class="box">                        
                                    <div class="preview-img" id="profile_img_preview">
                                    </div> 
                                    <div class="upload-img">
                                        <label class="file-input-label" id="profile_img_btn" for="profile_image">
                                            <input type="file" name="profile_image" id="profile_image" class="file-input-input" required>
                                            <img src="<?php echo e(asset('web_assets/images/upload-img.png')); ?>" alt="">
                                            Add Image
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                        <div class="form-group mb-3">       
                            <div class="common-upload">
                                <p><label>Add Company / Shop Images</label></p>
                                <div class="box">                        
                                    <div class="preview-img" id="comp_images"></div>
                                    <div class="upload-img">
                                        <input type="file" name="company_images" id="company_images" class="file-input-input" multiple required>
                                        <label class="file-input-label" for="company_images">
                                            <img src="<?php echo e(asset('web_assets/images/upload-comp-img.png')); ?>" alt="">
                                            Add Image
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                        <div class="form-group mb-3">       
                            <div class="common-upload">
                                <p><label>Add Shop Documents Image</label></p>
                                 <div class="box"> 
                                 <div class="preview-img" id="document_images"></div>
                                    <div class="upload-img">
                                        <input type="file" name="company_docs" id="company_docs" class="file-input-input" multiple required>
                                        <label class="file-input-label" for="company_docs">
                                            <img src="<?php echo e(asset('web_assets/images/upload-doc.png')); ?>" alt="">
                                            Add Image
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 

                <div class="mb-3">
                    <div class="common-check my-3">
                        <label class="checkbox">I agree to the <a href="<?php echo e(route('terms_and_condition')); ?>" title="">Terms & Conditions</a>
                        <input type="checkbox" id="terms" name="terms" required /><span class="checkmark"></span>
                       </label>
                        <!-- <label class="checkbox">I agree to the <a href="<?php echo e(route('terms_and_condition')); ?>" target="_blank" title="">Terms & Conditions</a>
                           <input type="checkbox" id="terms" name="terms"><span class="checkmark"></span>
                        </label> -->
                    </div>
                </div>

                <div class="form-group text-center">           
                  <button type="submit" id="signup_btn" name="signup_btn" class="btn primary-btn">Sign up</button>
                </div>  
                <div class="signup-detail mt-4">
                    <p class="mb-0">Already have an account? <a href="<?php echo e(route('signin')); ?>" class="clr-primary">Sign in</a></p>  
                </div> 

            </form>        
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script type="text/javascript">
    /*var input = document.querySelector(".countrycode");
        window.intlTelInput(input, {         
        utilsScript: "<?php echo e(asset('web_assets/js/utils.js')); ?>",
    });*/
   /* $("#profile_image").on('change',function(){
      var imagePreview = $("#profile_image").val();
      $("#profile_img_preview").attr('src',imagePreview);
    })*/
</script>
 <?php $__env->stopSection(); ?>

<?php echo $__env->make('customer.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/ssgc-bulk/resources/views/customer/auth/signup.blade.php ENDPATH**/ ?>