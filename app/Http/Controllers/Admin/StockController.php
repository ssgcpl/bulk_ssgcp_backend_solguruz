<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Stock;
use App\Models\StockGro;
use App\Models\StockTransfer;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\OrderReturnItem;
use App\Models\Notification;
use Validator,Auth,DB,Hash;
use App\Models\Helpers\CommonHelper;


class StockController extends Controller
{
     use CommonHelper;

    public function __construct()
    {
      $this->middleware('auth');
      $this->middleware('permission:stock-list', ['only' => ['index','show']]);
      $this->middleware('permission:stock-create', ['only' => ['create','store']]);
      $this->middleware('permission:stock-edit', ['only' => ['edit','update']]);
      $this->middleware('permission:stock-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request) {
      $page_title = trans('stocks.heading');
      return view ('admin.stocks.index',compact('page_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index_ajax(Request $request) {


        $query           =    Stock::where('product_id',$request['product_id']);
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
        $filter = $filter->where(function($q) use ($searchValue){
                            $q->orWhere('id','like','%'.$searchValue.'%')
                            ->orwhere('pof_no','like','%'.$searchValue.'%')
                            ->orWhere('pof_qty','like','%'.$searchValue.'%')
                            ->orWhere('ecm_no','like','%'.$searchValue.'%')
                            ->orWhere('ecm_qty','like','%'.$searchValue.'%');
                          });
        }

        $filter_count = $filter->count();
        $totalRecordwithFilter = $filter_count;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();
        foreach ($empQuery as $emp) {
            $emp['pof_no'] = $emp->pof_no;
            $emp['pof_qty'] = $emp->pof_qty;
            $emp['ecm_no'] = $emp->ecm_no;
            $emp['ecm_qty'] = $emp->ecm_qty;

            $emp['gro_no'] = "<a href='".route('view_gro_detail')."?stock_id=".$emp->id."&prod_id=".$emp->product_id."'>View</>";
            $gro_data =  StockGro::select(
                            DB::raw("sum(gro_qty) as gro_total_qty"),
                        )
                        ->where('stock_id', $emp->id)
                        ->groupBy('stock_id')
                        ->first();
            $emp['gro_total_qty'] = @$gro_data['gro_total_qty'];
            $emp['created_date'] = date("d-m-Y",strtotime($emp->created_at));
            ## Set dynamic route for action buttons
            $emp['show']= route("stocks.show",$emp["id"])."?prod_id=".$emp->product_id;
            $emp['edit']= route("stocks.edit",$emp["id"])."?prod_id=".$emp->product_id;
            $emp['delete']= route("stocks.destroy",$emp["id"]);
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


      public function get_stock_summary(Request $request){
            $product_id = $request['product_id'];
            $data = $this->getStockData($product_id);
            return json_encode($data);
      }

      public function create(){
        $page_title = trans('stocks.admin_heading');
        return view('admin.stocks.create', compact('page_title'));
      }


      public function store(Request $request)
      {

         $validator = Validator::make($request->all(),[
          'pof_no' => 'required|numeric',
          'pof_qty' =>'required|numeric',
          'ecm_no' => 'required|numeric',
          'ecm_no' => 'required|numeric',
          ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->first()]);
        }
        DB::beginTransaction();
        try {

          $data = $request->all();
          $user = Auth::user();

          $stock = new Stock();

          $data['product_id'] = $request->product_id;
          $data['added_by'] = $user->id;

          $stock->fill($data);
          $stock->save();

          if($stock){

              $stock_id = $stock->id;

              if(isset($data['gro_no']) && isset($data['gro_qty'])) {

                for($i=0; $i<count($data['gro_no']); $i++){
                  $gro_no = $data['gro_no'][$i];
                  $gro_qty = $data['gro_qty'][$i];
                  StockGro::create([
                      "stock_id" => $stock_id,
                      "gro_no"   => $gro_no,
                      "gro_qty"  => $gro_qty,
                  ]);
                }

              }
          }
          if($stock) {
              DB::commit();
              return response()->json(['success' => trans('stocks.added')]);
          } else {
              DB::rollback();
              return response()->json(['error' => trans('stocks.error')]);

          }
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
      }


      public function show(Request $request,$id)
      {
        $page_title  = trans('stocks.show');
        $stock       = Stock::find($id);
        $stock_gro   = StockGro::where('stock_id',$id)->get();
        if(empty($stock)){
            return redirect()->route('stocks.index')->with('error',trans('common.no_data'));
        }
        return view('admin.stocks.show',compact('stock','stock_gro','page_title'));
      }
      public function edit(Request $request,$id)
      {
        $page_title  = trans('stocks.update');
        $stock       = Stock::find($id);
        $stock_gro   = StockGro::where('stock_id',$id)->get();
        if(empty($stock)){
            return redirect()->route('stocks.index')->with('error',trans('common.no_data'));
        }
        return view('admin.stocks.edit',compact('stock','stock_gro','page_title'));
      }

      public function update(Request $request,$id){
        try {
          DB::beginTransaction();
          $data       =  $request->all();
          $validator = Validator::make($request->all(),[
            'pof_no' => 'required|numeric',
            'pof_qty' =>'required|numeric',
            'ecm_no' => 'required|numeric',
            'ecm_no' => 'required|numeric',
          ]);
          if($validator->fails()){
              return response()->json(['error' => $validator->errors()->first()]);
          }

          $stock = Stock::find($id);

          if(empty($stock))
          {
              return redirect()->route('stocks.index')->with('error',trans('admin.error'));
          }

          $user = Auth::user();
          $data['added_by'] = $user->id;
             // print_r($data); die;
          $stock->fill($data);
          $stock->save();

          if($stock) {

              if(isset($data['gro_no']) && isset($data['gro_qty'])) {
                StockGro::where('stock_id',$id)->delete();
                for($i=0; $i<count($data['gro_no']); $i++){
                  $gro_no = $data['gro_no'][$i];
                  $gro_qty = $data['gro_qty'][$i];
                  StockGro::create([
                      "stock_id" => $id,
                      "gro_no"   => $gro_no,
                      "gro_qty"  => $gro_qty,
                  ]);
                }
              }
              DB::commit();
              return response()->json(['success' => trans('stocks.updated')]);
            } else {
                DB::rollback();
                return response()->json(['error' => trans('stocks.error')]);
            }
        }catch (\Exception $e) {
              DB::rollback();
              return response()->json(['error' => $e->getMessage()]);
        }

      }

      public function destroy(Request $request,$id)
      {
        $stock_gro = StockGro::where('stock_id',$id)->delete();
        $stock = Stock::find($id);

        if($stock->delete()){
            return redirect()->back()->with('success',trans('stocks.deleted'));
        }else{
            return redirect()->back()->with('error',trans('stocks.error'));
        }
      }


       public function view_gro(Request $request) {
          $page_title = trans('stocks.heading');
          return view ('admin.stocks.view_gro_detail',compact('page_title'));
        }

        public function index_gro_ajax(Request $request) {


        $query           =    StockGro::where('stock_id',$request['stock_id']);
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
        $filter = $filter->where(function($q)use ($searchValue) {
                            $q->where('stock_id','like','%'.$searchValue.'%')
                            ->orWhere('id','like','%'.$searchValue.'%')
                            ->orWhere('gro_no','like','%'.$searchValue.'%')
                            ->orWhere('gro_qty','like','%'.$searchValue.'%');
                     });
        }

        $filter_count = $filter->count();
        $totalRecordwithFilter = $filter_count;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();
        foreach ($empQuery as $emp) {
            $emp['gro_no'] = $emp->gro_no;
            $emp['gro_qty'] = $emp->gro_qty;

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


}
