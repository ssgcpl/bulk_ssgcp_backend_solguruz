@extends('layouts.admin.app')
@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">{{trans('customers.show_retailers_heading')}} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{trans('customers.show_retailers')}}
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
              {{ trans('customers.show_retailers_details')}}
              </h4>
              <a href="{{route('customers.edit',$customer_id)}}" class="btn btn-success">
                  <i class="fa fa-arrow-left"></i>
                  {{ trans('common.back') }}
              </a>
            </div>
            <div class="card-content">
              <div class="card-body">
                <form id="stateForm" method="POST" accept-charset="UTF-8">
                
                  <div class="modal-body">
                
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group ">
                          <label id="image-error" for="countries" class ="content-label">
                          {{trans('customers.name')}}<span class="text-danger custom_asterisk">*</span>
                          </label>
                        <p class="details">{{ $retailers->first_name }}</p>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group filter">
                          <label for="states" class="content-label">{{trans('customers.company_name')}}<span class="text-danger custom_asterisk">*</span></label>
                          <p class="details">{{ $retailers->company_name }}</p>
                        </div>
                      </div>
                    </div>

                        <div class="tab-content">
                         
                              <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="name" class="content-label">{{trans('customers.email')}}<span class="text-danger custom_asterisk">*</span></label>
                                    <p class="details">{{ $retailers->email }}</p>
                                  </div>  
                                </div>
                                 <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="name" class="content-label">{{trans('customers.mobile_number')}}<span class="text-danger custom_asterisk">*</span></label>
                                    <p class="details">{{ $retailers->mobile_number }}</p>
                                  </div>  
                                </div>
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
@endsection
@section('js')
<script type="text/javascript">

</script>
@endsection