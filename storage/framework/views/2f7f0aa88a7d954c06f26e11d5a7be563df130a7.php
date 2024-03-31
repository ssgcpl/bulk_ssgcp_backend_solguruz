
<?php $__env->startSection('vendor_css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/vendors/css/tables/datatable/datatables.min.css')); ?>">
<style type="text/css">
  td form{
    display: inline;
  }
  .dt-buttons{margin-right: 10px}
  .select2-selection {padding: 0px 5px !important}
  .block { color:red;  }
</style>
<?php $__env->stopSection(); ?>   
<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?> 

<?php $__env->startSection('content'); ?>
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-overlay"></div>
  <div class="header-navbar-shadow"></div>
  <div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0"><?php echo e(trans('sub_admin.admin_heading')); ?></h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(trans('common.home')); ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?php echo e(trans('sub_admin.plural')); ?>

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
                <h4 class="card-title"><?php echo e(trans('sub_admin.title')); ?></h4>
                   <div class="col-md-3">
                      <div class="form-group">
                        <select id="status_type" class ="form-control">
                        <option value='all'><?php echo e(trans('common.all')); ?></option>
                        <option value="active"><?php echo e(trans('common.active')); ?></option>
                        <option value="inactive"><?php echo e(trans('common.inactive')); ?></option>
                        </select>
                      </div>
                    </div>
              
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sub-admin-create')): ?>
                  <div class="box-tools pull-right">
                    <a href="<?php echo e(route('sub_admin.create')); ?>" class="btn btn-success pull-right">
                    <?php echo e(trans('sub_admin.add_new')); ?>

                    </a>
                  </div>
                <?php endif; ?>
              </div>
              <div class="card-content">
                <div class="card-body card-dashboard">
                  <div class="table-responsive">
                    <table class="table zero-configuration data_table_ajax">
                      <thead>
                        <tr>
                          <th><?php echo e(trans('common.id')); ?></th>
                          <th><?php echo e(trans('sub_admin.name')); ?></th>
                          <th><?php echo e(trans('sub_admin.email')); ?></th>
                          <th><?php echo e(trans('sub_admin.mobile_number')); ?></th>
                          <th><?php echo e(trans('sub_admin.role')); ?></th>
                          <th width="15%"><?php echo e(trans('common.status')); ?></th>
                          <th><?php echo e(trans('sub_admin.created_date')); ?></th>
                          <th><?php echo e(trans('sub_admin.updated_date')); ?></th>
                          <th><?php echo e(trans('common.action')); ?></th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th><?php echo e(trans('common.id')); ?></th>
                          <th><?php echo e(trans('sub_admin.name')); ?></th>
                          <th><?php echo e(trans('sub_admin.email')); ?></th>
                          <th><?php echo e(trans('sub_admin.mobile_number')); ?></th>
                          <th><?php echo e(trans('sub_admin.role')); ?></th>
                          <th  width="15%"><?php echo e(trans('common.status')); ?></th>
                          <th><?php echo e(trans('sub_admin.created_date')); ?></th>
                          <th><?php echo e(trans('sub_admin.updated_date')); ?></th>
                          <th><?php echo e(trans('common.action')); ?></th>
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page_js'); ?>
<!-- BEGIN: Page Vendor JS-->
<script src="<?php echo e(asset('admin_assets/app-assets/vendors/js/tables/datatable/datatables.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin_assets/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin_assets/app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin_assets/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js')); ?>"></script>
<!-- END: Page Vendor JS-->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script src="<?php echo e(asset('admin_assets/custom/data_tables/export/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin_assets/custom/data_tables/export/jszip.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin_assets/custom/data_tables/export/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin_assets/custom/data_tables/export/buttons.print.min.js')); ?>"></script>
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
            url: "<?php echo e(route('status_sub_admin')); ?>",
            data: {
                    "status": status, 
                    "id" : id,  
                    "_token": "<?php echo e(csrf_token()); ?>"
                  },
            beforeSend: function () {
                element.next('.loading').css('visibility', 'visible');
            },
            success: function (data) {
              setTimeout(function() {
                    element.next('.loading').css('visibility', 'hidden');
                }, delay);
              location.reload();
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

</script>
<script type="text/javascript">
  $(document).ready(function(){

    fill_datatable();

    function fill_datatable() {

      $('.data_table_ajax').DataTable({ 
        //"scrollY": 300, "scrollX": true,
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
              processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"><?php echo e(trans("common.loading")); ?></span> '},
        ajax: {
            url: "<?php echo e(route('dt_sub_admin')); ?>",
            data: {"_token": "<?php echo e(csrf_token()); ?>"  , 'status_type':$("#status_type").val()},
           //success:function(data) { console.log(data); },
            error:function(data){ console.log(data); },
        },
        columns: [

           { data: 'id'},
           { data: 'first_name',orderable: false},
           { data: 'email',orderable: false},
           { data: 'mobile_number',orderable: false},
           { data: 'role',orderable: false},
           { data: 'status',
              mRender : function(data, type, row) {

                  var status=data;

                  if(status=='active'){
                    type = "checked";
                  }else{
                    type = '';
                  }
                  var status_label = status.charAt(0).toUpperCase() +""+status.slice(1); 
                  
                  return '<label>'+status_label+'</label><div class="custom-control custom-switch custom-switch-success mr-2 mb-1"><input type="checkbox"'+type+' + class="status custom-control-input" id="customSwitch'+row["id"]+'" data_id="'+row["id"]+'"><label class="custom-control-label" for="customSwitch'+row["id"]+'"></label></div>';


                  /*  var status=data;

                    if(status=='active'){

                      type = "selected";
                      data = '';

                    }else{

                      data = 'selected';
                      type = '';

                    }

                   return '<select class="status form-control" id="'+row["id"]+'"><option value="active"'+type+'>'+'<?php echo e(trans("common.active")); ?>'+'</option><option value="inactive"'+data+'>'+'<?php echo e(trans("common.inactive")); ?>'+'</option></select><span class="loading" style="visibility: hidden;"><i class="fa fa-spinner fa-spin fa-1x fa-fw"></i><span class="sr-only">'+'<?php echo e(trans("common.loading")); ?>'+'</span></span>';
           */
                },orderable: false
            },
            { data: 'created_date',orderable: false},
            { data: 'updated_date',orderable: false},
           { 
              mRender : function(data, type, row) {
                  var block_status=row['block'];

                    if(block_status=='1'){

                      data = "block";


                    }else{

                      data = '';
                      
                    }

                return '<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("sub-admin-edit")): ?><a class="btn" href="'+row["edit"]+'"><i class="fa fa-edit"></i></a><?php endif; ?><a class="btn" href="'+row["show"]+'"><i class="fa fa-eye"></i></a><?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("sub_admin-edit")): ?><a class="btn" href="'+row["activity_log"]+'"><i class="fa fa-cog"></i></a><?php endif; ?>';
               }, orderable: false, searchable: false
            },
          ]
      });
    }
    
  $('#status_type').on('change', function (event) {

      $('.data_table_ajax').DataTable().destroy();
      fill_datatable();  
    })


  });
  


</script>
<script>
    function status_alert(status) {
    if(status == 'active')
    {
      if(confirm("<?php echo e(trans('sub_admin.confirm_status_active')); ?>")){
        return true;
      }else{
        return false;
      }
    }
      else if(status =='inactive')
      {
        if(confirm("<?php echo e(trans('sub_admin.confirm_status_inactive')); ?>")){
        return true;
          }else{
            return false;
          }
      }
    }
</script>
<script type="text/javascript">
    function block_alert(block_status,id)
    {
      if(block_status == '0')
      {
      if(confirm("<?php echo e(trans('sub_admin.confirm_block_admin')); ?>")){
         block_status = '1';
         block_status_change(block_status,id);
      }else{
        return false;
      }
      }
      else if(block_status =='1')
      {
        if(confirm("<?php echo e(trans('sub_admin.confirm_unblock_admin')); ?>")){
           block_status = '0';
             block_status_change(block_status,id);
          }else{
            return false;
          }
      }
    }


</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/ssgc-bulk/resources/views/admin/sub_admin/index.blade.php ENDPATH**/ ?>