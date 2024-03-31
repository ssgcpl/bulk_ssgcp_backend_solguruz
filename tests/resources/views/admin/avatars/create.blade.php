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
                    <h2 class="content-header-title float-left mb-0">{{trans('avatars.heading')}} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('avatars.index')}}">{{ trans('avatars.heading') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('avatars.add_new') }}
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
              {{trans('avatars.heading')}}
              </h4>
              <a href="{{route('avatars.index')}}" class="btn btn-success">
                  <i class="fa fa-arrow-left"></i>
                  {{ trans('common.back') }}
              </a>
            </div>
            <div class="card-content">
              <div class="card-body">    
                <form method="POST" id="categoryForm" action="{{route('avatars.store')}}" accept-charset="UTF-8" enctype="multipart/form-data" >
                  @csrf
                  <!-- <hr style="border-top: 3px solid blue;"> -->
                    <div class="form-body">
                      <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="published_date" class="content-label">{{trans('avatars.type')}}<span class="text-danger custom_asterisk">*</span></label>
                              <select class="form-control" name="type" id="type" required>
                                <option value="all">{{trans('avatars.please_select')}}</option>
                                <option value="free" @if(old('type') == 'free')selected @endif>{{trans('avatars.free')}}</option>
                                <option value="paid" @if(old('type') == 'paid')selected @endif>{{trans('avatars.paid')}}</option>
                              </select>
                              @if ($errors->has('type')) 
                                <strong class="help-block alert-danger">{{ $errors->first('type') }}</strong>
                              @endif
                            </div>  
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="price" class="content-label">{{trans('avatars.price')}}<span class="text-danger custom_asterisk">*</span></label>
                              <input class="form-control" minlength="1" maxlength="8" placeholder="{{trans('avatars.price')}}" name="price" type="number" value="{{old('price') ? old('price') : '0' }}" required oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  id="price">
                              @if ($errors->has('price')) 
                                <strong class="help-block alert-danger">{{ $errors->first('price') }}</strong>
                              @endif
                            </div>  
                          </div>
                          <div class="col-md-12">
                            <div class="form-group">
                              <label for="content:" class="content-label">{{trans('avatars.name')}}<span class="text-danger custom_asterisk">*</span></label>
                            
                              <input type="text" class="form-control" placeholder="{{trans('avatars.name')}}" name="name" type="text" value="{{old('name')}}" maxlength="50" required>
                              @if ($errors->has('name')) 
                                <strong class="help-block alert-danger">{{ $errors->first('name') }}</strong>
                              @endif
                            </div>  
                          </div>
                      </div>
                    </div>
                  <div class="form-body">
                      <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                              <label for="image" class="content-label">{{trans('avatars.image')}}<span class="text-danger custom_asterisk">*</span></label>
                              <input type="file" id="image" name="image" required >
                             
                            </div>
                            <label id="image-error" for="image"></label>
                            <strong class="help-block alert-danger">
                              {{ @$errors->first('image') }}
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
    var value = $('#type').val();
    if(value == 'paid'){

      $('#price').attr('disabled',false);
      $('#price').attr('min',1);
    }else{
      $('#price').attr('disabled',true);
      $('#price').val(0);
      $('#price').removeAttr('min');
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
  FilePond.setOptions({
       imageTransformImageFilter: (file) => new Promise(resolve => {

        // no gif mimetype, do transform
        if (!/image\/gif/.test(file.type)) return resolve(true);

        const reader = new FileReader();
        reader.onload = () => {

            var arr = new Uint8Array(reader.result),
            i, len, length = arr.length, frames = 0;

            // make sure it's a gif (GIF8)
            if (arr[0] !== 0x47 || arr[1] !== 0x49 || 
                arr[2] !== 0x46 || arr[3] !== 0x38) {
                // it's not a gif, we can safely transform it
                resolve(true);
                return;
            }

            for (i=0, len = length - 9; i < len, frames < 2; ++i) {
                if (arr[i] === 0x00 && arr[i+1] === 0x21 &&
                    arr[i+2] === 0xF9 && arr[i+3] === 0x04 &&
                    arr[i+8] === 0x00 && 
                    (arr[i+9] === 0x2C || arr[i+9] === 0x21)) {
                    frames++;
                }
            }

            // if frame count > 1, it's animated, don't transform
            if (frames > 1) {
                return resolve(false);
            }

            // do transform
            resolve(true);
        }
        reader.readAsArrayBuffer(file);

    }),
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
     
  });
  const input = document.querySelector('#image');
  const pond1 = FilePond.create(input);

 
   //Change Locale
  @if(config("app.locale") == 'ar'){
   FilePond.setOptions(labels);
  }
  @endif
});

</script>


@endsection
