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
                    <h2 class="content-header-title float-left mb-0">{{ trans('tickets.admin_heading') }}</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ trans('tickets.plural') }}
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
                <h4 class="card-title">{{ trans('tickets.title') }}</h4>
              </div>
              <div class="card-content">
                <div class="card-body card-dashboard">
                  <div class="row">
               
                <div class="col-md-2">
                      <div class="form-group">
                        <label class="content-label">Start Date</label>
                        <input id="start_date" type="" class="form-control datepicker_future_not_allow" autocomplete="off" name="" value="">
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label class="content-label">End Date</label>
                        <input id="end_date" type="" class="form-control datepicker_future_not_allow" name="" autocomplete="off" name="" value="">
                      </div>
                    </div>
                      <div class="col-md-2">  
                      <div class="form-group">
                        <label class="content-label"> &nbsp;</label>
                        <a href="javascript:void(0)" id="apply_date" class="btn btn-success pull-right form-control">Apply
                        </a>
                      </div>
                    </div>
                     <div class="col-md-3">
                      <div class="form-group">
                        <label class="content-label"></label>
                        <select id="status_type" class = "form-control" name='booking'>
                        <option value=''>{{trans('tickets.select_type')}}</option>
                        <option value='pending'>{{trans('tickets.pending')}}</option>
                        <option value="acknowledged">{{trans('tickets.acknowledged') }}</option>
                        <option value="resolved">{{trans('tickets.resolved') }}</option>
                        </select>
                      </div>
                    </div>
              </div>
                  <div class="table-responsive">
                    <table id="tickets" class="table zero-configuration data_table_ajax">
                      <thead>
                        <tr>
                  <th>{{ trans('tickets.id') }}</th>
                  <th>{{ trans('tickets.name') }}</th>
                  <th>{{ trans('tickets.email') }}</th>
                  <th>{{ trans('tickets.request_as') }}</th>
                  <th>{{ trans('tickets.reason') }}</th>
                  <th>{{ trans('tickets.message') }}</th>
                  <th width="10%">{{ trans('tickets.created_date') }}</th>
                  <th width="10%">{{ trans('tickets.updated_date') }}</th>
                  <th width="15%">{{ trans('common.status') }}</th>
                  <th>{{ trans('common.action') }}</th>
                </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>
                      <tr>
                  <th>{{ trans('tickets.id') }}</th>
                  <th>{{ trans('tickets.name') }}</th>
                  <th>{{ trans('tickets.email') }}</th>
                  <th>{{ trans('tickets.request_as') }}</th>
                  <th>{{ trans('tickets.reason') }}</th>
                  <th>{{ trans('tickets.message') }}</th>
                  <th width="10%">{{ trans('tickets.created_date') }}</th>
                  <th width="10%">{{ trans('tickets.updated_date') }}</th>
                  <th width="80px !important;">{{ trans('common.status') }}</th>
                  <th>{{ trans('common.action') }}</th>
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
  <div class="modal fade" id="ticketsModal" tabindex="-1" role="dialog" aria-labelledby="NotifyLabel">
     <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">{{trans('tickets.send_comment')}}</h4>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
       
        </div>
        <div class="modal-body">
          <form class="form-group" method="PUT" id="ticketsForm">
            <div class="row">
              <input class="form-control" id="tickets_id" required="true" name="tickets_id" type="hidden">          
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="name" class="content-label" >{{trans('tickets.comment')}}</label>
                  <textarea class="form-control" placeholder="{{trans('tickets.comment')}}" required="true" name="comment" id="comment" rows="3"> </textarea>
                </div>
              </div>

            </div>
          </form>
         
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary waves-effect waves-float waves-light" type="button" id="send_comment">{{trans('tickets.send')}}</button>
          <button type="button" id="close_comment" class="btn btn-primary waves-effect waves-float waves-light" data-dismiss="modal">{{trans('tickets.close')}}</button>
        </div>
      </div>
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
    $(document).on('change','.ticket_status',function(){
      if ($(this).val() == "resolved"){
        var status = $(this).val();
        var id = $(this).attr('id');
        $('#ticketsModal').modal('show');
        $('#close_comment').click(function() {
          location.reload();
        });
        $('#send_comment').click(function(){

        var validator = $('#ticketsForm').validate({ 
          rules: {
                  id: {
                      required: true
                  },
                  comment: {
                      required: true
                  }
              },
              messages:{
                comment:{
                  required:"{{trans('tickets.message_cannot_be_empty')}}",
                }
              }
          });

          if (validator.valid()) 
            {
          
            }
          else 
            {
                return false;
            }
          var comment      =  $('#comment').val();
          var delay = 500;
          $.ajax({
                type:'post',
                url: "{{ route('r_status') }}",
                data: {
                        "id"         : id, 
                        "status": status, 
                        "comment"       : comment,  
                        "_token": "{{ csrf_token() }}"
                      },
                beforeSend: function () {
                    $('#send_comment').html('Sending..<span id="loader" style="visibility: hidden;"><i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">a</span></span>');
                    $('#loader').css('visibility', 'visible');
                },
                success: function (data) {
                  $('#send_comment').html('Send');
                  $('#comment').val("");
                  $('#loader').css('visibility', 'hidden');
                  if(data.type == 'error'){
                    toastr.error(data.message);
                  }else{
                    $('#tickets').modal('hide');
                    location.reload();
                    toastr.success(data.message);
                  }
                },
                error: function () {
                  $('#send_comment').html('Send');
                  $('#loader').css('visibility', 'hidden');
                  toastr.error('Work in progress');
                }
            })
        })
      }
      else if($(this).val() == "acknowledged"){
        var status = $(this).val();
        var id = $(this).attr('id');
        $('#ticketsModal').modal('show');
        $('#close_comment').click(function() {
          location.reload();
        });
        $('#send_comment').click(function(){

        var validator = $('#ticketsForm').validate({ 
          rules: {
                  id: {
                      required: true
                  },
                  comment: {
                      required: true
                  }
              },
              messages:{
                comment:{
                  required:"{{trans('tickets.message_cannot_be_empty')}}",
                }
              }
          });

          if (validator.valid()) 
            {
          
            }
          else 
            {
                return false;
            }
          var comment      =  $('#comment').val();
          var delay = 500;
          $.ajax({
                type:'post',
                url: "{{ route('acknowledged_status') }}",
                data: {
                        "id"         : id, 
                        "status": status, 
                        "comment"       : comment,  
                        "_token": "{{ csrf_token() }}"
                      },
                beforeSend: function () {
                    $('#send_comment').html('Sending..<span id="loader" style="visibility: hidden;"><i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">a</span></span>');
                    $('#loader').css('visibility', 'visible');
                },
                success: function (data) {
                  $('#send_comment').html('Send');
                  $('#comment').val("");
                  $('#loader').css('visibility', 'hidden');
                  if(data.type == 'error'){
                    toastr.error(data.message);
                  }else{
                    $('#tickets').modal('hide');
                    location.reload();
                    toastr.success(data.message);
                  }
                },
                error: function () {
                  $('#send_comment').html('Send');
                  $('#loader').css('visibility', 'hidden');
                  toastr.error('Work in progress');
                }
            })
        })
      }
      else{
        var status = $(this).val();
        var id = $(this).attr('id');
        var delay = 500;
        var element = $(this);
        $.ajax({
            type:'post',
            url: "{{route('ticket_status')}}",
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
              if(data.type == 'error'){
                toastr.error(data.error);
              }else{
                toastr.success(data.success);
              }
            },
            error: function () {
              toastr.error(data.error);
            }
        })
      }
    })
</script>
<script type="text/javascript">
 $(document).ready(function(){
  // alert();
  
   $("#start_date").val('');
   $("#end_date").val('');

 // fill_datatable(status);
  execute_table();
  function fill_datatable(status,start_date,end_date) {
    var lang_url = '';
      
      var table = $('#tickets').removeAttr('width').DataTable({
        aaSorting: [[ 0, "desc" ]],
        scrollY: 300, 
        scrollX: true,
        processing: true,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100,"All"]],
        serverSide: true,
        serverMethod:'POST',
        processing: true,
        stateSave:true,
        language: {
              processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">{{trans("common.loading")}}</span>',
              url: lang_url
        },
         
        ajax: {
            url: "{{route('dt_tickets')}}",
            data: {
                    "_token": "{{csrf_token()}}", 
                    'status': status,
                    'start_date': start_date,
                    'end_date': end_date,
                  },
             //   success:function(data) { console.log(data); },
                error:function(data) {  console.log(data); }
        },
        columnDefs: [
            {
                render: function (data, type, full, meta) {
                  if(data != null)
                  {
                    return "<div style='white-space:normal;overflow-wrap: break-word;width:40px;'>" + data + "</div>";
                  }
                  else
                  {
                    return "<div style='white-space:normal;overflow-wrap: break-word;width:40px;'></div>";
                  }
                },
                targets: 4
            }
        ],
        columns: [
            { data: 'id',
              mRender : function(data, type, row) { 
                  return row['id'];
                } 
            },
            {
              data: 'full_name',
             /* mRender : function(data, type, row) { 
              return '<a class="" href="'+row["show"]+'">'+row.full_name+'</a>';
              },orderable:false,*/
              mRender : function(data, type, row) {
                    return row['full_name'];  
                }, orderable: false, searchable: false
            },
           
           { data: 'email', orderable: false, searchable: false},
           { data: 'user_type', orderable: false, searchable: false
           },
           { data: 'reason', orderable: false, searchable: false},
           { data: 'message', orderable: false, searchable: false},
            { data: 'created_at',
              mRender : function(data, type, row) { 
                  return row['created'];
                } 
            },
             { data: 'updated_at',
              mRender : function(data, type, row) { 
                  return row['updated'];
                } 
            },
           {
              mRender : function(data, type, row) {

                    var status = row['status'];
                    var type = row['user_type'];
                    var new_ticket = '';
                    var resolved = '';
                    var acknowledged = '';
                    

                    if(status == 'acknowledged'){
                      acknowledged = "selected";

                    }else if(status == 'resolved'){
                      resolved = 'selected';
                    }else{
                      new_ticket = 'selected';
                    }
                    if(resolved == "selected"){
                      return '{{trans("tickets.resolved")}}';  
                    }else{
                      if(type != 'Guest') {
                      return '<select class="ticket_status form-control" id="'+row["id"]+'" style="width:100px !important;" ><option value="acknowledged"'+acknowledged+'>'+'{{trans("tickets.acknowledged")}}'+'</option><option value="resolved"'+resolved+'>'+'{{trans("tickets.resolved")}}'+'</option><option value="new_ticket"'+new_ticket+'>'+'{{trans("tickets.pending")}}'+'</option></select><span class="loading" style="visibility: hidden;"><i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">'+'{{trans("common.loading")}}'+'</span></span>';
                      }else{
                        return '-';  
                      }
                    }
                },orderable: false, searchable: false 
           },
          
             { 
              mRender : function(data, type, row) {
                return '<a class="" href="'+row["show"]+'"><i class="fa fa-eye"></i></a>';
               }, orderable: false, searchable: false,"width": "20%",
            },
          ]
     });
  }
  
   $('#apply_date').on('click', function (event) {
    if($('#start_date').val() != '' && $('#end_date').val() != '') {
      var from_date = $("#start_date").val();
      var to_date = $("#end_date").val();
      var dateArr = from_date.split("-");
      from_date = new Date(dateArr[2]+'-'+dateArr[1]+'-'+dateArr[0]);
      var dateArr1 = to_date.split("-");
      to_date = new Date(dateArr1[2]+'-'+dateArr1[1]+'-'+dateArr1[0]);
      if(from_date > to_date){
        toastr.error("End date should not be less than start date");
        return false;
      }
      else {
        $('#tickets').DataTable().destroy();
        execute_table();   
        //fill_datatable();
      }
       
    }else{
      toastr.error('Please select start date and end date');
      return false;
    }
  });

    function execute_table() {
     var status = $('status_type').val();
     var user_type = $('user_type').val();

     var start_date = $('#start_date').val();
     var end_date = $('#end_date').val();
     fill_datatable(status,start_date,end_date);
    }

     $('#start_date,#end_date').on('change', function (event) {
      $('#tickets').DataTable().destroy();
      execute_table();  
    });

     $('#status_type').on('change', function (event) {
      $('#tickets').DataTable().destroy();
      fill_datatable($('#status_type').val());  
    })
  
});
</script>

<script>
    function delete_alert() {
      if(confirm("Are you sure want to delete this item?")){
        return true;
      }else{
        return false;
      }
    }

</script>
<script type="text/javascript">
  $(".datetimepicker").datetimepicker({
    // format: 'YYYY-MM-DD H:mm',
    // minDate: new Date().setHours(0,0,0,0),
    format: 'YYYY-MM-DD',
    icons:{
        time: 'glyphicon glyphicon-time',
        date: 'glyphicon glyphicon-calendar',
        previous: 'glyphicon glyphicon-chevron-left',
        next: 'glyphicon glyphicon-chevron-right',
        today: 'glyphicon glyphicon-screenshot',
        up: 'glyphicon glyphicon-chevron-up',
        down: 'glyphicon glyphicon-chevron-down',
        clear: 'glyphicon glyphicon-trash',
        close: 'glyphicon glyphicon-remove'
    },
    locale : '{{config("app.locale")}}'
  });

  $(".datetimepicker2").datetimepicker({
    // format: 'YYYY-MM-DD H:mm',
    maxDate: new Date().setHours(0,0,0,0),
    format: 'YYYY-MM-DD',
    icons:{
        time: 'glyphicon glyphicon-time',
        date: 'glyphicon glyphicon-calendar',
        previous: 'glyphicon glyphicon-chevron-left',
        next: 'glyphicon glyphicon-chevron-right',
        today: 'glyphicon glyphicon-screenshot',
        up: 'glyphicon glyphicon-chevron-up',
        down: 'glyphicon glyphicon-chevron-down',
        clear: 'glyphicon glyphicon-trash',
        close: 'glyphicon glyphicon-remove'
    },
    locale : '{{config("app.locale")}}'
  });

  $('.multiselect-input').attr('autocomplete','off');
</script>
@endsection