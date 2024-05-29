<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Helpers\CommonHelper;
use DB;


class ReportController extends Controller
{
    use CommonHelper;

    public function __construct()
    {
      $this->middleware('auth');
      $this->middleware('permission:datewise-report', ['only' => ['index','show']]);
    }

    public function index(Request $request) {
        $page_title = trans('stocks.datewise_stock_report_heading');
        return view ('admin.reports.datewise_report',compact('page_title'));
    }
    public function index_ajax(Request $request) {

        $query           = Product::query();
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

        
        $from_date       =    $request['start_date'];
        $to_date         =    $request['end_date'];

        if($from_date != null && $to_date != null){
            $from_date = date("Y-m-d",strtotime($from_date));
            $to_date = date("Y-m-d",strtotime($to_date));
            $query->whereHas('order_items.order', function ($q) use ($from_date, $to_date) {
              $q->whereBetween('placed_at', [$from_date." 00:00:00",$to_date." 23:59:00"]);
          });
        }

        
        ## Total number of record with filtering
        $filter = $query;

        if($searchValue != ''){
        $filter = $filter->where(function($q)use ($searchValue) {
                            $q->where('name_english','like','%'.$searchValue.'%')
                            ->orWhere('name_hindi','like','%'.$searchValue.'%')
                            ->orWhere('sku_id','like','%'.$searchValue.'%')
                            ->orWhere('id','=',$searchValue);
                     });
        }

        $filter_count = $filter->count();
        $totalRecordwithFilter = $filter_count;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();
        $whereconditions_for_hold_qty = [
            ['orders.order_status','=','on_hold'],
        ];

        foreach ($empQuery as $emp) {
           $emp['selected'] = '<input type="checkbox" class="mark" value="'.$emp->id.'" >';
        
           $emp['name'] = $emp->get_name();
        	 $emp['sku_id'] = $emp->sku_id;
            
           $stock_data = $this->getStockData($emp->id); 
           $emp['return_go_down'] = $stock_data['return_go_down'];
           $emp['balance_outside'] = $stock_data['balance_outside'];
           $emp['balance_inside'] =  $stock_data['balance_inside'];
           $emp['total_hold_order_qty'] = $this->getTotalHoldOrderQuantity($emp->id,$from_date, $to_date);
            ## Set dynamic route for action buttons
          // $emp['edit']= route('stocks.index')."?prod_id=".$emp->id;
           $emp['need'] = $emp['total_hold_order_qty']  - $emp['balance_inside'];
           $balance_inside_plus_need = $emp['balance_inside'] + $emp['need'];
           $emp['possiable_book_qty'] =  $emp['balance_outside'];
           if($balance_inside_plus_need < $emp['possiable_book_qty'])
            {
                $emp['possiable_book_qty'] = $balance_inside_plus_need;
            }
            $emp['order_qty'] = $emp['possiable_book_qty'] - $emp['balance_inside'];
 
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


    public function download_stock_report_pdf(Request $request)
    {
        $prod_ids  = $request->id;
        $order = $request->order;
        $length = $request->length;
        $start = $request->start_id;
        $data_array = [];
         if($prod_ids == '') {

              if($length != '-1') {
                //$ids =  Order::where('is_cart','0')->orderBy('id',$order)->limit($length)->get();
                if($order == 'asc'){
                  $ids = Product::where('id', '>=', $start)
                      ->take($length)->orderBy('id',$order)->get();
                }else {
                  $ids = Product::where('id', '<=', $start)
                      ->take($length)->orderBy('id',$order)->get();
                }
              }else {
                $ids =  Product::Query()->orderBy('id',$order)->get();
              }
            /*  $data =  Product::Query();
              $ids = $data->get();*/
              foreach ($ids as $id) {
                $prod_ids[] = $id->id;
              } 
          }
        foreach ($prod_ids  as $id=>$value){
            $item =  Product::where('id',$value)->first();

            $stock_data = $this->getStockData($value);
            $total_pof = $stock_data['total_pof'];
                $total_ecm = $stock_data['total_ecm'];
            $total_gro = $stock_data['total_gro'];
            $scrap_qty =$stock_data['scrap_qty'];
            $order_supplied = $stock_data['order_supplied'];
            $return_go_down = $stock_data['return_go_down'];
            $total_gro = $stock_data['total_gro'];
            $balance_outside = $stock_data['balance_outside'];
            $balance_inside =  $stock_data['balance_inside'];
            $total_balance =$stock_data['total_balance'];
         
            $data_array[] = ['id' => $item->id,
                             'heading' => $item->get_name(),
                             'sku_id' => $item->sku_id,
                             'stock_status' =>ucfirst(str_replace('_',' ',$item->stock_status)),
                             'mrp' => $item->mrp,
                             'dealer_sale_price' => $item->dealer_sale_price,
                             'retailer_sale_price' =>$item->retailer_sale_price,
                             'date'=>$item->created_at,
                             'visible_to' =>$item->visible_to,
                             'total_pof'=> $total_pof,
                             'total_ecm'=> $total_ecm,
                             'total_gro'=> $total_gro,
                             'order_supplied'=> $order_supplied,
                             'return_go_down'=> $return_go_down,
                             'balance_outside'=> $balance_outside,
                             'balance_inside'=> $balance_inside,
                             'total_balance'=>$total_balance,
                             'scrap_qty'=>$scrap_qty,
                           ];

        }

        $dir = public_path('uploads/stock_report_pdf');
        if (! is_dir($dir))
            mkdir($dir, 0777, true);
       // return view('stock_report_pdf',compact('data_array'));  
        $pdf = \PDF::loadView('stock_report_pdf', ['data_array' => $data_array]);
        $name = 'uploads/stock_report_pdf'.'/stock_report_pdf.pdf';
        $pdf->save($name);
        // return asset($name);

        if($name) {
            return response()->json(['success' => 'Stock Report Exported Successfully', 'data' => asset($name)]);
        } else {
            return response()->json(['error' => 'Error in Stock Report Exported']);
        }
    }
    public function getTotalHoldOrderQuantity($productId,$startDate = null, $endDate = null)
    {
            $whereconditions_for_hold_qty = [
              ['orders.order_status', '=', 'on_hold'],
              ['order_items.product_id', '=', $productId]
          ];

          // Start building the query
          $query = DB::table('order_items')
              ->join('orders', 'order_items.order_id', '=', 'orders.id')
              ->where($whereconditions_for_hold_qty);

          // Add date range filter if both dates are provided
          if ($startDate != null && $endDate != null) {
              $query->whereBetween('orders.placed_at', [
                  $startDate . " 00:00:00",
                  $endDate . " 23:59:59"
              ]);
          }

          $result = $query->select(DB::raw('SUM(order_items.ordered_quantity) as total_quantity'))->first();
        
      return $result && ($result->total_quantity !== null && $result->total_quantity !== '') ? $result->total_quantity : 0;
    }
    
}
