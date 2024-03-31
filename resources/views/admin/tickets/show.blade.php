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
                    <h2 class="content-header-title float-left mb-0">{{ trans('tickets.show') }} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('tickets.index')}}">{{ trans('tickets.plural') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ trans('tickets.show') }}
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
                <h4 class="card-title">{{ trans('tickets.details') }}</h4>
                <a href="{{route('tickets.index')}}" class="btn btn-success">
                  <i class="fa fa-arrow-left"></i>
                  {{ trans('common.back') }}
                </a>
              </div>
              <div class="card-content">
                <div class="card-body">
                  <div class="form-body">
                    <div class="row">
                      <div class="col-md-6">  
                        <div class="form-group">
                          <label for="name" class="content-label">{{trans('tickets.name')}}</label>
                           <p class="details">{{$ticket->full_name}}</p> 
                        </div>
                      </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label for="status" class="content-label">{{trans('tickets.email')}}</label>
                          <p class="details">{{$ticket->email}}</p>                         
                        </div>
                      </div>
                    </div>
                        <div class="row">
                      <div class="col-md-6">  
                        <div class="form-group">
                          <label for="name" class="content-label">{{trans('tickets.contact_number')}}</label>
                           <p class="details">{{$ticket->mobile_number}}</p> 
                        </div>
                      </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label for="status" class="content-label">{{trans('tickets.user_type')}}</label>
                          <p class="details">{{ucfirst($ticket->user_type)}}</p>                         
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="status" class="content-label">{{trans('tickets.message')}}</label>
                          <p class="details">{{$ticket->message}}</p>                         
                        </div>
                      </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label for="status" class="content-label">{{trans('tickets.comment')}}</label>
                          <p class="details">{{$ticket->comment}}</p>                         
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="status" class="content-label">{{trans('tickets.acknowledgement')}}</label>
                          <p class="details">{{$ticket->acknowledged_comment}}</p>                         
                        </div>
                      </div>
                    </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="status" class="content-label">{{trans('tickets.created_date')}}</label>
                          <p class="details">{{date('d-m-Y h:i A',strtotime($ticket->created_at))}}</p>                         
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="status" class="content-label">{{trans('tickets.updated_date')}}</label>
                          <p class="details">
                             @if($ticket->updated_at)
                            {{date('d-m-Y h:i A',strtotime($ticket->updated_at))}}
                                @endif
                          </p>                        
                        </div>
                      </div>
                    </div>
                     <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="status" class="content-label">{{trans('common.status')}}</label>
                          <p class="details">{{ucfirst($ticket->status)}}</p>                         
                        </div>
                      </div>
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