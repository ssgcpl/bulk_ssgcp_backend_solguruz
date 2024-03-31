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
                    <h2 class="content-header-title float-left mb-0">{{ trans('avatars.admin_heading') }}</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ trans('avatars.plural') }}
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
                <h4 class="card-title">{{ trans('avatars.title') }}</h4>
                @can('avatars-create')
                  <div class="box-tools pull-right">
                    <a href="{{ route('avatars.create') }}" class="btn btn-success pull-right">
                    {{ trans('avatars.add_new') }}
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
                          <th>{{trans('avatars.h_image')}}</th>
                          <th>{{trans('avatars.name')}}</th>
                          <th>{{trans('avatars.type')}}</th>
                          <th>{{trans('avatars.price')}}</th>
                          <th>{{trans('common.status')}}</th>
                           @if(auth()->user()->can('avatars-edit') || auth()->user()->can('avatars-list') || user()->can('avatars-delete')  )
                          <th>{{trans('common.action')}}</th>
                          @endif
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th>{{trans('common.id')}}</th>
                          <th>{{trans('avatars.h_image')}}</th>
                          <th>{{trans('avatars.name')}}</th>
                          <th>{{trans('avatars.type')}}</th>
                          <th>{{trans('avatars.price')}}</th>
                          <th>{{trans('common.status')}}</th>
                           @if(auth()->user()->can('avatars-edit') || auth()->user()->can('avatars-list') || user()->can('avatars-delete')  )
                          <th>{{trans('common.action')}}</th>
                          @endif
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
    $(document).on('change','.status',function(){
    var status = this.checked ? 'active' : 'inactive';
    var confirm = status_alert(status);
    if(confirm){
      var id = $(this).attr('data_id');
      var delay = 500;
      var element = $(this);
      $.ajax({
        type:'post',
        url: "{{route('status_avatars')}}",
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
          fill_datatable();
        },
        error: function () {
          toastr.error(data.error);
        }
      })
    } else {
      location.reload();
    }
  });
    fill_datatable();
    function fill_datatable() {
      $('.data_table_ajax').DataTable({
        aaSorting : [[0, 'desc']],
        scrollY: 300, 
        scrollX: true,
        dom: 'Blfrtip',
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        pageLength: 10,
        buttons: [
        ],
        processing: true,
        serverSide: true,
        serverMethod:'POST',
        processing: true,
        bDestroy: true,
        language: {
              processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">{{trans("common.loading")}}</span> ',
              emptyTable: "{{trans('datatable.empty_avatars')}}",
              zeroRecords:"{{trans('datatable.no_avatars')}}",
            },

        ajax: {
            url: "{{route('dt_avatars')}}",
            data: {"_token": "{{csrf_token()}}"},
           //success:function(data) { console.log(data); },
            error:function(data){ console.log(data); },
        },
        columns: [
           { data: 'id'},
           { data: 'image',
              mRender : function(data, type, row) {
                   
                  return row['avatar_image'];
                },orderable: false
            },
           { data: 'name',orderable: false},
           { data: 'type',orderable: false},
           { data: 'price',orderable: false},
           { data: 'status',
              mRender : function(data, type, row) {
                  var status=data;
                  if(status=='active'){
                    type = "checked";
                  } else {
                    type = '';
                  }
                  var status_label = status.charAt(0).toUpperCase() +""+status.slice(1); 
                  return '<label id="status_label">'+status_label+'</label><div class="custom-control custom-switch custom-switch-success mr-2 mb-1"><input type="checkbox"'+type+' + class="status custom-control-input" id="customSwitch'+row["id"]+'" data_id="'+row["id"]+'"><label class="custom-control-label" for="customSwitch'+row["id"]+'"></label></div>'
                },orderable: false
            },
            @if(auth()->user()->can('authors-edit') || auth()->user()->can('authors-list') || auth()->user()->can('authors-delete') )
            { 
              mRender : function(data, type, row) {
                return '@can("authors-edit")<a class="btn" href="'+row["edit"]+'"><i class="fa fa-edit"></i></a>@endcan<a class="btn" href="'+row["show"]+'"><i class="fa fa-eye"></i></a>@csrf</form>@can("authors-delete")<form action="'+row["delete"]+'" method="post"><button class="btn" type="submit" onclick=" return delete_alert()"><i class="fa fa-trash"></i></button>@method("delete")@csrf</form>@endcan';
               }, orderable: false, searchable: false
            },
            @endif
          ]
      });
    }
    
  });
  function status_alert(status) {
    if(status == 'active'){
      if(confirm("{{trans('common.confirm_status_active')}}")){
        return true;
      } else {
        return false;
      }
    } else if(status =='inactive') {
      if(confirm("{{trans('common.confirm_status_inactive')}}")){
        return true;
      }else{
        return false;
      }
    }
  }
  function delete_alert() {
      if(confirm("{{trans('common.confirm_delete')}}")){
        return true;
      }else{
        return false;
      }
    }
</script>
@endsection