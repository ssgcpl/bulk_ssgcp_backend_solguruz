<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\WishList;
use App\Models\WishListDealer;
use App\Models\ProductCoverImage;
use App\Models\ProductCategory;
use App\Models\RelatedProduct;
use App\Models\DealerRetailer;
use App\Models\BusinessCategory;
use App\Models\Notification;
use App\Models\CompanyDocImage;
use Validator,Auth,DB,Hash;
use App\Models\Helpers\CommonHelper;

class WishListController extends Controller
{
      use CommonHelper;

    public function __construct()
    {
      $this->middleware('auth');
      $this->middleware('permission:wish-list-list', ['only' => ['index','show']]);
      $this->middleware('permission:wish-list-create', ['only' => ['create','store']]);
      $this->middleware('permission:wish-list-edit', ['only' => ['edit','update']]);
      $this->middleware('permission:wish-list-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index() {
      $page_title = trans('wish_list.heading');
      return view ('admin.wish_list.index',compact('page_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index_ajax(Request $request) {

        $ids             = WishList::orderBy('created_at','DESC')->pluck('product_id')->toArray();
       // $ids             = WishList::groupBy('product_id')->pluck('product_id');
        $query           =    Product::whereIn('id', $ids)
                            ->orderByRaw('FIELD (id, ' . implode(', ', $ids) . ') ASC');
                           
        //$query = Product::whereIn('id',$ids);
        /*  $query           =  Product::with(['wishlist'=>function($qq) use($ids){
            //$qq->orderBy('id','desc');
          $qq->orderBy($ids,'desc');
        }])->whereIn('id',$ids);*/
        $request         =    $request->all();
        $totalRecords    =    $query->count();
        $draw            =    $request['draw'];
        $row             =    $request['start'];
        $length = ($request['length'] == -1) ? $totalRecords : $request['length'];
        $rowperpage      =    $length; // Rows display per page
        $columnIndex     =    $request['order'][0]['column']; // Column index
        $columnName      =    $request['columns'][$columnIndex]['data']; // Column name
        //$columnSortOrder =    $request['order'][0]['dir']; // asc or desc
        $searchValue     =    $request['search']['value']; // Search value


        ## Total number of records without filtering
        $total = $query->count();
        $totalRecords = $total;

        $from_date       =    $request['start_date'];
        $to_date         =    $request['end_date'];

        if($from_date != '' && $to_date != ''){
           $from_date = date("Y-m-d",strtotime($from_date));
           $to_date = date("Y-m-d",strtotime($to_date));
           $query = $query->whereHas('wishlist',function($q) use($from_date,$to_date){
                        $q->whereBetween('created_at',[$from_date." 00:00:00",$to_date." 23:59:00"]);
           });
           //$wishlist = $wishlist->whereBetween('created_at',[$from_date." 00:00:00",$to_date." 23:59:00"]);
        }
 
        ## Total number of record with filtering
        $filter = $query;

       if($searchValue != '') {

                          $filter =  $filter->where(function($q) use ($searchValue){
                           $q->Where('id','like','%'.$searchValue.'%')
                            ->orWhere('sku_id','like','%'.$searchValue.'%')
                            ->OrWhere('name_hindi','like','%'.$searchValue.'%')
                            ->OrWhere('name_english','like','%'.$searchValue.'%');
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
            ## Set dynamic route for action buttons
           // $emp['number'] = $i;
            //$emp['id'] = $emp->id;
            $emp['product_name'] = $emp->get_name();
            $emp['product_image'] = '<img src="'.asset($emp->image).'" width="100px" height="100px">';
            $emp['mrp'] = $emp->mrp;
            $emp['sale_price'] = $emp->retailer_sale_price;
            $total_qty = WishList::where('product_id',$emp->id)->sum('wish_product_qty');
            $emp['wish_product_qty'] = $total_qty;
          //  $emp['created_at'] = date('d-m-Y H:i A',strtotime($emp['created_at']));
            $emp['show']= route("wish_list.show",$emp->id);
            $data[]=$emp;
           // $i++;
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


    public function show(Request $request,$id) {
      $page_title = trans('wish_list.heading');
      $product = Product::find($id);
      return view ('admin.wish_list.show',compact('page_title','product'));
    }

    public function index_ajax_detail(Request $request) {

     	$product_id      = $request->product_id;
     	$query           = WishList::where('product_id',$product_id)->orderBy('created_at','DESC');
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
            $query = $query->whereBetween('created_at',[$from_date." 00:00:00",$to_date." 23:59:00"]);
          }

          $user_type = $request['user_type'];
          if($user_type != null) {
         // $query =  $query->whereHas('user',function($q) use ($user_type){
                          $query->Where('user_type',$user_type);
          }

        ## Total number of record with filtering
        $filter = $query;

        if($searchValue != ''){
        /*$filter = $filter->whereHas('user',function($q) use ($searchValue){
                           $q->Where('company_name','like',"%".$searchValue."%")
                              ->orWhere('id','like',"%".$searchValue."%")
                              ->orWhere('first_name','like',"%".$searchValue."%")
                              ->orWhere('mobile_number','like',"%".$searchValue."%");  
                          })->Where(function($q1) use ($searchValue){ 
                            $q1->where('user_type','like','%'.$searchValue."%")
                            ->orWhere('id','=',"%".$searchValue."%");
                          });*/
          $filter = $filter->where(function ($query) use ($searchValue) {
                    $query->whereHas('user', function ($q1) use ($searchValue) {
                        $q1->where('company_name', 'like', '%' . $searchValue . '%')
                           ->orWhere('mobile_number', 'like', '%' . $searchValue . '%');
                    })
                    ->orWhere('user_type', 'like', '%' . $searchValue . '%')
                    ->orWhere('id', 'like', '%' . $searchValue . '%')
                    ->orWhere('user_id', 'like', '%' . $searchValue . '%');
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
            $emp['number'] = $i;
            $user = User::find($emp->user_id);
            if($user) {
              $emp['user_id'] = $emp->user_id;
              $emp['company_name'] = $user->company_name;
              $emp['mobile_number'] = $user->mobile_number;
              $emp['user_type'] = $emp->user_type;
            }else {
                $emp['user_id'] = '';
                $emp['company_name'] = '';
                $emp['mobile_number'] = '';
                $emp['user_type'] = '';
            }
         // print_r($user->dealers);
          	$emp['dealer'] = @(count($user->dealers)>0) ? "<a href='".route('view_dealer_detail',$emp['id'])."?product_id=".$product_id."'>View</a>" :'-' ;

           /* $starttimestamp = strtotime($emp->created_at);
            $endtimestamp = strtotime(date("Y-m-d H:i:s"));
            $difference = round(abs($endtimestamp - $starttimestamp)/3600);
            $hours =  $difference;
            if($difference <= 24) {
              $emp['created_at']  = $difference." Hours Ago";
            }else {
              $emp['created_at']  = date("d-m-Y h:i A",strtotime($emp['created_at']));
            }*/

            $emp['show']= route("view_customer_detail",$emp->user_id)."?product_id=".$product_id;
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

    public function view_dealer_detail(Request $request,$id){
       $page_title = trans('wish_list.dealer_heading');
       return view ('admin.wish_list.show_dealers',compact('page_title','id'));
    }

    public function view_customer_detail(Request $request,$id){
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
       
       return view ('admin.wish_list.show_customers',compact('page_title','customer','company_docs','company_images','id'));
    }    


     public function dealer_ajax_detail(Request $request) {

        $wishlist_id      =   $request->wish_list_id;
        $query           =    WishListDealer::where('wish_list_id',$wishlist_id);
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
        $filter =  $filter->whereHas('user',function($q) use ($searchValue){
                          $q->Where('first_name','like','%'.$searchValue.'%')
                            ->orWhere('mobile_number','like','%'.$searchValue.'%')
                            ->orWhere('company_name','like','%'.$searchValue.'%');
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
            $emp['number'] = $i;
            $user = User::find($emp->dealer_id);
            $emp['user_id'] = $emp->dealer_id;
            $emp['company_name'] = $user->company_name;
            $emp['mobile_number'] = $user->mobile_number;
            $emp['user_type'] = $user->user_type;
            $emp['dealer'] = "<a href=''>View</a>";

            /*$starttimestamp = strtotime($emp->created_at);
            $endtimestamp = strtotime(date("Y-m-d H:i:s"));
            $difference = round(abs($endtimestamp - $starttimestamp)/3600);
            $hours =  $difference;
            if($difference <= 24) {
              $emp['created_at']  = $difference." Hours Ago";
            }else {
              $emp['created_at']  = date("d-m-Y h:i A",strtotime($emp['created_at']));
            }*/

            $emp['show']= route("customers.show",$emp->user_id);
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

      public function destroy(Request $request,$id)
      {
        $wishlist = WishList::find($id);
        if($wishlist->delete()){
            return redirect()->back()->with('success',trans('wish_list.deleted'));
        }else{
            return redirect()->back()->with('error',trans('wish_list.error'));
        }
      }
}
