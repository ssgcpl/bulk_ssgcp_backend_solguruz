@extends('layouts.admin.app')
@section('vendor_css')
<link rel="stylesheet" type="text/css" href="{{asset('admin_assets/app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
<style type="text/css">
  td form{
    display: inline;
  }
  .dt-buttons{margin-right: 10px}
  .select2-selection {padding: 0px 5px !important}
  .update_stock_popup {cursor: pointer; color: blue}
</style>
@endsection
@section('css')
@endsection

@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-overlay"></div>
  <div class="header-navbar-shadow"></div>
  <div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">{{ trans('wish_suggestions.heading') }}</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ trans('wish_suggestions.show') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
      <!-- Zero configuration table -->
      <section id="basic-datatable">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">{{ trans('wish_suggestions.show') }}</h4>
                 @can('wish-suggestion-list')
                <a href="{{route('wish_suggestions.index')}}" class="btn btn-success">
                    <i class="fa fa-arrow-left"></i>
                    {{ trans('common.back') }}
                </a>
              @endcan
              </div>
              <div class="card-content">
                <div class="card-body card-dashboard">
                  <hr>
                 <div class="row">
                    <div class="col-md-6">
                     <div class="form-group">
                         <label for="name" class="content-label">{{trans('common.id')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input class="form-control" name="id" value="{{ $wish_suggestion->id }}" disabled>
                      </div>  
                    </div>
                     <div class="col-md-6">
                     <div class="form-group">
                         <label for="name" class="content-label">{{trans('wish_suggestions.subject')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input class="form-control" name="subject" value="{{ $wish_suggestion->subject }}" disabled>
                      </div>  
                    </div>
                     <div class="col-md-6">
                     <div class="form-group">
                         <label for="name" class="content-label">{{trans('wish_suggestions.book_name')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input class="form-control" name="book_name" value="{{ $wish_suggestion->book_name }}" disabled>
                      </div>  
                    </div>
                     <div class="col-md-6">
                     <div class="form-group">
                         <label for="name" class="content-label">{{trans('wish_suggestions.user_name')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input class="form-control" name="book_name" value="{{ $wish_suggestion->user->first_name }}" disabled>
                      </div>  
                    </div>
                     <div class="col-md-6">
                     <div class="form-group">
                         <label for="name" class="content-label">{{trans('users.phone_number')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input class="form-control" name="phone_number" value="{{ $wish_suggestion->user->mobile_number }}" disabled>
                      </div>  
                    </div>
                    <div class="col-md-6">
                     <div class="form-group">
                         <label for="name" class="content-label">{{trans('users.email')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input class="form-control" name="email" value="{{ $wish_suggestion->email }}" disabled>
                      </div>  
                    </div>
                     <div class="col-md-6">
                     <div class="form-group">
                         <label for="name" class="content-label">{{trans('wish_suggestions.description')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input class="form-control" name="email" value="{{ $wish_suggestion->description }}" disabled>
                      </div>  
                    </div>
                    <div class="col-md-6">
                     <div class="form-group">
                         <label for="name" class="content-label">{{trans('wish_suggestions.date_time')}}<span class="text-danger custom_asterisk">*</span></label>
                        <input class="form-control" name="date_time" value="{{ date('d-m-Y H:i A',strtotime($wish_suggestion->created_at)) }}" disabled>
                      </div>  
                    </div>
                      <div class="col-md-12">
                     <div class="form-group">
                         <label for="name" class="content-label">{{trans('wish_suggestions.image_or_pdf')}}<span class="text-danger custom_asterisk">*</span></label>
                           <div class="table-responsive">
                    <table class="table zero-configuration data_table_ajax">
                      <thead>
                        <tr>
                          <th>{{trans('common.id')}}</th>
                          <th>{{trans('wish_suggestions.image')}}</th>
                          <th>{{trans('wish_suggestions.pdf')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th>{{trans('common.id')}}</th>
                          <th>{{trans('wish_suggestions.image')}}</th>
                          <th>{{trans('wish_suggestions.pdf')}}</th>
                        </tr>  
                      </tfoot>
                    </table>
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
      <!--/ Zero configuration table -->
    </div>
  </div>
</div>


@endsection
@section('page_js')
<!-- BEGIN: Page Vendor JS-->
<script src="{{asset('admin_assets/app-assets/vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('admin_assets/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js')}}"></script>
<script src="{{asset('admin_assets/app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js')}}"></script>
<script src="{{asset('admin_assets/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js')}}"></script>
<!-- END: Page Vendor JS-->
@endsection

@section('js')
<script src="{{asset('admin_assets/custom/data_tables/export/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('admin_assets/custom/data_tables/export/jszip.min.js')}}"></script>
<script src="{{asset('admin_assets/custom/data_tables/export/buttons.html5.min.js')}}"></script>
<script src="{{asset('admin_assets/custom/data_tables/export/buttons.print.min.js')}}"></script>

<script type="text/javascript">
  $(document).ready(function(){

    $('.data_table_ajax').DataTable({ 
        aaSorting : [[0, 'desc']],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        pageLength: 10,
        serverSide: true,
        serverMethod:'POST',
        language: {
              processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">{{trans("common.loading")}}</span> '},
        ajax: {
            url: "{{route('dt_wish_suggestion_images')}}",
            data: {
                    "_token": "{{csrf_token()}}",
                    "wish_suggestion_id": "{{$wish_suggestion->id}}",
                    },
           //success:function(data) { console.log(data); },
            error:function(data){ console.log(data); },
        },
        columns: [
           { data: 'id'},
           { data: 'image',orderable: true},
           { data: 'pdf',orderable: false},
          ]
    });
  });
</script>

<script type="text/javascript">
   function delete_alert() {
      if(confirm("{{trans('common.confirm_delete')}}")){
        return true;
      }else{
        return false;
      }
    }
</script>


@endsection
