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
                    <h2 class="content-header-title float-left mb-0">{{trans('reasons.heading')}} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('reasons.index')}}">{{ trans('reasons.plural') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{trans('reasons.update')}}
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
              {{ trans('reasons.details')}}
              </h4>
              @can('reason-list')
                <a href="{{route('reasons.index')}}" class="btn btn-success">
                    <i class="fa fa-arrow-left"></i>
                    {{ trans('common.back') }}
                </a>
              @endcan
            </div>
            <div class="card-content">
              <div class="card-body">    
                <form id="stateForm" method="POST" action="{{route('reasons.update',$reason->id)}}" accept-charset="UTF-8" enctype="multipart/form-data">
                <input name="_method" type="hidden" value="PUT">
                  @csrf
              
                 <div class="form-body">
                    <div class="row">
                      <div class="col-md-12">  
                        <div class="form-group">
                          <label for="name" class="content-label">{{trans('reasons.name')}}<span class="text-danger custom_asterisk">*</span></label>
                           <input class="form-control @error('name') ? is-invalid : 'is-valid'  @enderror" minlength="2" maxlength="255" placeholder="{{trans('reasons.pl_name')}}" name="name" type="text" value="{{$reason->name }}" required>
                            @error('name')
                              <div class="invalid-feedback">
                                 <strong> {{ @$errors->first('name') }} </strong>
                              </div>
                            @enderror
                      </div>  
                        </div>
                      </div>
                    </div>
                  <!--   <div class="row">
                      <div class="col-md-4">
                        <div class="form-group @error('type') ? has-error : ''  @enderror">
                            <label for="type" class="content-label">{{trans('reasons.type')}}<span class="text-danger custom_asterisk">*</span></label>
                            <select  class="form-control" minlength="2" maxlength="255" id="type" name="type">
                              <option value="{{App\Models\Reason::CUSTOMER_TICKET_TYPE}}" @if($reason->type == App\Models\Reason::CUSTOMER_TICKET_TYPE ) selected="selected" @endif>{{trans('reasons.customer_ticket')}}</option>
                              <option value="{{App\Models\Reason::CUSTOMER_CANCEL_ORDER_TYPE}}" @if($reason->type == App\Models\Reason::CUSTOMER_CANCEL_ORDER_TYPE) selected="selected" @endif>{{trans('reasons.customer_order_cancel')}}</option>
                            </select>
                            @error('type')
                              <div class="invalid-feedback">
                                 <strong> {{ @$errors->first('type') }} </strong>
                              </div>
                            @enderror
                        </div>
                      </div>
                    </div> -->
                  </div>
                  <div class="modal-footer">
                    <button id="edit_btn" type="submit" class="btn btn-info btn-fill btn-wd">{{trans('common.submit')}}</button>
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
@endsection
