<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\OrderNote;
use Validator, Auth, DB, Hash;
use App\Models\CouponQrCode;
use App\Models\OrderAddress;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\BusinessCategory;
use App\Models\OrderCouponQrCode;
use App\Http\Controllers\Controller;
use App\Models\Helpers\CommonHelper;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOrderUpdateNotifyEmailCustomer;
use App\Mail\SendOrderManuallyNotifyEmailCustomer;

class CouponOrderController extends Controller
{
    use CommonHelper;
    public function edit(Request $request, $id)
    {
        $page_title = trans('orders.show');
        $order = Order::find($id);
        $user_type = $order->user->user_type;
        $coupon_code_sold = OrderItem::where('order_id',$id)
                                    ->whereHas('order_coupon_qr_codes',function($q){ 
                                    $q->where('status', 'sold'); })->count();
        //$shipping_address = OrderAddress::where(['is_shipping'=>'1','order_id'=>$id])->first();
        //$billing_address = OrderAddress::where(['is_billing'=>'1','order_id'=>$id])->first();
        $order_notes  = OrderNote::where('order_id', $id)->orderBy('id', 'DESC')->get();

        return view('admin.coupon_orders.edit', compact('page_title', 'order', 'user_type', 'order_notes','coupon_code_sold'));
    }

    public function ajax_order_items(Request $request)
    {

        $query           =    OrderItem::where('order_id', $request->order_id);
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
                            $q->whereHas('coupon', function ($q) use ($searchValue) {
                                $q->whereHas('coupon', function ($q) use ($searchValue) {
                                    $q->where('name', 'like', '%' . $searchValue . '%');
                                });
                            });
                            $q->orWhere('id', $searchValue)
                            ->orWhere('ordered_quantity', $searchValue);
                        });
        }

        $filter_count = $filter->count();
        $totalRecordwithFilter = $filter_count;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();
        foreach ($empQuery as $emp) {


            $emp['product_name'] = $emp->coupon->coupon->name;
            $emp['product_image'] = ($emp->coupon->image) ? '<img src="' . asset($emp->coupon->image) . '" width="100%" height="100%">' : '';
            $emp['total_sale_price'] = $emp->sale_price * $emp->supplied_quantity;
            $emp['available_stock'] = '0';
            $emp['ordered_qty'] = $emp->ordered_quantity;
            ## Set dynamic route for action buttons
            $emp['show'] = route("coupon_orders.show", $emp["id"]);
            $data[] = $emp;
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

    public function show(Request $request, $id)
    {
        $page_title = trans('orders.show');
        $order_item = OrderItem::find($id);
        return view('admin.coupon_orders.show', compact('page_title', 'order_item'));
    }

    public function ajax_order_items_qr_codes(Request $request)
    {
        $query           =    OrderCouponQrCode::where('order_item_id', $request->order_item_id);
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
            $filter =  $filter->Where(function ($q) use ($searchValue) {
                $q->Where('customer_contact', 'like', '%' . $searchValue . '%')
                    ->orWhere('customer_name', 'like', '%' . $searchValue . '%');

                $q->OrwhereHas('coupon_qr_code', function ($q) use ($searchValue) {
                    $q->Where('qr_code_value', 'like', '%' . $searchValue . '%');
                });
            });
        }

        $filter_count = $filter->count();
        $totalRecordwithFilter = $filter_count;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();
        $i = 1;
        foreach ($empQuery as $emp) {
            ## Set dynamic route for action buttons
            $emp['unique_code'] = $emp->coupon_qr_code ? $emp->coupon_qr_code->qr_code_value : '';
            $emp['qr_code'] = $emp->coupon_qr_code ? '<img src="' . asset($emp->coupon_qr_code->qr_code) . '" width="100%" height="100%">' : '';
            $emp['sale_price'] =@($emp['sale_price']!= '0.00') ? ($emp->sale_price)  : '';
            // $emp['customer_name'] = $emp->order_coupon_qr_code ? $emp->order_coupon_qr_code->customer_name : '';
            $emp['customer_name'] = $emp->customer_name;
            $emp['customer_contact'] = $emp->customer_contact;
            $emp['id'] = $row + $i;
            $data[] = $emp;
            $i++;
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
}
