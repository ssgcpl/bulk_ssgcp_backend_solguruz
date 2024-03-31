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
use App\Models\Payment;
use App\Models\BusinessCategory;
use Validator,Auth,DB,Hash;
use App\Models\Helpers\CommonHelper;
use App\Models\Helpers\PaymentHelper;
use App\Mail\SendOrderManuallyNotifyEmailCustomer;
use App\Mail\SendOrderUpdateNotifyEmailCustomer;
use App\Mail\SendOrderPlacedEmailCustomer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;

class PendingOrderController extends Controller
{
  use CommonHelper,PaymentHelper;

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('permission:order-list', ['only' => ['index','show']]);
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(){  
    $page_title = trans('orders.admin_heading');
    return view('admin.pending_orders.index',compact('page_title'));
  }
  public function pending_orders_ajax(Request $request){
       $query           =    Order::has('payments')
                              ->whereIn('order_status',['failed','pending'])
                              //->where('is_cart','0')
                              //->where('is_payment_attempt','1')
                              ->where('is_payment_attempt','<>','0')
                            //  ->where('is_payment_attempt','<>','2')
                              ->orderBy('placed_at','DESC');
    
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
        /* $from_date       =    $request['start_date'];
          $to_date         =    $request['end_date'];
          if($from_date != null && $to_date != null){
            $from_date = date("Y-m-d",strtotime($from_date));
            $to_date = date("Y-m-d",strtotime($to_date));
            $query = $query->whereBetween('created_at',[$from_date.' 00:00:00',$to_date.' 23:59:59']);
          }

           ## Filter by visible to
           /* if ($request['user_type'] !=''){
              $user_type = $request['user_type'];
              $query = $query->where('user_type',$user_type);
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

            }*/
        ## Total number of record with filtering
        $filter = $query;

        if($searchValue != ''){
        $filter = $filter->whereHas('user',function($q1) use($searchValue){
                          $q1->where('company_name','like','%'.$searchValue.'%');
                           $q1->orwhere('mobile_number','like','%'.$searchValue.'%');
                     })->orWhere(function($q)use ($searchValue) {
                            $q->where('id',$searchValue)
                            ->whereIn('order_status',['pending','failed'])
                            ->where('is_payment_attempt','<>','0');
                        });
        }

        $filter_count = $filter->count();
        $totalRecordwithFilter = $filter_count;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();
        foreach ($empQuery as $emp) {

          if($emp->order_status == 'pending' && $emp->is_payment_attempt == '1'){
              $order_status = 'under_process';
          }else {
              $order_status = $emp->order_status;
          }

          $emp['selected'] = '<input type="checkbox" class="mark" value="'.$emp->id.'" >';
          $emp['company_name'] = $emp->user->company_name;
          $emp['number'] = $emp->user->mobile_number;
          $emp['order_status'] = ucfirst(str_replace('_',' ',$order_status));
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
              $emp['edit']= route("pending_orders.edit",$emp["id"]);
          }else if(strtolower($emp->order_type) == 'digital_coupons'){
            $emp['edit']= route("coupon_edit",$emp["id"]);
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
      $all_payments = Payment::where('order_id',$id)->orderBy('id','asc')->get();
      return view('admin.pending_orders.edit',compact('page_title','order','user_type','shipping_address','billing_address','order_notes','total_supplied_quantity','all_payments'));
  }

    public function coupon_edit(Request $request, $id)
    {
        $page_title = trans('orders.show');
        $order = Order::find($id);
        $user_type = $order->user->user_type;
        $all_payments = Payment::where('order_id',$id)->orderBy('id','asc')->get();
        $order_notes  = OrderNote::where('order_id', $id)->orderBy('id', 'DESC')->get();

        return view('admin.pending_orders.coupon_edit', compact('page_title', 'order', 'user_type', 'order_notes','all_payments'));
    }

   public function markOrderSuccess(Request $request){
    try {
      $settings = Setting::pluck('value','name')->all();  
      $payment = Payment::find($request->payment_id);
      $cart = Order::find($payment->order_id);
      if($cart){
        $user = User::find($cart->user_id);
        $admin = User::where('user_type','admin')->first();
        if(!$user){
          return response()->json(['error' => 'No user found']);
        }
        if($cart->order_status == 'processing'){
          return response()->json(['error' => 'This order is already placed, kindly intiate refund if multiple payment received for same order.']);
        } else if($cart->order_status == 'cancelled'){
          return response()->json(['error' => 'This order is already cancelled, kindly intiate refund if payment received for this order.']);        
        }
        $cart->order_status = 'processing';
        if($cart->order_type == 'digital_coupons')
        {
          $cart->order_status   = 'completed';
        }
        $cart->placed_at = date('Y-m-d H:i:s');
        $cart->is_cart = '0';
        $cart->is_payment_attempt = '2';
        $cart->payment_status = 'paid';
        //$this->generateInvoice($cart);
        $cart->save();

        //ECom Express
        //$this->ecom_express_api_call($cart);
         if($cart->order_type == 'digital_coupons')
          {
            //assign QR Codes and update refer history
            $this->assign_qr_codes($cart);
            $this->maintainReferralHistory((integer)$cart->redeemed_points, 'deducted', 'no_deduct',$user,'',$cart->id);
          }
        
        $payment->status = 'paid';
        $payment->save();
        DB::commit();

        //###### Send Order Placed Email ######
        if($user->email) {
          $subject = "SSGC- We are happy to announce your order confirmation!";
          Mail::to(trim($user->email))->send(new SendOrderPlacedEmailCustomer($user,$cart,$subject));
          //Mail::to(trim($admin->email))->send(new SendOrderPlacedEmailAdmin($user,$cart,$subject));
        }
    
        //###### Send Order Placed Email ######

        //###### Customer Order Placed Notification ######
        $title    = 'Order Placed Successfully';
        $body     = "Congratulations! Your order ".$cart->order_id.", worth ".$cart->total_payable." Rs. has been placed successfully.";
        $slug     = 'customer_order_placed';
        $this->sendNotification($user,$title,$body,$slug,$cart,null);
        //###### Customer Order Placed Notification ######

        //###### Customer Digital Purchase Notification ######
        $digital_content = false;
        if($cart->order_type == 'digital_coupons') {
           $digital_content = true;
        }
        if($digital_content) {
          $title    = 'Digital Purchase';
          $body     = 'Thanks for purchasing digital coupons, You can access it from "Digital Coupons" section.';
          $slug     = 'customer_digital_purchase';
          $this->sendNotification($user,$title,$body,$slug,null,null);
        }
        //###### Customer Digital Purchase Notification ######

        //###### Admin New Order Placed ######
        //$admin = User::where('user_type','admin')->first();
        if($admin){
            $customer_name = 'Customer';
            if($cart->user->first_name){
              $customer_name = $cart->user->first_name.' '.$cart->user->last_name;
            }
            $title    = $customer_name.' Purchased New Product';
            $body     = $customer_name." purchased new product physical or digital product";
            $slug     = 'admin_new_order_placed';
            $this->sendNotification($admin,$title,$body,$slug,$cart,null);
        }
        //###### Admin New Order Placed ######

        //###### Send Order Placed SMS to Customer #####
        $send_order_placed_sms = $this->sendOrderStatusSMS('',$user->mobile_number,$cart,'order_placed');
        //###### Send Order Placed SMS to Customer #####

        return response()->json(['success' => 'Order placed successfully']);   
      } else {
        return response()->json(['error' => 'No order found for this payment']);     
      }
    } catch (Exception $e) {
      return response()->json(['error' => $e->getMessage()]);
    }
  }

  public function markOrderFailure(Request $request){
    try {
      $settings = Setting::pluck('value','name')->all();  
      $payment = Payment::find($request->payment_id);
      $cart = Order::find($payment->order_id);
      if($cart){
        $user = User::find($cart->user_id);
        $admin = User::where('user_type','admin')->first();
        if(!$user){
          return response()->json(['error' => 'No user found']);
        }
        if($cart->order_status == 'processing'){
          return response()->json(['error' => 'This order is already placed, kindly intiate refund if multiple payment received for same order.']);
        } else if($cart->order_status == 'cancelled'){
          return response()->json(['error' => 'This order is already cancelled, kindly intiate refund if payment received for this order.']);        
        }
        $cart->order_status = 'failed';
        $cart->placed_at = date('Y-m-d H:i:s');
        $cart->is_cart = '0';
        $cart->is_payment_attempt = '2';
        $cart->payment_status = 'failed';
        $cart->save();

        $payment->status = 'failed';
        $payment->save();   
        $id = $this->createDuplicateOrder($cart->id);
        DB::commit();
        return response()->json(['success' => 'Order payment mark as failed successfully']);   
      } else {
        return response()->json(['error' => 'No order found for this payment']);     
      }
    } catch (Exception $e) {
      return response()->json(['error' => $e->getMessage()]);
    }
  }

}