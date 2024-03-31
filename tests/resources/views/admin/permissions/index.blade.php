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
                    <h2 class="content-header-title float-left mb-0">{{trans('permissions.heading')}}</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ trans('permissions.plural') }}
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
                <h4 class="card-title">{{ trans('permissions.title') }}</h4>
                @can('permission-create')
                  <div class="box-tools pull-right">
                    <a href="{{ route('permissions.create',basename(Request::url())) }}" class="btn btn-success pull-right">
                    {{ trans('permissions.add_new') }}
                    </a>
                  </div>
                @endcan
              </div>
              <div class="card-content">
                <div class="card-body card-dashboard">
                  <div class="table-responsive">
                    <table id="example2" class="table zero-configuration data_table_ajax">
                      <thead>
                        <tr>
                          <th>{{trans('common.id')}}</th>
                          <th>{{trans('permissions.name')}}</th>
                          @can('role-delete')
                          <th>{{trans('common.action')}}</th>
                          @endcan
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($permissions as $key => $permission)
                          <tr>
                            <td>{{++$key}}</td>
                            <td>{{ $permission->name }}</td>
                            @can('permission-delete')
                            <td>
                                {!! Form::open(['method' => 'DELETE','route' => ['permissions.destroy', $permission->id],'style'=>'display:inline']) !!}
                                  <button class="btn" type="submit">
                                    <i class="fa fa-trash"></i>
                                  </button>
                                {!! Form::close() !!}
                            </td>
                            @endcan
                          </tr>
                        @endforeach  
                      </tbody>
                      <tfoot>
                        <tr>
                            <th>{{trans('common.id')}}</th>
                            <th>{{trans('permissions.name')}}</th>
                            @can('role-delete')
                            <th>{{trans('common.action')}}</th>
                            @endcan
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
<script type="text/javascript">
  $('#example2').DataTable({
                'paging'      : true,
                'lengthChange': true,
                'searching'   : true,
                'ordering'    : true,
                'info'        : true,
                'autoWidth'   : true
              })
</script>
@endsection