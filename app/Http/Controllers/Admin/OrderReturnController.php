<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductBarcode;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderReturn;
use App\Models\OrderReturnItem;
use App\Models\OrderReturnNote;
use App\Models\VerifiedBarcode;
use App\Models\Category;
use App\Models\RelatedProduct;
use App\Models\BusinessCategory;
use Validator,Auth,DB,Hash,Artisan;
use App\Models\Helpers\CommonHelper;
use App\Mail\SendOrderReturnManuallyNotifyEmailCustomer;
use App\Mail\SendOrderUpdateNotifyEmailCustomer;
use App\Mail\SendOrderReturnUpdateNotifyEmailCustomer;
use Illuminate\Support\Facades\Mail;


class OrderReturnController extends Controller
{
    use CommonHelper;

    public function __construct()
    {
      $this->middleware('auth');
      $this->middleware('permission:order-return-list', ['only' => ['index','show']]);
      $this->middleware('permission:order-return-list', ['only' => ['create','store']]);
      $this->middleware('permission:order-return-list', ['only' => ['edit','update']]);
      $this->middleware('permission:order-return-list', ['only' => ['destroy']]);
    }

    public function index() {
      $page_title = trans('order_return.heading');
      $total_return_orders = OrderReturn::where('is_cart','0')->count();
      $total_sale = OrderReturn::select(
                          DB::raw("sum(total_sale_price) as total"),
                          )->first();
      $total_sale_price = $total_sale['total']; 
      return view ('admin.order_return.index',compact('page_title','total_return_orders','total_sale_price')); 
    }

    public function index_ajax(Request $request) {

        $query           =    OrderReturn::where('is_cart','0');
        $request         =    $request->all();
        $totalRecords    =    $query->count();
        $draw            =    $request['draw'];
        $row             =    $request['start'];
        $length = ($request['length'] == -1) ? $totalRecords : $request['length'];
        $rowperpage      =    $length; // Rows display per page
        $columnIndex     =    $request['order'][0]['column']; // Column index
        $columnName      =    $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder =    $request['order'][0]['dir']; // asc or desc
        $searchValue     =    $request['search']['value']; // Search value

       
        ## Total number of records without filtering
        $total = $query->count();
        $totalRecords = $total;

          ## from/to date filter
          $from_date       =    $request['start_date'];
          $to_date         =    $request['end_date'];
          if($from_date != null && $to_date != null){
            $from_date = date("Y-m-d",strtotime($from_date));
            $to_date = date("Y-m-d",strtotime($to_date));
            $query = $query->whereBetween('created_at',[$from_date.' 00:00:00',$to_date.' 23:59:59']);
          }

            ## Filter by visible to
            if ($request['user_type'] !=''){
          	  $user_type = $request['user_type'];
              $query = $query->where('requested_as',$user_type);
            }

            ## Filter by visible to
            if ($request['order_return_status'] !=''){
          	  $order_status = $request['order_return_status'];
              $query = $query->where('order_status',$order_status);
              
            }
        ## Total number of record with filtering
        $filter = $query;

        if($searchValue != ''){
        $filter = $filter->whereHas('user',function($q1) use($searchValue){
                           $q1->where('company_name','like','%'.$searchValue.'%');
                           $q1->orwhere('mobile_number','like','%'.$searchValue.'%');
                     })->orWhere(function($q)use ($searchValue) {
                            $q->where('id',$searchValue);
                        });
        }

        $filter_count = $filter->count();
        $totalRecordwithFilter = $filter_count;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();
        foreach ($empQuery as $emp) {

        	$emp['selected'] = '<input type="checkbox" class="mark" value="'.$emp->id.'" >';
          $emp['company_name'] = $emp->user->company_name;
          $emp['number'] = $emp->user->mobile_number;  
          $emp['user_type'] =ucfirst($emp->requested_as);
          $emp['order_return_status'] = ucfirst(str_replace('_',' ',$emp->order_status));

          if($emp->returned_at == Null) {
            $starttimestamp = strtotime($emp->created_at);
            $endtimestamp = strtotime(date("Y-m-d H:i:s"));
            $difference = round(abs($endtimestamp - $starttimestamp)/3600);
            if($difference <= 24) {
              $emp['created_date']  = $difference." Hours Ago";
            }else {
              $emp['created_date']  = date("d-m-Y h:i A",strtotime($emp->created_at));
            }
          }else {
            $starttimestamp = strtotime($emp->returned_at);
            $endtimestamp = strtotime(date("Y-m-d H:i:s"));
            $difference = round(abs($endtimestamp - $starttimestamp)/3600);
            if($difference <= 24) {
              $emp['created_date']  = $difference." Hours Ago";
            }else {
              $emp['created_date']  = date("d-m-Y h:i A",strtotime($emp->returned_at));
            }
          }

           $emp['total_price'] =$emp->total_sale_price;
         

        	## Set dynamic route for action buttons
            //$emp['show']= route("orders.show",$emp["id"]);
          	$emp['edit']= route("order_return.edit",$emp["id"]);
        	  $data[]=$emp;
        }
        
        ## Response
        $response = array(
          "draw" => intval($draw),
          "iTotalRecords" => $totalRecords,
          "iTotalDisplayRecords" => $totalRecordwithFilter,
          "aaData" => $data
        );

        echo json_encode($response);
    }

    public function create(){
        $page_title = trans('order_return.admin_heading');
       //  $order_return = [];
       // $customers = array();
       /* $order_customers = Order::where('is_cart','0')->groupBy('user_id')->get();
        foreach($order_customers as $order_customer){
          $customers[] = User::find($order_customer->user_id);
        }*/
        $customers = User::whereIn('user_type',['customer','dealer','retailer'])->get();

        return view('admin.order_return.create', compact('page_title','customers'));
    }

    public function edit(Request $request,$id){
      $page_title = trans('orders.show');
      $order_return = OrderReturn::find($id);
      $order_return_notes =  OrderReturnNote::where('order_return_id',$id)->orderBy('id','DESC')->get();
      $user_type = $order_return->user->user_type;
      
      return view('admin.order_return.edit',compact('page_title','order_return','order_return_notes','user_type'));
    }

    public function ajax_order_return_items(Request $request) {

        $query           =    OrderReturnItem::where('order_return_id',$request->order_return_id);
        $request         =    $request->all();
        $totalRecords    =    $query->count();
        $draw            =    $request['draw'];
        $row             =    $request['start'];
        $length = ($request['length'] == -1) ? $totalRecords : $request['length'];
        $rowperpage      =    $length; // Rows display per page
        $columnIndex     =    $request['order'][0]['column']; // Column index
        $columnName      =    $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder =    $request['order'][0]['dir']; // asc or desc
        $searchValue     =    $request['search']['value']; // Search value

       
        ## Total number of records without filtering
        $total = $query->count();
        $totalRecords = $total;

        ## Total number of record with filtering
        $filter = $query;

        if($searchValue != ''){
        $filter = $filter->whereHas('user',function($q1) use($searchValue){
                           $q1->where('company_name','like','%'.$searchValue.'%');
                           $q1->orwhere('mobile_number','like','%'.$searchValue.'%');
                     })->orWhere(function($q)use ($searchValue) {
                            $q->where('id',$searchValue);
                        });
        }

        $filter_count = $filter->count();
        $totalRecordwithFilter = $filter_count;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();
        foreach ($empQuery as $emp) {

          $emp['product_name']= $emp->product->get_name();
          $emp['product_image'] = ($emp->product->image) ? '<img src="'.asset($emp->product->image).'" width="80%" height="80%">' : '';
          $emp['mrp']= $emp->mrp;
          $emp['sale_price']= $emp->sale_price;
            $emp['ordered_qty'] = ($emp->order_item) ? $emp->order_item->supplied_quantity : $emp->accepted_quantity;
          if($emp->order_item) {
          $returnable_qty = $emp->product->get_max_return_quantity($emp->order_item->supplied_quantity);
          }else {
            $returnable_qty = $emp->product->get_max_return_quantity($emp->accepted_quantity);
          }
          $emp['returnable_qty'] = $returnable_qty;
         // $emp['returned_qty'] = $emp->total_quantity;
           $emp['returned_qty'] = $emp->total_quantity;
          
          $emp['accepted_qty'] = $emp->accepted_quantity;
          $emp['refused_qty'] =  ($emp->rejected_quantity >='0') ? '<input type="textbox" name="rejected_qty_'.$emp->id.'" id="rejected_qty_'.$emp->id.'" value="'.$emp->rejected_quantity.'" style="width:80px" disabled></input><a class="edit_rejected_qty" href="javascript:void(0)"  style="margin-left:-17px;" id="'.$emp->id.'"><i class="fa fa-edit"></i></a>':'';
          /*if($emp->order_item) {
           // $emp['weight'] = $emp->product->weight * $emp->order_item->supplied_quantity;
             $emp['weight'] = $emp->product->weight * $emp->total_quantity;
          }else {*/
            $emp['weight'] = number_format($emp->product->weight * $emp->accepted_quantity,'2','.',',');
          //}
          $emp['total_sale_price']= $emp->total_sale_price;
          $emp['refundable_amount'] = $emp->sale_price * $emp->accepted_quantity;
           $stock = $this->getStockData($emp->product->id);
           $emp['stock'] = $stock['total_balance'];
          ## Set dynamic route for action buttons
            //$emp['show']= route("products.show",$emp->product->id);
            $emp['view_product']= route("view_product_detail",$emp->product->id)."?order_return_id=".$emp->order_return_id;
            $emp['verify']= '<a href="javascript:void(0)" id="'.$emp->product->id.'" name="'.$returnable_qty.','.$emp->returned_qty.','.$emp->id.'" class="verify" >Verify</a>';
            $emp['delete']= route("order_return.destroy",$emp["id"]);
            $data[]=$emp;
        }
      
        ## Response
        $response = array(
          "draw" => intval($draw),
          "iTotalRecords" => $totalRecords,
          "iTotalDisplayRecords" => $totalRecordwithFilter,
          "aaData" => $data
        );

        echo json_encode($response);
    }

    public function update_order_return_status(Request $request){
      $order_return_id = $request->order_return_id;
      $order_return = OrderReturn::where('id',$order_return_id)->first();
      $order_return->order_status = $request->order_return_status;
      $order_return->save();
      return response()->json(['success'=>'Order Return Status Updated']);
    }

    public function update_rejected_qty(Request $request){
      $order_return_item_id = $request->order_return_item_id;
      $order_return_item = OrderReturnItem::where('id',$order_return_item_id)->first();
     // $total = $order_return_item->accepted_quantity + $order_return_item->rejected_quantity;
      if($request->rejected_qty < $order_return_item->total_quantity) {
        $order_return_item->rejected_quantity = $request->rejected_qty;
        $order_return_item->accepted_quantity = $order_return_item->total_quantity - $request->rejected_qty;
        $order_return_item->return_sale_price = ($order_return_item->accepted_quantity * $order_return_item->sale_price);
      }else {
        return response()->json(['error'=>'Rejected Quantity Should be less than Ordered Quantity']);
      }
      $order_return_item->save();
      return response()->json(['success'=>'Rejected Quantity Updated Successfully']);
    }

    public function notify_user_order_return(Request $request){
      $validator = Validator::make($request->all(),[
              'order_return_id'         => 'required|exists:order_returns,id',
              'transaction_id'   => 'nullable|min:0|max:100',
              'payment_status'   => 'nullable',
              'admin_note'       => 'nullable|min:3|max:100',
              'customer_note'    => 'nullable|min:3|max:100',
          ]);

          if($validator->fails()){
            return response()->json(['error' => $validator->errors()->first()]);
          }
      
          $data = $request->all();
          $order_return = OrderReturn::find($data['order_return_id']);
          $user = $order_return->user;

        $order_return_notes = OrderReturnNote::create([
                  'order_return_id'=>$order_return->id,
                  'transaction_id'=>$data['transaction_id'],
                  'payment_status'=>$data['payment_status'],
                  'customer_note'=>$data['customer_note'],
                  'admin_note'=>$data['admin_note'],
                  'added_by' => Auth::user()->id,
            ]);
            $order_return_note = OrderReturnNote::find($order_return_notes->id);
           //###### Send Email ######
            if($user->email) {
                $subject = "SSGC Bulk Order - Order Return Info";
                Mail::to($user->email)->send(new SendOrderReturnManuallyNotifyEmailCustomer($user,$order_return_note,$subject,$data['customer_note']));
            }
             //###### Send Email ######  

            return response()->json(['success' => 'Manually order return notes added successfully','order_return_note'=>$order_return_note]);
    }

    public function add_more_return_item(Request $request){

        $orderreturnitems = OrderReturnItem::query()
                    ->whereHas('order_return',function($q) use($request){
                      $q->where('user_id',$request['order_return_user_id'])
                      ->where('id',$request['order_return_id']);
                  })->get();
       $item_ids = [];
       foreach ($orderreturnitems as $order_return_item) {
        $item_ids[] = $order_return_item['product_id'];
       }
       $orderitems = OrderItem::query()->whereHas('order',function($q) use($request){
                    $q->where('user_id',$request['order_return_user_id']);
                  })->whereNotIn('product_id',$item_ids)->groupBy('product_id')->get();
      
       foreach ($orderitems as $order_item) {
        $data[] = ['id'=>$order_item->product->id,'sku_id'=>$order_item->product->sku_id,'name'=>$order_item->product->get_name(),'order_item_id'=>$order_item->id];
       }
      /* $products = Product::where(['status'=>'active','is_live'=>'1'])->whereIn('id',$filtered_item_ids)->get();
       $data =array();
       foreach($products as $product) {
        $data[] = ['id'=>$product->id,'sku_id'=>$product->sku_id,'name'=>$product->get_name()];
       }*/

       return response()->json(['success'=>'Data Found','products'=>$data]);
    }

    public function add_order_return_item(Request $request){
      $validator = Validator::make($request->all(),[
          'order_return_id' => 'required',
          'order_item_id' =>'required',
          'product_id' => 'required|nullable',
          'returned_quantity' => 'required|nullable',
        ]); 
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->first()]);
        }
          DB::beginTransaction();
          try {
            $data = $request->all();
            $order_return_item =  new OrderReturnItem();
            $order_item = OrderItem::find($data['order_item_id']);
            $product  = Product::find($data['product_id']);
            $user = User::find($data['order_return_user_id']);
            $order_return_item['order_return_id'] = $data['order_return_id'];
            $order_return_item['order_item_id'] = $data['order_item_id'];
            $order_return_item['product_id'] = $data['product_id'];
           // $order_return_item['total_quantity'] = $order_item->supplied_quantity;
           // $order_return_item['accepted_quantity'] = $data['returned_quantity'];
            $order_return_item['total_quantity'] = $data['returned_quantity'];
            $order_return_item['mrp'] = $product->mrp;
            $order_return_item['sale_price'] = $product->get_price($user);
            $order_return_item['total_mrp'] = $product->mrp * $data['returned_quantity'];
            $order_return_item['total_sale_price'] = $product->get_price($user) * $data['returned_quantity'];
            $order_return_item['requested_as'] = $user->user_type;
            //$order_return_item['return_sale_price'] = $order_return_item['accepted_quantity'] * $order_return_item['sale_price'];
            $order_return_item['return_sale_price'] = $order_return_item['returned_quantity'] * $order_return_item['sale_price'];
            $order_return_item['rejected_quantity'] = '0';
            if($order_return_item->save($data)){
              DB::commit();
              return response()->json(['success'=>'Order Return Item Added Successfully']);  
          }
        }catch (\Exception $e) {
              DB::rollback();
              return response()->json(['error' => $e->getMessage()]);
           } 
    }

    public function update_order_return_summary(Request $request){
      $order_return_id  = $request->order_return_id;
      $data = $this->updateReturnCartCalculation($order_return_id);
      if(!empty($data)) {
        return response()->json(['success' => 'Order  Return Summary Updated Successfully']);
      }
    }

    public function notify_order_return_update(Request $request){

        $order_return_id = $request->order_return_id;
       // $order_return_item_id = $request->order_return_item_id
        $subject = "SSGC Bulk Order - Order Return Update Info";
        $order_return = OrderReturn::find($order_return_id);
        //$order_return_items = OrderReturnItem::where('order_return_id',$order_return_id)->get();
        //$order_return_summary = OrderReturn::find($order_return_id);
        $user = User::find($order_return->user_id);
        $data = $this->generateOrderReturnInvoiceData($order_return);
        
        Mail::to($user->email)->send(new SendOrderReturnUpdateNotifyEmailCustomer($user,$subject,$data));
        return response()->json(['success' => 'Order Return Update Email Sent To Customer Successfully']);
    }

    public function verify_product_barcodes(Request $request){
        $order_return_id = $request->order_return_id;
        $product_id = $request->product_id;
        $barcode = $request->barcode;
        
          $is_product_ordered = OrderItem::where('product_id',$product_id)->count();
          if($is_product_ordered == 0) {
                  return response()->json(['error'=>'No order placed for this product barcode']);
          }
          $is_product_barcode = ProductBarcode::where(['product_id'=>$product_id,'barcode_value'=>$barcode])->count();
          
          if($is_product_barcode == 0) {
                  return response()->json(['error'=>'Barcode Does not match with product']);
          }
          $is_barcode_verified  = VerifiedBarcode::where(['barcode'=>$barcode,'product_id'=>$product_id])->count(); 
         if($is_barcode_verified == 0) {
        // Add barcode in verified barcodes table to check whether it is scanned or not
            DB::beginTransaction();
            try {
              $data = $request->all();
              $verified_barcodes =  new VerifiedBarcode();
              $verified_barcodes['product_id'] = $data['product_id'];
              $verified_barcodes['barcode'] = $data['barcode'];
              $verified_barcodes['added_by'] = Auth::user()->id;
              if($verified_barcodes->save($data)){
                DB::commit();
                $cnt = ProductBarcode::where(['product_id'=>$product_id,'barcode_value'=>$barcode])->count();
                $count = $cnt++;
                return response()->json(['success'=>'Data Found','count'=>$count]); 
              }
             }catch (\Exception $e) {
                DB::rollback();
                return response()->json(['error' => $e->getMessage()]);
            }
          
        }
        else {
         return response()->json(['error'=>'Barcode Already Taken']);  
        }
    }

    public function verify_qty(Request $request){
         $data = $request->all();
         //$id = $data['order_return_id'];
         $id = $data['order_returnitem_id'];
        // $order_return_item = OrderReturnItem::where('order_item_id',$id)->first();
          $order_return_item = OrderReturnItem::find($id);
         //$order_return_item->accepted_quantity =  $data['total_count'];
         // $order_return_item->rejected_quantity = $data['return_qty'] - $data['total_count'];
         
          if($order_return_item->rejected_quantity == '' && $order_return_item->accepted_quantity == ''){
            $order_return_item->accepted_quantity =  $data['total_count'];
             $order_return_item->return_sale_price = $order_return_item->accepted_quantity * $order_return_item->sale_price;
            $order_return_item->rejected_quantity = $data['return_qty'] - $data['total_count'];
          }else {

           $order_return_item->accepted_quantity =  $order_return_item->accepted_quantity + $data['total_count'];
           $order_return_item->return_sale_price = $order_return_item->accepted_quantity * $order_return_item->sale_price;
           $order_return_item->rejected_quantity = $order_return_item->rejected_quantity - $data['total_count'];
         //  $order_return_item->rejected_quantity = $data['total_count'] - $order_return_item->accepted_quantity; 
          }
         
         $order_return_item->save();
         return response()->json(['success'=>'Order Return Item Data Updated Successfully']);
    }

    public function check_barcodes(Request $request) {
        $data = $request->all();
        $barcode = $data['barcode'];
        $user_id = $data['user_id'];
        $user = User::find($user_id);
        $validator= $request->validate([
          'barcode'   => 'required',
          'user_id'    => 'required',
        ]);

        $product_barcode = ProductBarcode::where('barcode_value',$barcode)->first();
        if(empty($product_barcode)) {
             return response()->json(['error'=>'Barcode doesnot exist']);
        }
       
        // Check barcode verified
        $is_barcode_verified  = VerifiedBarcode::where(['barcode'=>$barcode,'product_id'=>$product_barcode->product_id])->count(); 
        if($is_barcode_verified != 0) {
             return response()->json(['error'=>'Barcode Already Taken']);
        }

        try {
          $data = $request->all();
          $verified_barcodes =  new VerifiedBarcode();
          $verified_barcodes['product_id'] = $product_barcode->product_id;
          $verified_barcodes['barcode'] = $barcode;
          $verified_barcodes['status'] = 0;
          $verified_barcodes['added_by'] = Auth::user()->id;
          if($verified_barcodes->save($data)){
            DB::commit();
          }
        }catch (\Exception $e) {
          DB::rollback();
          return response()->json(['error' => $e->getMessage()]);
        }
     
        $data = $this->get_temp_verified_product($user);

        return response()->json(['success'=>'Data Saved Successfully','data'=>$data]);
    }

    public function get_temp_verified_product($user){
        $product_ids = array();
        $products = VerifiedBarcode::where('status','0')->get();
       
        foreach ($products as $product) {
          $product_ids[] = $product->product_id;
        }
        $products = Product::whereIn('id',$product_ids)->get();
        $verified_qty = '0';
        $accepted_qty = '0';
        $total_sale_price = '0';
        $total_weight = '0';
        $total_mrp = '0';
        foreach ($products as $product) {
            $verified_qty = VerifiedBarcode::where('status','0')->where('product_id',$product->id)->count();
           $accepted_qty = $accepted_qty + $verified_qty; 
           $total_weight += number_format($product->weight * $verified_qty,'2','.',',');
           $total_mrp += $product->mrp * $verified_qty;
           $total_sale_price += $product->get_price($user) * $verified_qty;
        }
        $data = ['total_qty'=>$accepted_qty,'total_mrp'=>$total_mrp,'total_sale_price'=>$total_sale_price , 'total_weight'=>$total_weight];
        return $data;      
    }


    public function product_list_ajax(Request $request) {

        $user_id = $request->user_id;
        $product_ids = array();
        $products = VerifiedBarcode::where('status','0')->get();
       
        foreach ($products as $product) {
          $product_ids[] = $product->product_id;
        }
        $query           =    Product::whereIn('id',$product_ids);
        $request         =    $request->all();
        $totalRecords    =    $query->count();
        $draw            =    $request['draw'];
        $row             =    $request['start'];
        $length = ($request['length'] == -1) ? $totalRecords : $request['length'];
        $rowperpage      =    $length; // Rows display per page
        $columnIndex     =    $request['order'][0]['column']; // Column index
        $columnName      =    $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder =    $request['order'][0]['dir']; // asc or desc
        $searchValue     =    $request['search']['value']; // Search value

       
        ## Total number of records without filtering
        $total = $query->count();
        $totalRecords = $total;

        ## Total number of record with filtering
        $filter = $query;

        if($searchValue != ''){
        $filter = $filter->whereHas('user',function($q1) use($searchValue){
                           $q1->where('company_name','like','%'.$searchValue.'%');
                           $q1->orwhere('mobile_number','like','%'.$searchValue.'%');
                     })->orWhere(function($q)use ($searchValue) {
                            $q->where('id',$searchValue);
                        });
        }

        $filter_count = $filter->count();
        $totalRecordwithFilter = $filter_count;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();
        $user = User::find($user_id);

        foreach ($empQuery as $emp) {

          $emp['product_name']= $emp->get_name();
          $emp['product_image'] = ($emp->image) ? '<img src="'.asset($emp->image).'" width="80px" height="80px">' : '';
          $emp['mrp']= $emp->mrp;
          if($user){
            $emp['sale_price']= $emp->get_price($user);  
          }
          
          $emp['ordered_qty'] =  VerifiedBarcode::where('status','0')->where('product_id',$emp->id)->count();
          $emp['accepted_qty'] = $emp['ordered_qty'];
          $emp['returned_qty'] = $emp['accepted_qty'];
          $returnable_qty = $emp->get_max_return_quantity($emp['accepted_qty']);
          $emp['returnable_qty'] = $returnable_qty;
          $emp['refused_qty'] = 0;
          $emp['weight'] = number_format($emp->weight * $emp['ordered_qty'],'2','.',',');
          $emp['refundable_amount'] = $emp['sale_price'] * $emp['ordered_qty'];
          $stock = $this->getStockData($emp->id);
          $emp['stock'] = $stock['total_balance'];
          $emp['total_sale_price'] += $emp['sale_price'];
          $emp['total_quantity'] += $emp['ordered_qty'];
          $emp['total_weight'] += number_format($emp['weight'],'2','.',',');
          ## Set dynamic route for action buttons
          $emp['delete_product']= route("delete_verified_product",$emp["id"]);
          $data[]=$emp;
        }
        
        ## Response
        $response = array(
          "draw" => intval($draw),
          "iTotalRecords" => $totalRecords,
          "iTotalDisplayRecords" => $totalRecordwithFilter,
          "aaData" => $data
        );

        echo json_encode($response);
    }

    
    public function create_order_returns_item(Request $request) {
        $data     = $request->all();  
        $user     = User::find($data['user_id']);
        $data_return = [
              'user_id'          => $user->id,
              'requested_as'     => $user->user_type,
              'total_weight'     => $data['total_weight'],
              'total_quantity'   => $data['total_quantity'],
              'accepted_quantity'=> $data['total_quantity'],
              'total_mrp'        => $data['total_mrp'],
              'total_sale_price' => $data['total_sale_price'],
              'order_status'     => 'in_review',
              'payment_type'     => 'offline',
              'returned_at'        => date('Y-m-d H:i:s'),
              'created_at'        => date('Y-m-d H:i:s'),
              'added_by'         => Auth::user()->id 
        ];
        
          $order_return = new OrderReturn();
          $order_return->fill($data_return);
          $order_return->save();
          $id = $order_return->id;

          if($id) {
              //Add cart items
              $product_ids = array();
              $barcodes = VerifiedBarcode::where('status','0')->groupBy('product_id')->get();
              foreach ($barcodes as $barcode) {
                $product = Product::find($barcode->product_id);
                $return_item  = new OrderReturnItem();
                $return_item->order_return_id = $id;
                //$return_item->order_item_id   = '';
                $return_item->product_id = $product->id;
                $return_item->requested_as = $user->user_type;
                $return_item->mrp = $product->mrp;
                $return_item->sale_price = $product->get_price($user);
                $return_item->total_sale_price = $product->sale_price * 1;
                $return_item->total_quantity = VerifiedBarcode::where('status','0')->where('product_id',$barcode->product_id)->count();
                $return_item->accepted_quantity = $return_item->total_quantity;
                $return_item->rejected_quantity = 0 ;

                if($return_item->save()){
                  DB::commit();
                  try {
                      DB::table('verified_barcodes')
                      ->where('status','0')->where('product_id',$barcode->product_id)
                      ->update(['status' => "1"]);
                  }catch (\Exception $e) {
                    DB::rollback();
                    return response()->json(['error' => $e->getMessage()]);
                  }
              }    
             }
                   // $cart = $this->updateReturnCartCalc($id);
             $order_summary = OrderReturn::find($id);
             return response()->json(['success'=> trans('order_return.added'),'id'=>$id,'order_summary'=>$order_summary]);
          }else{
              DB::rollback();
              return response()->json(['error' => trans('order_return.error')]);
            }
        
      }

    public function download_order_return_pdf(Request $request)
    {
        $order_return_ids  = $request->id;
        $order = $request->order;
        $length = $request->length;
        $start = $request->start_id;
        $data_array = [];
        if($order_return_ids == '') {
            if($length != '-1') {
               // $ids =  OrderReturn::where('is_cart','0')->orderBy('id',$order)->limit($length)->get();
                  if($order == 'asc'){
                  $ids = OrderReturn::where('is_cart','0')->where('id', '>=', $start)
                      ->take($length)->orderBy('id',$order)->get();
                }else {
                  $ids = OrderReturn::where('is_cart','0')->where('id', '<=', $start)
                      ->take($length)->orderBy('id',$order)->get();
                }
              }else {
                $ids =  OrderReturn::where('is_cart','0')->orderBy('id',$order)->get();
              }
          foreach ($ids as $id) {
            $order_return_ids[] = $id->id;
          }
        }

       /* foreach ($order_return_ids  as $key=>$value){
            $item =  OrderReturn::where('id',$value)->first();
            $data_array[] = ['id' => $item->id,
                             'business_name' => $item->user->company_name,
                             'order_status' => $item->order_status,
                             'payment_status' =>$item->payment_status,
                           ];

        }
       
        $dir = public_path('uploads/order_return_pdf');
        if (! is_dir($dir))
            mkdir($dir, 0777, true);

        $pdf = \PDF::loadView('order_return_pdf', ['data_array' => $data_array]);
        $name = 'uploads/order_return_pdf'.'/order_return.pdf';
        $pdf->save($name);*/
        // return asset($name);

        foreach ($order_return_ids as $id=>$value){
            $item =  OrderReturn::where('id',$value)->first();
            $data_array[] = $this->generateOrderReturnInvoiceData($item);
        }

        $dir = public_path('uploads/order_return_invoice');
        if (! is_dir($dir))
            mkdir($dir, 0777, true);
       /* if(\File::exists(public_path('uploads/invoices/order_invoices.pdf'))){
          \File::delete(public_path('uploads/invoices/order_invoices.pdf'));
        }*/
        Artisan::call('optimize:clear');
        $pdf = \PDF::loadHtml(view('order_return_invoice', ['data_array' => $data_array]));
        $name = 'uploads/order_return_invoice/order_return_invoices_'.time().'.pdf';
        $pdf->save($name);


        if($name) {
            return response()->json(['success' => 'Order Return Exported Successfully', 'data' => asset($name)]);
        } else {
            return response()->json(['error' => 'Error in Order Return Export']);
        }
    }

    public function view_product_detail($id){
        $page_title  = trans('order_return.view_product');
        $categories = Category::WhereNull('parent_id')->where('is_live','1')->where('status','active')->get();

        $product        = Product::find($id);
        if(empty($product)){
            return redirect()->route('products.index')->with('error',trans('common.no_data'));
        }
        $product_category_ids = ProductCategory::where('product_id',$id)->pluck('category_id')->toArray();
        $business_categories = BusinessCategory::whereIn('layout',['books'])->where(['is_live'=>'1', 'status'=>'active'])->get();
        $related_product_ids = RelatedProduct::where('product_id',$id)->pluck('related_product_id')->toArray();
        $related_products = Product::where(['status'=>'active','is_live'=>'1'])->get();
        return view('admin.order_return.view_product',compact('product','page_title','categories','product_category_ids','business_categories','related_products','related_product_ids'));
    }

    public function destroy(Request $request,$id){
        $order_return_item = OrderReturnItem::find($id);
        $verified_products = VerifiedBarcode::where('product_id',$order_return_item->product_id)->get();
        foreach ($verified_products as $verified_product) {
         $verified_product->delete();
        }
        if($order_return_item->delete()){
          //  $this->updateCartCalc($order_item->order_id);
              return redirect()->back()->with('success',trans('order_return.deleted'));
          }else{
              return redirect()->back()->with('error',trans('order_return.error'));
          }
    }

      public function delete_verified_product(Request $request,$id){
        $user = User::find($request->user_id);
        $verified_products = VerifiedBarcode::where('product_id',$id)->where('status','0')->get();
        foreach ($verified_products as $verified_product) {
         $verified_product->delete();
        }
        $data = $this->get_temp_verified_product($user);
        return response()->json(['success' => 'Product deleted','data'=>$data]);
      }

}
