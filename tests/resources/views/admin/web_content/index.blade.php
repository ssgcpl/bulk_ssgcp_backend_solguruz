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
<link href="{{asset('css/select2/select2.min.css')}}" rel="stylesheet">
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
                    <h2 class="content-header-title float-left mb-0">{{ trans('web_content.admin_heading') }}</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ trans('web_content.plural') }}
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
        <div class="card">
          <div class="card-header d-block">
            <div class="row align-items-end">
              <div class="col-md-4">
                <label>{{ trans('web_content.add_book') }}</label>
                <select id="books_dropdown" class="form-control" name='status'>
                  <option value="">{{trans('web_content.please_select')}}</option>
                  @foreach($books as $book)
                  <option value="{{$book->id}}">{{\Str::limit($book->name,20)}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-2">
                <a class="btn btn-success" id="add_book" href="#">
                  {{trans('web_content.add')}}
                  <form method="POST" action="{{route('add_from_library')}}" id="add_book_form">
                    @csrf
                    <input type="hidden" value="" id="book_id" name="book_id">
                  </form>
                </a>
              </div>
              <div class="col-md-6">
                @can('web-content-create')
                  <div class="box-tools pull-right">
                    <a href="{{ route('web_content.create') }}" class="btn btn-success pull-right">
                    {{ trans('web_content.add_new') }}
                    </a>
                  </div>
                @endcan
              </div>
            </div>
          </div>
          <div class="card-content">
            <div class="card-body card-dashboard">
              <div class="table-responsive">
                <table class="table zero-configuration data_table_ajax">
                  <thead>
                    <tr>
                      <th>{{trans('common.id')}}</th>
                      <th>{{trans('web_content.thumbnail_index')}}</th>
                      <th>{{trans('web_content.title')}}</th>
                      <th>{{trans('web_content.author_name')}}</th>
                      <th>{{trans('web_content.type')}}</th>
                      <th>{{trans('web_content.created_at')}}</th>
                      <th>{{trans('common.status')}}</th>
                       @if(auth()->user()->can('web-content-edit') || auth()->user()->can('web-content-list') || user()->can('web-content-delete')  )
                      <th>{{trans('common.action')}}</th>
                      @endif
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>{{trans('common.id')}}</th>
                      <th>{{trans('web_content.thumbnail_index')}}</th>
                      <th>{{trans('web_content.title')}}</th>
                      <th>{{trans('web_content.author_name')}}</th>
                      <th>{{trans('web_content.type')}}</th>
                      <th>{{trans('web_content.created_at')}}</th>
                      <th>{{trans('common.status')}}</th>
                       @if(auth()->user()->can('web-content-edit') || auth()->user()->can('web-content-list') || user()->can('web-content-delete')  )
                      <th>{{trans('common.action')}}</th>
                      @endif
                    </tr>
                  </tfoot>
                </table>
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
        url: "{{route('status_web_content')}}",
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
              emptyTable: "{{trans('datatable.empty_web_content')}}",
              zeroRecords:"{{trans('datatable.no_web_content')}}",
            },

        ajax: {
            url: "{{route('dt_web_content')}}",
            data: {"_token": "{{csrf_token()}}"},
           
        },
        columns: [
           { data: 'id'},
           { 
              mRender : function(data, type, row) {
                   return row['image'];
              },orderable: false 
           },
           { data: 'title',orderable: false},
           { data: 'author_name',orderable: false},
           { data: 'type',orderable: false},
           { 
              mRender : function(data, type, row) {
                   return row['created'];
              },orderable: false 
           },
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
            @if(auth()->user()->can('web-content-edit') || auth()->user()->can('web-content-list') || auth()->user()->can('web-content-delete') )
            { 
              mRender : function(data, type, row) {
                return '@can("web-content-edit")<a class="btn" href="'+row["edit"]+'"><i class="fa fa-edit"></i></a>@endcan<a class="btn" href="'+row["show"]+'"><i class="fa fa-eye"></i></a>@csrf</form>@can("web-content-delete")<form action="'+row["delete"]+'" method="post"><button class="btn" type="submit" onclick=" return delete_alert()"><i class="fa fa-trash"></i></button>@method("delete")@csrf</form>@endcan';
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
<script src="{{asset('js/select2/select2.min.js')}}"></script>
<script src="{{asset('js/select2/i18n/ar.js')}}"></script>
<script src="{{asset('js/select2/i18n/en.js')}}"></script>
<script>
  
  $("#books_dropdown").select2({
    tags: true,
    tokenSeparators: [',', ' '],
    createTag: function (params) {
      return undefined;
    }
  });

  $('#add_book').on('click',function(){
    var id = $('#books_dropdown').val();

    if(id == ''){
      var message = "{{trans('web_content.please_select_book')}}";
      toastr.error(message);
    }else{
      $('#book_id').val(id);
      $('#add_book_form').submit();
    }

  });
</script>
@endsection