@extends('layouts.admin.app')
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
                    <h2 class="content-header-title float-left mb-0">{{trans('contact_us.contact_details')}} </h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('contact_us.index')}}">{{ trans('contact_us.contacts') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{trans('contact_us.contact_details')}}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form">
      <div class="row match-height">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">
              {{ trans('contact_us.contact_details')}}
              </h4>
              <a href="{{route('contact_us.index')}}" class="btn btn-success">
                  <i class="fa fa-arrow-left"></i>
                  {{ trans('common.back') }}
              </a>
            </div>
            <div class="card-content">
              <div class="card-body">    
                <form id="stateForm" method="POST" action="" accept-charset="UTF-8" >
  
                    <div class="form-body">
                      <div class="row">
                          
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="first_name" class="content-label">{{trans('contact_us.contact_name')}}</label>
                            <input class="form-control"  name="first_name" type="text" value="{{$query->name}}" disabled>
                           
                          </div>  
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="first_name" class="content-label">{{trans('contact_us.contact_date')}}</label>
                            <input class="form-control"  name="created_at" type="text" value="{{$query->created_at->format('d/m/Y H:i:s')}}" disabled>
                           
                          </div>  
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="title" class="content-label">{{trans('contact_us.status')}}</label>
                           
                            <input class="form-control"  name="status" type="text" value="{{trans('contact_us.'.$query->status)}}" disabled>
                            
                           </select>
                           
                          </div>  
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="first_name" class="content-label">{{trans('contact_us.contact_email')}}</label>
                            <input class="form-control"  name="email" type="text" value="{{$query->email}}" disabled>
                           
                          </div>  
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="first_name" class="content-label">{{trans('contact_us.contact_number')}}</label>
                            <input class="form-control"  name="mobile_number" type="text" value="{{$query->mobile_number}}" disabled>
                           
                          </div>  
                        </div>
                        @if($query->reason_id != null)
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="first_name" class="content-label">{{trans('contact_us.contact_reason')}}</label>
                            <input class="form-control"  name="first_name" type="text" value="{{$query->reason ? $query->reason->reason_title : ''}}" disabled>
                          
                          </div>  
                        </div>
                        @endif

                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="first_name" class="content-label">{{trans('contact_us.contact_message')}}</label>
                            <textarea class="form-control"  name="message" type="text" value="{{$query->message}}" disabled>{{$query->message}}</textarea> 
                          </div>  
                        </div>
                        @if($query->comment != null)
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="first_name" class="content-label">{{trans('contact_us.comment')}}</label>
                            <textarea class="form-control"  name="comment" type="text" value="{{$query->comment}}" disabled>{{$query->comment}}</textarea> 
                          </div>  
                        </div>
                        @endif
                          
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
  $(document).on('change','.user_status',function(){
        var status = $(this).val();
        var id = $(this).attr('id');
        var delay = 500;
        var element = $(this);
        $.ajax({
            type:'post',
            url: "{{route('contact_status')}}",
            data: {
                    "status": status, 
                    "id" : id,  
                    "_token": "{{ csrf_token() }}"
                  },
            beforeSend: function () {
                element.next('.loading').css('visibility', 'visible');
            },
            success: function (data) {
              setTimeout(function() {
                    element.next('.loading').css('visibility', 'hidden');
                }, delay);
              toastr.success(data.success);
              loadData();
            },
            error: function () {
              toastr.error(data.error);
            }
        })
    })
</script>
@endsection
