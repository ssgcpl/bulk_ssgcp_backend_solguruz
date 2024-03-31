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
                <form method="POST" id="StateForm" action="{{route('states.update',$state->id)}}" accept-charset="UTF-8" enctype="multipart/form-data" >
                  <input name="_method" type="hidden" value="PUT">
                
                  @csrf
                  <div class="form-body">
                  <div class="row">
                    <div class="col-md-12">
                     <div class="form-group">
                       <label for="state name" class="content-label">{{trans('states.name')}}<span class="text-danger custom_asterisk">*</span></label>
                       <p class="details">{{ $state->name }}</p>
                      </div>  
                    </div>
                  </div>
                    <div class="row">
                    <div class="col-md-12">
                     <div class="form-group">
                         <label for="name" class="content-label">{{trans('cities.name')}}<span class="text-danger custom_asterisk">*</span></label>
                       <p class="details">{{ $country->name }}</p>
                     </div>  
                    </div>
                  </div>
              
                  </div>
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


