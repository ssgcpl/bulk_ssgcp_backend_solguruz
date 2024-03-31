
<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('css/filepond/filepond.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('css/filepond/filepond-plugin-image-preview.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('css/filepond/filepond-plugin-image-edit.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0"><?php echo e(trans('product_barcodes.heading')); ?> </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(trans('common.home')); ?></a>
                            </li>
                            <li class="breadcrumb-item"><a href="<?php echo e(route('product_barcodes.index')); ?>"><?php echo e(trans('product_barcodes.heading')); ?></a></li>
                            <li class="breadcrumb-item active"><?php echo e(trans('product_barcodes.add_new')); ?>

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
              <?php echo e(trans('product_barcodes.heading')); ?>

              </h4>
              <a href="<?php echo e(route('product_barcodes.index')); ?>" class="btn btn-success">
                  <i class="fa fa-arrow-left"></i>
                  <?php echo e(trans('common.back')); ?>

              </a>
            </div>
            <div class="card-content">
              <div class="card-body">    
                <form method="POST" id="createForm"  accept-charset="UTF-8" enctype="multipart/form-data" >

                  <?php echo csrf_field(); ?>
                  <!-- <hr style="border-top: 3px solid blue;"> -->
                  <div class="form-body">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> ? has-error : ''  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                          <label for="title" class="content-label"><?php echo e(trans('product_barcodes.product_list')); ?><span class="text-danger custom_asterisk">*</span></label>
                            <select id="product_id" class ="form-control" name='product_id'>
                                  <option value=''><?php echo e(trans('common.select')); ?></option>
                                  <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($product->id); ?>" ><?php echo e($product->name_hindi ? $product->name_hindi : $product->name_english); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                              <?php if($errors->has('title')): ?> 
                            <strong class="help-block alert-danger"><?php echo e($errors->first('title')); ?></strong>
                          <?php endif; ?>
                        </div>  
                      </div>

                      <div class="col-md-4">
                         <div class="form-group">
                          <label for="name_english" class="content-label"><?php echo e(trans('product_barcodes.name_english')); ?></label>
                                      <input
                                          class="form-control"
                                          placeholder="<?php echo e(trans('product_barcodes.name_english')); ?>"
                                          name="name_english" id="name" value="" disabled>
                                  </div>
                      </div>
                      <div class="col-md-4">
                         <div class="form-group">
                          <label for="name_english" class="content-label"><?php echo e(trans('product_barcodes.product_id')); ?></label>
                                      <input
                                          class="form-control"
                                          placeholder="<?php echo e(trans('product_barcodes.product_id')); ?>"
                                          name="prod_id" id="prod_id" value="" disabled>
                                  </div>
                      </div>
                         <div class="col-md-4">
                         <div class="form-group">
                          <label for="name_english" class="content-label"><?php echo e(trans('product_barcodes.sku_id')); ?></label>
                                      <input
                                          class="form-control"
                                          placeholder="<?php echo e(trans('product_barcodes.sku_id')); ?>"
                                          name="sku_id" id="sku_id" value="" disabled>
                                  </div>
                      </div>
                    </div>
                      <div class="row">
                        <div class="col-md-4">
                         <div class="form-group">
                          <label for="barcode_qty" class="content-label"><?php echo e(trans('product_barcodes.barcode_qty')); ?><span
                                              class="text-danger custom_asterisk">*</span></label>
                                      <input
                                          class="form-control"
                                          placeholder="<?php echo e(trans('product_barcodes.barcode_qty')); ?>"
                                          name="barcode_qty" value="">
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
  $(document).ready(function(){

    $("#product_id").select2({
    tags: true,
    // tokenSeparators: [',', ' '],
    createTag: function (params) {
      var term = $.trim(params.term);

      if (term === '') {
        return null;
      }

      return {
        id: term,
        text: term,
        newTag: true // add additional parameters
      }
    }
  });

    $('#createForm').submit(function(evt){
          // tinyMCE.triggerSave();
          evt.preventDefault();
          var form = $('#createForm')[0];
          var data = new FormData(form);
          var product_id = $("#product_id").val();
          var route = "<?php echo e(route('product_barcodes.show','id')); ?>";
          route = route.replace('id',product_id);
           // console.log(data); return false;
          $.ajax({
                  url: "<?php echo e(route('product_barcodes.store')); ?>",
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
                            location.href =  route;
                          }, 2000);
                      } else {
                          toastr.error(response.error);
                      }
                      $('#action_btn').prop('disabled',false);
                      $('#action_btn').text("<?php echo e(trans('common.submit')); ?>");
                  }
          });
          //console.log('data',data)
    });

    $(document).on('change','#product_id',function(){
      var product_id = $("#product_id").val();
      var route = "<?php echo e(route('get_product_detail',':id')); ?>";
      route = route.replace(':id',product_id);
      if(product_id != null){
         $.ajax({
                  url: route,
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
                      //    toastr.success(response.success);
                          $("#name").val(response.data.name);
                          $("#sku_id").val(response.data.sku_id);
                          $("#prod_id").val(response.data.id);
                      } else {
                          toastr.error(response.error);
                      }
                      $('#action_btn').prop('disabled',false);
                      $('#action_btn').text("<?php echo e(trans('common.submit')); ?>");
                  }
          });
      }
    });
  })
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/ssgc-bulk/resources/views/admin/product_barcodes/create.blade.php ENDPATH**/ ?>