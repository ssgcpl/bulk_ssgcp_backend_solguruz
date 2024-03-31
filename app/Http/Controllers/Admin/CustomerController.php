<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CompanyDocImage;
use App\Models\Order;
use App\Models\DealerRetailer;
use App\Models\Country;
use App\Models\DeviceDetail;
use App\Models\Notification;
use Validator,Auth,DB,Hash;
use App\Models\Helpers\CommonHelper;


class CustomerController extends Controller
{
    use CommonHelper;

    public function __construct()
    {
      $this->middleware('auth');
      $this->middleware('permission:customer-list', ['only' => ['index','show']]);
      $this->middleware('permission:customer-create', ['only' => ['create','store']]);
      $this->middleware('permission:customer-edit', ['only' => ['edit','update']]);
      $this->middleware('permission:customer-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index() {
      $page_title = trans('customers.heading');
      $total_customers = User::whereIn('user_type',['retailer','dealer'])->count();
      $total_retailers = User::where('user_type','retailer')->count();
      $total_dealers = User::where('user_type','dealer')->count();
      return view ('admin.customers.index',compact('page_title','total_customers','total_retailers','total_dealers')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index_ajax(Request $request) {

        $query = User::whereIn('user_type',['retailer','dealer']);
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


        ## Filter by Status
        if($request['filter_type'] =='all'){
            $query = $query;
          }else if ($request['filter_type'] =='active'){
              $query = $query->where('status','active');
          }else if ($request['filter_type'] =='inactive'){
             $query = $query->where('status','inactive');
          } 

          ## Filter by type
        if($request['filter_user_type'] =='all'){
            $query = $query;
          }else if ($request['filter_user_type'] =='retailer'){
              $query = $query->where('user_type','retailer');
          }else if ($request['filter_user_type'] =='dealer'){
             $query = $query->where('user_type','dealer');
          } 

        ## Total number of record with filtering
        $filter = $query;

        if($searchValue != ''){
        $filter = $filter->where(function($q)use ($searchValue) {
                            $q->where('first_name','like','%'.$searchValue.'%')
                            ->orWhere('company_name','like','%'.$searchValue.'%')
                            ->orWhere('email','like','%'.$searchValue.'%')
                            ->orWhere('mobile_number','like','%'.$searchValue.'%')
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
        foreach ($empQuery as $emp) {


            $emp['name']= $emp["first_name"].' '.$emp["last_name"];
            $emp['email']= ($emp["email"]) ? $emp["email"] : '';
            $emp['company_name']   = $emp->company_name;
            $emp['user_type'] =  ucfirst($emp["user_type"]);   
            
            ## Set dynamic route for action buttons
            $emp['show']= route("customers.show",$emp["id"]);
            $emp['edit']= route("customers.edit",$emp["id"]);
            $emp['send_email'] = route("email",["id"=>$emp["id"]]);
            $emp['send_sms'] = route("sms",["id"=>$emp["id"]]);
            $emp['notification'] = route("notification",["id"=>$emp["id"]]);
            $emp['delete'] = route("customers.destroy",$emp["id"]);
            
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

     public function edit($id) {
        //
        $page_title = trans('customers.update');
        $customer = User::find($id);
        $company_details = CompanyDocImage::where('company_id',$id)->get();
        $retailers = User::where('user_type','retailer')->where('status','active')->get();
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
        $dealer_retailer_count = DealerRetailer::where('dealer_id',$id)->count();

        if($customer){
            return view('admin.customers.edit',compact(['customer','company_docs','company_images','retailers','dealer_retailer_count','page_title']));
        }else{
            return redirect()->route('customers.index')->with('error', trans('customers.admin_error'));
        }
       
    }

    public function update(Request $request, $id) {

       $validator = $request->validate([
          'first_name'        => 'sometimes|min:2|max:50',
          'company_name'      => 'sometimes',
          'user_discount'     => 'sometimes',    
        ]);


       $data = $request->all();
       $customer = User::find($id);

      /* $users = User::where('user_type','customer')->get();
          //print_r($users); 
          foreach ($users as $user) {
            if($data['mobile_number'] == $user->mobile_number && $id != $user->id)
            {
              return redirect()->back()->with('error',trans('customers.mobile_number_unique_error'));
            }  
          }*/

	       DB::beginTransaction();
       try {

          $user = Auth::user(); 
 			$customer['first_name'] = $data['first_name'];
 			$customer['company_name'] = $data['company_name'];
 			$customer['user_discount'] = $data['user_discount'];
          //$customer->fill($data);
 		  if($customer->update($data)) 
          {

            DB::commit();
            return redirect()->route('customers.index')->with('success',trans('customers.updated'));
           
          } else {
              DB::rollback();
              return redirect()->route('customers.index')->with('error',trans('customers.admin_error'));
          }

         }catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('customers.index')->with('error',$e->getMessage());
          }

    }

    public function add_more_retailers(Request $request){
      	$data1 =array();
        $count = DealerRetailer::where('dealer_id',$request['dealer_id'])->where('retailer_id',$request['retailer_id'])->count();
        		if($count != 0){
          			return response()->json(['error' =>trans('customers.retailer_already_exist')]);
          		}
          		$data1['dealer_id'] = $request['dealer_id'];
          		$data1['retailer_id'] = $request['retailer_id'];
          		$dealer_retailer = new DealerRetailer();
          		$dealer_retailer->fill($data1);
          		if($dealer_retailer->save()){
                return response()->json(['success' =>trans('customers.retailer_added')]);
          		}
          		else {
					      return response()->json(['error' =>trans('customers.something_went_wrong')]);
          		}
    }

    public function is_dealer_or_retailer(Request $request)
    {
    	$customer = User::find($request['id']);
    	if($request['user_type'] == 'retailer'){
    		$customer->user_type = 'dealer';
          $admin = Auth::user();
          $title    = 'Customer Changed to Dealer';
          if($customer->first_name) {
            $body     = "Retailer customer -".$customer->first_name." ".$customer->last_name." changed to Dealer ";
          }else{
            $body     = "Retailer customer converted to Dealer";
          }
          $slug     = 'customer_type_changed';
          $this->sendNotification($admin,$title,$body,$slug,$customer,null);

          $admin = Auth::user();
          $title    = 'Admin Changed You As Dealer';
          if($customer->first_name) {
            $body     = "Congratulations ".$customer->first_name." ".$customer->last_name." !! You are now dealer ";
          }else{
            $body     = "Congratulations !! You are now dealer";
          }
          $slug     = 'customer_type_changed';
          $this->sendNotification($customer,$title,$body,$slug,$customer,null);
    	}
    	if($request['user_type'] == 'dealer'){
    		$dealer_retailer = DealerRetailer::where('dealer_id',$request['id'])->delete();
    		$customer->user_type = 'retailer';
    	}
    	   if($customer->save()){
        	return response()->json(['success' => trans('customers.user_type_updated')]);
       	   } else {
        		return response()->json(['error' => trans('customers.admin_error')]);
       		}
    }

    public function ajax_retailers(Request $request) {
        $query           = DealerRetailer::where('dealer_id',$request['id']);
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
        $filter = $filter->whereHas('retailer', function($q) use ($searchValue) {
                            $q->where('first_name','like','%'.$searchValue.'%')
                            ->orWhere('company_name','like','%'.$searchValue.'%')
                            ->orWhere('email','like','%'.$searchValue.'%')
                            ->orWhere('mobile_number','like','%'.$searchValue.'%')
                           // ->orWhere('id','like','%'.$searchValue.'%')
                            ->orWhere('status','like','%'.$searchValue.'%');
                     });
        }

        $filter_count = $filter->count();
        $totalRecordwithFilter = $filter_count;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();
        
        foreach ($empQuery as $emp) {


            $emp['name']= $emp->retailer->first_name.' '.$emp->retailer->last_name;
            $emp['email']= ($emp->retailer->email) ? $emp->retailer->email : '';
            $emp['company_name']   = $emp->retailer->company_name;
            $emp['mobile_number'] = $emp->retailer->mobile_number;
            
            ## Set dynamic route for action buttons
            $emp['show']= route("show_retailers",$emp["id"]);
            $emp['delete_retailer'] = route("remove_retailers",$emp["id"]);
            
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


   
    public function show($id) {
	        $page_title = trans('customers.show');
	        $customer = User::find($id);
          $company_details = CompanyDocImage::where('company_id',$id)->get();
          $retailers = User::where('user_type','retailer')->where('status','active')->get();
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

          $dealer_retailer_count = DealerRetailer::where('dealer_id',$id)->count();

          if($customer){
            return view('admin.customers.show',compact(['customer','company_docs','company_images','retailers','dealer_retailer_count','page_title']));
	        }else{
	            return redirect()->route('customers.index')->with('error', trans('customers.admin_error'));
	        }
    }

    public function show_retailers($id) {
          $page_title = trans('customers.show');
          $dealer_retailer = DealerRetailer::find($id);
          $customer_id = $dealer_retailer->dealer_id;

          if($dealer_retailer){
            $retailers = User::find($dealer_retailer->retailer_id);
            return view('admin.customers.show_retailers',compact(['retailers','customer_id','page_title']));
          }else{
              return redirect()->route('customers.index')->with('error', trans('customers.admin_error'));
          }
    }

    public function remove_retailers($id){
        $dealer_retailer = DealerRetailer::find($id);

        if($dealer_retailer && $dealer_retailer->delete()){
            return redirect()->back()->with('success',trans('customers.retailer_deleted'));
        }else{
            return redirect()->back()->with('error',trans('customers.admin_error'));
        }
    }

    public function ajax_user_orders(Request $request) {
        $query           =    Order::where('user_id',$request['id'])->where('is_cart','0');
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
                            ->orWhere('total_sale_price','like','%'.$searchValue.'%')
                            ->orWhere('order_status','like','%'.$searchValue.'%');
                     });
        }

        $filter_count = $filter->count();
        $totalRecordwithFilter = $filter_count;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();
        foreach ($empQuery as $emp) {
            $emp['date_time']= date("d-m-Y H:i A",strtotime($emp->created_at));
            $emp['total_sale_price']   = $emp->total_sale_price;
            $emp['order_status'] = ucfirst(str_replace('_',' ',$emp->order_status));
            
            ## Set dynamic route for action buttons
              ## Set dynamic route for action buttons
            //$emp['show']= route("orders.show",$emp["id"]);
            if(strtolower($emp->order_type) == 'physical_books') {
              $emp['show']= route("orders.edit",$emp["id"]);
          }else if(strtolower($emp->order_type) == 'digital_coupons'){
            $emp['show']= route("coupon_orders.edit",$emp["id"]);
          }
            // $emp['show']= route("orders.edit",$emp["id"]);
           
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


    public function status(Request $request)
    {   
        $user = User::find($request->id);

        $user->update(['status'=>$request->status]);

        if($user) {

            if($user->status == 'inactive'){
                $userTokens = $user->tokens;
                foreach($userTokens as $token) {
                    $token->revoke();   
                }
                $user->device_details()->delete();
            }
            return response()->json(['type' => 'success','message' => trans('customers.status_updated')]);
        } else {
            return response()->json(['type' => 'error','message' => trans('customers.error')]);
        }
    }


}
