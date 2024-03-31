@extends('layouts.admin.app')
@section('vendor_css')
<link rel="stylesheet" type="text/css" href="{{asset('admin_assets/app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
<style type="text/css">
  td form{
    display: inline;
  }
  .dt-buttons{margin-right: 10px}
  .select2-selection {padding: 0px 5px !important}
  .block { color:red;  }
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
                    <h2 class="content-header-title float-left mb-0">{{ trans('admin_lists.admin_activity_log') }}</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ trans('admin_lists.activity_logs') }}
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
                <h4 class="card-title">{{ trans('admin_lists.activity_list') }}</h4>
              </div>
              <div class="card-content">
                <div class="card-body card-dashboard">
                  <div class="table-responsive">
                    <table class="table zero-configuration data_table_ajax">
                      <thead>
                        <tr>
                          <th>{{trans('common.id')}}</th>
                          <th>{{trans('admin_lists.item_name')}}</th>
                          <th>{{trans('admin_lists.activity_by')}}</th>
                          <th>{{trans('admin_lists.activity')}}</th>
                          <th>{{trans('admin_lists.module')}}</th>
                          <th>{{trans('admin_lists.time')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th>{{trans('common.id')}}</th>
                          <th>{{trans('admin_lists.item_name ')}}</th>
                          <th>{{trans('admin_lists.activity_by')}}</th>
                          <th>{{trans('admin_lists.activity')}}</th>
                          <th>{{trans('admin_lists.module')}}</th>
                          <th>{{trans('admin_lists.time')}}</th>
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
<input type="hidden" name="sub_admin_id" id="sub_admin_id" value="{{$sub_admin_id}}">
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

/*  $(document).on('change','.status',function(){
      var status = $(this).val();
      var confirm = status_alert(status);
      if(confirm){
        var id = $(this).attr('id');
        var delay = 500;
        var element = $(this);
        $.ajax({
            type:'post',
            url: "{{route('status_admin_lists')}}",
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
              toastr.success(data.success)
            },
            error: function () {
              toastr.error(data.error);
            }
        })
      }else{
      location.reload();
      }
  })

*/

</script>
<script type="text/javascript">
  $(document).ready(function(){

    fill_datatable();

    function fill_datatable() {
      var sub_admin_id = $('#sub_admin_id').val();
      $('.data_table_ajax').DataTable({ 
        "scrollY": 300, "scrollX": true,
        aaSorting : [[0, 'desc']],
        dom: 'Blfrtip',
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        pageLength: 10,
        buttons: [
        ],
        processing: true,
        serverSide: true,
        serverMethod:'POST',
        processing: true,
        language: {
              processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">{{trans("common.loading")}}</span> '},
        ajax: {
            url: "{{route('dt_log')}}",
            data: {"_token": "{{csrf_token()}}" ,'id': sub_admin_id },
            error:function(data){ console.log(data); },
        },
        columns: [

           { data: 'id'},
           { data: 'item_name',orderable: false},
           { data: 'name',orderable: false},
           { data: 'activity',orderable: false},
           { data: 'area',orderable: false},
           { data: 'created_date',orderable: false},
            
          ]
      });
    }
  });

</script>

@endsection