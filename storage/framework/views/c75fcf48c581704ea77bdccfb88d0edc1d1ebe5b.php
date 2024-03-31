
<?php $__env->startSection('css'); ?>
<style type="text/css">
   .cover_images {width: 40%}
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
                  <h2 class="content-header-title float-left mb-0"><?php echo e(trans('order_return.heading')); ?> </h2>
                  <div class="breadcrumb-wrapper col-12">
                      <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a
                                  href="<?php echo e(route('home')); ?>"><?php echo e(trans('common.home')); ?></a>
                          </li>
                          <li class="breadcrumb-item"><a
                                  href="<?php echo e(route('order_return.edit',$_GET['order_return_id'])); ?>"><?php echo e(trans('order_return.update')); ?></a></li>
                          <li class="breadcrumb-item active"><?php echo e(trans('order_return.view_product')); ?>

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
                    <?php echo e(trans('order_return.product_detail')); ?>

                </h4>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('product-list')): ?>
                    <a href="<?php echo e(route('order_return.edit',$_GET['order_return_id'])); ?>" class="btn btn-success">
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
                              <label for="categories"  class="content-label"><?php echo e(trans('products.categories')); ?><span  class="text-danger custom_asterisk">*</span></label>
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
                                            <input type="checkbox" id="category[]" name="category[]" value="<?php echo e($category->id); ?>" disabled class="list" <?php echo e(in_array($category->id, $product_category_ids) || (is_array(old('list')) && in_array($category->id, old('list'))) ? 'checked' : ''); ?> disabled>
                                            <?php echo e($category->category_name); ?>

                                               <?php echo $__env->make('admin.products.manage_checkbox_show',
                                              ['childs' => $category->sub_category,'category' => $product_category_ids], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>                  
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
                                  <input name="language_english" value="english" id="english" type="checkbox" <?php echo e(@(($product->language == 'english') || ($product->language == 'both')) ? 'checked':''); ?> disabled>&nbsp; <label for="english"><?php echo e(trans('common.english')); ?></label>
                                  &nbsp;&nbsp;
                                  <input name="language_hindi" value="hindi" id="hindi" type="checkbox" <?php echo e(@($product->language == 'hindi' || $product->language == 'both') ? 'checked':''); ?> disabled>&nbsp;<label for="hindi"><?php echo e(trans('common.hindi')); ?></label>
                              </div>
                          </div>
                        </div>
                          <div class="row" id="english_div">
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label for="name_english"
                                          class="content-label"><?php echo e(trans('products.name_english')); ?><span
                                              class="text-danger custom_asterisk">*</span></label>
                                      <p class="details"> <?php echo e($product->name_english); ?> </p>
                                  </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="heading_english"
                                        class="content-label"><?php echo e(trans('products.sub_heading_english')); ?><span
                                            class="text-danger custom_asterisk">*</span></label>
                                   <p class="details"> <?php echo e($product->sub_heading_english); ?> </p>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="description_english"
                                      class="content-label"><?php echo e(trans('products.description_english')); ?></label>
                                  <textarea class="form-control" disabled>
                                     <?php echo e($product->description_english); ?></textarea>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="additional_info_english"
                                      class="content-label"><?php echo e(trans('products.additional_info_english')); ?></label>
                                 <textarea class="form-control" disabled><?php echo e($product->additional_info_english); ?></textarea>
                                </div>
                              </div>
                          </div>
                          <div class="row" id="hindi_div">
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label for="name_hindi"
                                          class="content-label"><?php echo e(trans('products.name_hindi')); ?><span
                                              class="text-danger custom_asterisk">*</span></label>
                                     <p class="details"> <?php echo e($product->name_hindi); ?> </p>
                                  </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sub_heading_hindi"
                                        class="content-label"><?php echo e(trans('products.sub_heading_hindi')); ?><span
                                            class="text-danger custom_asterisk">*</span></label>
                                    <p class="details"> <?php echo e($product->sub_heading_hindi); ?> </p>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="description_hindi"
                                      class="content-label"><?php echo e(trans('products.description_hindi')); ?></label>
                                  <textarea class="form-control" disabled><?php echo e($product->description_hindi); ?></textarea>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="additional_info_english"
                                      class="content-label"><?php echo e(trans('products.additional_info_hindi')); ?></label>
                                  <textarea class="form-control" disabled><?php echo e($product->additional_info_hindi); ?></textarea>
                                </div>
                              </div>
                          </div>
                      <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                  <label for="types"
                                      class="content-label"><?php echo e(trans('common.type')); ?><span
                                          class="text-danger custom_asterisk">*</span></label><br>
                                 <select id="business_category_id" class ="form-control" name='type' disabled="">
                                  <option value=''><?php echo e(trans('common.select')); ?></option>
                                  <?php $__currentLoopData = $business_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($bc->id); ?>" <?php echo e(@($bc->id == $product->business_category_id)?'selected':''); ?>><?php echo e(ucfirst($bc->category_name)); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                              </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                              <label for="image" class="content-label"><?php echo e(trans('products.product_image')); ?><span class="text-danger custom_asterisk">*</span></label><br>
                             <img src="<?php echo e(asset($product->image)); ?>" class="cover_images">
                            </div>
                            <label id="image-error" for="image"></label>
                            <strong class="help-block alert-danger">
                              <?php echo e(@$errors->first('image')); ?>

                            </strong>
                          </div>
                            <div class="col-md-4" id="product_cover_images">
                            <div class="form-group">
                                <label id="image-error"
                                    for="image"><?php echo e(trans('products.product_cover_images')); ?> <span
                                        class="text-danger custom_asterisk">*</span><br/>(Recomanded size: 250px x 350px)</label>
                                 <?php $__currentLoopData = $product->cover_images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cvr_img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <!--  <a href="javascript:void(0)" title="Remove"> --><img src="<?php echo e(asset($cvr_img->image)); ?>" class="cover_images"><span class="" id="<?php echo e($cvr_img->id); ?>"  ><!-- <i class="fa fa-window-close"></i></span></a> -->
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>                                       
                            </div>
                        </div>
                      </div>
                   
                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="mrp"
                                  class="content-label"><?php echo e(trans('products.mrp')); ?><span
                                      class="text-danger custom_asterisk">*</span></label><br>
                              <p class="details"> <?php echo e($product->mrp); ?></p>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="sale_price"
                                  class="content-label"><?php echo e(trans('products.dealer_sale_price')); ?><span
                                      class="text-danger custom_asterisk">*</span></label><br>
                             <p class="details"> <?php echo e($product->dealer_sale_price); ?></p>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="sale_price"
                                  class="content-label"><?php echo e(trans('products.retailer_sale_price')); ?><span
                                      class="text-danger custom_asterisk">*</span></label><br>
                              <p class="details"> <?php echo e($product->retailer_sale_price); ?></p>
                          </div>
                        </div>
                        <div class="col-md-3" >
                          <div class="form-group">
                              <label for="sku_id"
                                  class="content-label"><?php echo e(trans('products.sku_id')); ?><span
                                      class="text-danger custom_asterisk">*</span></label><br>
                             <p class="details"> <?php echo e($product->sku_id); ?></p>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="weight"
                                  class="content-label"><?php echo e(trans('products.weight')); ?> (Kg)<span
                                      class="text-danger custom_asterisk">*</span></label><br>
                            <p class="details"><?php echo e($product->weight); ?></p>
                          </div>
                        </div>
                         <div class="col-md-3">
                          <div class="form-group">
                              <label for="weight"
                                  class="content-label"><?php echo e(trans('products.last_returnable_date')); ?><span
                                      class="text-danger custom_asterisk">*</span></label><br>
                             <p class="details"> <?php echo e($product->last_returnable_date); ?></p>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="last returnable qty"
                                  class="content-label"><?php echo e(trans('products.last_returnable_qty')); ?>(in %)<span
                                      class="text-danger custom_asterisk">*</span></label><br>
                             <p class="details"> <?php echo e($product->last_returnable_qty); ?></p>
                          </div>
                        </div>
                         <div class="col-md-3">
                              <div class="form-group">
                                  <label for="languages"
                                      class="content-label"><?php echo e(trans('products.visible_to')); ?><span
                                          class="text-danger custom_asterisk">*</span></label><br>
                                  <input name="visible_to_dealer" value="dealer" id="dealer" type="checkbox" <?php echo e(@($product->visible_to =='dealer' || $product->visible_to =='both')?'checked' :''); ?> disabled>&nbsp; <label for="dealer"><?php echo e(trans('products.dealer')); ?></label>
                                  &nbsp;&nbsp;
                                  <input name="visible_to_retailer" value="retailer" id="retailer" type="checkbox" <?php echo e(@($product->visible_to =='retailer' || $product->visible_to =='both')?'checked' :''); ?> disabled>&nbsp;<label for="retailer"><?php echo e(trans('products.retailer')); ?></label>
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
                            <p class="details"> <?php echo e($product->length); ?></p>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="height"
                                class="content-label"><?php echo e(trans('products.height')); ?> (in CM)<span
                                    class="text-danger custom_asterisk">*</span></label><br>
                            <p class="details" ><?php echo e($product->height); ?></p>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="width"
                                class="content-label"><?php echo e(trans('products.width')); ?> (in CM)<span
                                    class="text-danger custom_asterisk">*</span></label><br>
                           <p class="details"> <?php echo e($product->width); ?></p>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                             <label class=""><?php echo e(trans('products.stock_status')); ?></label>
                                <select id="stock_status" class = "form-control" name='stock_status' disabled="">
                                  <option value=''><?php echo e(trans('common.select')); ?></option>
                                  <option value="in_stock" <?php echo e(@($product->stock_status == 'in_stock'?'selected':'')); ?> ><?php echo e(trans('products.in_stock')); ?></option>
                                  <option value="out_of_stock" <?php echo e(@($product->stock_status) == 'out_of_stock'?'selected':''); ?>><?php echo e(trans('products.out_of_stock')); ?></option>
                                </select>
                          </div>
                        </div>
                         <div class="col-md-4">
                              <div class="form-group">
                                  <label for="types"
                                      class="content-label"><?php echo e(trans('products.related_products')); ?><span
                                          class="text-danger custom_asterisk">*</span></label><br>
                                 <select id="related_products[]" class = "related_products_list form-control" name='related_products[]' multiple disabled=""> 
                                  <option value=''><?php echo e(trans('common.select')); ?></option>
                                  <?php $__currentLoopData = $related_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($rp->id); ?>" <?php echo e(in_array($rp->id, $related_product_ids)?'selected':''); ?>><?php echo e(($rp->name_english) ? ucfirst($rp->name_english) : ucfirst($rp->name_hindi)); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                              </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                          <div class="row">
                            <div class="col-md-12">
                                <input class="" name="is_live" type="checkbox"  id='is_live' <?php if($product->is_live == 1): ?> checked <?php endif; ?> disabled>&nbsp;&nbsp; <?php echo e(trans('business_categories.publish')); ?>

                            </div>
                        </div>
                      
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
       $(".related_products_list").select2({
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

      var lang = "<?php echo e($product->language); ?>";
      if(lang == 'hindi'){
        $('#hindi_div').show();
        $('#english_div').hide();
      }else if(lang == 'english'){
        $('#hindi_div').hide();
        $('#english_div').show();
      }else  if(lang == 'both'){
        $('#hindi_div').show();
        $('#english_div').show();
      }
  })
  </script>
  <?php echo $__env->make('layouts.admin.elements.filepond_js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/ssgc-bulk/resources/views/admin/order_return/view_product.blade.php ENDPATH**/ ?>