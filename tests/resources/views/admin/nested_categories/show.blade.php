@extends('layouts.admin.app')

@section('vendor_css')
@endsection 

@section('css')
@endsection

@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">{{ trans('nested_categories.show') }} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('nested_categories.index')}}">{{ trans('nested_categories.plural') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ trans('nested_categories.show') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
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
                <h4 class="card-title">{{ trans('nested_categories.details') }}</h4>
                <a href="{{route('nested_categories.index')}}" class="btn btn-success">
                  <i class="fa fa-arrow-left"></i>
                  {{ trans('common.back') }}
                </a>
              </div>
              <div class="card-content">
                <div class="card-body">
                  <div class="form-body">
                    <div class="row">
                      <div class="col-md-12">  
                        <div class="form-group">
                          <label for="name" class="content-label">{{trans('nested_categories.name')}}</label>
                           <p class="details">{{$nested_category->category_name}}</p> 
                        </div>
                        <div class="form-group">
                          <label for="name" class="content-label">{{trans('nested_categories.level')}}</label>
                           <p class="details">{{$nested_category->level}}</p> 
                        </div>
                        <div class="form-group">
                          <label for="name" class="content-label">{{trans('nested_categories.parent')}}</label>
                           <p class="details">{{isset($nested_category->parent->category_name) ? $nested_category->parent->category_name : '-'}}</p> 
                        </div>
                      </div>
                    </div>


                    
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="status" class="content-label">{{trans('common.status')}}</label>
                          <p class="details">{{$nested_category->status}}</p>                         
                        </div>
                      </div>
                    </div>
                    </div>

                    <div class="modal-footer">
                      <a class="btn btn-info btn-fill btn-wd" href="{{route('nested_categories.edit',$nested_category->id)}}">
                        {{trans('common.edit')}}
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- // Basic Floating Label Form section end -->
    </div>
  </div>
</div>
<!-- END: Content-->
@endsection 

@section('page_js')

@endsection

@section('js')

@endsection