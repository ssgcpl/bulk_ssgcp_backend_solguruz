<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderNote;
use App\Models\OrderItem;
use App\Models\RelatedProduct;
use App\Models\Category;
use App\Models\Setting;
use App\Models\BusinessCategory;
use Validator,Auth,DB,Hash;
use App\Models\Helpers\CommonHelper;
use App\Mail\SendOrderManuallyNotifyEmailCustomer;
use App\Mail\SendOrderUpdateNotifyEmailCustomer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;

class OrderController extends Controller
{
    use CommonHelper;

    public function __construct()
    {
      $this->middleware('auth');
      $this->middleware('permission:order-list', ['only' => ['index','show']]);
      $this->middleware('permission:order-create', ['only' => ['create','store']]);
      $this->middleware('permission:order-edit', ['only' => ['edit','update']]);
      $this->middleware('permission:order-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index() {
      $page_title = trans('orders.heading');
      $on_hold = Order::where('order_status','on_hold')->count();
      $pending_payment = Order::where('order_status','pending_payment')->count();
      $processing = Order::where('order_status','processing')->count();
      $shipped = Order::where('order_status','shipped')->count();
      $completed = Order::where('order_status','completed')->count();
      $refunded = Order::where('order_status','refunded')->count();
      $cancelled = Order::where('order_status','cancelled')->count();
      $total_sale = Order::select(
                          DB::raw("sum(total_sale_price) as total"),
                          )->first();
      $total_sale_price = $total_sale['total'];
      return view ('admin.orders.index',compact('page_title','on_hold','pending_payment','processing','shipped','completed','refunded','cancelled','total_sale_price'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index_ajax(Request $request) {

        $query           =    Order::where('is_cart','0')->where('order_status','<>','pending')->where('order_status','<>','failed');
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
             /* $query = $query->whereHas('user',function($q) use($user_type) {
              	 $q->where('user_type',$user_type);
              });*/
              $query = $query->where('user_type',$user_type);
            }
            if($request['print_status'] !=''){
          	  $print_status = $request['print_status'];
              $query = $query->where('print_status',$print_status);
            }

            ## Filter by visible to
            if ($request['order_type'] !=''){
          	  $order_type = $request['order_type'];
              $query = $query->where('order_type',$order_type);

            }

             ## Filter by visible to
            if ($request['order_status'] !=''){
          	  $order_status = $request['order_status'];
              $query = $query->where('order_status',$order_status);

            }
        ## Total number of record with filtering
        $filter = $query;

        if($searchValue != ''){
        $filter = $filter->whereHas('user',function($q1) use($searchValue){
                           $q1->where('company_name','like','%'.$searchValue.'%');
                           $q1->orwhere('mobile_number','like','%'.$searchValue.'%');
                           $q1->orwhere('print_status','like','%'.$searchValue.'%');
                     })->orWhere(function($q)use ($searchValue) {
                            $q->where('id',$searchValue)
                            ->where('order_status','<>','pending')
                            ->where('order_status','<>','failed')
                            ->where('is_cart','0');
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
          $order_status = ucfirst(str_replace('_',' ',$emp->order_status));
          $color_code = 'FFA500';
          if($emp->order_status == 'processing') {
            $color_code = "#FFD700";
          }else if($emp->order_status == 'pending_payment') {
            $color_code = "#00CED1";
          }else if($emp->order_status == 'on_hold') {
            $color_code = "#FFA500";
          }else if($emp->order_status == 'shipped') {
            $color_code = "#32CD32";
          }else if($emp->order_status == 'completed') {
            $color_code = "#008000";
          }else if($emp->order_status == 'cancelled') {
            $color_code = "#FF0000";
          }else if($emp->order_status == 'refunded') {
            $color_code = "#483D8B";
          }
        	$emp['order_status'] = '<span style="color:'.$color_code.'">'.$order_status.'</span>';

        	$emp['print_status'] = ucfirst(str_replace('_',' ',$emp->print_status));
        	$emp['order_amount'] = $emp->total_payable;
        	$address = OrderAddress::where('order_id',$emp->id)->where('is_shipping','1')->first();
          $emp['order_types'] = ucfirst(str_replace('_',' ',$emp->order_type));
          if($address !='') {
            $emp['shipping_address'] = $address->house_no.", ".$address->street.", ".$address->landmark.", ".$address->area.", ".$address->city.", ".$address->state.", ".$address->postal_code;
          }else {
            $emp['shipping_address'] = '-';
          }

          if($emp->placed_at == Null) {
            $starttimestamp = strtotime($emp->created_at);
            $endtimestamp = strtotime(date("Y-m-d H:i:s"));
            $difference = round(abs($endtimestamp - $starttimestamp)/3600);
            if($difference <= 24) {
              $emp['created_date']  = $difference." Hours Ago";
            }else {
              $emp['created_date']  = date("d-m-Y h:i A",strtotime($emp->created_at));
            }
          }else {
            $starttimestamp = strtotime($emp->placed_at);
            $endtimestamp = strtotime(date("Y-m-d H:i:s"));
            $difference = round(abs($endtimestamp - $starttimestamp)/3600);
            if($difference <= 24) {
              $emp['created_date']  = $difference." Hours Ago";
            }else {
              $emp['created_date']  = date("d-m-Y h:i A",strtotime($emp->placed_at));
            }
          }
            

        	## Set dynamic route for action buttons
            //$emp['show']= route("orders.show",$emp["id"]);
          if(strtolower($emp->order_type) == 'physical_books') {
            	$emp['edit']= route("orders.edit",$emp["id"]);
        	}else if(strtolower($emp->order_type) == 'digital_coupons'){
        		$emp['edit']= route("coupon_orders.edit",$emp["id"]);
        	}
            $data[]=$emp;
        }
      //  print_r($data['status_hide']); die;

        ## Response
        $response = array(
          "draw" => intval($draw),
          "iTotalRecords" => $totalRecords,
          "iTotalDisplayRecords" => $totalRecordwithFilter,
          "aaData" => $data
        );

        echo json_encode($response);
    }


    public function edit(Request $request ,$id){
    	$page_title = trans('orders.show');
    	$order = Order::find($id);
      $order_items = OrderItem::where('order_id',$id)->get();
      $total_supplied_quantity = 0;
      foreach ($order_items as $order_item) {
        $total_supplied_quantity += $order_item->supplied_quantity;
      }
    	$user_type = $order->user_type;
    	$shipping_address = OrderAddress::where(['is_shipping'=>'1','order_id'=>$id])->first();
    	$billing_address = OrderAddress::where(['is_billing'=>'1','order_id'=>$id])->first();
    	//$order_notes  = OrderNote::where('order_id',$id)->orderBy('id','DESC')->limit('5')->get();
      $order_notes  = OrderNote::where('order_id',$id)->orderBy('id','DESC')->get();
    	return view('admin.orders.edit',compact('page_title','order','user_type','shipping_address','billing_address','order_notes','total_supplied_quantity'));
    }

    public function ajax_order_items(Request $request) {

        $query           =    OrderItem::where('order_id',$request->order_id);
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
        if ($searchValue != '') {

            $filter =   $filter->where(function ($q) use ($searchValue) {
                            $q->whereHas('product', function ($q) use ($searchValue) {
                                $q->where('name_english', 'like', '%' . $searchValue . '%')
                                ->orWhere('name_hindi', 'like', '%' . $searchValue . '%');
                            });
                            $q->orWhere('id', $searchValue);
                        });
        }

        $filter_count = $filter->count();
        $totalRecordwithFilter = $filter_count;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();
        foreach ($empQuery as $emp) {

        	$emp['ordered_qty'] = $emp->ordered_quantity;
        	$emp['supplied_qty'] = $emp->supplied_quantity;
         /* if($emp->language == 'english'){
            $name = $emp->product->name_english;
          }else if($emp->language == 'hindi'){
            $name = $emp->product->name_hindi;
          }else {
            $name = $emp->product->get_name();
          }*/
        	$emp['product_name']= $emp->book_name;
          $emp['sku_id']= $emp->product->sku_id;
        	$emp['product_image'] = ($emp->product->image) ? '<img src="'.asset($emp->product->image).'" width="100%" height="100%">' : '';
        	$emp['supplied_qty'] =  ($emp->supplied_quantity) ? '<input type="textbox" name="supplied_qty"'.$emp->id.'" id="supplied_qty_'.$emp->id.'" value="'.$emp->supplied_quantity.'" disabled style="width:100px;"></input><a class="edit_supplied_qty" href="javascript:void(0)"  id="'.$emp->id.'"><span class="fa fa-edit errspan"></span></a>':$emp->supplied_quantity;
        	$emp['weight'] = number_format($emp->product->weight * $emp->supplied_quantity,'2','.',',');
        	$emp['total_sale_price']= $emp->sale_price * $emp->supplied_quantity;
        	$stock =  $this->getStockData($emp->product->id);
          $emp['available_stock'] = $stock['total_balance'];

        	## Set dynamic route for action buttons
            $emp['show']= route("products.show",$emp["product_id"]);
            $emp['view_product']= route("view_product_details",$emp->product->id)."?order_id=".$emp->order_id;
            $emp['delete']= route("orders.destroy",$emp["id"]);
            $data[]=$emp;
        }
      //  print_r($data['status_hide']); die;

        ## Response
        $response = array(
          "draw" => intval($draw),
          "iTotalRecords" => $totalRecords,
          "iTotalDisplayRecords" => $totalRecordwithFilter,
          "aaData" => $data
        );

        echo json_encode($response);
    }


    public function update_order_status(Request $request)
    {
    	$order_id = $request->order_id;
    	$order = Order::where('id',$order_id)->first();
      if($request->order_status == '' || $request->order_status == 'null' ){
        return response()->json(['error'=>'Please select order status first']);
      }
      if($order->order_status == 'pending' && $order->is_payment_attempt == '1'){
      return response()->json(['error'=>'This Order is in Under process,it cannot be changed']);
      }

        $order->order_status = $request->order_status;
        $order->is_payment_attempt = '2';
        $order->save();
        $user = $order->user;

      // check if the order is not already completed
      if($order->status != 'completed')
      {
        //check if this order is the first order of the user and completed
        if($request->order_status == 'completed')
        {
          //check if the user is referred
          if($user->referrer_id != NULL)
          {
            $first_order = Order::where('user_id',$user->id)->orderBy('id','asc')->first();
            if($first_order->id == $order->id)
            {
                //add points to the user who refer the other user
                $referrer_user = User::find($user->referrer_id);
                $this->maintainReferralHistory((integer)Setting::get('refer_points'), 'added','',$referrer_user,'',null,$user->id);

                //add points to the user who has used the referral code
                $this->maintainReferralHistory((integer)Setting::get('referred_points'), 'added','',$user,'',$order->id,$referrer_user->id);

                //Notify the user (who has referred the app)
                $title    = 'You have earned referal points.';
                $body     = "Hurray! Your friend has used your referral code. You have earned ".(integer)Setting::get('refer_points')." referral points.";
                $slug     = 'refer_points_earned';
                $this->sendNotification($referrer_user,$title,$body,$slug,$user,null);

            }
          }
        }
      }

      if($request->order_status == 'completed'){
          $admin = User::where('user_type','admin')->first();
          $title    = 'Order Delivered';
          if($user->first_name) {
            $body     = "Order Id :".$order->id." delivered for ".$user->first_name." ".$user->last_name;
          }else{
            $body     = "Order delivered to customer";
          }
          $slug     = 'customer_order_delivered';
          $this->sendNotification($admin,$title,$body,$slug,$order,null);

          ######### send notification to customer ############
           if($user->first_name) {
            $body     = "Congratulations ! ".$user->first_name." ".$user->last_name." Your order Order Id :".$order->id." is delivered successfully.";
          }else{
            $body     = "Order delivered to customer";
          }
          $slug     = 'customer_order_delivered';
          $this->sendNotification($user,$title,$body,$slug,$order,null);
           ######### send notification to customer ############
      }

      if($request->order_status == 'refunded'){
          if($order->is_refunded == '0'){
            $this->maintainReferralHistory($order->redeemed_points, 'added','',$user,'1',$order->id);
            $order->refunded_points = $order->redeemed_points;
            $order->refunded_amount = $order->total_payable;
            $order->is_refunded = '1';
            $order->save();
          }
          $admin = User::where('user_type','admin')->first();
          $title    = 'Order Payment Refunded';
          if($user->first_name) {
            $body     = "Order Id :".$order->id." refunded to ".$user->first_name." ".$user->last_name;
          }else{
            $body     = "Order payment refunded to customer";
          }
          if($order->order_type == 'digital_coupons'){
            $slug     = 'customer_coupon_order_refunded';
          }else {
            $slug     = 'customer_order_refunded';  
          }
          
          $this->sendNotification($admin,$title,$body,$slug,$order,null);

          ######### send notification to customer ############
          if($user->first_name) {
            $body     = "Your payment has been refunded successfully for Order Id :".$order->id;
          }else{
            $body     = "Order payment refunded successfully";
          }
           if($order->order_type == 'digital_coupons'){
            $slug     = 'customer_coupon_order_refunded';
          }else {
            $slug     = 'customer_order_refunded';  
          }
          $this->sendNotification($user,$title,$body,$slug,$order,null);
           ######### send notification to customer ############
      }

      if($request->order_status == 'cancelled'){
          $admin = User::where('user_type','admin')->first();
          $title    = 'Order Cancelled';
          if($user->first_name) {
            $body     = "Order Id :".$order->id." cancelled for ".$user->first_name." ".$user->last_name;
          }else{
            $body     = "Order cancelled";
          }
          $slug     = 'customer_order_cancelled';
          $this->sendNotification($admin,$title,$body,$slug,$order,null);

           ######### send notification to customer ############
          if($user->first_name) {
            $body     = "Your order has been cancelled successfully for Order Id :".$order->id ;
          }else{
            $body     = "Your Order cancelled";
          }
          if($order->order_type == 'digital_coupons'){
            $slug     = 'customer_coupon_order_refunded';
          }else {
            $slug     = 'customer_order_cancelled';
          }
          $this->sendNotification($user,$title,$body,$slug,$order,null);
           ######### send notification to customer ############
      }
      // return redirect()->route('orders.edit',$order_id)->with('success','Order Status Updated');
      return response()->json(['success'=>'Order Status Updated']);
    }

    public function update_refund_details(Request $request){
      $validator = Validator::make($request->all(),[
          'order_id' => 'required',
          'refunded_points' => 'required|nullable',
          'refunded_amount' => 'required|nullable',
      ]);
      if($validator->fails()){
            return response()->json(['error' => $validator->errors()->first()]);
      }
      $data = $request->all();
      $order = Order::find($data['order_id']);
      $user = User::find($order->user_id);
      if($order->is_refunded == '1') {
          return response()->json(['error'=>'Order already refunded']);
      }
      
      //if($order->redeemed_points != '' && $order->redeemed_points_discount != ''){
        // if($data['refunded_points'] > $order->redeemed_points) {
        //   return response()->json(['error'=>'Refunded Points should not be more than redeemed points']);
        // }
        $order->order_status = 'refunded';
        $order->is_refunded = '1';
        $order->is_payment_attempt = '2';
       
        $order->refunded_points = $data['refunded_points'];
        $order->refunded_amount = $data['refunded_amount'];
        $this->maintainReferralHistory($order->refunded_points, 'added','',$user,'1',$order->id);
     // }
      if($order->save())
      {
         $admin = User::where('user_type','admin')->first();
          $title    = 'Order Payment Refunded';
          if($user->first_name) {
            $body     = "Order Id :".$order->id." refunded to ".$user->first_name." ".$user->last_name;
          }else{
            $body     = "Order payment refunded to customer";
          }
          if($order->order_type == 'digital_coupons'){
            $slug     = 'customer_coupon_order_refunded';
          }else {
            $slug     = 'customer_order_refunded';  
          }
          
          $this->sendNotification($admin,$title,$body,$slug,$order,null);

          ######### send notification to customer ############
          if($user->first_name) {
            $body     = "Your payment has been refunded successfully for Order Id :".$order->id;
          }else{
            $body     = "Order payment refunded successfully";
          }
           if($order->order_type == 'digital_coupons'){
            $slug     = 'customer_coupon_order_refunded';
          }else {
            $slug     = 'customer_order_refunded';  
          }
          $this->sendNotification($user,$title,$body,$slug,$order,null);
           ######### send notification to customer ############
        return response()->json(['success'=>'Order Status Updated']);
      }else{
        return response()->json(['error'=>'Something went wrong']);
      }
    }


    public function show(Request $request ,$id){
    	$page_title = trans('orders.show');
    	$order = Order::find($id);
    	$shipping_address = OrderAddress::where(['is_shipping'=>'1','order_id'=>$id])->first();
    	$billing_address = OrderAddress::where(['is_billing'=>'1','order_id'=>$id])->first();
    	$order_notes  = OrderNote::where('order_id',$id)->orderBy('id','DESC')->get();
    	return view('admin.orders.show',compact('page_title','order','shipping_address','billing_address','order_notes'));
    }

    public function update_supplied_qty(Request $request){
    	$order_item_id = $request->order_item_id;
    	$order_item = OrderItem::where('id',$order_item_id)->first();
    	$order_item->supplied_quantity = $request->supplied_qty;
      $this->updateCartCalc($order_item->order_id);
      $order_item->save();
      return response()->json(['success'=>'Supplied Quantity Updated Successfully']);
    }

    public function add_more_item(Request $request){
       $products = Product::where(['status'=>'active','is_live'=>'1'])->get();
       $data =array();
       foreach($products as $product) {
        $data[] = ['id'=>$product->id,'sku_id'=>$product->sku_id,'name'=>$product->get_name()];
       }

       return response()->json(['success'=>'Data Found','products'=>$data]);
    }

    public function add_order_item(Request $request){
    	$validator = Validator::make($request->all(),[
          'order_id' => 'required',
          'product_id' => 'required|nullable',
          'ordered_quantity' => 'required|nullable',
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->first()]);
        }

          DB::beginTransaction();
        	try {
		        $data = $request->all();
            $order = Order::find($data['order_id']);
            $order_item =  new OrderItem();
		        $product  = Product::find($data['product_id']);
		        $order_item['order_id'] = $data['order_id'];
		        $order_item['product_id'] = $data['product_id'];
            $order_item['book_name'] = $product->get_name();
            $order_item['book_sub_heading'] = $product->get_sub_heading();
            $order_item['book_description'] = $product->get_description();
            $order_item['book_additional_info'] = $product->get_additional_info();
		        $order_item['ordered_quantity'] = $data['ordered_quantity'];
		        $order_item['supplied_quantity'] = $data['ordered_quantity'];
		        $order_item['mrp'] = $product->mrp;
		        $order_item['sale_price'] = $product->get_price($order->user);
            $order_item['total_mrp'] = $product->mrp * $data['ordered_quantity'];
            $order_item['total_sale_price'] = $product->get_price($order->user) * $data['ordered_quantity'];
		        if($order_item->save($data)){
              //$this->updateCartCalc($data['order_id']);
		        	DB::commit();
		        	return response()->json(['success'=>'Order Item Added Successfully']);
		    	}
		    }catch (\Exception $e) {
              DB::rollback();
              return response()->json(['error' => $e->getMessage()]);
       		 }
    }

    public function update_order_detail(Request $request){
     // print_r($request->all()); die;
    	$validator = Validator::make($request->all(),[
          'order_id' => 'required',
         // 'transaction_id' => 'required',
          'transaction_id' => 'nullable',
          'payment_status'=>'required',
         // 'bundles' =>'required'
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->first()]);
        }
          DB::beginTransaction();
        	try {
		        $data = $request->all();

		        $order =  Order::find($data['order_id']);
		        $order['transaction_id'] = $data['transaction_id'];
            if(isset($data['delivery_charges'])){
              $order['delivery_charges'] = $data['delivery_charges'];
            }
		        $order['payment_status'] = $data['payment_status'];
		        $order['bundles'] = $data['bundles'];

		        if($order->save($data)){
              $this->updateCartCalc($data['order_id']);
		        	DB::commit();
             	return response()->json(['success'=>'Order Details Updated Successfully']);
		    	}
		    }catch (Exception $e) {
              DB::rollback();
              return response()->json(['error' => $e->getMessage()]);
       		 }
    }


    public function update_shipping_address(Request $request)
    {
    	$data = $request->all();
    	if($this->update_address_detail($data,'shipping')){
    		return response()->json(['success'=>'Order Address Updated Successfully']);
    	}

    }

     public function update_billing_address(Request $request)
    {
    	$data = $request->all();
    	if($this->update_address_detail($data,'billing')){
    		return response()->json(['success'=>'Order Address Updated Successfully']);
    	}

    }

    public function update_address_detail($data,$address_type){

    	$validator = Validator::make($data,[
          'order_id' => 'required',
          'customer_name' => 'required|nullable',
          'email' => 'required|nullable',
          'contact_number' => 'required|nullable',
          'house_no'=>'required|nullable',
          'street'=>'required|nullable',
          'landmark'=>'required|nullable',
          'area'=>'required|nullable',
          'city'=>'required|nullable',
          'postal_code'=>'required|nullable',
          'state'=>'required|nullable',
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->first()]);
        }
          DB::beginTransaction();
        	try {
		        $order_id = $data['order_id'];
		        if($address_type == 'shipping') {
		        	$order_address =  OrderAddress::where(['order_id'=>$order_id,'is_shipping'=>'1'])->first();
		    	}
		    	else if($address_type == 'billing') {
		        	$order_address =  OrderAddress::where(['order_id'=>$order_id,'is_billing'=>'1'])->first();
		    	}
		        $order_address->fill($data);
		        if($order_address->save()){
		        	DB::commit();
		        	return true;
		    	}
		    }catch (\Exception $e) {
              DB::rollback();
              return response()->json(['error' => $e->getMessage()]);
       		 }
    }


    public function notify_user(Request $request){
    	$validator = Validator::make($request->all(),[
              'order_id'                => 'required|exists:orders,id',
              //'courier_name'      => 'required|nullable|min:0|max:100',
              'courier_name'      => 'nullable|min:0|max:100',
              //'tracking_number'   => 'required|min:0|max:100',
              'tracking_number'   => 'nullable|min:0|max:100',
              'admin_note'       => 'nullable|min:3|max:100',
              'customer_note'    => 'nullable|min:3|max:100',
          ]);

          if($validator->fails()){
            return response()->json(['error' => $validator->errors()->first()]);
          }

      $data = $request->all();
		  $order = Order::find($data['order_id']);
		  $user = $order->user;

		    $order_notes = OrderNote::create([
                  'order_id'=>$order->id,
                  'courier_name'=>$data['courier_name'],
                  'tracking_number'=>$data['tracking_number'],
                  'customer_note'=>$data['customer_note'],
                  'admin_note'=>$data['admin_note'],
                  'added_by' => Auth::user()->id,
            ]);
            $order_note = OrderNote::find($order_notes->id);
           //###### Send Email ######
            if($user->email) {
                $subject = "SSGC Bulk Order - Order Delivery Info";
                Mail::to($user->email)->send(new SendOrderManuallyNotifyEmailCustomer($user,$order_note,$subject,$data['customer_note']));
            }
            //###### Send Email ######

            #####Send Notification######
            if($user->first_name) { 
              $username= $user->first_name; 
            }
            else {
              $username= 'Customer';
            }
            $title    = 'SSGC Bulk Order - Order Delivery Info';
            $body     = "Dear ".$username.",
                          Your order with ID ".$order->id." has been dispatched. You can track your order using the following details:
                          Courier Name: ".$data['courier_name'].",
                          Tracking Number: ".$data['tracking_number'].",
                          Customer Notes: ".$data['customer_note'].".
                          Thank you for placing an order on  BO SSGC!";
            $slug     = 'order_update_notify';
            $this->sendNotification($user,$title,$body,$slug,$order,null);
            #####Send Notification######
            
            return response()->json(['success' => 'Manually order notes added successfully']);

    }

    public function update_order_summary(Request $request){
    	$order_id  = $request->order_id;
    	$data = $this->updateCartCalc($order_id);
    	if(!empty($data)) {
    		return response()->json(['success' => 'Order Summary Updated Successfully']);
    	}
    }


    public function notify_order_update(Request $request){

    	  $order_id = $request->order_id;
    	  $subject = "SSGC Bulk Order - Order Update Info";
    	  $order = Order::find($order_id);
    	 // $order_summary = Order::find($order_id);
    	  $data = $this->generateInvoiceData($order);
        $user = User::find($order->user_id);
        Mail::to($user->email)->send(new SendOrderUpdateNotifyEmailCustomer($user,$subject,$data));
        return response()->json(['success' => 'Order Update Email Sent To Customer Successfully']);
    }

    public function download_order_pdf(Request $request)
    {
        $order_ids  = $request->id;
        $order = $request->order;
        $length = $request->length;
        $start = $request->start_id;
        $data_array = [];
        if($order_ids == '') {
              if($length != '-1') {
                //$ids =  Order::where('is_cart','0')->orderBy('id',$order)->limit($length)->get();
                if($order == 'asc'){
                  $ids = Order::where('is_cart','0')->where('id', '>=', $start)
                      ->take($length)->orderBy('id',$order)->get();
                }else {
                  $ids = Order::where('is_cart','0')->where('id', '<=', $start)
                      ->take($length)->orderBy('id',$order)->get();
                }
              }else {
                $ids =  Order::where('is_cart','0')->orderBy('id',$order)->get();
              }
              foreach ($ids as $id) {
                $order_ids[] = $id->id;
              }
        }

        foreach ($order_ids	 as $id=>$value){
            $item =  Order::where('id',$value)->first();
            $data_array[] = $this->generateInvoiceData($item);
        }

        $dir = public_path('uploads/invoice');
        if (! is_dir($dir))
            mkdir($dir, 0777, true);
       /* if(\File::exists(public_path('uploads/invoices/order_invoices.pdf'))){
          \File::delete(public_path('uploads/invoices/order_invoices.pdf'));
        }*/
        Artisan::call('optimize:clear');
        $pdf = \PDF::loadHtml(view('invoice', ['data_array' => $data_array]));
        $name = 'uploads/invoice/order_invoices_'.time().'.pdf';
        $pdf->save($name);

      /*  $dir = public_path('uploads/order_pdf');
        if (! is_dir($dir))
            mkdir($dir, 0777, true);
       //   return view('order_pdf',compact('data_array'));
        $pdf = \PDF::loadHtml('order_pdf', ['data_array' => $data_array]);
        $name = 'uploads/order_pdf'.'/order.pdf';
        $pdf->save($name);
      */ 

        if($name) {
            return response()->json(['success' => 'Order Exported Successfully', 'data' => asset($name)]);
        } else {
            return response()->json(['error' => 'Error in Barcode Exported']);
        }
    }

      public function view_product_details($id){
        $page_title  = trans('order.view_product');
        $categories = Category::WhereNull('parent_id')->where('is_live','1')->where('status','active')->get();

        $product        = Product::find($id);
        if(empty($product)){
            return redirect()->route('products.index')->with('error',trans('common.no_data'));
        }
        $product_category_ids = ProductCategory::where('product_id',$id)->pluck('category_id')->toArray();
        $business_categories = BusinessCategory::whereIn('layout',['books'])->where(['is_live'=>'1', 'status'=>'active'])->get();
        $related_product_ids = RelatedProduct::where('product_id',$id)->pluck('related_product_id')->toArray();
        $related_products = Product::where(['status'=>'active','is_live'=>'1'])->get();
        return view('admin.orders.view_product',compact('product','page_title','categories','product_category_ids','business_categories','related_products','related_product_ids'));
      }

    public function bulk_mark_as_printed(Request $request){
      $item_ids  = $request->id;
      Order::whereIn('id',$item_ids)->update(['print_status'=>'printed']);
      return response()->json(['success' => trans('orders.order_printed')]);
    }

    public function destroy(Request $request,$id)
    {
    	$order_item = OrderItem::find($id);
        if($order_item->delete()){
        //	$this->updateCartCalc($order_item->order_id);
            return redirect()->back()->with('success',trans('orders.deleted'));
        }else{
            return redirect()->back()->with('error',trans('orders.error'));
        }
    }

}
