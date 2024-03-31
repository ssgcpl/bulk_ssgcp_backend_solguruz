
<?php $__env->startSection('css'); ?>
<style type="text/css">
  #e_product_type_div {margin-top: 1rem; margin-bottom: 1rem; padding-top: 10px; border: 0; border-top: 1px solid rgba(34, 41, 47, 0.1); border-bottom: 1px solid rgba(34, 41, 47, 0.1); }
  .add_new_chapter {cursor: pointer;}
  /*.close_chapter_div {float: right; position: relative; padding: 2px; top: 1px}*/
  .close_chapter_div {float: right; padding: 2px; top: 1px}

</style>
<?php echo $__env->make('layouts.admin.elements.filepond_css', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-header row">
      <div class="content-header-left col-md-9 col-12 mb-2">
          <div class="row breadcrumbs-top">
              <div class="col-12">
                  <h2 class="content-header-title float-left mb-0"><?php echo e(trans('products.heading')); ?> </h2>
                  <div class="breadcrumb-wrapper col-12">
                      <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a
                                  href="<?php echo e(route('home')); ?>"><?php echo e(trans('common.home')); ?></a>
                          </li>
                          <li class="breadcrumb-item"><a
                                  href="<?php echo e(route('products.index')); ?>"><?php echo e(trans('products.plural')); ?></a></li>
                          <li class="breadcrumb-item active"><?php echo e(trans('products.add_new')); ?>

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
                    <?php echo e(trans('products.details')); ?>

                </h4>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('product-list')): ?>
                    <a href="<?php echo e(route('products.index')); ?>" class="btn btn-success">
                        <i class="fa fa-arrow-left"></i>
                        <?php echo e(trans('common.back')); ?>

                    </a>
                <?php endif; ?>
            </div>
            <div class="card-content">
              <div class="card-body">
                <form method="POST" id="createForm" accept-charset="UTF-8" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="form-body">
                      <div class="row">
                          <div class="col-md-12">
                              <div class="form-group">
                                  <label for="tags"
                                      class="content-label"><?php echo e(trans('products.categories')); ?><span
                                          class="text-danger custom_asterisk">*</span></label>

                                  <ul class="nav nav-pills" role="tablist">
                                      <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                          <li class="nav-item">
                                              <a class="nav-link" id="" data-toggle="pill"
                                                  href="#tab_<?php echo e($category->id); ?>" role="tab"
                                                  aria-selected="true"><?php echo e($category->category_name); ?>

                                              </a>
                                          </li>
                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                  </ul>
                                  <div class="tab-content">
                                      <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                          <div class="tab-pane" id="tab_<?php echo e($category->id); ?>"
                                              role="tabpanel">
                                              <div class="container">
                                                  <div class="row">
                                                      <div class="col-md-6">
                                                          <div id="treeview-checkbox-demo">
                                                              <ul>
                                                                  
                                                                  <input type="checkbox"
                                                                      id="category[]"
                                                                      name="category[]"
                                                                      value="<?php echo e($category->id); ?>"
                                                                      class="list"
                                                                      <?php echo e(is_array(old('category')) && in_array($category->id, old('category')) ? 'checked' : ''); ?>>
                                                                  <?php echo e($category->category_name); ?>

                                                                  <?php echo $__env->make('admin.products.manage_checkbox',
                                                                      [
                                                                          'childs' =>
                                                                              $category->sub_category,
                                                                      ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                                  
                                                              </ul>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <hr>
                      <div class="row">
                          <div class="col-md-3">
                              <div class="form-group">
                                  <label for="languages"
                                      class="content-label"><?php echo e(trans('common.language')); ?><span
                                          class="text-danger custom_asterisk">*</span></label><br>
                                  <input name="language_english" value="english" id="english" type="checkbox" checked>&nbsp; <label for="english"><?php echo e(trans('common.english')); ?></label>
                                  &nbsp;&nbsp;
                                  <input name="language_hindi" value="hindi" id="hindi" type="checkbox" checked>&nbsp;<label for="hindi"><?php echo e(trans('common.hindi')); ?></label>
                              </div>
                          </div>
                        </div>
                          <div class="row" id="english_div">
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label for="name_english"
                                          class="content-label"><?php echo e(trans('products.name_english')); ?><span
                                              class="text-danger custom_asterisk">*</span></label>
                                      <input
                                          class="form-control"
                                          placeholder="<?php echo e(trans('products.name_english')); ?>"
                                          name="name_english" id="name_english" value="" required>
                                  </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="heading_english"
                                        class="content-label"><?php echo e(trans('products.sub_heading_english')); ?><span
                                            class="text-danger custom_asterisk">*</span></label>
                                    <input
                                        class="form-control"
                                        placeholder="<?php echo e(trans('products.sub_heading_english')); ?>"
                                        name="sub_heading_english" id="sub_heading_english" value="" required>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="description_english"
                                      class="content-label"><?php echo e(trans('products.description_english')); ?></label>
                                  <textarea class="form-control"
                                      id="description_english" name="description_english"></textarea>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="additional_info_english"
                                      class="content-label"><?php echo e(trans('products.additional_info_english')); ?></label>
                                  <textarea class="form-control"
                                      id="additional_info_english" name="additional_info_english"></textarea>
                                </div>
                              </div>
                          </div>
                          <div class="row" id="hindi_div">
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label for="name_hindi"
                                          class="content-label"><?php echo e(trans('products.name_hindi')); ?><span
                                              class="text-danger custom_asterisk">*</span></label>
                                      <input
                                          class="form-control"
                                          placeholder="<?php echo e(trans('products.name_hindi')); ?>"
                                          name="name_hindi" value="" id="name_hindi" required>
                                  </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sub_heading_hindi"
                                        class="content-label"><?php echo e(trans('products.sub_heading_hindi')); ?><span
                                            class="text-danger custom_asterisk">*</span></label>
                                    <input
                                        class="form-control"
                                        placeholder="<?php echo e(trans('products.sub_heading_hindi')); ?>"
                                        name="sub_heading_hindi" value="" id="sub_heading_hindi" required >
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="description_hindi"
                                      class="content-label"><?php echo e(trans('products.description_hindi')); ?></label>
                                  <textarea class="form-control"
                                      id="description_hindi" name="description_hindi"></textarea>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="additional_info_english"
                                      class="content-label"><?php echo e(trans('products.additional_info_hindi')); ?></label>
                                  <textarea class="form-control"
                                      id="additional_info_hindi" name="additional_info_hindi"></textarea>
                                </div>
                              </div>
                          </div>
                      <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                  <label for="types"
                                      class="content-label"><?php echo e(trans('common.type')); ?> (Business Category)<span
                                          class="text-danger custom_asterisk">*</span></label><br>
                                 <select id="business_category_id" class = "form-control" name='business_category_id' required>
                                  <option value=''><?php echo e(trans('common.select')); ?></option>
                                  <?php $__currentLoopData = $business_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($bc->id); ?>"><?php echo e(ucfirst($bc->category_name)); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                              </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                              <label for="image" class="content-label"><?php echo e(trans('products.product_image')); ?><span class="text-danger custom_asterisk">*</span></label><br>
                              <input type="file" class="filepond_img" id="image" name="image" required>

                            </div>
                            <label id="image-error" for="image"></label>
                            <strong class="help-block alert-danger">
                              <?php echo e(@$errors->first('image')); ?>

                            </strong>
                          </div>
                            <div class="col-md-4" id="book_cover_images">
                            <div class="form-group">
                                <label id="image-error"
                                    for="image"><?php echo e(trans('products.product_cover_images')); ?> <span
                                        class="text-danger custom_asterisk">*</span><br/>(Recomanded size: 250px x 350px)</label>
                                <input class="filepond_img2"
                                    type="file" multiple name="product_cover_images[]" required>
                            </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="mrp"
                                  class="content-label"><?php echo e(trans('products.mrp')); ?><span
                                      class="text-danger custom_asterisk">*</span></label><br>
                              <input class="form-control numberonly" name="mrp" minlength="1" maxlength="8" value="" id="mrp" type="text" required>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="sale_price"
                                  class="content-label"><?php echo e(trans('products.dealer_sale_price')); ?><span
                                      class="text-danger custom_asterisk">*</span></label><br>
                              <input class="form-control numberonly" name="dealer_sale_price" minlength="1" maxlength="8" value="" id="dealer_sale_price" type="text" required>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="sale_price"
                                  class="content-label"><?php echo e(trans('products.retailer_sale_price')); ?><span
                                      class="text-danger custom_asterisk">*</span></label><br>
                              <input class="form-control numberonly" name="retailer_sale_price" minlength="1" maxlength="8" value="" id="retailer_sale_price" type="text" required>
                          </div>
                        </div>
                        <div class="col-md-3" >
                          <div class="form-group">
                              <label for="sku_id"
                                  class="content-label"><?php echo e(trans('products.sku_id')); ?><span
                                      class="text-danger custom_asterisk">*</span></label><br>
                              <input class="form-control" name="sku_id" value="" id="sku_id" type="text" required>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="weight"
                                  class="content-label"><?php echo e(trans('products.weight')); ?> (Kg)<span
                                      class="text-danger custom_asterisk">*</span></label><br>
                              <input class="form-control number_decimal" name="weight" value="" id="weight" type="text" required>
                          </div>
                        </div>
                         <div class="col-md-3">
                          <div class="form-group">
                              <label for="weight"
                                  class="content-label"><?php echo e(trans('products.last_returnable_date')); ?><span
                                      class="text-danger custom_asterisk">*</span></label><br>
                              <input class="form-control datepicker" name="last_returnable_date" value="" id="last_returnable_date" type="text" required>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="weight"
                                  class="content-label"><?php echo e(trans('products.last_returnable_qty')); ?>(in %)<span
                                      class="text-danger custom_asterisk">*</span></label><br>
                              <input class="form-control number_decimal" name="last_returnable_qty" value="" id="last_returnable_qty" type="text" required>
                          </div>
                        </div>
                         <div class="col-md-3">
                              <div class="form-group">
                                  <label for="languages"
                                      class="content-label"><?php echo e(trans('products.visible_to')); ?><span
                                          class="text-danger custom_asterisk">*</span></label><br>
                                  <input name="visible_to[]" value="dealer" id="dealer" type="checkbox" checked="">&nbsp; <label for="english"><?php echo e(trans('products.dealer')); ?></label>
                                  &nbsp;&nbsp;
                                  <input name="visible_to[]" value="retailer" id="retailer" type="checkbox" checked="">&nbsp;<label for="retailer"><?php echo e(trans('products.retailer')); ?></label>
                              </div>
                          </div>
                      </div>
                      <div class="row" id="dimensions_lable">
                        <div class="col-md-12">
                          <label for="" class="content-label"><b><?php echo e(trans('products.dimensions')); ?></b></label><br/>
                        </div>
                      </div>
                      <div class="row" id="dimensions_div">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="length"
                                class="content-label"><?php echo e(trans('products.length')); ?> (in CM)<span
                                    class="text-danger custom_asterisk">*</span></label><br>
                            <input class="form-control number_decimal" name="length" value="" id="length" type="text" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="height"
                                class="content-label"><?php echo e(trans('products.height')); ?> (in CM)<span
                                    class="text-danger custom_asterisk">*</span></label><br>
                            <input class="form-control number_decimal" name="height" value="" id="height" type="text" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="width"
                                class="content-label"><?php echo e(trans('products.width')); ?> (in CM)<span
                                    class="text-danger custom_asterisk">*</span></label><br>
                            <input class="form-control number_decimal" name="width" value="" id="width" type="text" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                             <label class=""><?php echo e(trans('products.stock_status')); ?><span
                                    class="text-danger custom_asterisk">*</span></label>
                                <select id="stock_status" class = "form-control" name='stock_status' required>
                                  <option value=''><?php echo e(trans('common.select')); ?></option>
                                  <option value="in_stock"><?php echo e(trans('products.in_stock')); ?></option>
                                  <option value="out_of_stock"><?php echo e(trans('products.out_of_stock')); ?></option>
                                </select>
                          </div>
                        </div>

                         <div class="col-md-4">
                              <div class="form-group">
                                  <label for="types"
                                      class="content-label"><?php echo e(trans('products.related_products')); ?></label><br>
                                 <select id="related_products" class = "form-control" name='related_products[]' multiple>
                                  <option value=''><?php echo e(trans('common.select')); ?></option>
                                  <?php $__currentLoopData = $related_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($rp->id); ?>"><?php echo e(($rp->name_english) ? ucfirst($rp->name_english) : ucfirst($rp->name_hindi)); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                              </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                          <div class="row">
                            <div class="col-md-12">
                                <input class="" name="is_live" type="checkbox"  id='is_live' checked>&nbsp;&nbsp; <?php echo e(trans('business_categories.publish')); ?>

                            </div>
                        </div>
                        <button id="action_btn" type="submit"
                            class="btn btn-info btn-fill btn-wd"><?php echo e(trans('common.submit')); ?></button>
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
    $(function () {
        $("ul.nav-pills li:first").find('a:first').addClass("active");
        $(".tab-content .tab-pane:first").addClass("active");
    });
    $(document).ready(function() {
      $("#related_products").select2({
    tags: true,
    tokenSeparators: [',', ' '],
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

    $(document).on('click',"#hindi",function(){
      if($(this).is(':checked')){
        $('#hindi_div').show();
        $('#sub_heading_hindi').prop('required',true);
        $('#name_hindi').prop('required',true);
      }else {
        if($('#english').is(':checked')){
          $('#sub_heading_hindi').prop('required',false);
          $('#name_hindi').prop('required',false);
          $('#hindi_div').hide();
        }else{
          toastr.error("<?php echo e(trans('products.one_lang_required')); ?>");
          $('#hindi').prop('checked',true);
        }
        
      }
    })

    $(document).on('click',"#english",function(){
      if($(this).is(':checked')){
        $('#english_div').show();
        $('#sub_heading_english').prop('required',true);
        $('#name_english').prop('required',true);
      }else {
        if($('#hindi').is(':checked')){
          $('#english_div').hide();
          $('#sub_heading_english').prop('required',false);
          $('#name_english').prop('required',false);
        }else{
          toastr.error("<?php echo e(trans('products.one_lang_required')); ?>");
          $('#english').prop('checked',true);
        }
      }
    })
    //Save Categories
    $('#createForm').submit(function(evt){
          // tinyMCE.triggerSave();
          evt.preventDefault();
          var form = $('#createForm')[0];
          var data = new FormData(form);
           // console.log(data); return false;
          $.ajax({
                  url: "<?php echo e(route('products.store')); ?>",
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
                            location.href =  "<?php echo e(route('products.index')); ?>";
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
  })
  </script>
  <?php echo $__env->make('layouts.admin.elements.filepond_js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/ssgc-bulk/resources/views/admin/products/create.blade.php ENDPATH**/ ?>