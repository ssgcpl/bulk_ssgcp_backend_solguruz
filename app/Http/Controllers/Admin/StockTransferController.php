<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Stock;
use App\Models\StockTransfer;
use App\Models\Product;
use App\Models\Notification;
use Validator,Auth,DB,Hash;
use App\Models\Helpers\CommonHelper;


class StockTransferController extends Controller
{
    use CommonHelper;

    public function __construct()
    {
      $this->middleware('auth');
      $this->middleware('permission:stock-transfer-list', ['only' => ['index','show']]);
      $this->middleware('permission:stock-transfer-create', ['only' => ['create','store']]);
      $this->middleware('permission:stock-transfer-edit', ['only' => ['edit','update']]);
      $this->middleware('permission:stock-transfer-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request) {
      $page_title = trans('stocks_transfer.heading');
      return view ('admin.stocks_transfer.index',compact('page_title')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index_ajax(Request $request) {


        $query           =    StockTransfer::where('product_id',$request['product_id']);
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
                            $q->where('id','like','%'.$searchValue.'%')
                              ->orWhere('gto_out_qty','like','%'.$searchValue.'%')
                              ->orWhere('gto_in_qty','like','%'.$searchValue.'%')
                              ->orWhere('gto_out_no','like','%'.$searchValue.'%')
                              ->orWhere('scrap_qty','like','%'.$searchValue.'%')
                              ->orWhere('scrap_no','like','%'.$searchValue.'%')
                              ->orWhere('gto_in_no','like','%'.$searchValue.'%');
                     });
        }

        $filter_count = $filter->count();
        $totalRecordwithFilter = $filter_count;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();
        foreach ($empQuery as $emp) {
            $emp['gto_in_no'] = ($emp->gto_in_no)?$emp->gto_in_no : '-';
            $emp['gto_in_qty'] = ($emp->gto_in_qty)?$emp->gto_in_qty : '-';
            $emp['gto_out_no'] = ($emp->gto_out_no)?$emp->gto_out_no : '-';
            $emp['gto_out_qty'] = ($emp->gto_out_qty)?$emp->gto_out_qty : '-';
            $emp['scrap_no'] = ($emp->scrap_no)?$emp->scrap_no : '-';
            $emp['scrap_qty'] = ($emp->scrap_qty)?$emp->scrap_qty : '-';
            $emp['created_date'] = date("d-m-Y",strtotime($emp->created_at));


            ## Set dynamic route for action buttons
            $emp['show']= route("stocks_transfer.show",$emp["id"])."?prod_id=".$emp->product_id;
            $emp['edit']= route("stocks_transfer.edit",$emp["id"])."?prod_id=".$emp->product_id;
            $emp['delete']= route("stocks_transfer.destroy",$emp["id"]);
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
        $page_title = trans('stocks_transfer.admin_heading');
        return view('admin.stocks_transfer.create', compact('page_title'));
      }


      public function store(Request $request){
        $data = $request->all();
       
      	if($data['type'] == 'gto_in') {
      	 $validator = Validator::make($request->all(),[
          'gto_in_no' => 'required|numeric',
          'gto_in_qty' =>'required|numeric',
           ]); 
      	}else if($data['type'] == 'gto_out'){
      	 $validator = Validator::make($request->all(),[
          'gto_out_no' => 'required|numeric',
          'gto_out_qty' =>'required|numeric',
           ]);

      	}else if($data['type'] == 'scrap_return'){
      	 $validator = Validator::make($request->all(),[
          'scrap_no' => 'required|numeric',
          'scrap_qty' =>'required|numeric',
           ]);
      	}
       
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->first()]);
        }
        DB::beginTransaction();
        try {

          $user = Auth::user();
        
          $stock_transfer = new StockTransfer();

          $data['product_id'] = $request->product_id;
          $data['added_by'] = $user->id;

          $stock_transfer->fill($data);

          $stock_transfer->save();

          if($stock_transfer){
			  DB::commit();
              return response()->json(['success' => trans('stocks_transfer.added')]);
          } else {
              DB::rollback();
              return response()->json(['error' => trans('stocks_transfer.error')]);
             
          }
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
      }


      public function show(Request $request,$id)
      {
      	$page_title  = trans('stocks_transfer.update');
        $stock_transfer  = StockTransfer::find($id);
        if(empty($stock_transfer)){
            return redirect()->route('stocks_transfer.index')->with('error',trans('common.no_data'));
        }
        return view('admin.stocks_transfer.show',compact('stock_transfer','page_title'));
      }

      public function edit(Request $request,$id)
      {
      	$page_title  = trans('stocks_transfer.update');
        $stock_transfer  = StockTransfer::find($id);
        if(empty($stock_transfer)){
            return redirect()->route('stocks_transfer.index')->with('error',trans('common.no_data'));
        }
        return view('admin.stocks_transfer.edit',compact('stock_transfer','page_title'));
      }

     public function update(Request $request,$id)
     {
     	$data = $request->all();
       
      	if($data['type'] == 'gto_in') {
      	 $validator = Validator::make($request->all(),[
          'gto_in_no' => 'required|numeric',
          'gto_in_qty' =>'required|numeric',
           ]); 
      	}else if($data['type'] == 'gto_out'){
      	 $validator = Validator::make($request->all(),[
          'gto_out_no' => 'required|numeric',
          'gto_out_qty' =>'required|numeric',
           ]);

      	}else if($data['type'] == 'scrap_return'){
      	 $validator = Validator::make($request->all(),[
          'scrap_no' => 'required|numeric',
          'scrap_qty' =>'required|numeric',
           ]);
      	}
       
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->first()]);
        }
        DB::beginTransaction();
        try {

          $user = Auth::user();
        
          $stock_transfer = StockTransfer::find($id);

          $data['product_id'] = $request->product_id;
          $data['added_by'] = $user->id;

          $stock_transfer->fill($data);
          $stock_transfer->save();

          if($stock_transfer){
			  DB::commit();
              return response()->json(['success' => trans('stocks_transfer.updated')]);
          } else {
              DB::rollback();
              return response()->json(['error' => trans('stocks_transfer.error')]);
             
          }
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }    

      }

      public function destroy(Request $request,$id)
      {
        $stock = StockTransfer::find($id);

        if($stock->delete()){
            return redirect()->back()->with('success',trans('stocks_transfer.deleted'));
        }else{
            return redirect()->back()->with('error',trans('stocks_transfer.error'));
        }
      }

}
