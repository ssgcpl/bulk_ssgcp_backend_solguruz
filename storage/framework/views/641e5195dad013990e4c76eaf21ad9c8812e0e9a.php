
<?php $__env->startSection('vendor_css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('admin_assets/app-assets/vendors/css/tables/datatable/datatables.min.css')); ?>">
<style type="text/css">
  td form{
    display: inline;
  }
  .dt-buttons{margin-right: 10px}
  .select2-selection {padding: 0px 5px !important}
  .order_summary { background-color:#ececec; padding: 10px;}
   .note_div { max-height: 350px; overflow: auto;}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<style type="text/css">
   .cover_images {width: 40%}
</style>
<?php echo $__env->make('layouts.admin.elements.filepond_css', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-header row">
      <div class="content-header-left col-md-9 col-12 mb-2">
          <div class="row breadcrumbs-top">
              <div class="col-12">
                  <h2 class="content-header-title float-left mb-0"><?php echo e(trans('order_return.heading')); ?> </h2>
                  <div class="breadcrumb-wrapper col-12">
                      <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a
                                  href="<?php echo e(route('home')); ?>"><?php echo e(trans('common.home')); ?></a>
                          </li>
                          <li class="breadcrumb-item"><a
                                  href="<?php echo e(route('order_return.index')); ?>"><?php echo e(trans('order_return.plural')); ?></a></li>
                          <li class="breadcrumb-item active"><?php echo e(trans('order_return.update')); ?>

                          </li>
                      </ol>
                  </div>
              </div>
          </div>
      </div>
    </div>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <b><?php echo e(trans('common.whoops')); ?></b>
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>
    <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form">
      <div class="row match-height">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <?php echo e(trans('order_return.details')); ?>

                </h4>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('order-list')): ?>
                    <a href="<?php echo e(route('order_return.index')); ?>" class="btn btn-success">
                        <i class="fa fa-arrow-left"></i>
                        <?php echo e(trans('common.back')); ?>

                    </a>
                <?php endif; ?>
            </div>
            <div class="card-content">
              <div class="card-body card-dashboard">
                <form method="POST" id="createForm" accept-charset="UTF-8" enctype="multipart/form-data">
                  <input type="hidden" name="order_return_id" id="order_return_id" value="<?php echo e($order_return->id); ?>">
                    <?php echo csrf_field(); ?>
                     <div class="form-body">
                      <div class="row">
                        <div class="col-md-2">
                          <span><b>Order ID</b> - <?php echo e($order_return->id); ?></span><br>
                          <span><b>Customer ID</b> - <?php echo e($order_return->user_id); ?></span>
                        </div>
                        <div class="col-md-4">
                          <span><b>Order Date/Time</b> - <?php echo e(date("d-m-Y h:i A",strtotime($order_return->returned_at))); ?></span>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class=""><?php echo e(trans('order_return.order_return_status')); ?></label>
                             <select id="order_return_status" class = "form-control" name=''>
                                <option value=''><?php echo e(trans('common.select')); ?></option>
                                <option value="return_placed" <?php echo e(@($order_return->order_status == 'return_placed') ? 'selected':''); ?>><?php echo e(trans('order_return.return_placed')); ?></option>
                                <option value="dispatched" <?php echo e(@($order_return->order_status == 'dispatched') ? 'selected':''); ?>><?php echo e(trans('order_return.dispatched')); ?></option>
                                <option value="in_review" <?php echo e(@($order_return->order_status == 'in_review') ? 'selected':''); ?>><?php echo e(trans('order_return.in_review')); ?></option>
                                <option value="accepted" <?php echo e(@($order_return->order_status == 'accepted') ? 'selected':''); ?>><?php echo e(trans('order_return.accepted')); ?></option>
                                <option value="rejected" <?php echo e(@($order_return->order_status == 'rejected') ? 'selected':''); ?>><?php echo e(trans('order_return.rejected')); ?></option>
                              </select>
                          </div>
                        </div>
                        <div class="col-md-2">
                            <label></label>
                            <div class="form-group">
                            <a href="javascript:void(0)" id="update_order_return_status" class="form-control btn btn-success">Update
                            </a></div>
                        </div>
                      </div>
                      <div class="row"> 
                     <div class="col-md-12">
                    <h4>Return Ordered Items</h4>
                     </div>
                     </div>
                    <div class="table-responsive">
                      <table class="table zero-configuration data_table_ajax">
                        <thead>
                          <tr>
                            <th><?php echo e(trans('common.id')); ?></th>
                            <th><?php echo e(trans('order_return.product_name')); ?></th>
                            <th><?php echo e(trans('order_return.product_image')); ?></th>
                            <th><?php echo e(trans('order_return.mrp')); ?></th>
                            <th><?php echo e(trans('order_return.sale_price')); ?></th>
                            <th><?php echo e(trans('order_return.ordered_qty')); ?></th>
                            <th><?php echo e(trans('order_return.returnable_qty')); ?></th>
                            <th><?php echo e(trans('order_return.returned_qty')); ?></th>
                            <th><?php echo e(trans('order_return.refused_qty')); ?></th>
                            <th><?php echo e(trans('order_return.accepted_qty')); ?></th>
                            <th><?php echo e(trans('order_return.refundable_amount')); ?></th>
                            <th><?php echo e(trans('order_return.weight')); ?></th>
                            <th><?php echo e(trans('order_return.stock')); ?></th>
                            <th><?php echo e(trans('common.action')); ?></th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                          <tr>
                            <th><?php echo e(trans('common.id')); ?></th>
                            <th><?php echo e(trans('order_return.product_name')); ?></th>
                            <th><?php echo e(trans('order_return.product_image')); ?></th>
                            <th><?php echo e(trans('order_return.mrp')); ?></th>
                            <th><?php echo e(trans('order_return.sale_price')); ?></th>
                            <th><?php echo e(trans('order_return.ordered_qty')); ?></th>
                            <th><?php echo e(trans('order_return.returnable_qty')); ?></th>
                            <th><?php echo e(trans('order_return.returned_qty')); ?></th>
                            <th><?php echo e(trans('order_return.refused_qty')); ?></th>
                            <th><?php echo e(trans('order_return.accepted_qty')); ?></th>
                            <th><?php echo e(trans('order_return.refundable_amount')); ?></th>
                            <th><?php echo e(trans('order_return.weight')); ?></th>
                            <th><?php echo e(trans('order_return.stock')); ?></th>  
                            <th><?php echo e(trans('common.action')); ?></th>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                                         <!-- <div class="form-body"> -->
                      <br>
                      <div class="row">
                        <div class="col-md-7"></div>
                        <div class="col-md-2">
                          <div class="form-group">
                            <a href="javascript:void(0)" id="add_return_item" class="form-control btn btn-success">Add Item
                            </a>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <a href="javascript:void(0)" id="update_order_return" class="form-control btn btn-success">Update Order
                            </a>
                          </div>
                        </div>
                      </div>
                      <div class="order_summary"> 
                        <h5>Order Return Summary</h5>
                        <div class="row">
                          <div class="col-md-4">
                              <p class="details"><b>Total MRP :</b>  <?php echo e($order_return->total_mrp); ?><br>
                                <b>Total Sale Price :</b> <?php echo e($order_return->total_sale_price); ?> <br>
                                <b>Payment via : </b><?php echo e(($order_return->payment_type)? ($order_return->payment_type):'Offline'); ?><br>
                                <b>Total Accepted Quantity :</b> <?php echo e($order_return->accepted_quantity); ?><br>
                                <b>Total Rejected Quantity :</b> <?php echo e($order_return->rejected_quantity); ?><br>
                                <b>Total Weight (in KG) : </b><?php echo e(number_format($order_return->total_weight,'2','.','.')); ?><br>
                               <b>Total Returnable Amount : </b><?php echo e($order_return->accepted_sale_price); ?><br>
                              </p>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                           <b>Courier Name</b><br>
                           <!--  <input type="text" class="form-control" id="courier_name" name="courier_name" value="<?php echo e($order_return->courier_name); ?>" readonly> -->
                           <?php if($order_return->receipt_image != null): ?>
                           <span><?php echo e($order_return->courier_name); ?></span>
                           <?php else: ?> 
                           <span>-</span>
                           <?php endif; ?>
                           </div>
                           <div class="form-group">
                            <?php if($order_return->receipt_image != null): ?>
                           <a href="<?php echo e(asset($order_return->receipt_image)); ?>" target="_blank"> <img src="<?php echo e(asset($order_return->receipt_image)); ?>" width="30%" height="30%"></a>
                            <?php endif; ?>
                           </div>
                          </div>
                          <div class="col-md-4">
                          <div class="form-group">
                            <label>Tracking Number</label>
                            <input type="text" class="form-control" id="tracking_number" name="tracking_number" value="<?php echo e($order_return->tracking_number); ?>" disabled="">
                          </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-9"></div>
                          <div class="col-md-3">
                                <div class="form-group">
                                <a href="javascript:void(0)" id="send_return_update" class="form-control btn btn-success">Update 
                                </a></div>
                          </div>
                        </div>
                      </div>
                <!-- </div> -->
           
                  <div class="modal-footer">
                  </div>
                </form>
                  <h4>Notify User</h4>
                  <form method="POST" id="notifyUserForm" accept-charset="UTF-8">
                      <?php echo csrf_field(); ?>
                      <input type="hidden" name="order_return_id" id="order_return_id" value="<?php echo e($order_return->id); ?>">
                       
                    <div class="row">
                       <div class="col-md-6">
                        <div class="form-group">
                          <label>Transaction ID</label>
                          <input type="text" class="form-control" id="transaction_id" name="transaction_id">
                        </div>
                        <div class="form-group">
                          <label>Payment Status</label>
                          <select class="form-control" id="payment_status" name="payment_status">
                            <option value="">Select</option>
                            <option value="refunded">Refunded</option>
                            <option value="pending">Pending</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label>Customer Note</label>
                          <input type="text" class="form-control" id="customer_note" name="customer_note">
                        </div>
                        <div class="form-group">
                          <label>Admin Note</label>
                          <input type="text" class="form-control" id="admin_note" name="admin_note">
                        </div>
                        <div class="form-group">
                           <input type="submit" class="btn btn-success" id="update_return_note" name="update_return_note" value="Update">
                        </div>
                        </div>
                        <div class="col-md-6 note_div">
                          <h4>Note Log</h4>
                            <?php $__currentLoopData = $order_return_notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order_return_note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <p class="details">
                              <?php if($order_return_note->customer_note !=''): ?>
                                <b><u>Customer Note  <?php echo e(date('d-m-Y h:i A',strtotime($order_return_note->created_at))); ?> by <?php echo e(App\Models\User::find($order_return_note->added_by)->first_name); ?></u></b><br>
                                <span><?php echo e($order_return_note->customer_note); ?></span><br>
                                <span>Order No.<?php echo e($order_return_note->order_return_id); ?>  transaction ID  <?php echo e(@($order_return_note->transaction_id) ? $order_return_note->transaction_id : '-'); ?> and payment status is  <?php echo e(@($order_return_note->payment_status) ? $order_return_note->payment_status : '-'); ?> </span><br><br>
                              <?php endif; ?>
                              <?php if($order_return_note->admin_note !=''): ?>
                                <b><u>Admin Note  <?php echo e(date('d-m-Y h:i A',strtotime($order_return_note->created_at))); ?> by <?php echo e(App\Models\User::find($order_return_note->added_by)->first_name); ?></u></b><br>
                                <span><?php echo e($order_return_note->admin_note); ?></span><br>
                                <span>Order No.<?php echo e($order_return_note->order_return_id); ?>  transaction ID  <?php echo e(@($order_return_note->transaction_id) ? $order_return_note->transaction_id : '-'); ?> and payment status is  <?php echo e(@($order_return_note->payment_status) ? $order_return_note->payment_status : '-'); ?> </span>
                              <?php endif; ?>
                            </p>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          
                        </div>
                    </div>
                  </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  </div>
</div>

<!-- update quantity popup -->
<div class="modal" tabindex="-1" role="dialog" id="update_rejected_qty_popup">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post">
        <?php echo csrf_field(); ?>
        <div class="modal-body">
          <input type="hidden" class="" id="order_return_item_id" name="order_return_item_id" >
          <label>Enter rejected Quantity</label>
          <input type="text" class="form-control numberonly" id="edit_rejected_qty" name="edit_rejected_qty">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="update_rejected_qty">Update</button>
         </div>
      </form>
    </div>
  </div>
</div>


<!-- add more item into order  popup-->
<div class="modal" tabindex="-1" role="dialog" id="add_more_return_item_popup">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post">
        <?php echo csrf_field(); ?>
        <div class="modal-body">
          <input type="hidden" class="" id="order_return_id" name="order_return_id" value="<?php echo e($order_return->id); ?>">
          <input type="hidden" class="" id="order_return_user_id" name="order_return_user_id" value="<?php echo e($order_return->user_id); ?>">
          
          <div id="products_div"></div>
          <label>Select product</label>
          <select name="product_id" id="product_id" class ="select2 form-control">
          </select>
          <label>Enter Returned Quantity</label>
          <input type="text" class="form-control numberonly" id="returned_quantity" name="returned_quantity">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="add_more_return_item_btn">Add</button>
         </div>
      </form>
    </div>
  </div>
</div>


<!-- verify quantity popup -->
<div class="modal" tabindex="-1" role="dialog" id="verify_qty_popup">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="verifyQtyForm">
        <?php echo csrf_field(); ?>
        <div class="modal-body">
          <h4>Verify Qty</h4>
          <p>Total Returnable Qty :<span id="returnable_qty"></span><br>
          Total Returned Qty : <span id="returned_qty"></span></p>
          <input type="hidden" class="" id="prod_id" name="prod_id" >
          <input type="hidden" class="" id="return_qty" name="return_qty" >
          <input type="hidden" class="" id="order_return_id" name="order_return_id" value="<?php echo e($order_return->id); ?>">
          <input type="hidden" class="" id="order_returnitem_id" name="order_returnitem_id" value="">
          <input type="hidden" class="" id="verified_barcodes" name="verified_barcodes" >
          <label>Enter Barcode</label>
          <input type="text" class="form-control" id="barcode" name="barcode">
          <label>Total Count</label>
          <input type="text" class="form-control numberonly" value="0" id="total_count" name="total_count">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="verify_qty_btn">Verify</button>
         </div>
      </form>
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
    $(document).ready(function() {
    
    fill_datatable();
    function fill_datatable() {
      var order_return_id = $("#order_return_id").val();
      $('.data_table_ajax').DataTable({ 
        aaSorting : [[0, 'desc']],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        pageLength: 10,
        "scrollY": true, "scrollX": true,
        processing: true,
        serverSide: true,
        serverMethod:'POST',
        processing: true,
        bDestroy: true,
        // columnDefs: [
        //     { width: 300, targets: 13 },
            
        // ],
        // fixedColumns: false,
        language: {
              processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"><?php echo e(trans("common.loading")); ?></span> '},
        ajax: {
            url: "<?php echo e(route('dt_order_return_items')); ?>",
            data: {
                    "_token": "<?php echo e(csrf_token()); ?>",
                    'order_return_id':order_return_id,
                   },
           //success:function(data) { console.log(data); },
            error:function(data){ console.log(data); },
        },
        columns: [
           { data: 'id'},
           { data: 'product_name',orderable: false},
           { data: 'id',
            mRender : function(data, type, row) { 
                return row['product_image'];
              },orderable: false, searchable: false 
            },
           { data: 'mrp',orderable: false},
           { data: 'sale_price',orderable: false},
           { data: 'ordered_qty',orderable: false},
           { data: 'returnable_qty',orderable: false},
           { data: 'returned_qty',orderable: false},
           { data: 'refused_qty',orderable: false},
           { data: 'accepted_qty',orderable: false},
           { data: 'refundable_amount',orderable: false},
           { data: 'weight',orderable: false},
           { data: 'stock',orderable: false},
           {
            data: '',
                mRender : function(data, type, row) {
                // '+row['verify']+'
                return row['verify']+'<a href="'+row['view_product']+'"><i class="fa fa-eye"></i></a> <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("order-delete")): ?><a class="" href="#" onclick=" return delete_alert('+row["id"]+') "><i class="fa fa-trash"></i><form action="'+row["delete"]+'" method="post" id="form'+row["id"]+'"><?php echo method_field("delete"); ?><?php echo csrf_field(); ?></form></a><?php endif; ?>';
      
               }, orderable: false, searchable: false
            },
          ]
      });
    }

  
    $("#update_order_return_status").click(function(evt){
       var order_return_id = $("#order_return_id").val();
       var order_return_status = $("#order_return_status").val();
       evt.preventDefault();
       $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            }
          });
       $.ajax({
                  url: '<?php echo e(route("update_order_return_status")); ?>',
                  data: {
                          "order_return_id" : order_return_id,
                          "order_return_status":order_return_status,
                        },
                  type: "POST",
                  async:false,
                  beforeSend: function(xhr){
                      $('#update_order_return_status').prop('disabled',true);
                    },
                  success: function(response) {
                    if(response.error){
                      toastr.error(response.error);
                    }
                    else{
                     location.reload();
                    }

                  }
          });
    })

    $('body').on('click',".edit_rejected_qty",function(){
      var id = $(this).attr("id");
      $("#order_return_item_id").val(id);
      $("#edit_rejected_qty").val($("#rejected_qty_"+id).val());
      $("#update_rejected_qty_popup").modal("show");
    })

    $("#update_rejected_qty").click(function(evt){
       var order_return_item_id = $("#order_return_item_id").val();
       var rejected_qty = $("#edit_rejected_qty").val();
       if(rejected_qty == ''){
        toastr.error("Please enter refused qty");
        return false;
       }
       evt.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            }
          });
        $.ajax({
                  url: '<?php echo e(route("update_rejected_qty")); ?>',
                  data: {
                          "order_return_item_id" : order_return_item_id,
                          "rejected_qty":rejected_qty,
                        },
                  type: "POST",
                  async:false,
                  beforeSend: function(xhr){
                      $('#update_supp_qty').prop('disabled',true);
                    },
                  success: function(response) {
                    if(response.error){
                      toastr.error(response.error);
                    }
                    else{
                     toastr.success(response.success);
                     location.reload();
                    }

                  }
          });
    })

    $("#add_return_item").click(function(){
      $("#add_more_return_item_popup").modal("show");
      var order_return_id = $("#order_return_id").val();
      var order_return_user_id = $("#order_return_user_id").val();
     
       $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            }
          });
        $.ajax({
                  url: '<?php echo e(route("add_more_return_item")); ?>',
                  data: {'order_return_user_id':order_return_user_id,'order_return_id':order_return_id},
                  type: "POST",
                  async:false,
                  beforeSend: function(xhr){
                    },
                  success: function(response) {
                    if(response.success){
                      var html = '';
                      html ='<option value="">Select</option>'
                      $.each(response.products, function(key,value) {
                        html+='<option value="'+value.id+','+value.order_item_id+'"> '+value.id+','+value.name+','+value.sku_id+'</option>';
                      });
                      $("#product_id").html(html);
                   //   toastr.success(response.success);
                    }
                    else{
                       toastr.error(response.error);
                    }

                  }
          });
    });

    $(document).on("click","#add_more_return_item_btn",function(){
      var product_data = $("#product_id option:selected").val();
      var data = product_data.split(',');
      var product_id = data[0];
      var order_item_id = data[1];
      var order_return_id = $("#order_return_id").val();
      var returned_qty = $("#returned_quantity").val();
      var order_return_user_id = $("#order_return_user_id").val();
       $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            }
          });
      $.ajax({
                  url: '<?php echo e(route("add_order_return_item")); ?>',
                  data: { 'order_return_id' : order_return_id ,'order_item_id':order_item_id, 'product_id':product_id ,'order_return_user_id':order_return_user_id,'returned_quantity':returned_qty},
                  type: "POST",
                  async:false,
                  beforeSend: function(xhr){
                    },
                  success: function(response) {
                    if(response.success){
                      toastr.success(response.success);
                      location.reload();
                    }
                    else{
                       toastr.error(response.error);
                    }

                  }
          });
    })

 
     $(document).on("click","#update_order_return",function(){
          var order_return_id = $("#order_return_id").val();
           $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            }
          });
          $.ajax({

                  url: "<?php echo e(route('update_order_return_summary')); ?>",
                  data: {'order_return_id':order_return_id},
                  type: "POST",
                  dataType : 'json',
                  beforeSend: function(xhr){
                      $('#update_order_return').prop('disabled',true);
                      $('#update_order_return').text("<?php echo e(trans('common.updating')); ?>");
                    },
                  success: function(response) {
                      console.log(response);
                      if(response.success) {
                          toastr.success(response.success);
                          location.reload();
                      } else {
                          toastr.error(response.error);
                      }
                      $('#update_order_return').prop('disabled',false);
                      $('#update_order_return').text("<?php echo e(trans('common.submit')); ?>");
                  }
          });
    })

    $(document).on("click","#send_return_update",function(){
          var order_return_id = $("#order_return_id").val();
           $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            }
          });
          $.ajax({

                  url: "<?php echo e(route('notify_order_return_update')); ?>",
                  data: {'order_return_id':order_return_id},
                  type: "POST",
                  dataType : 'json',
                  beforeSend: function(xhr){
                      $('#send_return_update').prop('disabled',true);
                      $('#send_return_update').text("<?php echo e(trans('common.sending')); ?>");
                    },
                  success: function(response) {
                      console.log(response);
                      if(response.success) {
                          toastr.success(response.success);
                           location.reload();
                      } else {
                          toastr.error(response.error);
                      }
                      $('#send_return_update').prop('disabled',false);
                      $('#send_return_update').text("Update");
                  }
          });
    })

    $("#update_return_note").prop('disabled',true);
    $("#notifyUserForm input[type=text]").keyup(function(){
      if($("#payment_status").val()!='' || $("#transaction_id").val()!='' || $("#customer_note").val()!='' || $("#admin_note").val()!=''){
        $("#update_return_note").prop('disabled',false);
      }else {
        $("#update_return_note").prop('disabled',true);
      }
    })


    $('#notifyUserForm').submit(function(evt){
          // tinyMCE.triggerSave();
          evt.preventDefault();
           if($("#customer_note").val() == '' && $("#admin_note").val() == ''){
            toastr.error("Please add atleast one note");
            return false;
          }
          var form = $('#notifyUserForm')[0];
          var data = new FormData(form);

          $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            }
          });
           // console.log(data); return false;
          $.ajax({
                  url: "<?php echo e(route('notify_user_order_return')); ?>",
                  data: data,
                  type: "POST",
                  processData : false,
                  contentType : false,
                  dataType : 'json',
                  beforeSend: function(xhr){
                      $('#update_return_note').prop('disabled',true);
                      $('#update_return_note').val("<?php echo e(trans('common.updating')); ?>");
                    },
                  success: function(response) {
                      console.log(response);
                      if(response.success) {
                          toastr.success(response.success);
                           location.reload();
                      } else {
                          toastr.error(response.error);
                      }
                      $('#update_return_note').prop('disabled',false);
                      $('#update_return_note').val("<?php echo e(trans('common.update')); ?>");
                  }
          });
    }) 

      $(document).on('click','.verify',function(){
      var id=$(this).attr('id');
      var qty = $(this).attr('name').split(',');
      $("#prod_id").val(id);
      $("#returnable_qty").text(qty[0]);
      $("#returned_qty").text(qty[1]);
      $("#return_qty").val(qty[1]);
      $("#order_returnitem_id").val(qty[2]);
      $("#verify_qty_popup").modal("show");
    });

       
    $('#barcode').on('keypress', function(event) {
    if (event.keyCode === 13) { 
        var product_id = $("#prod_id").val();
        var barcode = $("#barcode").val();
        var order_return_id = $("#order_return_id").val();
        var count = $("#total_count").val();
           $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            }
          });
          $.ajax({
                  url: '<?php echo e(route("verify_product_barcodes")); ?>',
                  data: { 'product_id':product_id,'barcode':barcode ,'order_return_id':order_return_id},
                  type: "POST",
                  beforeSend: function(xhr){
                      $('#barcode').prop('disabled',true);
                    },
                  success: function(response) {
                    $("#barcode").val('');
                    if(response.error){
                      toastr.error(response.error);
                    }
                    else{
                        total_count = parseInt(count) + parseInt(response.count);
                        $("#total_count").val(total_count);
                    }
                    $('#barcode').prop('disabled',false);
                  }
          });
        }
    })


    $(document).on('click','#verify_qty_btn',function(evt){
          $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            }
          });
          evt.preventDefault();
          var form = $('#verifyQtyForm')[0];
          var data = new FormData(form);
           // console.log(data); return false;
          $.ajax({
                  url: "<?php echo e(route('verify_qty')); ?>",
                  data: data,
                  type: "POST",
                  processData : false,
                  contentType : false,
                  dataType : 'json',
                  beforeSend: function(xhr){
                      $('#action_btn').prop('disabled',true);
                      $('#action_btn').text("<?php echo e(trans('common.submitting')); ?>");
                    },
                  success: function(response) {
                      console.log(response);
                      if(response.success) {
                          toastr.success(response.success);
                           location.reload();
                      } else {
                          toastr.error(response.error);
                      }
                      $('#action_btn').prop('disabled',false);
                      $('#action_btn').text("<?php echo e(trans('common.submit')); ?>");
                  }
          });
    })

  })
  </script>
  <script type="text/javascript">
   function delete_alert(id) {
      if(confirm("<?php echo e(trans('common.confirm_delete')); ?>")){
        $("#form"+id).submit();
        return true;
      }else{
        return false;
      }
    }
</script>
 
  <?php echo $__env->make('layouts.admin.elements.filepond_js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/ssgc-bulk/resources/views/admin/order_return/edit.blade.php ENDPATH**/ ?>