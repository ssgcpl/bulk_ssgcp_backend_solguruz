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
                    <h2 class="content-header-title float-left mb-0">{{trans('sub_admin.admin_heading')}} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('sub_admin.index')}}">{{ trans('sub_admin.plural') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('sub_admin.edit') }}
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
              {{ trans('sub_admin.details')}}
              </h4>
              @can('sub-admin-list')
                <a href="{{route('sub_admin.index')}}" class="btn btn-success">
                    <i class="fa fa-arrow-left"></i>
                    {{ trans('common.back') }}
                </a>
              @endcan
            </div>
            <div class="card-content">
              <div class="card-body">    
             <form method="POST" id="ReasonForm" action="{{route('sub_admin.update',$user->id)}}" accept-charset="UTF-8" enctype="multipart/form-data" >
                   <input name="_method" type="hidden" value="PUT">
                  @csrf
                  <div class="form-body">
                 
                  <div class="row">
                    <div class="col-md-6">
                     <div class="form-group">
                        <label for="name" class="content-label">{{trans('sub_admin.name')}}<span class="text-danger custom_asterisk"></span></label>
                       <p class="details"> {{ $user->first_name }} </p>
                       </div>  
                    </div>

                    <div class="col-md-6">
                     <div class="form-group">
                        <label for="email" class="content-label">{{trans('sub_admin.email')}}<span class="text-danger custom_asterisk">*</span></label>
                        <p class="details"> {{ $user->email }} </p> </div>  
                    </div>
                  
                  </div>

                
                  <div class="row">
                    <div class="col-md-6">
                     <div class="form-group">
                        <label for="mobile_number" class="content-label">{{trans('sub_admin.mobile_number')}}<span class="text-danger custom_asterisk">*</span></label>
                        <p class="details"> {{ $user->mobile_number }} </p>
                      </div>  
                    </div>
                
                    <div class="col-md-6">
                     <div class="form-group">
                        <label for="role_and_permission" class="content-label">{{trans('sub_admin.roles_and_permission')}}<span class="text-danger custom_asterisk">*</span></label>
                        <p class="details"> {{ ucfirst($user->role) }} </p>
                      </div>  
                    </div>
                  </div>
                 <div class="row">
                     <div class="col-md-6">
                     <div class="form-group">
                        <label for="mobile_number" class="content-label">{{trans('common.status')}}<span class="text-danger custom_asterisk">*</span></label>
                        <p class="details"> {{ ucfirst($user->status) }} </p>
                      </div>  
                    </div>
                
                  </div>  

                </div>
                  </div>
                </div>
                  <div class="modal-footer">
                    <a class="btn btn-success" href="{{route('sub_admin.edit',$user->id)}}">
                  {{trans('common.edit')}}
                </a>  </div>
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
  
</script>

@endsection
