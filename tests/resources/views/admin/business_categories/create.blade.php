@extends('layouts.admin.app')
@section('css')


<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.4.0/cropper.css">
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
                    <h2 class="content-header-title float-left mb-0">{{trans('business_categories.heading')}} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('business_categories.index')}}">{{ trans('business_categories.plural') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('business_categories.add_new') }}
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
              {{ trans('business_categories.details')}}
              </h4>
              <a href="{{route('business_categories.index')}}" class="btn btn-success">
                  <i class="fa fa-arrow-left"></i>
                  {{ trans('common.back') }}
              </a>
            </div>
            <div class="card-content">
              <div class="card-body">
                <form method="POST" id="BusinessCategoryForm" action="{{route('business_categories.store')}}" accept-charset="UTF-8" enctype="multipart/form-data" >
                  @csrf
                    <div class="form-body">
                        <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                            <label for="business_category_name" class="content-label">{{trans('business_categories.name')}}<span class="text-danger custom_asterisk">*</span></label>
                            <input class="form-control @error('category_name') ? is-invalid : '' @enderror" minlength="2" maxlength="255" placeholder="{{trans('business_categories.pl_name')}}" value="{{old('category_name')}}" name="category_name" required >
                            @if ($errors->has('category_name'))
                                <strong class="invalid-feedback">{{ $errors->first('category_name') }}</strong>
                            @endif
                            </div>
                        </div>
                        </div>

                        <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                            <label for="business_category_layout" class="content-label">{{trans('business_categories.layout_types')}}<span class="text-danger custom_asterisk">*</span></label>
                                <select class="form-control  @error('layout') ? is-invalid : '' @enderror" id="layout" name="layout" required>
                                <option>Select Type</option>
                                @foreach($layouts as $layout)
                                <option value="{{ $layout->name }}"  {{ (old('layout') == $layout->name) ? 'selected':'' }}>{{ trans('business_categories.'.$layout->name) }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('type'))
                                <strong class="invalid-feedback">{{ $errors->first('type') }}</strong>
                            @endif
                            </div>
                        </div>
                        </div>
                        <div class="row" id="URL_div" style="display: {{  (old('layout') == 'url') ? 'block':'none'; }}">
                        <div class="col-md-12">
                            <div class="form-group">
                            <label for="business_category_url" class="content-label">{{trans('business_categories.url')}}<span class="text-danger custom_asterisk">*</span></label>
                            <input class="form-control @error('url') ? is-invalid : '' @enderror" minlength="2" maxlength="255" placeholder="{{trans('business_categories.pl_url')}}" name="url">
                            @if ($errors->has('url'))
                                <strong class="invalid-feedback">{{ $errors->first('url') }}</strong>
                            @endif
                            </div>
                        </div>
                        </div>

                      <!--   <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="business_category_description" class="content-label">{{trans('business_categories.description')}}<span class="text-danger custom_asterisk">*</span></label>
                                    <textarea  class="form-control  @error('description') ? is-invalid : '' @enderror" id="description" name="description"  >{{old('description')}}</textarea>
                                   @if($errors->has('description'))
                                    <strong class="invalid-feedback">{{ $errors->first('description') }}</strong>
                                    @endif
                                </div>
                                </div>
                            </div> -->
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                                <label for="category_image-error" for="image" class="content-label">{{trans('business_categories.image')}}<span class="text-danger custom_asterisk">*</span></label>
                                <input type="file" class="@error('category_image') ? is-invalid : '' @enderror filepond_img" name="category_image" required>
                                <div class="img-preview"></div>
                                <div id="galleryImages"></div>
                                @if ($errors->has('category_image'))
                                    <strong class="invalid-feedback">{{ $errors->first('category_image') }}</strong>
                                    @endif
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                                <label class="content-label">Display Rank<span class="text-danger custom_asterisk">*</span></label>
                                <input type="text" required class="form-control numberonly @error('display_order') ? is-invalid : '' @enderror" name="display_order" value="{{old('display_order')}}">
                                @if($errors->has('display_order'))
                                  <strong class="invalid-feedback">{{ $errors->first('display_order') }}</strong>
                                @endif
                            </div>
                          </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input name="is_live" type="checkbox" @if(old('is_live') == 1) checked @endif id='is_live'>&nbsp;&nbsp; {{ trans('business_categories.publish') }}
                            </div>
                        </div>
                  </div>
                  <div class="modal-footer">
                    <button id="edit_btn" type="submit" class="btn btn-success">{{trans('common.submit')}}</button>
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
  if (document.location.search.match(/type=embed/gi)) {
    window.parent.postMessage("resize", "*");
  }

$(document).ready(function() {

 // $('#URL_div').css('display','none');
 $('#publish').on('change', function(){
   this.value = this.checked ? 1 : 0;
}).change();

  $(document).on('change','#layout',function(){
      var layout = $('#layout').val();
      if(layout == 'url')
      {
          $('#URL_div').css('display', 'block');
      }
      else
      {
       $('#URL_div').css('display', 'none');
      }

  });
});
</script>
@include('layouts.admin.elements.filepond_js')
@endsection
