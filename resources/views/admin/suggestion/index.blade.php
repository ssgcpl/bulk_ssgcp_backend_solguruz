@extends('layouts.admin.app')
@section('vendor_css')
<link rel="stylesheet" type="text/css" href="{{asset('admin_assets/app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
<style type="text/css">
  td form{
    display: inline;
  }
  .dt-buttons{margin-right: 10px}
  .select2-selection {padding: 0px 5px !important}
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
                    <h2 class="content-header-title float-left mb-0">{{ trans('suggestion.box') }}</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ trans('suggestion.box') }}
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
                <h4 class="card-title">{{ trans('suggestion.list') }}</h4>
               
              </div>
              <div class="card-content">
                <div class="card-body card-dashboard">
                  <div class="table-responsive">
                    <table class="table zero-configuration data_table_ajax">
                      <thead>
                        <tr>
                          <th>{{trans('common.id')}}</th>
                          <th>{{trans('suggestion.name')}}</th>
                          <th>{{trans('suggestion.title')}}</th>
                          <th>{{trans('suggestion.email')}}</th>
                          <th>{{trans('suggestion.message')}}</th>
                          
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th>{{trans('common.id')}}</th>
                          <th>{{trans('suggestion.name')}}</th>
                          <th>{{trans('suggestion.title')}}</th>
                          <th>{{trans('suggestion.email')}}</th>
                          <th>{{trans('suggestion.message')}}</th>
                          
                        </tr>
                      </tfoot>
                    </table>
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
     /* scrollY: 300, 
      scrollX: true,*/
      processing: true,
      serverSide: true,
      serverMethod:'POST',
      lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100,"All"]],
      pageLength: 10,
      language: {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
            emptyTable: "{{trans('datatable.empty_suggestion')}}",
            zeroRecords:"{{trans('datatable.no_suggestion')}}",
            },
      ajax: {
          url: "{{route('dt_suggestion')}}",
          data: {"_token": "{{csrf_token()}}"},
      },
      columns: [
         { data: 'id' },
         { data: 'name',orderable:false},
         { data: 'title',orderable:false },
         { data:'user_id',
            mRender : function(data, type, row) { 
                return row['email'];
              },orderable: false, searchable: false 
           },
           { data: 'description',orderable:false },
      ],
      columnDefs: [
            { width: 500, targets: 4 }
      ],
   });
});
</script>

@endsection