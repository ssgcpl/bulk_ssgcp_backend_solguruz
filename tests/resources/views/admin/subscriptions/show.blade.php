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
                            <li class="breadcrumb-item"><a href="{{route('subscriptions.index')}}">{{ trans('subscriptions.heading') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('subscriptions.details') }}
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
              {{trans('subscriptions.heading')}}
              </h4>
              <a href="{{route('subscriptions.index')}}" class="btn btn-success">
                  <i class="fa fa-arrow-left"></i>
                  {{ trans('common.back') }}
              </a>
            </div>
            <div class="card-content">
              <div class="card-body">    
                <form id="stateForm" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
                
                  <div class="form-body">
                  <div class="row">
                    <div class="col-md-12">
                     <div class="form-group">
                        <label for="name" class="content-label">{{trans('subscriptions.name')}}</label>
                        <input class="form-control" minlength="2" maxlength="190" placeholder="{{trans('subscriptions.pl_name')}}" name="name" value="{{$subscriptions->name}}" disabled type="text">
                        @if ($errors->has('name')) 
                          <strong class="help-block alert-danger">{{ $errors->first('name') }}</strong>
                        @endif
                      </div>  
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                     <div class="form-group">
                        <label for="description" class="content-label">{{trans('subscriptions.description')}}</label>
                        <input class="form-control" placeholder="{{trans('subscriptions.pl_description')}}" name="description" value="{{$subscriptions->description}}" disabled maxlength="190" type="text">
                        
                      </div>  
                    </div>
                  </div>
  
                  <div class="row">
                    
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="price" class="content-label">{{trans('subscriptions.no_of_free_avatars')}}</label>
                        <input class="form-control" placeholder="{{trans('subscriptions.no_of_free_avatars')}}" name="no_of_free_avatars" value="{{ $subscriptions->no_of_free_avatars}}" disabled type="number" maxlength="4" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" id="no_of_free_avatars" max="9999">
                          
                      </div>  
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="published_date" class="content-label">{{trans('subscriptions.price_per')}}</label>
                        <input class="form-control" type="text" name="price_per" value="{{trans('subscriptions.'.$subscriptions->price_per)}}" disabled> 
                      </div>  
                    </div>
                    <div class="col-md-4">
                     <div class="form-group">
                        <label for="price" class="content-label">{{trans('subscriptions.validity')}}</label>
                        <input class="form-control" placeholder="{{trans('subscriptions.validity')}}" name="validity" value="{{$subscriptions->validity}}" disabled type="number" maxlength="1" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" id="validity" max="6" min="1">
                          
                      </div>  
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                        <div class="form-group @error('color_code') ? has-error : ''  @enderror">
                          <label for="colour" class="content-label">{{ trans('subscriptions.color_code') }}</label>
                          <input class="form-control" minlength="2" maxlength="7" placeholder="{{trans('subscriptions.color_code')}}" name="color_code" type="color" value="{{$subscriptions->color_code }}" disabled>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="published_date" class="content-label">{{trans('subscriptions.type')}}</label>
                        <input class="form-control" type="text" name="type" value="{{trans('subscriptions.'.$subscriptions->type)}}" disabled>
                      </div>  
                    </div>
                    <div class="col-md-4">
                     <div class="form-group">
                        <label for="price" class="content-label">{{trans('subscriptions.price')}}</label>
                        <input class="form-control" placeholder="{{trans('subscriptions.price')}}" name="price" value="{{$subscriptions->price}}" disabled type="number" maxlength="8" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" id="price">
                         
                      </div>  
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="image" class="content-label">{{trans('subscriptions.image')}}</label>
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
                          <input type="checkbox" name="permissions[]" checked disabled>
                        {{$benefit->benefit->title}}</label>
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
                  <a class="btn btn-success btn-fill btn-wd" href="{{route('subscriptions.edit',$subscriptions->id)}}">
                  {{trans('common.edit')}}
                  </a>
                </div>
              </form>
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
