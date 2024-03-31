
<?php $__env->startSection('content'); ?>
<style type="text/css">
  .add_new_gro {cursor: pointer;}
  .form-group label {width:100%;}
  .text-right { float: right; }
  .close_gro_div {float: right; position: relative; padding: 2px; top: 1px}
</style>

<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0"><?php echo e(trans('stocks.admin_heading')); ?> </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(trans('common.home')); ?></a>
                            </li>
                            <li class="breadcrumb-item"><a href="<?php echo e(route('stocks.index')); ?>?prod_id=<?php echo e($_REQUEST['prod_id']); ?>"><?php echo e(trans('stocks.plural')); ?></a></li>
                            <li class="breadcrumb-item active"><?php echo e(trans('stocks.add_new')); ?>

                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if($errors->any()): ?>
      <div class="alert alert-danger">
        <b><?php echo e(trans('common.whoops')); ?></b>
        <ul>
          <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
      </div>
    <?php endif; ?>
    <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form">
      <div class="row match-height">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">
              <?php echo e(trans('stocks.details')); ?>

              </h4>
              <a href="<?php echo e(route('stocks.index')); ?>?prod_id=<?php echo e($_REQUEST['prod_id']); ?>" class="btn btn-success">
                  <i class="fa fa-arrow-left"></i>
                  <?php echo e(trans('common.back')); ?>

              </a>
            </div>
            <div class="card-content">
              <div class="card-body">  
                <form method="POST" id="createForm" accept-charset="UTF-8">
                  <?php echo csrf_field(); ?>
                   <input type="hidden" name="product_id" id="product_id" value="<?php echo e($_REQUEST['prod_id']); ?>">
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-6">
                              <div class="form-group ">
                                <label for="pof_no" class="content-label"><?php echo e(trans('stocks.pof_no')); ?><span class="text-danger custom_asterisk">*</span></label>
                                <input class="form-control <?php $__errorArgs = ['pof_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ? is-invalid : ''  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(trans('stocks.pof_no')); ?>" name="pof_no" type="text" value="<?php echo e(old('pof_no')); ?>" required>
                                <?php if($errors->has('pof_no')): ?> 
                                  <strong class="invalid-feedback"><?php echo e($errors->first('pof_no')); ?></strong>
                                <?php endif; ?>
                              </div>  
                      </div>
                      <div class="col-md-6">
                              <div class="form-group ">
                                <label for="pof_qty" class="content-label"><?php echo e(trans('stocks.pof_qty')); ?><span class="text-danger custom_asterisk">*</span></label>
                                <input class="form-control <?php $__errorArgs = ['pof_qty'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ? is-invalid : ''  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(trans('stocks.pof_qty')); ?>" name="pof_qty" type="text" value="<?php echo e(old('pof_qty')); ?>" required>
                                <?php if($errors->has('pof_qty')): ?> 
                                  <strong class="invalid-feedback"><?php echo e($errors->first('pof_qty')); ?></strong>
                                <?php endif; ?>
                              </div>  
                      </div>
                     </div>
                   
                      <div class="row">
                      <div class="col-md-6">
                              <div class="form-group ">
                                <label for="ecm_no" class="content-label"><?php echo e(trans('stocks.ecm_no')); ?><span class="text-danger custom_asterisk">*</span></label>
                                <input class="form-control <?php $__errorArgs = ['ecm_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ? is-invalid : ''  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(trans('stocks.ecm_no')); ?>" name="ecm_no" type="text" value="<?php echo e(old('ecm_no')); ?>" required>
                                <?php if($errors->has('ecm_no')): ?> 
                                  <strong class="invalid-feedback"><?php echo e($errors->first('ecm_no')); ?></strong>
                                <?php endif; ?>
                              </div>  
                      </div>
                      <div class="col-md-6">
                              <div class="form-group ">
                                <label for="ecm_qty" class="content-label"><?php echo e(trans('stocks.ecm_qty')); ?><span class="text-danger custom_asterisk">*</span></label>
                                <input class="form-control <?php $__errorArgs = ['ecm_qty'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ? is-invalid : ''  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(trans('stocks.ecm_qty')); ?>" name="ecm_qty" type="text" value="<?php echo e(old('ecm_qty')); ?>" required>
                                <?php if($errors->has('ecm_qty')): ?> 
                                  <strong class="invalid-feedback"><?php echo e($errors->first('ecm_qty')); ?></strong>
                                <?php endif; ?>
                              </div>  
                      </div>
                     </div>
                     <hr/>
                      <div class="row"><div class="col-md-12"><span class="add_new_gro text-right"><i class="fa fa-plus"></i> Add More</span></div></div>
                      <div id="gro_div">
                     <div class="row">
                            <div class="col-md-6">
                              <div class="form-group ">
                                <label for="gro_no" class="content-label"><?php echo e(trans('stocks.gro_no')); ?><span class="text-danger custom_asterisk">*</span></label>
                                <input class="form-control <?php $__errorArgs = ['gro_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ? is-invalid : ''  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"  placeholder="<?php echo e(trans('stocks.gro_no')); ?>" name="gro_no[]" type="text" value="<?php echo e(old('gro_no')); ?>" required>
                                <?php if($errors->has('gro_no')): ?> 
                                  <strong class="invalid-feedback"><?php echo e($errors->first('gro_no')); ?></strong>
                                <?php endif; ?>
                              </div>  
                            </div>

                            <div class="col-md-6">
                              <div class="form-group ">
                                <label for="gro_qty" class="content-label"><?php echo e(trans('stocks.gro_qty')); ?><span class="text-danger custom_asterisk">*</span></label>
                                <input class="form-control <?php $__errorArgs = ['gro_qty'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ? is-invalid : ''  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"  placeholder="<?php echo e(trans('stocks.gro_qty')); ?>" name="gro_qty[]" type="text" value="<?php echo e(old('gro_qty')); ?>" required>
                                <?php if($errors->has('gro_qty')): ?> 
                                  <strong class="invalid-feedback"><?php echo e($errors->first('gro_qty')); ?></strong>
                                <?php endif; ?>
                              </div>  
                            </div>
                     
                     </div>   
                     
                  </div>
                  
                  <div class="modal-footer">
                    <button id="edit_btn" type="submit" class="btn btn-success btn-fill btn-wd"><?php echo e(trans('common.submit')); ?></button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
 <script type="text/javascript">

  $(document).ready(function() {

  $('.add_new_gro').click(function() {
      var html = ` <div class="row"> 
                        <div class="col-md-6">
                              <div class="form-group ">
                                <label for="gro_no" class="content-label"><?php echo e(trans('stocks.gro_no')); ?><span class="text-danger custom_asterisk">*</span></label>
                                <input class="form-control <?php $__errorArgs = ['gro_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ? is-invalid : ''  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"  placeholder="<?php echo e(trans('stocks.gro_no')); ?>" name="gro_no[]" type="text" value="<?php echo e(old('gro_no')); ?>" required>
                                <?php if($errors->has('gro_no')): ?> 
                                  <strong class="invalid-feedback"><?php echo e($errors->first('gro_no')); ?></strong>
                                <?php endif; ?>
                              </div>  
                            </div>

                            <div class="col-md-5">
                              <div class="form-group ">
                                <label for="gro_qty" class="content-label"><?php echo e(trans('stocks.gro_qty')); ?><span class="text-danger custom_asterisk">*</span></label>
                                <input class="form-control <?php $__errorArgs = ['gro_qty'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ? is-invalid : ''  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"  placeholder="<?php echo e(trans('stocks.gro_qty')); ?>" name="gro_qty[]" type="text" value="<?php echo e(old('gro_qty')); ?>" required>
                                <?php if($errors->has('gro_qty')): ?> 
                                  <strong class="invalid-feedback"><?php echo e($errors->first('gro_qty')); ?></strong>
                                <?php endif; ?>
                              </div>  
                            </div>
                            <div class="col-md-1"><i class="fa fa-close close_gro_div"></i></div>
                            </div>
                     `;
      $('#gro_div').append(html);                          
  });

   $('body').on('click', '.close_gro_div', function(){
    $(this).parent().parent().remove();
  })

    var product_id = $("#product_id").val();

     $('#createForm').submit(function(evt){
          // tinyMCE.triggerSave();
          evt.preventDefault();
          var form = $('#createForm')[0];
          var data = new FormData(form);
           // console.log(data); return false;
          $.ajax({
                  url: "<?php echo e(route('stocks.store')); ?>",
                  data: data,
                  type: "POST",
                  processData : false,
                  contentType : false,
                  dataType : 'json',
                  beforeSend: function(xhr){
                      $('#action_btn').prop('disabled',true);
                      $('#action_btn').text("<?php echo e(trans('common.submitting')); ?>");
                    },
                  success: function(response) {
                      console.log(response);
                      if(response.success) {
                          toastr.success(response.success);
                          setTimeout(function(){
                            location.href =  "<?php echo e(route('stocks.index')); ?>?prod_id="+product_id;
                          }, 2000);
                      } else {
                          toastr.error(response.error);
                      }
                      $('#action_btn').prop('disabled',false);
                      $('#action_btn').text("<?php echo e(trans('common.submit')); ?>");
                  }
          });
          //console.log('data',data)
    }) 

  });


  
</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/ssgc-bulk/resources/views/admin/stocks/create.blade.php ENDPATH**/ ?>