@extends('layouts.admin.app')
@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">{{trans('cms.update')}} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('cms.index')}}">{{ trans('cms.cms_index') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{trans('cms.update')}}
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
              {{ $cms->page_name}}
              </h4>
              <a href="{{route('cms.index')}}" class="btn btn-success">
                  <i class="fa fa-arrow-left"></i>
                  {{ trans('common.back') }}
              </a>
            </div>
            <div class="card-content">
              <div class="card-body">    
                <form id="stateForm" method="POST" action="{{route('cms.update',$cms->id)}}" accept-charset="UTF-8" enctype="multipart/form-data">
                  <input name="_method" type="hidden" value="PUT">
                  @csrf
                  <div class="tab-content" style="margin-top: 10px;">
                    <div class="form-group">
                      <label for="content" class="content-label">{{trans('cms.content')}}<span class="text-danger custom_asterisk">*</span></label>
                      <textarea class="form-control" id="summary-ckeditor"placeholder="{{trans('cms.content')}}" name="content" type="text" value="" required>
                       {{ old('content') ? old('content') : $cms->content}}
                      </textarea>
                      @error('content')
                        <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="form-group">
                      <label for="page_name" class="content-label">{{trans('cms.page_name')}}<span class="text-danger custom_asterisk">*</span></label>
                      <input class="form-control" minlength="2" maxlength="190" placeholder="{{trans('cms.page_name')}}" name="page_name" type="text" value="{{ old('page_name') ? old('page_name') : $cms->page_name}}" required>
                      @error('page_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
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
<script src="//cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replaceAll();
    CKEDITOR.config.allowedContent = true;
</script>

@endsection


