@extends('layouts.admin.app')
@section('css')
<meta name="viewport" content="width=device-width, initial-scale=1">
  
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.4.0/cropper.css">
@endsection
@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-header row">
      <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
          <div class="col-12">
            <h2 class="content-header-title float-left mb-0">
              {{trans('authors.heading')}} 
            </h2>
            <div class="breadcrumb-wrapper col-12">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a href="{{route('home')}}">{{ trans('common.home') }}</a>
                </li>
                <li class="breadcrumb-item">
                  <a href="{{route('authors.index')}}">{{ trans('authors.plural') }}</a>
                </li>
                <li class="breadcrumb-item active">
                  {{ trans('authors.add_new') }}
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
              {{ trans('authors.details')}}
              </h4>
              @can('authors-list')
                <a href="{{route('authors.index')}}" class="btn btn-success">
                    <i class="fa fa-arrow-left"></i>
                    {{ trans('common.back') }}
                </a>
              @endcan
            </div>
            <div class="card-content">
              <form method="POST" id="ReasonForm" action="{{route('authors.store')}}" accept-charset="UTF-8" enctype="multipart/form-data" >
                <div class="card-body">    
                  @csrf
                  <div class="form-body">
                    <div class="row">
                      <div class="col-md-12">
                       <div class="form-group">
                          <label for="name" class="content-label">{{trans('authors.name')}}<span class="text-danger custom_asterisk">*</span></label>
                          <input class="form-control" minlength="2" maxlength="50" placeholder="{{trans('authors.name')}}" name="author_name" value="{{old('author_name')}}" required>
                            @error('author_name')
                              <div class="alert alert-danger">
                                <strong> {{ @$errors->first('author_name') }} </strong>
                              </div>
                            @enderror
                        </div>  
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
@endsection