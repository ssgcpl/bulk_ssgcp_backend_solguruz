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
                    <h2 class="content-header-title float-left mb-0">{{ trans('contact_us.contact_heading') }}</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{ trans('common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ trans('contact_us.contacts') }}
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
                <h4 class="card-title">{{ trans('contact_us.contacts') }}</h4>
            
              </div>
               
              <div class="card-content">
                <div class="card-body card-dashboard">
                  <div class="table-responsive">
                    <table class="table zero-configuration data_table_ajax">
                      <thead>
                        <tr>
                          <th>{{trans('contact_us.id')}}</th>
                          <th>{{trans('contact_us.contact_name')}}</th>
                          <th>{{trans('contact_us.contact_email')}}</th>
                          <th>{{trans('contact_us.contact_reason')}}</th>
                          <th>{{trans('contact_us.contact_message')}}</th>
                          <th>{{trans('contact_us.status')}}</th>
                          <th>{{trans('contact_us.action')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>
                        <tr>
                         <th>{{trans('contact_us.id')}}</th>
                          <th>{{trans('contact_us.contact_name')}}</th>
                          <th>{{trans('contact_us.contact_email')}}</th>
                          <th>{{trans('contact_us.contact_reason')}}</th>
                          <th>{{trans('contact_us.contact_message')}}</th>
                          <th>{{trans('contact_us.status')}}</th>
                          <th>{{trans('contact_us.action')}}</th>
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
<div class="modal fade contact-view" id="contact-edit" data-backdrop="static" >
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <form id="edit_contact_form">
                <div class="modal-header">
                    <h5 class="modal-title" id="id_title_edit">{{trans('contact_us.update_status')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"> 
                    
                        @csrf
                        <div class="contact">
                            <div class="head">
                                              
                            </div>
                            
                            <input type="hidden" name="id" id="req_id" value="">
                            <fieldset class="form-group">
                                <label for="">{{trans('contact_us.status')}}</label>
                                <select class="form-control" id="edit_status" name="status" required>
                                    <option value="">{{trans('contact_us.please_select')}}</option>
                                    <option value="acknowledged" id="edit_ack_status">{{trans('contact_us.acknowledged')}}</option>
                                    <option value="resolved">{{trans('contact_us.resolved')}}</option>
                                </select>
                            </fieldset>
                             
                             <div class="form-group">
                                <label>{{trans('contact_us.contact_message')}}</label>
                                <textarea class="form-control" rows="4" placeholder="" readonly id="message_edit">I have a problem.</textarea>
                            </div>
                            <div class="form-group">
                                <label>{{trans('contact_us.comment')}}</label>
                                <textarea class="form-control" rows="4" placeholder="" name="comment" id="admin_comment_edit" maxlength="1000" required ></textarea>
                            </div>                                                
                        </div>
                    
                </div>
                <div class="modal-footer text-center">
                    <button type="submit" class="btn btn-primary">{{trans('contact_us.update_status')}}</button>
                </div>
            </form>
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
    fill_datatable();
  });

  $(document).on('change','.ticket_status',function(){
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
              fill_datatable();
            },
            error: function (data) {
              toastr.error(data.error);
            }
        })
    })
  var lang_url = '';

  if ('{{config("app.locale")}}' == 'ar'){
    lang_url ="{{asset('admin_assets/custom/data_tables/Arabic.json')}}";
  }

  function fill_datatable() {

    $('.data_table_ajax').DataTable({
      aaSorting : [[0, 'desc']],
      scrollY: 300, 
      scrollX: true,
      dom: 'Blfrtip',
      lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
      pageLength: 10,
      buttons: [
        // {
        //     extend: 'excel',
        //     text: '<span class="fa fa-file-excel-o"></span> {{trans("common.export")}}',
        //     exportOptions: {
        //         modifier: {
        //             search: 'applied',
        //             order: 'applied'
        //         },
        //         columns: [0, 1, 2]
        //     },
        // },
        // {
        //     extend: 'print',
        //     text: '<i class="fa fa-print" aria-hidden="true"></i> {{trans("common.print")}}',
        //     autoPrint: true,
        //     exportOptions: {
        //         modifier: {
        //             search: 'applied',
        //             order: 'applied'
        //         },
        //         columns: [0, 1, 2, 3]
        //     },
        // }

      ],
      processing: true,
      serverSide: true,
      serverMethod:'POST',
      processing: true,
      bDestroy: true,
      language: {
          processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
           url: lang_url,
              emptyTable: "{{trans('datatable.empty_message')}}",
              zeroRecords:"{{trans('datatable.no_message')}}",
        },
      ajax: {
          url: "{{route('dt_contact_us')}}",
          data: {"_token": "{{csrf_token()}}"},
      },
  
      columns: [

         { data: 'id'},
         { data: 'name',orderable:false},
         { data: 'email',orderable:false},
         { data: 'reason_id',
            mRender : function(data, type, row) {
        
              return row['reason_name'];
            },orderable: false 
          },
         { data: 'message',orderable:false},
         { data: 'status',
            mRender : function(data, type, row) {
        
              return row['status_name'];
            },orderable: false 
          },
         // { data: 'status',
         //    mRender : function(data, type, row) {
         //      var status = data;
              
         //      pending_selected = '';
         //      ack_selected = '';
         //      resolved_selected = '';
    
         //      if (status == 'pending'){

         //        pending_selected = 'selected';

         //        return '<select class="ticket_status form-control" style="width:150px;" id="'+row["id"]+'"><option value="pending"'+pending_selected+'>{{trans("contact_us.pending")}}</option><option value="acknowledged"'+ack_selected+'>{{trans("contact_us.acknowledged")}}</option><span class="loading" style="visibility: hidden;"><i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">{{trans("common.loading")}}</span></span>';
         //      }else if(status == 'acknowledged') {

         //        ack_selected = 'selected';

         //        return '<select class="ticket_status form-control" style="width:150px;" id="'+row["id"]+'"><option value="acknowledged"'+ack_selected+'>{{trans("contact_us.acknowledged")}}</option><option value="resolved"'+resolved_selected+'>{{trans("contact_us.resolved")}}</option><span class="loading" style="visibility: hidden;"><i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">{{trans("common.loading")}}</span></span>';
         //      }else{
         //        return "{{trans('contact_us.resolved')}}";
         //      }
         //    },orderable: false 
         //  },
          { 
            mRender : function(data, type, row) {
                if(row['status'] == 'resolved'){
                  return '<a class="btn" href="'+row["show"]+'"><i class="fa fa-eye"></i></a>';  

                }else{
                  return '<a class="btn open_edit_modal" data-id="'+row["id"]+'"data-status="'+row["status"]+'"  data-message="'+row["message"]+'" data-comment="'+row["comment"]+'" ><i class="feather icon-edit"></i></a><a class="btn" href="'+row["show"]+'"><i class="fa fa-eye"></i></a>';  

                }
              }, orderable: false, searchable: false
          },
       
           
        ]
    });
  }

  //Open Edit Modal
    $(document).on('click','.open_edit_modal', function(){
    
      $('#req_id').val($(this).data('id'));
      $('#message_edit').html($(this).data('message'));
      $('#admin_comment_edit').html($(this).data('comment'));

      var status = $(this).data('status');

      if(status == 'acknowledged'){
        $('#edit_ack_status').hide();
        $('#edit_status').val('').select();
      }else{
        $('#edit_ack_status').show();
        $('#edit_status').val('').select();
      }
     
      $('#contact-edit').modal('show');
    });

    //Update Contact Query
    $('#edit_contact_form').on('submit', function(e){
      e.preventDefault();

      var status = $('#edit_status').val();
      if(status == 'resolved'){
        var confirm = status_alert();
        if(confirm){
           $.ajax({
              type:"POST",
              url:"{{route('contact_status')}}",
              data: $(this).serialize(),
              success:function(response){
                  if(response.type == 'success'){
                      toastr.success(response.message);
                      $('#contact-edit').modal('hide');
                      fill_datatable();
                  }else{
                      toastr.error(response.message);
                  }
              },
              error:function(response){
                  toastr.error(response.message);
              }
            });
        }
      }else{

         $.ajax({
            type:"POST",
            url:"{{route('contact_status')}}",
            data: $(this).serialize(),
            success:function(response){
                if(response.type == 'success'){
                    toastr.success(response.message);
                    $('#contact-edit').modal('hide');
                    fill_datatable();
                }else{
                    toastr.error(response.message);
                }
            },
            error:function(response){
                toastr.error(response.message);
            }
          }); 
      }
     
    });
    function status_alert(status) {
    
      if(confirm("{{trans('contact_us.confirm_status')}}")){
        return true;
      } else {
        return false;
      }
    
  }
</script>
@endsection