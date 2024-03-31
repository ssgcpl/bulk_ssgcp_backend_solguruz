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
                  <h2 class="content-header-title float-left mb-0">{{ trans('orders.heading') }} </h2>
                  <div class="breadcrumb-wrapper col-12">
                      <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a
                                  href="{{ route('home') }}">{{ trans('common.home') }}</a>
                          </li>
                          <li class="breadcrumb-item"><a
                                  href="{{ route('orders.edit',$_GET['order_id']) }}">{{ trans('orders.update') }}</a></li>
                          <li class="breadcrumb-item active">{{ trans('orders.view_product') }}
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
                    {{ trans('orders.product_detail') }}
                </h4>
                @can('product-list')
                    <a href="{{ route('orders.edit',$_GET['order_id']) }}" class="btn btn-success">
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
                                            <input type="checkbox" id="category[]" name="category[]" value="{{ $category->id }}" disabled class="list" {{ in_array($category->id, $product_category_ids) || (is_array(old('list')) && in_array($category->id, old('list'))) ? 'checked' : '' }} disabled>
                                            {{ $category->category_name }}
                                               @include('admin.products.manage_checkbox_show',
                                              ['childs' => $category->sub_category,'category' => $product_category_ids])                  {{-- </li> --}}
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
                                  <input name="language_english" value="english" id="english" type="checkbox" {{@(($product->language == 'english') || ($product->language == 'both')) ? 'checked':'' }} disabled>&nbsp; <label for="english">{{ trans('common.english') }}</label>
                                  &nbsp;&nbsp;
                                  <input name="language_hindi" value="hindi" id="hindi" type="checkbox" {{ @($product->language == 'hindi' || $product->language == 'both') ? 'checked':'' }} disabled>&nbsp;<label for="hindi">{{ trans('common.hindi') }}</label>
                              </div>
                          </div>
                        </div>
                          <div class="row" id="english_div">
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label for="name_english"
                                          class="content-label">{{ trans('products.name_english') }}<span
                                              class="text-danger custom_asterisk">*</span></label>
                                      <p class="details"> {{$product->name_english}} </p>
                                  </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="heading_english"
                                        class="content-label">{{ trans('products.sub_heading_english') }}<span
                                            class="text-danger custom_asterisk">*</span></label>
                                   <p class="details"> {{$product->sub_heading_english}} </p>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="description_english"
                                      class="content-label">{{ trans('products.description_english') }}</label>
                                  <textarea class="form-control" disabled>
                                     {{$product->description_english}}</textarea>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="additional_info_english"
                                      class="content-label">{{ trans('products.additional_info_english') }}</label>
                                 <textarea class="form-control" disabled>{{$product->additional_info_english}}</textarea>
                                </div>
                              </div>
                          </div>
                          <div class="row" id="hindi_div">
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label for="name_hindi"
                                          class="content-label">{{ trans('products.name_hindi') }}<span
                                              class="text-danger custom_asterisk">*</span></label>
                                     <p class="details"> {{$product->name_hindi}} </p>
                                  </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sub_heading_hindi"
                                        class="content-label">{{ trans('products.sub_heading_hindi') }}<span
                                            class="text-danger custom_asterisk">*</span></label>
                                    <p class="details"> {{$product->sub_heading_hindi}} </p>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="description_hindi"
                                      class="content-label">{{ trans('products.description_hindi') }}</label>
                                  <textarea class="form-control" disabled>{{$product->description_hindi}}</textarea>
                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label for="additional_info_english"
                                      class="content-label">{{ trans('products.additional_info_hindi') }}</label>
                                  <textarea class="form-control" disabled>{{$product->additional_info_hindi}}</textarea>
                                </div>
                              </div>
                          </div>
                      <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                  <label for="types"
                                      class="content-label">{{ trans('common.type') }}<span
                                          class="text-danger custom_asterisk">*</span></label><br>
                                 <select id="business_category_id" class ="form-control" name='type' disabled="">
                                  <option value=''>{{trans('common.select')}}</option>
                                  @foreach($business_categories as $bc)
                                  <option value="{{$bc->id}}" {{@($bc->id == $product->business_category_id)?'selected':''}}>{{ucfirst($bc->category_name) }}</option>
                                  @endforeach
                                </select>
                              </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                              <label for="image" class="content-label">{{trans('products.product_image')}}<span class="text-danger custom_asterisk">*</span></label><br>
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
                                 @foreach($product->cover_images as $cvr_img)
                                <!--  <a href="javascript:void(0)" title="Remove"> --><img src="{{asset($cvr_img->image)}}" class="cover_images"><span class="" id="{{$cvr_img->id}}"  ><!-- <i class="fa fa-window-close"></i></span></a> -->
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
                              <p class="details"> {{$product->mrp}}</p>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="sale_price"
                                  class="content-label">{{ trans('products.dealer_sale_price') }}<span
                                      class="text-danger custom_asterisk">*</span></label><br>
                             <p class="details"> {{$product->dealer_sale_price}}</p>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="sale_price"
                                  class="content-label">{{ trans('products.retailer_sale_price') }}<span
                                      class="text-danger custom_asterisk">*</span></label><br>
                              <p class="details"> {{$product->retailer_sale_price}}</p>
                          </div>
                        </div>
                        <div class="col-md-3" >
                          <div class="form-group">
                              <label for="sku_id"
                                  class="content-label">{{ trans('products.sku_id') }}<span
                                      class="text-danger custom_asterisk">*</span></label><br>
                             <p class="details"> {{$product->sku_id}}</p>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="weight"
                                  class="content-label">{{ trans('products.weight') }} (Kg)<span
                                      class="text-danger custom_asterisk">*</span></label><br>
                            <p class="details">{{$product->weight}}</p>
                          </div>
                        </div>
                         <div class="col-md-3">
                          <div class="form-group">
                              <label for="weight"
                                  class="content-label">{{ trans('products.last_returnable_date') }}<span
                                      class="text-danger custom_asterisk">*</span></label><br>
                             <p class="details"> {{$product->last_returnable_date}}</p>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                              <label for="last returnable qty"
                                  class="content-label">{{ trans('products.last_returnable_qty')}}(in %)<span
                                      class="text-danger custom_asterisk">*</span></label><br>
                             <p class="details"> {{$product->last_returnable_qty}}</p>
                          </div>
                        </div>
                         <div class="col-md-3">
                              <div class="form-group">
                                  <label for="languages"
                                      class="content-label">{{ trans('products.visible_to') }}<span
                                          class="text-danger custom_asterisk">*</span></label><br>
                                  <input name="visible_to_dealer" value="dealer" id="dealer" type="checkbox" {{@($product->visible_to =='dealer' || $product->visible_to =='both')?'checked' :''}} disabled>&nbsp; <label for="dealer">{{ trans('products.dealer') }}</label>
                                  &nbsp;&nbsp;
                                  <input name="visible_to_retailer" value="retailer" id="retailer" type="checkbox" {{@($product->visible_to =='retailer' || $product->visible_to =='both')?'checked' :''}} disabled>&nbsp;<label for="retailer">{{ trans('products.retailer') }}</label>
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
                            <p class="details"> {{$product->length}}</p>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="height"
                                class="content-label">{{ trans('products.height') }} (in CM)<span
                                    class="text-danger custom_asterisk">*</span></label><br>
                            <p class="details" >{{$product->height}}</p>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="width"
                                class="content-label">{{ trans('products.width') }} (in CM)<span
                                    class="text-danger custom_asterisk">*</span></label><br>
                           <p class="details"> {{$product->width}}</p>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                             <label class="">{{trans('products.stock_status')}}</label>
                                <select id="stock_status" class = "form-control" name='stock_status' disabled="">
                                  <option value=''>{{trans('common.select')}}</option>
                                  <option value="in_stock" {{@($product->stock_status == 'in_stock'?'selected':'')}} >{{trans('products.in_stock') }}</option>
                                  <option value="out_of_stock" {{@($product->stock_status) == 'out_of_stock'?'selected':''}}>{{trans('products.out_of_stock') }}</option>
                                </select>
                          </div>
                        </div>
                         <div class="col-md-4">
                              <div class="form-group">
                                  <label for="types"
                                      class="content-label">{{ trans('products.related_products') }}<span
                                          class="text-danger custom_asterisk">*</span></label><br>
                                 <select id="related_products[]" class = "related_products_list form-control" name='related_products[]' multiple disabled=""> 
                                  <option value=''>{{trans('common.select')}}</option>
                                  @foreach($related_products as $rp)
                                  <option value="{{$rp->id}}" {{ in_array($rp->id, $related_product_ids)?'selected':''}}>{{($rp->name_english) ? ucfirst($rp->name_english) : ucfirst($rp->name_hindi) }}</option>
                                  @endforeach
                                </select>
                              </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                          <div class="row">
                            <div class="col-md-12">
                                <input class="" name="is_live" type="checkbox"  id='is_live' @if($product->is_live == 1) checked @endif disabled>&nbsp;&nbsp; {{ trans('business_categories.publish') }}
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
@endsection

@section('js')
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
  })
  </script>
  @include('layouts.admin.elements.filepond_js')
@endsection
