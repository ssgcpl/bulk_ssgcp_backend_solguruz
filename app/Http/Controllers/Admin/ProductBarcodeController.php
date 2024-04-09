<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductBarcode;
use App\Models\ProductBarcodeCSVFile;
use App\Models\BusinessCategory;
use Validator,Auth,DB,Hash;
use App\Models\Helpers\CommonHelper;
use App\Jobs\DownloadBarcodeCSV;
use App\Jobs\DeleteBarcodeCSV;
use Picqer;
use PDF;	


class ProductBarcodeController extends Controller
{
    use CommonHelper;

    public function __construct()
    {
      $this->middleware('auth');
      $this->middleware('permission:product-barcode-list', ['only' => ['index','show']]);
      $this->middleware('permission:product-barcode-create', ['only' => ['create','store']]);
      $this->middleware('permission:product-barcode-edit', ['only' => ['edit','update']]);
      $this->middleware('permission:product-barcode-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index() {
      $page_title = trans('product_barcodes.heading');
      $type = BusinessCategory::where(['layout'=>'books','status'=>'active'])->get();
      return view ('admin.product_barcodes.index',compact('page_title','type')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index_ajax(Request $request) {

        $i = 1;
        //$ids             =    ProductBarcode::orderBy('created_at','DESC')->pluck('product_id');
        //$query           =    Product::whereIn('id',$ids);
        // $ids             = ProductBarcode::orderBy('created_at','DESC')->pluck('product_id')->toArray();
        // $query           = Product::whereIn('id', $ids)
        //                     ->orderByRaw('FIELD (id, ' . implode(', ', $ids) . ') ASC');

        $query = Product::has('barcodes')->withCount('barcodes')->orderBy('barcodes_count','desc');

            
        $request         =    $request->all();
        $totalRecords    =    $query->count();
        $draw            =    $request['draw'];
        $row             =    $request['start'];
        $length = ($request['length'] == -1) ? $totalRecords : $request['length'];
        $rowperpage      =    $length; // Rows display per page
        $columnIndex     =    $request['order'][0]['column']; // Column index
        $columnName      =    $request['columns'][$columnIndex]['data']; // Column name
       // $columnSortOrder =    $request['order'][0]['dir']; // asc or desc
        $searchValue     =    $request['search']['value']; // Search value

       
        ## Total number of records without filtering
        $total = $query->count();
        $totalRecords = $total;

           ## Filter by type

          if(isset($request['type']) && $request['type'] != ''){
           
            $query =  $query->where('business_category_id',$request['type']);
                          
          }

          ## from/to date filter
          if($request['start_date'] != '' && $request['end_date'] != ''){
             $from_date = date("Y-m-d",strtotime($request['start_date']));
             $to_date = date("Y-m-d",strtotime($request['end_date']));
             $query = $query->whereHas('latest_barcode',function($q) use ($from_date,$to_date){
                          $q->whereBetween('created_at',[$from_date.' 00:00:00',$to_date.' 23:59:59']);
                          });
    
          }

        ## Total number of record with filtering
        $filter = $query;

        if($searchValue != ''){
        $filter = $filter->where(function($q)use ($searchValue) {
                            $q->where('name_english','like','%'.$searchValue.'%')
                            ->orWhere('name_hindi','like','%'.$searchValue.'%')
                            ->orWhere('status','like','%'.$searchValue.'%')
                            ->orWhere('products.id','like','%'.$searchValue.'%')
                            ->orWhere('sku_id','like','%'.$searchValue.'%');
                     });
        }

        $filter_count = $filter->count();
        $totalRecordwithFilter = $filter_count;

        ## Fetch records
        $empQuery = $filter;
        //$empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $empQuery = $empQuery->offset($row)->limit($rowperpage)->get();
        $data = array();
        foreach ($empQuery as $emp) {
          $emp['selected'] = '<input type="checkbox" class="mark" value="'.$emp->id.'" >';
          $emp['num'] = $i;
          $emp['sku_id'] = $emp->sku_id;
          $emp['name'] = $emp->get_name();
          $barcode_qty =  ProductBarcode::select(
                          DB::raw("count(*) as barcode_qty"),
                          )->where('product_id', $emp->id)->count(); 
          $emp['total_barcode_qty'] =$barcode_qty;
          //$emp['created_date'] = date('d-m-Y H:i A',strtotime($emp->latest_barcode->created_at));
          $emp['created_at'] = $emp->latest_barcode->created_at;
        	$type = BusinessCategory::where('id',$emp->business_category_id)->first();
            $emp['type'] = $type->category_name;
            ## Set dynamic route for action buttons
            $emp['show']= route("product_barcodes.show",$emp["id"]);
            $emp['link']= '<a href="javascript:void(0)" class="add_more_barcode" id="'.$emp->id.'">Add More</a>';
            $data[]=$emp;
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

    public function create(){
        $page_title = trans('product_barcodes.admin_heading');
        $products = Product::where(['status'=>'active','is_live'=>'1'])->orderBy('id','desc')->get();
        return view('admin.product_barcodes.create', compact('page_title','products'));
    }

    public function get_product_detail(Request $request,$id){
    	$product_info = Product::find($id);
    	if(!$product_info){
    		return response()->json(['error' => trans('product_barcodes.error')]);
    	}
      $data = ["name"=> $product_info->get_name(),"sku_id"=> $product_info->sku_id,"id"=>$product_info->id] ;
    	return response()->json(['success' => trans('product_barcodes.detail_found'),'data'=>$data]);
    }

    public function store(Request $request){

    	 $validator = Validator::make($request->all(),[
          'barcode_qty'           => 'required',
          'product_id'            => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->first()]);
        }

        DB::beginTransaction();
        try {

            $data = $request->all();
            $user = Auth::user();
            $bar_code = '';
            

            if (!is_dir('uploads/bar_codes/')) {
                   mkdir('uploads/bar_codes/', 0777, true);
            }

        	  $product_id = $request->product_id;
            // $save_path= 'uploads/qr_codes/'.$fileNameToStore; //QR CODE LOCATION
            $quantity = $request->barcode_qty;

            for ($i = 1; $i <= $quantity; $i++) {

            	$generator = new Picqer\Barcode\BarcodeGeneratorHTML();
              //$unique_code = substr(str_shuffle("081231723897"), 0,12);//RANDOM
              //$unique_code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0,12);//RANDOM
                $unique_code = substr(str_shuffle("0123456789"), 0,10);//RANDOM
			         //$code = $generator->getBarcode($unique_code, $generator::TYPE_CODE_128);
			         $generatorJpg = new Picqer\Barcode\BarcodeGeneratorJPG();
               $fileNameToStore = md5(uniqid(rand(), true)).'.'.'jpg'; //UNIQUE QR 
               $save_path= 'uploads/bar_codes/'.$fileNameToStore; //QR CODE LOCATION
               // file_put_contents($save_path, $generatorJpg->getBarcode('081231723897', $generatorJpg::TYPE_CODABAR,'1','50'));
                 file_put_contents($save_path, $generatorJpg->getBarcode($unique_code, $generatorJpg::TYPE_CODE_128,'1','50'));
            
                $bar_code = ProductBarcode::create([
                    'product_id'     => $product_id,
                    'barcode_value'  => $unique_code,
                    'barcode_image'  => $save_path,
                ]);
            }
          
            if($bar_code) {
                DB::commit();
                return response()->json(['success' => trans('product_barcodes.added')]);
            } else {
                DB::rollback();
                return response()->json(['error' => trans('product_barcodes.error')]);

            }

        }catch (\Exception $e) {
              DB::rollback();
              return response()->json(['error' => $e->getMessage()]);
        }

    }

    public function show(Request $request,$id){
    	$page_title = trans('product_barcodes.show');
     	$product = Product::find($id);
      $product_barcodes = ProductBarcode::where('product_id',$product->id)->get();
    	$barcode_qty =  ProductBarcode::select(
                          DB::raw("count(*) as barcode_qty"),
                          )->where('product_id', $product->id)->count();
      $is_download_requested = ProductBarcodeCSVFile::where('product_id',$id)->where('is_completed','1')->first(); 

      return view('admin.product_barcodes.show', compact('page_title','product_barcodes','product','barcode_qty','is_download_requested'));
    }


    public function index_ajax_barcode(Request $request) {
        $query           = ProductBarcode::where('product_id',$request->product_id);
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
          if($request['start_date'] != '' && $request['end_date'] != ''){
             $from_date = date("Y-m-d",strtotime($request['start_date']));
             $to_date = date("Y-m-d",strtotime($request['end_date']));
             $query = $query->whereBetween('created_at',[$from_date.' 00:00:00',$to_date.' 23:59:59']           );
    
          }


        ## Total number of record with filtering
        $filter = $query;

        if($searchValue != ''){
        $filter = $filter->where(function($q)use ($searchValue) {
                            $q->where('barcode_value','like','%'.$searchValue.'%')
                            ->orWhere('id',$searchValue);
                     });
        }

        $filter_count = $filter->count();
        $totalRecordwithFilter = $filter_count;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        //$empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();
      //  $i = 1;
        foreach ($empQuery as $emp) {
          $emp['selected'] = '<input type="checkbox" class="mark" value="'.$emp->id.'" >';
       //   $emp['id'] = $i;
          $emp['product_title'] = $emp->product->get_name();
        	$emp['barcode_image'] = '<img src="'.asset($emp->barcode_image).'" />';
        	//$emp['created_date'] = $emp->created_at->format('d-m-Y h:i A');
          $emp['created_date'] = $emp->created_at->format('d-m-Y h:i A');
            ## Set dynamic route for action buttons
            $emp['show']= route("product_barcodes.show",$emp["id"]);
            $data[]=$emp;
        //    $i++;
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
        /*echo json_encode($request->product_id);
        $barcodes = ProductBarcode::where('product_id',$request->product_id)->orderBy('id', 'desc');
        return Datatables::of($barcodes)->make(true);*/
    }

    public function download_pdf(Request $request)
    {
        $barcode_ids  = $request->id;
        $order = $request->order;
        $length = $request->length;
        $start = $request->start_id;
      
        // print_r($user_subscription_ids);die;
        $data_array = [];
        if($barcode_ids == '') {
              $ids =  ProductBarcode::where('product_id',$request->prod_id)->get();
              foreach ($ids as $id) {
                $barcode_ids[] = $id->id;
              }
        }
        foreach ($barcode_ids as $id=>$value){
            $item =  ProductBarcode::where('id',$value)->first();
            $data_array[] = ['id' => $item->id,
                             'product_title' => $item->product->get_name(),
                             'barcode_value' => $item->barcode_value,
                             'barcode_image' =>$item->barcode_image];
        }

        $dir = public_path('uploads/barcode_pdf');
        if (! is_dir($dir))
            mkdir($dir, 0777, true);

        $pdf = \PDF::loadView('barcode_pdf', ['data_array' => $data_array]);
        $name = 'uploads/barcode_pdf'.'/product_barcodes.pdf';
        $pdf->save($name);
        // return asset($name);

        if($name) {
            return response()->json(['success' => 'Barcode Exported Successfully', 'data' => asset($name)]);
        } else {
            return response()->json(['error' => 'Error in Barcode Exported']);
        }
    }

    public function download_barcode_csv(Request $request){
        $csv_file = ProductBarcodeCSVFile::where('product_id',$request->product_id)->first();
        if($csv_file) {
            return response()->json(['error' => 'Request Already sent']);
        }
        $data = ['product_id'=>$request->product_id,'is_requested'=>'1'];
        $barcode_csv_file = ProductBarcodeCSVFile::create($data);
        $barcode_csv_file->save();
        $admin = Auth::user();
        dispatch(new DownloadBarcodeCSV($request->product_id,$admin));
        return response()->json(['success' => 'Request sent Successfully']);
    }

    public function reset_download(Request $request){
        $product_id = $request->product_id;
        if($product_id){
            ProductBarcodeCSVFile::where('product_id',$product_id)->where('is_requested','0')->where('is_completed','1')->delete();
            dispatch(new DeleteBarcodeCSV($product_id));
            return response()->json(['success' => 'Request sent Successfully']);
         }else {
           return response()->json(['error' => 'Request Already sent']);
         }
    }

  }
