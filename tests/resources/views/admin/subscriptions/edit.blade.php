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
                    <h2 class="content-header-title float-left mb-0">{{trans('subscriptions.heading')}} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('subscriptions.index')}}">{{ trans('subscriptions.plural') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('subscriptions.update') }}
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
              {{ trans('subscriptions.details')}}
              </h4>
              @can('subscriptions-list')
                <a href="{{route('subscriptions.index')}}" class="btn btn-success">
                    <i class="fa fa-arrow-left"></i>
                    {{ trans('common.back') }}
                </a>
              @endcan
            </div>
            <div class="card-content">
              <div class="card-body">    
                <form id="stateForm" method="POST" action="{{route('subscriptions.update',$subscriptions->id)}}" accept-charset="UTF-8" enctype="multipart/form-data">
                <input name="_method" type="hidden" value="PUT">
                  @csrf
                  <div class="form-body">
                  <div class="row">
                    <div class="col-md-12">
                     <div class="form-group">
                        <label for="name" class="content-label">{{trans('subscriptions.name')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input class="form-control" minlength="2" maxlength="190" placeholder="{{trans('subscriptions.pl_name')}}" name="name" value="{{old('name') ? old('name') : $subscriptions->name}}" required type="text">
                        @if ($errors->has('name')) 
                          <strong class="help-block alert-danger">{{ $errors->first('name') }}</strong>
                        @endif
                      </div>  
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                     <div class="form-group">
                        <label for="description" class="content-label">{{trans('subscriptions.description')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input class="form-control" placeholder="{{trans('subscriptions.pl_description')}}" name="description" value="{{old('description') ? old('description') : $subscriptions->description}}" required maxlength="190" type="text">
                        @if ($errors->has('description')) 
                          <strong class="help-block alert-danger">{{ $errors->first('description') }}</strong>
                        @endif
                      </div>  
                    </div>
                  </div>
                  
                  <div id="key_feature_bag">
                    <?php
                      $key_features_arr = explode(',', $subscriptions->key_features);
                      $key_features_count = count($key_features_arr);
                    ?>

                    <div class="row">
                      <div class="col-md-10">
                       <div class="form-group">
                          <label class="content-label" id="key_feature_label">{{trans('subscriptions.key_features')}} ({{$key_features_count}}/6)<span class="text-danger custom_asterisk">*</span></label>
                          <input class="form-control" placeholder="{{trans('subscriptions.pl_key_features')}}" name="key_features[]" value="{{$key_features_arr[0]}}" required maxlength="50" type="text">
                          @if ($errors->has('key_features')) 
                            <strong class="help-block alert-danger">{{ $errors->first('key_features') }}</strong>
                          @endif
                        </div>  
                      </div>
                      <div class="col-md-2">
                          <button id="add_more" type="button" class="btn btn-success btn-fill btn-wd" style="margin-top: 19px;">{{trans('subscriptions.add_more')}}</button>
                      </div>
                    </div>

                    @if($key_features_count > 1)
                      @foreach($key_features_arr as $key => $key_feaure)
                        @if($key != 0)
                          <div class="row key_feature_row">
                            <div class="col-md-10">
                             <div class="form-group">
                                <input class="form-control" placeholder="{{trans('subscriptions.pl_key_features')}}" name="key_features[]" value="{{$key_feaure}}" required maxlength="50" type="text">
                                @if ($errors->has('key_features')) 
                                  <strong class="help-block alert-danger">{{ $errors->first('key_features') }}</strong>
                                @endif
                              </div>  
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-success btn-fill btn-wd remove_row">{{trans("subscriptions.remove")}}</button>
                            </div>
                          </div>
                        @endif
                      @endforeach
                    @endif
                  </div>

                  <div class="row">
                    
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="price" class="content-label">{{trans('subscriptions.no_of_free_avatars')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input class="form-control" placeholder="{{trans('subscriptions.no_of_free_avatars')}}" name="no_of_free_avatars" value="{{old('no_of_free_avatars') ? old('no_of_free_avatars') : $subscriptions->no_of_free_avatars}}" required type="number" maxlength="4" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" id="no_of_free_avatars" max="9999">
                          @if ($errors->has('no_of_free_avatars')) 
                          <strong class="help-block alert-danger">{{ $errors->first('no_of_free_avatars') }}</strong>
                        @endif
                      </div>  
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="published_date" class="content-label">{{trans('subscriptions.price_per')}}<span class="text-danger custom_asterisk">*</span></label>
                        <select class="form-control" name="price_per" id="price_per" required>
                          <option value="all" @if(old('price_per') == 'all') selected @endif>{{trans('subscriptions.please_select')}}</option>
                          <option value="day" @if(old('price_per') == 'day' || $subscriptions->price_per == 'day') selected @endif>{{trans('subscriptions.day')}}</option>
                          <option value="week" @if(old('price_per') == 'week' || $subscriptions->price_per == 'week')selected @endif>{{trans('subscriptions.week')}}</option>
                          <option value="month" @if(old('price_per') == 'month' || $subscriptions->price_per == 'month')selected @endif>{{trans('subscriptions.month')}}</option>
                          <option value="year" @if(old('price_per') == 'year' || $subscriptions->price_per == 'year')selected @endif>{{trans('subscriptions.year')}}</option>
                        </select>
                        @if ($errors->has('price_per')) 
                          <strong class="help-block alert-danger">{{ $errors->first('price_per') }}</strong>
                        @endif
                      </div>  
                    </div>
                    <div class="col-md-4">
                     <div class="form-group">
                        <label for="price" class="content-label">{{trans('subscriptions.validity')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input class="form-control" placeholder="{{trans('subscriptions.validity')}}" name="validity" value="{{old('validity') ? old('validity') : $subscriptions->validity}}" required type="number" maxlength="1" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" id="validity" max="6" min="1">
                          @if ($errors->has('validity')) 
                          <strong class="help-block alert-danger">{{ $errors->first('validity') }}</strong>
                        @endif
                      </div>  
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                        <div class="form-group @error('color_code') ? has-error : ''  @enderror">
                          <label for="colour" class="content-label">{{ trans('subscriptions.color_code') }}<span class="text-danger custom_asterisk">*</span></label>
                          <input class="form-control" minlength="2" maxlength="7" placeholder="{{trans('subscriptions.color_code')}}" name="color_code" type="color" value="{{old('color_code') ? old('color_code') : $subscriptions->color_code }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="published_date" class="content-label">{{trans('subscriptions.type')}}<span class="text-danger custom_asterisk">*</span></label>
                        <select class="form-control" name="type" id="type" required>
                          <option value="all" @if(old('type') == 'all')selected @endif>{{trans('subscriptions.please_select')}}</option>
                          <option value="free" @if(old('type') == 'free' || $subscriptions->type == 'free')selected @endif>{{trans('subscriptions.free')}}</option>
                          <option value="paid" @if(old('type') == 'paid' || $subscriptions->type == 'paid')selected @endif>{{trans('subscriptions.paid')}}</option>
                        </select>
                        @if ($errors->has('type')) 
                          <strong class="help-block alert-danger">{{ $errors->first('type') }}</strong>
                        @endif
                      </div>  
                    </div>
                    <div class="col-md-4">
                     <div class="form-group">
                        <label for="price" class="content-label">{{trans('subscriptions.price')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input class="form-control" placeholder="{{trans('subscriptions.price')}}" name="price" value="{{old('price') ? old('price') : $subscriptions->price}}" required type="number" maxlength="8" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" id="price">
                          @if ($errors->has('price')) 
                          <strong class="help-block alert-danger">{{ $errors->first('price') }}</strong>
                        @endif
                      </div>  
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="image" class="content-label">{{trans('subscriptions.image')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input type="file" id="image" name="card_picture" required >
                       
                      </div>
                      <label id="card_picture-error" for="card_picture"></label>
                      <strong class="help-block alert-danger">
                        {{ @$errors->first('card_picture') }}
                      </strong>
                    </div>
                  </div>
                  <h4 class="card-title">
                 {{trans('subscriptions.benefits')}}
                  </h4>
                  <br/>
                  <div class="row">
                    @foreach($benefits as $benefit)
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="content-label">
                          <input type="checkbox" value="{{$benefit->id}}" name="permissions[]" @if($benefit->checked == 'true')checked @endif>
                        {{$benefit->title}}</label>
                      </div>
                    </div>
                    @endforeach
                  </div>
                  <div class="row">
                    <strong class="help-block alert-danger">
                      {{ @$errors->first('permissions') }}
                    </strong>
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
<script>

  $(document).ready(function(){
    var value = $('#price_per').val();
    if(value == 'day'){

      $('#validity').attr('max',6);
      $('#validity').attr('maxlength',1);
      
    }else if(value == 'week'){
      $('#validity').attr('max',4);
      $('#validity').attr('maxlength',1);
    }else if(value == 'month'){
      $('#validity').attr('max',11);
      $('#validity').attr('maxlength',2);
    }else if(value == 'year'){
      $('#validity').attr('max',100);
      $('#validity').attr('maxlength',3);
    }else{
      $('#validity').attr('disabled',true);
    }

    var type = $('#type').val();
    if(type == 'paid'){
      $('#price').attr('disabled',false);
      $('#price').attr('min',1);
    }else{
      $('#price').attr('disabled',true);
      $('#price').val(0);
      $('#price').removeAttr('min');
    }
  });
  $(document).on('change','#price_per',function(){

    var value = $('#price_per').val();
    if(value == 'day'){

      $('#validity').attr('max',6);
      $('#validity').attr('maxlength',1);
      $('#validity').attr('disabled',false);
      
    }else if(value == 'week'){
      $('#validity').attr('max',4);
      $('#validity').attr('disabled',false);
      $('#validity').attr('maxlength',1);
    }else if(value == 'month'){
      $('#validity').attr('max',11);
      $('#validity').attr('maxlength',2);
      $('#validity').attr('disabled',false);
    }else if(value == 'year'){
      $('#validity').attr('max',100);
      $('#validity').attr('maxlength',3);
      $('#validity').attr('disabled',false);
    }else{
      $('#validity').attr('disabled',true);
    }
  });
  $(document).on('change','#type',function(){

    var value = $(this).val();
    if(value == 'paid'){
      $('#price').attr('disabled',false);
      $('#price').val('');
      $('#price').attr('min',1);
    }else{
      $('#price').attr('disabled',true);
      $('#price').val(0);
      $('#price').removeAttr('min');
    }
  });
</script>
<script src="{{asset('js/filepond/filepond.min.js')}}"></script>
<!-- include FilePond plugins -->
<script src="{{asset('js/filepond/filepond-plugin-image-preview.min.js')}}"></script>
<script src="{{asset('js/filepond/filepond-plugin-file-validate-type.js')}}"></script>
<script src="{{asset('js/filepond/filepond-plugin-image-crop.js')}}"></script>
<script src="{{asset('js/filepond/filepond-plugin-image-resize.js')}}"></script>
<script src="{{asset('js/filepond/filepond-plugin-image-validate-size.js')}}"></script>
<script src="{{asset('js/filepond/filepond-plugin-image-transform.js')}}"></script>
<script src="{{asset('js/filepond/filepond-plugin-image-edit.js')}}"></script>
<script src="{{asset('js/filepond/ar_locale.js')}}"></script>
<!-- <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script> -->
<!-- include FilePond jQuery adapter -->
<script src="{{asset('js/filepond/filepond.jquery.js')}}"></script>

<script>
$(document).ready(function(){
  var total_rows = "{{$key_features_count}}";
  $("#add_more").click(function () {
      if(total_rows <= 5)
      {
        total_rows++;
        $('#key_feature_label').html('{{trans("subscriptions.key_features")}} ('+total_rows+'/6)<span class="text-danger custom_asterisk">*</span>');
        var newRowAdd =
        '<div class="row key_feature_row">'+
          '<div class="col-md-10">'+
           '<div class="form-group">'+
              '<input class="form-control" placeholder="{{trans("subscriptions.pl_key_features")}}" name="key_features[]" required maxlength="50" type="text">'+
              
            '</div>'  +
          '</div>'+
          '<div class="col-md-2">'+
              '<button type="button" class="btn btn-success btn-fill btn-wd remove_row">{{trans("subscriptions.remove")}}</button>'+
          '</div>'+
        '</div>';

        $('#key_feature_bag').append(newRowAdd);
      }
      
  });

  $(document).on("click", ".remove_row", function () {
      total_rows--;
      $('#key_feature_label').html('{{trans("subscriptions.key_features")}} ('+total_rows+'/6)<span class="text-danger custom_asterisk">*</span>');
      $(this).parents(".key_feature_row").remove();
  })
})

$(function(){
  
  FilePond.registerPlugin(

          FilePondPluginImagePreview,
          FilePondPluginFileValidateType,
          FilePondPluginImageCrop,
          FilePondPluginImageResize,
          FilePondPluginImageValidateSize,
          FilePondPluginImageTransform,
          FilePondPluginImageEdit,

        );
  
  const input = document.querySelector('#image');
  const pond1 = FilePond.create(input,{
      
      imagePreviewMaxHeight: 250,
      imagePreviewMaxWidth: 250,
      storeAsFile:true,
      credits:false,
      allowImageCrop:true,
      allowImageTransform:true,
      imageCropAspectRatio:'1:1',
      acceptedFileTypes:['image/png', 'image/jpeg','image/jpg','image/gif'],
      allowImageResize:true,
      imageResizeTargetWidth:500,
      imageResizeTargetHeight:500,
      imageResizeUpscale:true,
      imageValidateSizeMinWidth:250,
      imageValidateSizeMinHeight:250,
      files: [
         
            {
                // the server file reference
                source: "{{asset($subscriptions->card_picture)}}",

                // set type to local to indicate an already uploaded file
                options: {
                    type: 'remote',
                    
                
                },
            },
            
          ],
      
     
  });

   //Change Locale
  @if(config("app.locale") == 'ar'){
   FilePond.setOptions(labels);
  }
  @endif
});

</script>

@endsection
