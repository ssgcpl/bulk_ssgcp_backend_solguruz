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
                    <h2 class="content-header-title float-left mb-0">{{trans('states.admin_heading')}} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('states.index')}}">{{ trans('states.plural') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('states.add_new') }}
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

     <section id="multiple-column-form">
      <div class="row match-height">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">
              {{ trans('states.details')}}
              </h4>
              @can('states-list')
                <a href="{{route('states.index')}}" class="btn btn-success">
                    <i class="fa fa-arrow-left"></i>
                    {{ trans('common.back') }}
                </a>
              @endcan
            </div>
            <div class="card-content">
              <div class="card-body">    
                <form method="POST" id="StateForm" action="{{route('states.store')}}" accept-charset="UTF-8" enctype="multipart/form-data" >
                  @csrf
                  <div class="form-body">
                  <div class="row">
                    <div class="col-md-12">
                     <div class="form-group">
                        <label for="state name" class="content-label">{{trans('states.name')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input class="form-control @error('state_name') ? is-invalid : ''  @enderror" minlength="2" maxlength="255" placeholder="{{trans('states.name')}}" name="state_name" value="{{old('state_name')}}" required>
                          @error('state_name')
                            <div class="invalid-feedback">
                               <strong> {{ @$errors->first('state_name') }} </strong>
                            </div>
                          @enderror
                      </div>  
                    </div>
                  </div>
                    <div class="row">
                    <div class="col-md-12">
                     <div class="form-group">
                         <label for="name"> {{ trans('states.country_name')}}<span class="text-danger custom_asterisk">*</span></label>
                      <select name="country_name" class="form-control @error('country_name') ? is-invalid : ''  @enderror" >
                        <option value="">{{trans('states.select_country')}}</option>
                        @foreach($countries as $country)
                          <option value="{{$country->id}}" @if(old('country_name') == $country->id) selected @endif >{{$country->name}}</option>
                        @endforeach
                      </select>
                      <strong class="invalid-feedback">
                        {{ @$errors->first('country_name') }}
                      </strong>
                   
                      </div>  
                    </div>
                  </div>
                
                  <div class="row">
                      <div class="col-md-3">
                        <div class="form-group @error('flag') ? has-error : ''  @enderror">
                        </div>
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
</div>

</div>
@endsection
@section('js')
<script>
$(document).ready(function () {
  
});
</script>
@endsection


