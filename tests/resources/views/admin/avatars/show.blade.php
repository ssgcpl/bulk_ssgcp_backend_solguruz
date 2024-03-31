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
                            <li class="breadcrumb-item active">{{ trans('avatars.details') }}
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
                <form method="POST" id="categoryForm" action="#" accept-charset="UTF-8" enctype="multipart/form-data" >
                  
                  <!-- <hr style="border-top: 3px solid blue;"> -->
                    <div class="form-body">
                      <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="status" class="content-label">{{trans('avatars.type')}}<span class="text-danger custom_asterisk">*</span></label>
                              <input class="form-control" placeholder="{{trans('avatars.type')}}" name="type" type="text" value="{{trans('avatars.'.$avatars->type)}}" disabled>
                              
                            </div>  
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label for="price" class="content-label">{{trans('avatars.price')}}<span class="text-danger custom_asterisk">*</span></label>
                              <input class="form-control" minlength="2" maxlength="190" placeholder="{{trans('avatars.price')}}" name="price" type="text" value="{{$avatars->price}}" disabled>
                              
                            </div>  
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label for="price" class="content-label">{{trans('common.status')}}<span class="text-danger custom_asterisk">*</span></label>
                              <input class="form-control" minlength="2" maxlength="190" placeholder="{{trans('common.status')}}" name="status" type="text" value="{{trans('common.'.$avatars->status)}}" disabled>
                              
                            </div>  
                          </div>
                          <div class="col-md-12">
                            <div class="form-group">
                              <label for="content:" class="content-label">{{trans('avatars.name')}}<span class="text-danger custom_asterisk">*</span></label>
                            
                              <input type="text" class="form-control" placeholder="{{trans('avatars.name')}}" name="name" type="text" value="{{$avatars->name}}"disabled>
                              
                            </div>  
                          </div>
                      </div>
                    </div>
                  <div class="form-body">
                      <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                              <label for="image" class="content-label">{{trans('avatars.image')}}<span class="text-danger custom_asterisk">*</span></label>
                              <input type="file" id="image" name="image">
                             
                            </div>
                            <label id="image-error" for="image"></label>
                            <strong class="help-block alert-danger">
                              {{ @$errors->first('image') }}
                            </strong>
                          </div>
                      </div>
                  </div>
                 <div class="modal-footer">
                      <a class="btn btn-success btn-fill btn-wd" href="{{route('avatars.edit',$avatars->id)}}">
                  {{trans('common.edit')}}
                </a>
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
  
  const input = document.querySelector('#image');
  const pond1 = FilePond.create(input,{
      
      disabled:true,
      allowBrowse:false,
      allowRemove:false,
      allowDrop:false,
      allowBrowse:false,
      files: [
         
            {
                // the server file reference
                source: "{{asset($avatars->image)}}",

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
