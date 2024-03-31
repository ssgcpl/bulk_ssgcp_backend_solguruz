@extends('layouts.admin.app')
@section('css')
<style type="text/css">
  #e_product_type_div {margin-top: 1rem; margin-bottom: 1rem; padding-top: 10px; border: 0; border-top: 1px solid rgba(34, 41, 47, 0.1); border-bottom: 1px solid rgba(34, 41, 47, 0.1); }
  .add_new_chapter {cursor: pointer;}
  /*.close_chapter_div {float: right; position: relative; padding: 2px; top: 1px}*/
  .close_chapter_div {float: right; padding: 2px; top: 1px}

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
                          <li class="breadcrumb-item active">{{ trans('products.add_new') }}
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
                    <a href="{{ route('products.index') }}" class="btn btn-success">
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
                                  <label for="tags"
                                      class="content-label">{{ trans('products.categories') }}<span
                                          class="text-danger custom_asterisk">*</span></label>

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
                                                              <ul>
                                                                  {{-- <li> --}}
                                                                  <input type="checkbox"
                                                                      id="category[]"
                                                                      name="category[]"
                                                                      value="{{ $category->id }}"
                                                                      class="list"
                                                                      {{ is_array(old('category')) && in_array($category->id, old('category')) ? 'checked' : '' }}>
                                                                  {{ $category->category_name }}
                                                                  @include('admin.products.manage_checkbox',
                                                                      [
                                                                          'childs' =>
                                                                              $category->sub_category,
                                                                      ])
                                                                  {{-- </li> --}}
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
                                  <input name="language_english" value="english" id="english" type="checkbox" checked>&nbsp; <label for="english">{{ trans('common.english') }}</label>
                                  &nbsp;&nbsp;
                                  <input name="language_hindi" value="hindi" id="hindi" type="checkbox" checked>&nbsp;<label for="hindi">{{ trans('common.hindi') }}</label>
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
                                          name="name_english" id="name_english" value="" required>
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
                                        name="sub_heading_english" id="sub_heading_english" value="" required>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="description_english"
                                      class="content-label">{{ trans('products.description_english') }}</label>
                                  <textarea class="form-control"
                                      id="description_english" name="description_english"></textarea>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="additional_info_english"
                                      class="content-label">{{ trans('products.additional_info_english') }}</label>
                                  <textarea class="form-control"
                                      id="additional_info_english" name="additional_info_english"></textarea>
                                </div>
                              </div>
                          </div>
                          <div class="row" id="hindi_div">
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label for="name_hindi"
                                          class="content-label">{{ trans('products.name_hindi') }}<span
                                              class="text-danger custom_asterisk">*</span></label>
                                      <input
                                          class="form-control"
                                          placeholder="{{ trans('products.name_hindi') }}"
                                          name="name_hindi" value="" id="name_hindi" required>
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
                                        name="sub_heading_hindi" value="" id="sub_heading_hindi" required >
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="description_hindi"
                                      class="content-label">{{ trans('products.description_hindi') }}</label>
                                  <textarea class="form-control"
                                      id="description_hindi" name="description_hindi"></textarea>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="additional_info_english"
                                      class="content-label">{{ trans('products.additional_info_hindi') }}</label>
                                  <textarea class="form-control"
                                      id="additional_info_hindi" name="additional_info_hindi"></textarea>
                                </div>
                              </div>
                          </div>
                      <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                  <label for="types"
                                      class="content-label">{{ trans('common.type') }} (Business Category)<span
                                          class="text-danger custom_asterisk">*</span></label><br>
                                 <select id="business_category_id" class = "form-control" name='business_category_id' required>
                                  <option value=''>{{trans('common.select')}}</option>
                                  @foreach($business_categories as $bc)
                                  <option value="{{$bc->id}}">{{ucfirst($bc->category_name) }}</option>
                                  @endforeach
                                </select>
                              </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                              <label for="image" class="content-label">{{trans('products.product_image')}}<span class="text-danger custom_asterisk">*</span></label><br>
                              <input type="file" class="filepond_img" id="image" name="image" required>

                            </div>
                            <label id="image-error" for="image"></label>
                            <strong class="help-block alert-danger">
                              {{ @$errors->first('image') }}
                            </strong>
                          </div>
                            <div class="col-md-4" id="book_cover_images">
                            <div class="form-group">
                                <label id="image-error"
                                    for="image">{{ trans('products.product_cover_images') }} <span
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
                                  class="content-label">{{ trans('products.mrp') }}<span
                                      class="text-danger custom_asterisk">*</span></label><br>
                              <input class="form-control numberonly" name="mrp" minlength="1" maxlength="8" value="" id="mrp" type="text" required>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="sale_price"
                                  class="content-label">{{ trans('products.dealer_sale_price') }}<span
                                      class="text-danger custom_asterisk">*</span></label><br>
                              <input class="form-control numberonly" name="dealer_sale_price" minlength="1" maxlength="8" value="" id="dealer_sale_price" type="text" required>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="sale_price"
                                  class="content-label">{{ trans('products.retailer_sale_price') }}<span
                                      class="text-danger custom_asterisk">*</span></label><br>
                              <input class="form-control numberonly" name="retailer_sale_price" minlength="1" maxlength="8" value="" id="retailer_sale_price" type="text" required>
                          </div>
                        </div>
                        <div class="col-md-3" >
                          <div class="form-group">
                              <label for="sku_id"
                                  class="content-label">{{ trans('products.sku_id') }}<span
                                      class="text-danger custom_asterisk">*</span></label><br>
                              <input class="form-control" name="sku_id" value="" id="sku_id" type="text" required>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="weight"
                                  class="content-label">{{ trans('products.weight') }} (Kg)<span
                                      class="text-danger custom_asterisk">*</span></label><br>
                              <input class="form-control number_decimal" name="weight" value="" id="weight" type="text" required>
                          </div>
                        </div>
                         <div class="col-md-3">
                          <div class="form-group">
                              <label for="weight"
                                  class="content-label">{{ trans('products.last_returnable_date') }}<span
                                      class="text-danger custom_asterisk">*</span></label><br>
                              <input class="form-control datepicker" name="last_returnable_date" value="" id="last_returnable_date" type="text" required>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="weight"
                                  class="content-label">{{ trans('products.last_returnable_qty')}}(in %)<span
                                      class="text-danger custom_asterisk">*</span></label><br>
                              <input class="form-control number_decimal" name="last_returnable_qty" value="" id="last_returnable_qty" type="text" required>
                          </div>
                        </div>
                         <div class="col-md-3">
                              <div class="form-group">
                                  <label for="languages"
                                      class="content-label">{{ trans('products.visible_to') }}<span
                                          class="text-danger custom_asterisk">*</span></label><br>
                                  <input name="visible_to[]" value="dealer" id="dealer" type="checkbox" checked="">&nbsp; <label for="english">{{ trans('products.dealer') }}</label>
                                  &nbsp;&nbsp;
                                  <input name="visible_to[]" value="retailer" id="retailer" type="checkbox" checked="">&nbsp;<label for="retailer">{{ trans('products.retailer') }}</label>
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
                            <input class="form-control number_decimal" name="length" value="" id="length" type="text" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="height"
                                class="content-label">{{ trans('products.height') }} (in CM)<span
                                    class="text-danger custom_asterisk">*</span></label><br>
                            <input class="form-control number_decimal" name="height" value="" id="height" type="text" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="width"
                                class="content-label">{{ trans('products.width') }} (in CM)<span
                                    class="text-danger custom_asterisk">*</span></label><br>
                            <input class="form-control number_decimal" name="width" value="" id="width" type="text" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                             <label class="">{{trans('products.stock_status')}}<span
                                    class="text-danger custom_asterisk">*</span></label>
                                <select id="stock_status" class = "form-control" name='stock_status' required>
                                  <option value=''>{{trans('common.select')}}</option>
                                  <option value="in_stock">{{trans('products.in_stock') }}</option>
                                  <option value="out_of_stock">{{trans('products.out_of_stock') }}</option>
                                </select>
                          </div>
                        </div>

                         <div class="col-md-4">
                              <div class="form-group">
                                  <label for="types"
                                      class="content-label">{{ trans('products.related_products') }}</label><br>
                                 <select id="related_products" class = "form-control" name='related_products[]' multiple>
                                  <option value=''>{{trans('common.select')}}</option>
                                  @foreach($related_products as $rp)
                                  <option value="{{$rp->id}}">{{($rp->name_english) ? ucfirst($rp->name_english) : ucfirst($rp->name_hindi) }}</option>
                                  @endforeach
                                </select>
                              </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                          <div class="row">
                            <div class="col-md-12">
                                <input class="" name="is_live" type="checkbox"  id='is_live' checked>&nbsp;&nbsp; {{ trans('business_categories.publish') }}
                            </div>
                        </div>
                        <button id="action_btn" type="submit"
                            class="btn btn-info btn-fill btn-wd">{{ trans('common.submit') }}</button>
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
@endsection

@section('js')
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
          toastr.error("{{trans('products.one_lang_required')}}");
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
          toastr.error("{{trans('products.one_lang_required')}}");
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
                  url: "{{route('products.store')}}",
                  data: data,
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
  })
  </script>
  @include('layouts.admin.elements.filepond_js')
@endsection
