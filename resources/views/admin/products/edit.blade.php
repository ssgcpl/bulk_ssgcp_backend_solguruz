@extends('layouts.admin.app')
@section('css')
<style type="text/css">
   .cover_images {width: 40%}
</style>
@include('layouts.admin.elements.filepond_css')
@endsection
@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-header row">
      <div class="content-header-left col-md-9 col-12 mb-2">
          <div class="row breadcrumbs-top">
              <div class="col-12">
                  <h2 class="content-header-title float-left mb-0">{{ trans('products.heading') }} </h2>
                  <div class="breadcrumb-wrapper col-12">
                      <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a
                                  href="{{ route('home') }}">{{ trans('common.home') }}</a>
                          </li>
                          <li class="breadcrumb-item"><a
                                  href="{{ route('products.index') }}">{{ trans('products.plural') }}</a></li>
                          <li class="breadcrumb-item active">{{ trans('products.update') }}
                          </li>
                      </ol>
                  </div>
              </div>
          </div>
      </div>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <b>{{ trans('common.whoops') }}</b>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form">
      <div class="row match-height">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    {{ trans('products.details') }}
                </h4>
                @can('product-list')
                      <a href="{{ route('stocks.index') }}?prod_id={{$product->id}}" class="btn btn-success">
                        {{ trans('products.view_stock_details') }}
                    </a>
                    <a href="{{route('products.index')}}" class="btn btn-success">
                        <i class="fa fa-arrow-left"></i>
                        {{ trans('common.back') }}
                    </a>
                @endcan
            </div>
            <div class="card-content">
              <div class="card-body">
                <form method="POST" id="createForm" accept-charset="UTF-8" enctype="multipart/form-data">
                    @csrf
                    <div class="form-body">
                      <div class="row">
                            <div class="col-md-12">
                              <div class="form-group">
                              <label for="categories"  class="content-label">{{ trans('products.categories') }}<span  class="text-danger custom_asterisk">*</span></label>
                              <ul class="nav nav-pills" role="tablist">
                              @foreach ($categories as $category)
                              <li class="nav-item">
                                <a class="nav-link" id="" data-toggle="pill"
                                 href="#tab_{{ $category->id }}" role="tab"
                                 aria-selected="true">{{ $category->category_name }}
                                </a>
                              </li>
                              @endforeach
                              </ul>
                              <div class="tab-content">
                              @foreach ($categories as $category)
                                <div class="tab-pane" id="tab_{{ $category->id }}"
                                role="tabpanel">
                                  <div class="container">
                                    <div class="row">
                                      <div class="col-md-6">
                                        <div id="treeview-checkbox-demo">
                                          <ul>{{-- <li> --}}
                                            <input type="checkbox" id="category[]" name="category[]" value="{{ $category->id }}"class="list" {{ in_array($category->id, $product_category_ids) || (is_array(old('list')) && in_array($category->id, old('list'))) ? 'checked' : '' }}>
                                            {{ $category->category_name }}
                                               @include('admin.products.manage_checkbox',
                                              ['childs' => $category->sub_category,'category' => $product_category_ids,])                  {{-- </li> --}}
                                            </ul>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                @endforeach
                              </div>
                            </div>
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                          <div class="col-md-3">
                              <div class="form-group">
                                  <label for="languages"
                                      class="content-label">{{ trans('common.language') }}<span
                                          class="text-danger custom_asterisk">*</span></label><br>
                                  <input name="language_english" value="english" id="english" type="checkbox" {{@(($product->language == 'english') || ($product->language == 'both')) ? 'checked':'' }}>&nbsp; <label for="english">{{ trans('common.english') }}</label>
                                  &nbsp;&nbsp;
                                  <input name="language_hindi" value="hindi" id="hindi" type="checkbox" {{ @($product->language == 'hindi' || $product->language == 'both') ? 'checked':'' }}>&nbsp;<label for="hindi">{{ trans('common.hindi') }}</label>
                              </div>
                          </div>
                        </div>
                          <div class="row" id="english_div">
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label for="name_english"
                                          class="content-label">{{ trans('products.name_english') }}<span
                                              class="text-danger custom_asterisk">*</span></label>
                                      <input
                                          class="form-control"
                                          placeholder="{{ trans('products.name_english') }}"
                                          name="name_english" value="{{$product->name_english}}" id="name_english">
                                  </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="heading_english"
                                        class="content-label">{{ trans('products.sub_heading_english') }}<span
                                            class="text-danger custom_asterisk">*</span></label>
                                    <input
                                        class="form-control"
                                        placeholder="{{ trans('products.sub_heading_english') }}"
                                        name="sub_heading_english" value="{{$product->sub_heading_english}}" id="sub_heading_english">
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="description_english"
                                      class="content-label">{{ trans('products.description_english') }}</label>
                                  <textarea class="form-control"
                                      id="description_english" name="description_english">{{$product->description_english}}</textarea>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="additional_info_english"
                                      class="content-label">{{ trans('products.additional_info_english') }}</label>
                                  <textarea class="form-control"
                                      id="additional_info_english" name="additional_info_english">{{$product->additional_info_english}}</textarea>
                                </div>
                              </div>
                          </div>
                          <div class="row" id="hindi_div">
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label for="name_hindi"
                                          class="content-label">{{ trans('products.name_hindi') }}<span
                                              class="text-danger custom_asterisk">*</span></label>
                                      <input class="form-control"
                                          placeholder="{{ trans('products.name_hindi') }}"  name="name_hindi" value="{{$product->name_hindi}}" id="name_hindi">
                                  </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sub_heading_hindi"
                                        class="content-label">{{ trans('products.sub_heading_hindi') }}<span
                                            class="text-danger custom_asterisk">*</span></label>
                                    <input
                                        class="form-control"
                                        placeholder="{{ trans('products.sub_heading_hindi') }}"
                                        name="sub_heading_hindi" value="{{$product->sub_heading_hindi}}" id="sub_heading_hindi">
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="description_hindi"
                                      class="content-label">{{ trans('products.description_hindi') }}</label>
                                  <textarea class="form-control"
                                      id="description_hindi" name="description_hindi" >{{$product->description_hindi}}</textarea>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="additional_info_english"
                                      class="content-label">{{ trans('products.additional_info_hindi') }}</label>
                                  <textarea class="form-control"
                                      id="additional_info_hindi" name="additional_info_hindi">{{$product->additional_info_hindi}}</textarea>
                                </div>
                              </div>
                          </div>
                      <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                  <label for="types"
                                      class="content-label">{{ trans('common.type') }}<span
                                          class="text-danger custom_asterisk">*</span></label><br>
                                 <select id="business_category_id" class ="form-control" name='business_category_id' required>
                                 <!--  <option value=''>{{trans('common.select')}}</option> -->
                                  @foreach($business_categories as $bc)
                                  <option value="{{$bc->id}}" {{@($bc->id == $product->business_category_id)?'selected':''}}>{{ucfirst($bc->category_name) }}</option>
                                  @endforeach
                                </select>
                              </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                              <label for="image" class="content-label">{{trans('products.product_image')}}<span class="text-danger custom_asterisk">*</span></label><br>
                              <input type="file" class="filepond_img" id="image" name="image">
                             <img src="{{asset($product->image)}}" class="cover_images">
                             
                            </div>
                            <label id="image-error" for="image"></label>
                            <strong class="help-block alert-danger">
                              {{ @$errors->first('image') }}
                            </strong>
                          </div>
                            <div class="col-md-4" id="product_cover_images">
                            <div class="form-group">
                                <label id="image-error"
                                    for="image">{{ trans('products.product_cover_images') }} <span
                                        class="text-danger custom_asterisk">*</span><br/>(Recomanded size: 250px x 350px)</label>
                                <input class="filepond_img2"
                                    type="file" multiple name="product_cover_images[]" >
                                 @foreach($product->cover_images as $cvr_img)
                                 <a href="javascript:void(0)" title="Remove"><img src="{{asset($cvr_img->image)}}" class="cover_images"><span class="remove_cvr_img" id="{{$cvr_img->id}}"  ><i class="fa fa-window-close"></i></span></a>
                                @endforeach                                       
                            </div>
                        </div>
                      </div>
                   
                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="mrp"
                                  class="content-label">{{ trans('products.mrp') }}<span
                                      class="text-danger custom_asterisk">*</span></label><br>
                              <input class="form-control numberonly" name="mrp" minlength="1" maxlength="8" value="{{$product->mrp}}" id="mrp" type="text" required>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="sale_price"
                                  class="content-label">{{ trans('products.dealer_sale_price') }}<span
                                      class="text-danger custom_asterisk">*</span></label><br>
                              <input class="form-control numberonly" name="dealer_sale_price" minlength="1" maxlength="8" value="{{$product->dealer_sale_price}}" id="dealer_sale_price" type="text" required>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="sale_price"
                                  class="content-label">{{ trans('products.retailer_sale_price') }}<span
                                      class="text-danger custom_asterisk">*</span></label><br>
                              <input class="form-control numberonly" name="retailer_sale_price" minlength="1" maxlength="8" value="{{$product->retailer_sale_price}}" id="retailer_sale_price" type="text" required>
                          </div>
                        </div>
                        <div class="col-md-3" >
                          <div class="form-group">
                              <label for="sku_id"
                                  class="content-label">{{ trans('products.sku_id') }}<span
                                      class="text-danger custom_asterisk">*</span></label><br>
                              <input class="form-control" name="sku_id" value="{{$product->sku_id}}" id="sku_id" type="text" required>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="weight"
                                  class="content-label">{{ trans('products.weight') }} (Kg)<span
                                      class="text-danger custom_asterisk">*</span></label><br>
                              <input class="form-control number_decimal" name="weight" value="{{$product->weight}}" id="weight" type="text" required>
                          </div>
                        </div>
                         <div class="col-md-3">
                          <div class="form-group">
                              <label for="weight"
                                  class="content-label">{{ trans('products.last_returnable_date') }}<span
                                      class="text-danger custom_asterisk">*</span></label><br>
                              <input class="form-control datepicker" name="last_returnable_date" value="{{date('d-m-Y',strtotime($product->last_returnable_date))}}" id="last_returnable_date" type="text" required>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="last returnable qty"
                                  class="content-label">{{ trans('products.last_returnable_qty')}}(in %)<span
                                      class="text-danger custom_asterisk">*</span></label><br>
                              <input class="form-control number_decimal" name="last_returnable_qty" value="{{$product->last_returnable_qty}}" id="last_returnable_qty" type="text" required>
                          </div>
                        </div>
                         <div class="col-md-3">
                              <div class="form-group">
                                  <label for="languages"
                                      class="content-label">{{ trans('products.visible_to') }}<span
                                          class="text-danger custom_asterisk">*</span></label><br>
                                  <input name="visible_to[]" value="dealer" id="dealer" type="checkbox" {{@($product->visible_to =='dealer' || $product->visible_to =='both')?'checked' :''}}>&nbsp; <label for="dealer">{{ trans('products.dealer') }}</label>
                                  &nbsp;&nbsp;
                                  <input name="visible_to[]" value="retailer" id="retailer" type="checkbox" {{@($product->visible_to =='retailer' || $product->visible_to =='both')?'checked' :''}}>&nbsp;<label for="retailer">{{ trans('products.retailer') }}</label>
                              </div>
                          </div>
                      </div>
                      <div class="row" id="dimensions_lable">
                        <div class="col-md-12">
                          <label for="" class="content-label"><b>{{ trans('products.dimensions') }}</b></label><br/>
                        </div>
                      </div>
                      <div class="row" id="dimensions_div">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="length"
                                class="content-label">{{ trans('products.length') }} (in CM)<span
                                    class="text-danger custom_asterisk">*</span></label><br>
                            <input class="form-control number_decimal" name="length" value="{{$product->length}}" id="length" type="text" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="height"
                                class="content-label">{{ trans('products.height') }} (in CM)<span
                                    class="text-danger custom_asterisk">*</span></label><br>
                            <input class="form-control number_decimal" name="height" value="{{$product->height}}" id="height" type="text" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="width"
                                class="content-label">{{ trans('products.width') }} (in CM)<span
                                    class="text-danger custom_asterisk">*</span></label><br>
                            <input class="form-control number_decimal" name="width" value="{{$product->width}}" id="width" type="text" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                             <label class="">{{trans('products.stock_status')}} <span
                                          class="text-danger custom_asterisk">*</span></label>
                                <select id="stock_status" class = "form-control" name='stock_status' required>
                                  <option value=''>{{trans('common.select')}}</option>
                                  <option value="in_stock" {{@($product->stock_status == 'in_stock'?'selected':'')}} >{{trans('products.in_stock') }}</option>
                                  <option value="out_of_stock" {{@($product->stock_status) == 'out_of_stock'?'selected':''}}>{{trans('products.out_of_stock') }}</option>
                                </select>
                          </div>
                        </div>
                         <div class="col-md-4">
                              <div class="form-group">
                                  <label for="types"
                                      class="content-label">{{ trans('products.related_products') }}                                        </label><br>
                                 <select id="related_products[]" class = "related_products_list form-control" name='related_products[]' multiple>
                                  <option value=''>{{trans('common.select')}}</option>
                                  @foreach($related_products as $rp)
                                  <option value="{{$rp->id}}" {{ in_array($rp->id, $related_product_ids)?'selected':''}}>{{($rp->name_english) ? ucfirst($rp->name_english) : ucfirst($rp->name_hindi) }}
                                  </option>
                                  @endforeach
                                </select>
                              </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                          <div class="row">
                            <div class="col-md-12">
                                <input class="" name="is_live" type="checkbox"  id='is_live' @if($product->is_live == 1) checked @endif>&nbsp;&nbsp; {{ trans('business_categories.publish') }}
                            </div>
                        </div>
                        <button id="action_btn" 
                            class="btn btn-info btn-fill btn-wd">{{ trans('common.submit') }}</button>
                        <button id="publish"  class="btn btn-info btn-fill btn-wd">{{ trans('common.re_publish') }} </button>
                    </div>
                      <input type="hidden" name='publish_date' id="publish_date" value="">
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  </div>
</div>
@endsection

@section('js')
    <script type="text/javascript">
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
          toastr.error("{{trans('products.one_lang_required')}}");
          $('#hindi').prop('checked',true);
        }
        
      }
    });

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
          toastr.error("{{trans('products.one_lang_required')}}");
          $('#english').prop('checked',true);
        }
      }
    });

    /*$(function () {
        $("ul.nav-pills li:first").find('a:first').addClass("active");
        $(".tab-content .tab-pane:first").addClass("active");
    });*/
    $(document).ready(function() {
      var first = $('.tab-pane input:checkbox:checked').closest('.tab-pane').attr('id');
      $('.nav-pills a[href="#'+first+ '"]').tab('show');

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

      var lang = "{{$product->language}}";
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
    $(document).on('click',"#hindi",function(){
      if($(this).is(':checked')){
        $('#hindi_div').show();
      }else {
        $('#hindi_div').hide();
      }
    })

    $(document).on('click',"#english",function(){
      if($(this).is(':checked')){
        $('#english_div').show();
      }else {
        $('#english_div').hide();
      }
    })  

     $("#action_btn").click(function() {
            $('#publish_date').val(0);
            submit_form_data();
        });

     $("#publish").click(function() {
            $('#publish_date').val(1);
            submit_form_data();
        });
    //Save Categories
    function submit_form_data() {
    $('#createForm').submit(function(evt){
          // tinyMCE.triggerSave();
          evt.preventDefault();
          var form = $('#createForm')[0];
          var formData = new FormData(form);
          formData .append('_method', 'put');
          var route = "{{route('products.update',':id')}}";
          route = route.replace(':id', '{{$product->id}}');

          $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
          });
          $.ajax({
                  url: route,
                  data: formData,
                  type: "POST",
                  processData : false,
                  contentType : false,
                  dataType : 'json',
                  beforeSend: function(xhr){
                      $('#action_btn').prop('disabled',true);
                      $('#action_btn').text("{{trans('common.submitting')}}");
                    },
                  success: function(response) {
                      console.log(response);
                      if(response.success) {
                          toastr.success(response.success);
                          setTimeout(function(){
                            location.href =  "{{route('products.index')}}";
                          }, 2000);
                      } else {
                          toastr.error(response.error);
                      }
                      $('#action_btn').prop('disabled',false);
                      $('#action_btn').text("{{trans('common.submit')}}");
                  }
          });
          //console.log('data',data)
    })
    }

     $('.remove_cvr_img').click(function(evt){
      evt.preventDefault();
       $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
          });
       $.ajax({
                  url: '{{route("remove_cover_image")}}',
                  data: {
                          "id" : $(this).attr('id')
                        },
                  type: "POST",
                  async:false,
                  beforeSend: function(xhr){
                      $('.remove_cvr_img').prop('disabled',true);
                    },
                  success: function(response) {
                    if(response.error){
                      toastr.error(response.error);
                    }
                    else{
                     location.reload();
                    }

                  }
          });
    })
  })
  </script>
  @include('layouts.admin.elements.filepond_js')
@endsection
