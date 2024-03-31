@extends('layouts.admin.app')
@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">{{trans('permissions.add_new')}}</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('permissions.index')}}">{{trans('permissions.plural')}}</a></li>
                            <li class="breadcrumb-item active">{{trans('permissions.add_new')}}
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
              {{ trans('permissions.details') }}
              </h4>
              @can('permission-list')
              <a href="{{route('permissions.index')}}" class="btn btn-success">
                  <i class="fa fa-arrow-left"></i>
                  {{ trans('common.back') }}
              </a>
              @endcan
            </div>
            <div class="card-content">
              <div class="card-body">    
                {!! Form::open(array('route' => 'permissions.store','method'=>'POST')) !!}
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="usr">{{trans('permissions.name')}}</label>
                                            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="usr">{{trans('permissions.select_role')}}</label>
                                            {!! Form::select('role_id[]', $roles, @$selectedRole, ['class' => 'form-control m-bot15','multiple']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button id="btn_add_tml" type="submit" class="btn btn-danger btn-fill btn-wd">{{ trans('common.submit') }}</button>
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