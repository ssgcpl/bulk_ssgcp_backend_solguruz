<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Helpers\CommonHelper;
use App\Models\Order;

use DB;

class OrderReportController extends Controller
{
    use CommonHelper;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:order-report', ['only' => ['index', 'show']]);
    }

    public function index(Request $request)
    {
        $page_title = trans('orders.order_report');
        $on_hold = Order::where('order_status', 'on_hold')->count();
        $pending_payment = Order::where('order_status', 'pending_payment')->count();
        $processing = Order::where('order_status', 'processing')->count();
        $shipped = Order::where('order_status', 'shipped')->count();
        $completed = Order::where('order_status', 'completed')->count();
        $refunded = Order::where('order_status', 'refunded')->count();
        $cancelled = Order::where('order_status', 'cancelled')->count();
        $total_sale = Order::select(
            DB::raw("sum(total_sale_price) as total"),
        )->first();
        $total_sale_price = $total_sale['total'];
        return view('admin.reports.order_report', compact('page_title', 'on_hold', 'pending_payment', 'processing', 'shipped', 'completed', 'refunded', 'cancelled', 'total_sale_price'));
    }
    public function index_ajax(Request $request)
    {

        // $query = Order::where(function ($query) {
        //     $query->has('payments')
        //         ->whereIn('order_status', ['failed', 'pending'])
        //         ->where('is_payment_attempt', '<>', '0');
        // })->orWhere(function ($query) {
        //     $query->where('is_cart', '0')
        //         ->where('order_status', '<>', 'pending')
        //         ->where('order_status', '<>', 'failed');
        // });
        $query           =    Order::where('is_cart', '0')->where('order_status', '<>', 'pending')->where('order_status', '<>', 'failed');
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
        if ($from_date != null && $to_date != null) {
            $from_date = date("Y-m-d", strtotime($from_date));
            $to_date = date("Y-m-d", strtotime($to_date));
            $query = $query->whereBetween('placed_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }

        ## Filter by visible to
        if ($request['user_type'] != '') {
            $user_type = $request['user_type'];
           
            $query = $query->where('user_type', $user_type);
        }

        ## Filter by visible to
        if (isset($request['order_type']) && $request['order_type'] != '') {
            $order_type = $request['order_type'];
            $query = $query->where('order_type', $order_type);
        }

        ## Filter by visible to
        if ($request['order_status'] != '') {
            $order_status = $request['order_status'];
            $query = $query->where('order_status', $order_status);
        }
        if ($request['payment_status'] != '') {
            $payment_status = $request['payment_status'];
            $query = $query->where('payment_status', $payment_status);
        }
        ## Total number of record with filtering
        $filter = $query;

        if ($searchValue != '') {
            $filter = $filter->whereHas('user', function ($q1) use ($searchValue) {
                $q1->where('company_name', 'like', '%' . $searchValue . '%');
                $q1->orwhere('mobile_number', 'like', $searchValue);
                $q1->orwhere('email', 'like', $searchValue);
            })->orWhere(function ($q) use ($searchValue) {
                $q->where('id', $searchValue)
                    //->where('order_status','<>','pending')
                    //->where('order_status','<>','failed')
                    ->where('is_cart', '0');
            });
        }

        $filter_count = $filter->count();
        $totalRecordwithFilter = $filter_count;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();
        foreach ($empQuery as $emp) {
           // $on_hold = $emp->statuses->where('status', 'on_hold')->first();
            $processing = $emp->statuses->where('status', 'processing')->first();
            $shipped = $emp->statuses->where('status', 'shipped')->first();
            $completed = $emp->statuses->where('status', 'completed')->first();
            $emp['on_hold'] = $emp->placed_at ? date('d-m-Y h:i A', strtotime($emp->placed_at)) : '-';
            $emp['processing'] = $processing ? date('d-m-Y h:i A', strtotime($processing->created_at)) : '-';
            $emp['shipped'] = $shipped ? date('d-m-Y h:i A', strtotime($shipped->created_at)) : '-';
            $emp['completed'] = $completed ? date('d-m-Y h:i A', strtotime($completed->created_at)) : '-';
  
            $orderItems = $emp->order_items;
            $emp['selected'] = '<input type="checkbox" class="mark" value="' . $emp->id . '" >';
            $emp['company_name'] = $emp->user->company_name;
            $emp['mobile_number'] = $emp->user->mobile_number;
            $emp['total_items'] = count($orderItems);
            $order_status = ucfirst(str_replace('_', ' ', $emp->order_status));
            $color_code = 'FFA500';
            if ($emp->order_status == 'processing') {
                $color_code = "#FFD700";
            } else if ($emp->order_status == 'pending_payment') {
                $color_code = "#00CED1";
            } else if ($emp->order_status == 'on_hold') {
                $color_code = "#FFA500";
            } else if ($emp->order_status == 'shipped') {
                $color_code = "#32CD32";
            } else if ($emp->order_status == 'completed') {
                $color_code = "#008000";
            } else if ($emp->order_status == 'cancelled') {
                $color_code = "#FF0000";
            } else if ($emp->order_status == 'refunded') {
                $color_code = "#483D8B";
            }
            //   else if($emp->order_status == 'pending' && $emp->is_payment_attempt == '1'){
            //         $order_status = 'Under Process';
            //   }else{
            //         $order_status = $emp->order_status;
            //   }

            $emp['order_status'] = '<span style="color:' . $color_code . '">' . $order_status . '</span>';
            $emp['payment_status'] = ucfirst($emp->payment_status);
            $emp['order_amount'] = $emp->total_payable;

            if ($emp->placed_at == Null) {
                $starttimestamp = strtotime($emp->created_at);
                $endtimestamp = strtotime(date("Y-m-d H:i:s"));
                $difference = round(abs($endtimestamp - $starttimestamp) / 3600);
                if ($difference <= 24) {
                    $emp['placed_at']  = $difference . " Hours Ago";
                } else {
                    $emp['placed_at']  = date("d-m-Y h:i A", strtotime($emp->created_at));
                }
            } else {
                $starttimestamp = strtotime($emp->placed_at);
                $endtimestamp = strtotime(date("Y-m-d H:i:s"));
                $difference = round(abs($endtimestamp - $starttimestamp) / 3600);
                if ($difference <= 24) {
                    $emp['placed_at']  = $difference . " Hours Ago";
                } else {
                    $emp['created_date']  = date("d-m-Y h:i A", strtotime($emp->placed_at));
                }
            }


            ## Set dynamic route for action buttons
            //$emp['show']= route("orders.show",$emp["id"]);
            if (strtolower($emp->order_type) == 'physical_books') {
                $emp['edit'] = route("orders.edit", $emp["id"]);
            } else if (strtolower($emp->order_type) == 'digital_coupons') {
                $emp['edit'] = route("coupon_orders.edit", $emp["id"]);
            }
            $data[] = $emp;
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
