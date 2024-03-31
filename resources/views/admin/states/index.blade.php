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
                    <h2 class="content-header-title float-left mb-0">{{ trans('states.admin_heading') }}</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ trans('states.plural') }}
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
                <h4 class="card-title">{{ trans('states.title') }}</h4>
                @can('states-create')
                  <div class="box-tools pull-right">
                    <a href="{{ route('states.create') }}" class="btn btn-success pull-right">
                    {{ trans('states.add_new') }}
                    </a>
                  </div>
                @endcan
              </div>
              <div class="card-content">
                <div class="card-body card-dashboard">
                  <div class="table-responsive">
                    <table class="table zero-configuration data_table_ajax">
                      <thead>
                        <tr>
                          <th>{{trans('common.id')}}</th>
                          <th>{{trans('states.name')}}</th>
                          <th>{{trans('states.country_name')}}</th>
                          <th>{{trans('common.status')}}</th>
                          <th>{{trans('common.action')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th>{{trans('common.id')}}</th>
                          <th>{{trans('states.name')}}</th>
                          <th>{{trans('states.country_name')}}</th>
                          <th>{{trans('common.status')}}</th>
                          <th>{{trans('common.action')}}</th>
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
     $(document).on('change','.status',function(){
      var status = this.checked ? 'active' : 'inactive';
      var confirm = status_alert(status);
      if(confirm){
        var id = $(this).attr('data_id');
        var delay = 500;
        var element = $(this);
        $.ajax({
            type:'post',
            url: "{{route('status_states')}}",
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
                location.reload();
            },
            error: function () {
              toastr.error(data.error);
            }
        })
      }else{
      location.reload();
      }
  })

  </script>
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
              processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">{{trans("common.loading")}}</span> ',
              emptyTable: "{{trans('datatable.empty_states')}}",
              zeroRecords:"{{trans('datatable.no_states')}}",
            },
      ajax: {
          url: "{{route('dt_states')}}",
          data: {"_token": "{{csrf_token()}}"},
      },
      columns: [
         { data: 'id'},
         { data: 'name',orderable:false},
         { data: 'country_name',orderable:false },
         { data: 'status',
            mRender : function(data, type, row) {
                  var status=data;
                 
                 if(status=='active'){
                    type = "checked";
                  }else{
                    type = '';
                  }
                    var status_label = status.charAt(0).toUpperCase() +""+status.slice(1); 
                 
                  return '<label>'+status_label+'</label><div class="custom-control custom-switch custom-switch-success mr-2 mb-1"><input type="checkbox"'+type+' + class="status custom-control-input" id="customSwitch'+row["id"]+'" data_id="'+row["id"]+'"><label class="custom-control-label" for="customSwitch'+row["id"]+'"></label></div>'
              } 
          },
          { 
            mRender : function(data, type, row) {
                  return '<a class="" href="'+row["show"]+'"><i class="fa fa-eye"></i></a> <a class="" href="'+row["edit"]+'"><i class="fa fa-edit"></i></a>@csrf';
              },orderable:false,
          },
        ]
   });
});
</script>
<script>
  function delete_alert() {
      if(confirm("{{trans('common.confirm_delete')}}")){
        return true;
      }else{
        return false;
      }
    }

  function status_alert(status) {
    if(status == 'active')
    {
      if(confirm("{{trans('common.confirm_status_active')}}")){
        return true;
      }else{
        return false;
      }
    }
      else if(status =='inactive')
      {
        if(confirm("{{trans('common.confirm_status_inactive')}}")){
        return true;
          }else{
            return false;
          }
      }
    }
</script>

@endsection