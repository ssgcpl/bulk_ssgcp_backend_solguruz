@extends('layouts.admin.app')
@section('css')
<link href="{{asset('css/filepond/filepond.css')}}" rel="stylesheet">
<link href="{{asset('css/filepond/filepond-plugin-image-preview.css')}}" rel="stylesheet">
<link href="{{asset('css/filepond/filepond-plugin-image-edit.css')}}" rel="stylesheet">
@endsection
@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">{{trans('product_barcodes.heading')}} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('product_barcodes.index')}}">{{ trans('product_barcodes.heading') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('product_barcodes.add_new') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($errors->any())
      <div class="alert alert-danger">
        <b>{{trans('common.whoops')}}</b>
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
              {{trans('product_barcodes.heading')}}
              </h4>
              <a href="{{route('product_barcodes.index')}}" class="btn btn-success">
                  <i class="fa fa-arrow-left"></i>
                  {{ trans('common.back') }}
              </a>
            </div>
            <div class="card-content">
              <div class="card-body">    
                <form method="POST" id="createForm"  accept-charset="UTF-8" enctype="multipart/form-data" >

                  @csrf
                  <!-- <hr style="border-top: 3px solid blue;"> -->
                  <div class="form-body">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group @error('title') ? has-error : ''  @enderror">
                          <label for="title" class="content-label">{{trans('product_barcodes.product_list')}}<span class="text-danger custom_asterisk">*</span></label>
                            <select id="product_id" class ="form-control" name='product_id'>
                                  <option value=''>{{trans('common.select')}}</option>
                                  @foreach($products as $product)
                                  <option value="{{$product->id}}" >{{ $product->name_hindi ? $product->name_hindi : $product->name_english  }}</option>
                                  @endforeach
                                </select>
                              @if ($errors->has('title')) 
                            <strong class="help-block alert-danger">{{ $errors->first('title') }}</strong>
                          @endif
                        </div>  
                      </div>

                      <div class="col-md-4">
                         <div class="form-group">
                          <label for="name_english" class="content-label">{{ trans('product_barcodes.name_english') }}</label>
                                      <input
                                          class="form-control"
                                          placeholder="{{ trans('product_barcodes.name_english') }}"
                                          name="name_english" id="name" value="" disabled>
                                  </div>
                      </div>
                      <div class="col-md-4">
                         <div class="form-group">
                          <label for="name_english" class="content-label">{{ trans('product_barcodes.product_id') }}</label>
                                      <input
                                          class="form-control"
                                          placeholder="{{ trans('product_barcodes.product_id') }}"
                                          name="prod_id" id="prod_id" value="" disabled>
                                  </div>
                      </div>
                         <div class="col-md-4">
                         <div class="form-group">
                          <label for="name_english" class="content-label">{{ trans('product_barcodes.sku_id') }}</label>
                                      <input
                                          class="form-control"
                                          placeholder="{{ trans('product_barcodes.sku_id') }}"
                                          name="sku_id" id="sku_id" value="" disabled>
                                  </div>
                      </div>
                    </div>
                      <div class="row">
                        <div class="col-md-4">
                         <div class="form-group">
                          <label for="barcode_qty" class="content-label">{{ trans('product_barcodes.barcode_qty') }}<span
                                              class="text-danger custom_asterisk">*</span></label>
                                      <input
                                          class="form-control"
                                          placeholder="{{ trans('product_barcodes.barcode_qty') }}"
                                          name="barcode_qty" value="">
                                  </div>
                      </div>
                   </div>
                   
                  </div>
                  <div class="modal-footer">
                    <button id="edit_btn" type="submit" class="btn btn-success btn-fill btn-wd">{{trans('common.submit')}}</button>
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
          var route = "{{route('product_barcodes.show','id')}}";
          route = route.replace('id',product_id);
           // console.log(data); return false;
          $.ajax({
                  url: "{{route('product_barcodes.store')}}",
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
                            location.href =  route;
                          }, 2000);
                      } else {
                          toastr.error(response.error);
                      }
                      $('#action_btn').prop('disabled',false);
                      $('#action_btn').text("{{trans('common.submit')}}");
                  }
          });
          //console.log('data',data)
    });

    $(document).on('change','#product_id',function(){
      var product_id = $("#product_id").val();
      var route = "{{route('get_product_detail',':id')}}";
      route = route.replace(':id',product_id);
      if(product_id != null){
         $.ajax({
                  url: route,
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
                      //    toastr.success(response.success);
                          $("#name").val(response.data.name);
                          $("#sku_id").val(response.data.sku_id);
                          $("#prod_id").val(response.data.id);
                      } else {
                          toastr.error(response.error);
                      }
                      $('#action_btn').prop('disabled',false);
                      $('#action_btn').text("{{trans('common.submit')}}");
                  }
          });
      }
    });
  })
</script>
@endsection
