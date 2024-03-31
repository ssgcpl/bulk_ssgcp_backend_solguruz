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
                    <h2 class="content-header-title float-left mb-0">{{trans('web_content.heading')}} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('web_content.index')}}">{{ trans('web_content.heading') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('web_content.details') }}
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
              {{trans('web_content.heading')}}
              </h4>
              <a href="{{route('web_content.index')}}" class="btn btn-success">
                  <i class="fa fa-arrow-left"></i>
                  {{ trans('common.back') }}
              </a>
            </div>
            <div class="card-content">
              <div class="card-body">    
                <form method="POST" id="categoryForm" action="#" accept-charset="UTF-8" enctype="multipart/form-data" >
                  
                  <div class="form-body">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group @error('title') ? has-error : ''  @enderror">
                          <label for="title" class="content-label">{{trans('web_content.title')}}</label>
                          <input class="form-control" minlength="2" maxlength="190" placeholder="{{trans('web_content.title')}}" name="title" type="text" value="{{$content->title}}" disabled>
                          
                        </div>  
                      </div>
                      <div class="col-md-12">
                        <div class="form-group @error('content') ? has-error : ''  @enderror">
                          <label for="content:" class="content-label">{{trans('web_content.content')}}</label>
                        
                          <textarea class="form-control" placeholder="{{trans('web_content.content')}}" name="content" type="text" value="{{$content->content}}" rows="4" disabled maxlength="10000">{{$content->content}}</textarea>
                          
                        </div>  
                      </div>
                      <div class="col-md-6">
                        <div class="form-group @error('author_name') ? has-error : ''  @enderror">
                          <label for="author_name" class="content-label">{{trans('web_content.author_name')}}</label>
                          <input class="form-control" minlength="2" maxlength="50" placeholder="{{trans('web_content.author_name')}}" name="author_name" type="text" value="{{$content->author_name}}" disabled>
                          
                        </div>  
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="type" class="content-label">{{trans('web_content.type')}}</label>
                          <input class="form-control" minlength="2" maxlength="190" placeholder="{{trans('web_content.type')}}" name="title" type="text" value="{{trans('web_content.'.$content->type)}}" disabled>
                          
                        </div>  
                      </div>
                      <div class="col-md-12">
                        <div class="form-group @error('book_location') ? has-error : ''  @enderror">
                          <label for="book_location" class="content-label">{{trans('web_content.book_location')}}<span class="text-danger custom_asterisk">*</span></label>
                          <input class="form-control" minlength="2" maxlength="100" placeholder="{{trans('web_content.book_location')}}" name="book_location" type="text" value="{{old('book_location') ? old('book_location') : $content->book_location }}" disabled>
                          @if ($errors->has('book_location')) 
                            <strong class="help-block alert-danger">{{ $errors->first('book_location') }}</strong>
                          @endif
                        </div>  
                      </div>
                      <div class="col-md-6">
                        <div class="form-group @error('show_read_now_button') ? has-error : ''  @enderror">
                          <label for="show_read_now_button" class="content-label">{{trans('web_content.show_read_now_button')}}<span class="text-danger custom_asterisk">*</span></label>
                          <input type="checkbox" name="show_read_now_button" id="show_read_now_button" value="1" disabled @if($content->show_read_now_button == '1')checked @endif> 
                          @if ($errors->has('show_read_now_button')) 
                            <strong class="help-block alert-danger">{{ $errors->first('show_read_now_button') }}</strong>
                          @endif
                        </div>  
                      </div>
                      <div class="col-md-6" id="has_dynamic_link">
                        <div class="form-group @error('has_dynamic_link') ? has-error : ''  @enderror">
                          <label for="has_dynamic_link" class="content-label">{{trans('web_content.has_dynamic_link')}}<span class="text-danger custom_asterisk">*</span></label>
                          <input type="checkbox" name="has_dynamic_link" id="link" value="1" disabled @if($content->has_dynamic_link == '1')checked @endif> 
                          @if ($errors->has('has_dynamic_link')) 
                            <strong class="help-block alert-danger">{{ $errors->first('has_dynamic_link') }}</strong>
                          @endif
                        </div>  
                      </div>
                      <div class="col-md-12" id="dynamic_link" style="display: none"> 
                        <div class="form-group @error('dynamic_link') ? has-error : ''  @enderror">
                          <label for="dynamic_link" class="content-label">{{trans('web_content.dynamic_link')}}<span class="text-danger custom_asterisk">*</span></label>
                          <input class="form-control" minlength="2" maxlength="190" placeholder="{{trans('web_content.dynamic_link')}}" name="dynamic_link" type="url" value="{{old('dynamic_link') ? old('dynamic_link') : $content->dynamic_link }}" id="url" disabled>
                          @if ($errors->has('dynamic_link')) 
                            <strong class="help-block alert-danger">{{ $errors->first('dynamic_link') }}</strong>
                          @endif
                        </div>  
                      </div>
                    </div>
                  </div>
                  <div class="form-body">
                      <div class="row">
                          <div class="col-md-12">
                            <div class="form-group @error('thumbnail') ? has-error : ''  @enderror">
                              <label for="thumbnail" class="content-label">{{trans('web_content.thumbnail')}}</label>
                              <input type="file" id="thumbnail" name="thumbnail" disabled >
                             
                            </div>
                            <label id="thumbnail-error" for="thumbnail"></label>
                            
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-12">
                            <div class="form-group @error('banner_image') ? has-error : ''  @enderror">
                              <label for="banner_image" class="content-label">{{trans('web_content.banner_image')}}</label>
                              <input type="file" id="banner_image" name="banner_image" disabled >
                             
                            </div>
                            <label id="banner_image-error" for="banner_image"></label>
                           
                          </div>
                      </div>
                  </div>
              </div>
                   <div class="modal-footer">
                      <a class="btn btn-success btn-fill btn-wd" href="{{route('web_content.edit',$content->id)}}">
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
<script type="text/javascript">
  $(window).on('load',function(){
    if($('#show_read_now_button').is(':checked') == true){
      $('#has_dynamic_link').show();

      if($('#link').is(':checked') == true){
        $('#dynamic_link').show();
        $('#url').attr('required',true);
      }
    }else{
      $('#has_dynamic_link').hide();
      $('#dynamic_link').hide();
      $('#url').attr('required',false);
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
  
  const input = document.querySelector('#thumbnail');
  const pond1 = FilePond.create(input,{
      
      disabled:true,
      allowBrowse:false,
      allowRemove:false,
      allowDrop:false,
      allowBrowse:false,
      files: [
         
            {
                // the server file reference
                source: "{{asset($content->thumbnail)}}",

                // set type to local to indicate an already uploaded file
                options: {
                    type: 'remote',
                    
                
                },
            },
            
          ],
      
     
  });

  
  const input1 = document.querySelector('#banner_image');
  const pond2 = FilePond.create(input1,{
      
      disabled:true,
      allowBrowse:false,
      allowRemove:false,
      allowDrop:false,
      allowBrowse:false,
      files: [
         
            {
                // the server file reference
                source: "{{asset($content->banner_image)}}",

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
