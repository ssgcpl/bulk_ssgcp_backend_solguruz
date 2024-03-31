
<?php $__env->startSection('vendor_css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/vendors/css/tables/datatable/datatables.min.css')); ?>">
<style type="text/css">
  td form{
    display: inline;
  }
  .dt-buttons{margin-right: 10px}
  .select2-selection {padding: 0px 5px !important}
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
            <h2 class="content-header-title float-left mb-0">
              <?php echo e(trans('roles.heading')); ?>

            </h2>
            <div class="breadcrumb-wrapper col-12">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a href="<?php echo e(route('home')); ?>"><?php echo e(trans('common.home')); ?></a>
                </li>
                <li class="breadcrumb-item active">
                  <?php echo e(trans('roles.plural')); ?>

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
                <h4 class="card-title"><?php echo e(trans('roles.title')); ?></h4>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role-create')): ?>
                  <div class="box-tools pull-right">
                    <a href="<?php echo e(route('roles.create')); ?>" class="btn btn-success pull-right">
                    <?php echo e(trans('roles.add_new')); ?>

                    </a>
                  </div>
                <?php endif; ?>
              </div>
              <div class="card-content">
                <div class="card-body card-dashboard">

                  <div class="table-responsive">
                    <table id="example2" class="table zero-configuration data_table_ajax" width="100%">
                    <thead>
                      <tr>
                        <th><?php echo e(trans('common.id')); ?></th>
                        <th><?php echo e(trans('roles.name')); ?></th>
                        <th><?php echo e(trans('common.status')); ?></th>
                        <!-- <th><?php echo e(trans('role.role_title')); ?></th> -->
                        <th><?php echo e(trans('common.action')); ?></th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    <tr>
                      <th><?php echo e(trans('common.id')); ?></th>
                      <th><?php echo e(trans('roles.name')); ?></th>
                      <th><?php echo e(trans('common.status')); ?></th>
                      <!-- <th><?php echo e(trans('role.role_title')); ?></th> -->
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

<script type="text/javascript">

   fill_datatable();

    function fill_datatable() {
      $('.data_table_ajax').DataTable({ 
        "scrollY": 300, "scrollY": true,
        aaSorting : [[0, 'desc']],
        dom: 'Blfrtip',
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        pageLength: 10,
        processing: true,
        buttons: [
        ],
        serverSide: true,
        serverMethod:'POST',
        processing: true,
          bDestroy: true,
        language: {
              processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"><?php echo e(trans("common.loading")); ?></span> '},
        ajax: {
            url: "<?php echo e(route('dt_roles')); ?>",
            data: {"_token": "<?php echo e(csrf_token()); ?>"  , 'status_type':$("#status_type").val()},
           //success:function(data) { console.log(data); },
            error:function(data){ console.log(data); },
        },
        columns: [
           { data: 'id'},
           { data: 'name',orderable: false},
           { data: 'status',
              mRender : function(data, type, row) {
                var status = data;
                if(status=='active'){
                  type = "checked";
                } else {
                  type = '';
                }
                var status_label = status.charAt(0).toUpperCase() +""+status.slice(1);   
                return '<label>'+status_label+'</label><div class="custom-control custom-switch custom-switch-success mr-2 mb-1"><input type="checkbox"'+type+' + class="status custom-control-input" id="customSwitch'+row["id"]+'" data_id="'+row["id"]+'"><label class="custom-control-label" for="customSwitch'+row["id"]+'"></label></div>';
              },orderable: false
            },
            { 
              mRender : function(data, type, row) {  
                return '<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("role-edit")): ?><a class="" href="'+row["edit"]+'"><i class="fa fa-edit"></i></a><?php endif; ?> <a class="" href="'+row["show"]+'"><i class="fa fa-eye"></i></a>';
              }, orderable: false, searchable: false
            },
          ]
        });
      }
  $(document).on('change','.status',function(){
    var status = this.checked ? 'active' : 'inactive';
    var confirm = status_alert(status);
    if(confirm){
      var id = $(this).attr('data_id');
      var delay = 500;
      var element = $(this);
      $.ajax({
        type:'post',
        url: "<?php echo e(route('status_roles')); ?>",
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
    } else {
      location.reload();
    }
  })
  function status_alert(status) {
    if(status == 'active'){
      if(confirm("<?php echo e(trans('roles.confirm_status_active')); ?>")){
        return true;
      } else {
        return false;
      }
    } else if(status =='inactive') {
      if(confirm("<?php echo e(trans('roles.confirm_status_inactive')); ?>")){
        return true;
      } else {
        return false;
      }
    }
  }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/ssgc-bulk/resources/views/admin/roles/index.blade.php ENDPATH**/ ?>