<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Stock;
use App\Models\StockGro;
use App\Models\OrderItem;
use App\Models\OrderReturnItem;
use App\Models\StockTransfer;
use App\Models\ProductCoverImage;
use App\Models\ProductCategory;
use App\Models\RelatedProduct;
use App\Models\DealerRetailer;
use App\Models\BusinessCategory;
use App\Models\Notification;
use Validator,Auth,DB,Hash;
use App\Models\Helpers\CommonHelper;


class StockReportController extends Controller
{
    use CommonHelper;

    public function __construct()
    {
      $this->middleware('auth');
      $this->middleware('permission:stock-report-list', ['only' => ['index','show']]);
      $this->middleware('permission:stock-report-create', ['only' => ['create','store']]);
      $this->middleware('permission:stock-report-edit', ['only' => ['edit','update']]);
      $this->middleware('permission:stock-report-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index() {
     $page_title = trans('stocks.stock_report_heading');
     return view ('admin.stock_report.index',compact('page_title')); 
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

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

         ## Filter by Stock Status
          if($request['stock_status'] ==''){
            $query = $query;
          }else if ($request['stock_status'] =='in_stock'){
              $query = $query->where('stock_status','in_stock');
          }else if ($request['stock_status'] =='out_of_stock'){
             $query = $query->where('stock_status','out_of_stock');
          } 

        $from_date       =    $request['start_date'];
        $to_date         =    $request['end_date'];
        if($from_date != null && $to_date != null){
           $from_date = date("Y-m-d",strtotime($from_date));
            $to_date = date("Y-m-d",strtotime($to_date));
        	$query = $query->whereBetween('created_at',[$from_date." 00:00:00",$to_date." 23:59:00"]);
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
        foreach ($empQuery as $emp) {
          $emp['selected'] = '<input type="checkbox" class="mark" value="'.$emp->id.'" >';
        	$emp['image'] = ($emp->image) ? '<img src="'.asset($emp->image).'" 
        	width="80%" height="80%">' : '';
        //	$emp['sub_heading'] = ($emp->sub_heading_english)?$emp->sub_heading_english : $emp->sub_heading_hindi;
          $emp['name'] = $emp->get_name();
        	$emp['sku_id'] = $emp->sku_id;
            if($emp->stock_status == 'in_stock'){
                $stock_status = '<span style="color:green">'.trans('products.'.$emp->stock_status).'</span>';
            }else if($emp->stock_status == 'out_of_stock'){
                $stock_status = '<span style="color:red">'.trans('products.'.$emp->stock_status).'</span>';
            }
            $emp['stock_status'] = $stock_status;
        	$emp['visible_to'] = ucfirst($emp->visible_to);
        	$emp['mrp'] = $emp->mrp;
        	$emp['dealer_sale_price'] = $emp['dealer_sale_price'];
        	$emp['retailer_sale_price'] = $emp['retailer_sale_price'];
        	$emp['created_date'] = $emp->created_at->format('d-m-Y H:m A');
        	$type = BusinessCategory::where('id',$emp->business_category_id)->first();
            $emp['type'] = $type->category_name;
            $emp['stock'] = '';
            $emp['status'] = $emp->status;

            $stock_data = $this->getStockData($emp->id); 
            $emp['total_pof'] = $stock_data['total_pof'];
            $emp['total_ecm'] = $stock_data['total_ecm'];
            $emp['total_gro'] = $stock_data['total_gro'];
            $emp['order_supplied'] = $stock_data['order_supplied'];
            $emp['return_go_down'] = $stock_data['return_go_down'];
            $emp['balance_outside'] = $stock_data['balance_outside'];
            $emp['balance_inside'] =  $stock_data['balance_inside'];
            $emp['total_balance'] = $stock_data['total_balance'];
            $emp['scrap_qty']  = $stock_data['scrap_qty']; 


            ## Set dynamic route for action buttons
           $emp['edit']= route('stocks.index')."?prod_id=".$emp->id;
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



}
