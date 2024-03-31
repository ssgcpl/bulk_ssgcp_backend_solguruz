<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\SsgcSuggestion;
use App\Models\ProductCoverImage;
use App\Models\ProductCategory;
use App\Models\RelatedProduct;
use App\Models\DealerRetailer;
use App\Models\CompanyDocImage;
use App\Models\Notification;
use Validator,Auth,DB,Hash;
use App\Models\Helpers\CommonHelper;


class SsgcSuggestionController extends Controller
{
 	use CommonHelper;

 	 public function __construct()
    {
      $this->middleware('auth');
      $this->middleware('permission:ssgc-suggestion-list', ['only' => ['index','show']]);
      $this->middleware('permission:ssgc-suggestion-create', ['only' => ['create','store']]);
      $this->middleware('permission:ssgc-suggestion-edit', ['only' => ['edit','update']]);
      $this->middleware('permission:ssgc-suggestion-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index() {
      $page_title = trans('ssgc_suggestions.heading');
      return view ('admin.ssgc_suggestions.index',compact('page_title')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index_ajax(Request $request) {
 
        $ids =  SsgcSuggestion::groupBy('product_id')->pluck('product_id');
        $query           =  Product::whereIn('id',$ids); 
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
                            $q->where('name_english','like','%'.$searchValue.'%')
                            ->orWhere('name_hindi','like','%'.$searchValue.'%')
                            ->orWhere('id','like','%'.$searchValue.'%')
                            ->orWhere('status','like','%'.$searchValue.'%');
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
           	$emp['product_name'] = $emp->get_name();
          	$emp['product_image'] = ($emp->image) ? '<img src="'.asset($emp->image).'" width="100px" height="100px">' : '';
            // $user = SsgcSuggestion::where('product_id',$emp->id)->first()->user_id;
            // $emp['user_type'] = ucfirst(User::find($user)->user_type);
          	$emp['total_suggestions'] = SsgcSuggestion::where('product_id',$emp->id)->count();
            $emp['show']= route("ssgc_suggestions.show",$emp->id);
            $data[]=$emp;
            $i++;
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


     public function show(Request $request,$id) {
      $page_title = trans('ssgc_suggestions.heading');
      $product = Product::find($id);
      return view ('admin.ssgc_suggestions.show',compact('page_title','product')); 
    }

    public function index_ajax_detail(Request $request) {

      $product_id      = $request->product_id;
      $query           = SsgcSuggestion::where('product_id',$product_id);
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
          if($from_date != '' && $to_date != ''){
           
            $from_date = date("Y-m-d",strtotime($from_date));
            $to_date = date("Y-m-d",strtotime($to_date));

            $from_date = $from_date." 00:00:00";
            $to_date = $to_date." 23:59:59";
           
            $query = $query->whereBetween('created_at',[$from_date,$to_date]);
          }

        ## Total number of record with filtering
        $filter = $query;

        if($searchValue != ''){
        $filter = $filter->where(function($q)use ($searchValue,$product_id) {

                          $q->whereHas('user',function($q)use ($searchValue,$product_id){
                            $q->where('id','like','%'.$searchValue.'%')
                            ->orWhere('company_name','like','%'.$searchValue.'%')
                            ->orWhere('mobile_number','like','%'.$searchValue.'%')
                            ->orWhere('email','like','%'.$searchValue.'%')
                            ->orWhere('user_type','like','%'.$searchValue.'%');
                          })->orWhere('id','like','%'.$searchValue.'%')
                          ->orWhere('description','like','%'.$searchValue.'%')
                          ->orWhere('created_at','like','%'.$searchValue.'%');
                     })->where('product_id',$product_id);
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
            $emp['number'] = $i;
            $user = User::find($emp->user_id);
            $emp['user_id'] = $emp->user_id;
            $emp['company_name'] = $user->company_name;
            $emp['mobile_number'] = $user->mobile_number;
            $emp['email'] = $user->email;
            $emp['user_type'] = $user->user_type;
            $emp['description'] = $emp->description;

            $starttimestamp = strtotime($emp->created_at);
            $endtimestamp = strtotime(date("Y-m-d H:i:s"));
            $difference = round(abs($endtimestamp - $starttimestamp)/3600);
            $hours =  $difference;
            if($difference <= 24) {
              $emp['date_time']  = $difference." Hours Ago";
            }else {
              $emp['date_time']  = date("d-m-Y h:i A",strtotime($emp->created_at));
            }

            $emp['show']=  route("view_suggestion_customers",$emp->user_id)."?product_id=".$product_id;
            $emp['delete']= route("wish_list.destroy",$emp->id);
            $data[]=$emp;
            $i++;
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

     public function view_suggestion_customers(Request $request,$id){
       $page_title = trans('customers.show');
       $customer = User::find($id);
       $company_details = CompanyDocImage::where('company_id',$id)->get();
        $company_docs = array();
        $company_images = array();
        foreach ($company_details as $cd) {
          if($cd->documents !='') {
             $company_docs[]    = $cd->documents;
          }
          if($cd->images != '') {
             $company_images[]  = $cd->images;
          }
        }
       
       return view ('admin.ssgc_suggestions.show_customers',compact('page_title','customer','company_docs','company_images','id'));
    }    

	
}
