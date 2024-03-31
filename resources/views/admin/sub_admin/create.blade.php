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
                            <li class="breadcrumb-item active">{{ trans('sub_admin.add_new') }}
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
                <form method="POST" id="ReasonForm" action="{{route('sub_admin.store')}}" accept-charset="UTF-8" enctype="multipart/form-data" >
                  @csrf
                  <div class="form-body">
                 
                  <div class="row">
                    <div class="col-md-6">
                     <div class="form-group">
                        <label for="name" class="content-label">{{trans('sub_admin.name')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input class="form-control @error('name') ? is-invalid : ''  @enderror" minlength="2" maxlength="255" placeholder="{{trans('sub_admin.name')}}" name="first_name"   value="{{ old('first_name') }}"  required>
                          @error('first_name')
                            <div class="invalid-feedback">
                               <strong> {{ @$errors->first('first_name') }} </strong>
                            </div>
                          @enderror
                      </div>  
                    </div>
                    <div class="col-md-6">
                     <div class="form-group">
                        <label for="email" class="content-label">{{trans('sub_admin.email')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input type="email" class="form-control @error('email') ? is-invalid : ''  @enderror" minlength="2" maxlength="255" placeholder="{{trans('sub_admin.pl_email')}}" name="email"  value="{{ old('email') }}" required>
                          @error('email')
                            <div class="invalid-feedback">
                               <strong> {{ @$errors->first('email') }} </strong>
                            </div>
                          @enderror
                      </div>  
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                     <div class="form-group">
                        <label for="mobile_number" class="content-label">{{trans('sub_admin.mobile_number')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input type="text" class="form-control @error('mobile_number') ? is-invalid : ''  @enderror" minlength="2" maxlength="10" placeholder="{{trans('sub_admin.pl_mobile_number')}}" name="mobile_number" value="{{ old('mobile_number') }}" required>
                          @error('mobile_number')
                            <div class="invalid-feedback">
                               <strong> {{ @$errors->first('mobile_number') }} </strong>
                            </div>
                          @enderror
                      </div>  
                    </div>
                    <div class="col-md-6">
                     <div class="form-group">
                        <label for="password" class="content-label">{{trans('sub_admin.password')}}<span class="text-danger custom_asterisk">*</span></label>
                        <div class="input-group" >
                        <input type="password" class="form-control @error('password') ? is-invalid : ''  @enderror" minlength="2" maxlength="255" placeholder="{{trans('sub_admin.pl_password')}}" id="password" name="password" required>
                          <div class="input-group-append">
                         <span class="input-group-text"><a href="javascript:void(0)"><i class="fa fa-eye password_icon"></i></a></span></div>
                          </div> 
                         <!--  <label class="warning">Note: Password must have atleast one uppercase,one lowercase ,one special character,one number.</label> -->
                          @error('password')
                            <div class="invalid-feedback">
                               <strong> {{ @$errors->first('password') }} </strong>
                            </div>
                          @enderror
                      </div>  
                    </div>
                  </div>


                   <div class="row">
                    <div class="col-md-12">
                     <div class="form-group">
                        <label for="role_and_permission" class="content-label">{{trans('sub_admin.roles_and_permission')}}<span class="text-danger custom_asterisk">*</span></label>
                        <select id="role" name="role" class="form-control" required>
                          <option value="">Select Role</option>
                          @foreach($roles as $role)
                          <option value="{{ $role->id }}" >{{ ucfirst($role->name) }}</option>
                          @endforeach
                        </select>
                          @error('role')
                            <div class="invalid-feedback">
                               <strong> {{ @$errors->first('role') }} </strong>
                            </div>
                          @enderror
                      </div>  
                    </div>
                  </div>
  

                         
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
  $(document).ready(function(){
        $(document).on('click','.password_icon',function(){
                if($('.password_icon').hasClass('fa-eye'))
                {
                  $('.password_icon').removeClass('fa-eye');
                  $('.password_icon').addClass('fa-eye-slash');
                  $("#password").attr('type','text');
                }
                else
                {
                  $('.password_icon').addClass('fa-eye');
                  $('.password_icon').removeClass('fa-eye-slash');
                  $("#password").attr('type','password');
                }
        })

})

</script>

@endsection
