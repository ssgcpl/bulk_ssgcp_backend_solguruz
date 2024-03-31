<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Helpers\CommonHelper;
use Illuminate\Support\Str;
use DB;
use Auth,Hash;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;


class SubAdminController extends Controller
{
  use CommonHelper;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:sub-admin-list', ['only' => ['index','show']]);
        $this->middleware('permission:sub-admin-create', ['only' => ['create','store']]);
        $this->middleware('permission:sub-admin-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:sub-admin-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $page_title = trans('sub_admin.title');
        return view('admin.sub_admin.index',compact('page_title'));
    }


    public function index_ajax(Request $request){
        // print_r($request);

        $request         =    $request->all();
        $draw            =    $request['draw'];
        $row             =    $request['start'];
        $rowperpage      =    $request['length']; // Rows display per page
        $columnIndex     =    $request['order'][0]['column']; // Column index
        $columnName      =    $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder =    $request['order'][0]['dir']; // asc or desc
        $searchValue     =    $request['search']['value']; // Search value

        $query = User::WhereNotIn('user_type',['customer','dealer','retailer','developer','admin']);
    
        ## Total number of records without filtering
        $totalRecords = $query->count();

        ## Total number of record with filtering
        if($searchValue != ''){
            $filter =   $query->where(function($q) use ($searchValue) {
                            $q->where('first_name','like','%'.$searchValue.'%')
                              ->orWhere('email','like','%'.$searchValue.'%')
                              ->orWhere('mobile_number','like','%'.$searchValue.'%')
                              ->orWhere('id','like','%'.$searchValue.'%')
                              ->orWhere('user_type','like','%'.$searchValue.'%')
                              ->orWhere('status','like',$searchValue.'%');
                        });
        }
           ## Filter by Status
        if($request['status_type'] =='all'){
            $query = $query;
          }else if ($request['status_type'] =='active'){
              $query = $query->where('status','active');
          }else if ($request['status_type'] =='inactive'){
             $query = $query->where('status','inactive');
          } 

      
        $filter = $query;
        $totalRecordwithFilter = $filter->count();

      
        ## Fetch records
        $empQuery = $filter->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data     = array();
        $i = 1;
        foreach ($empQuery as $emp) {
        	 $user = User::find($emp->id);
        	$emp['role'] = $user->getRoleNames();
            $emp['created_date'] = date('d-m-Y',strtotime($emp->created_at));
            $emp['updated_date'] = date('d-m-Y',strtotime($emp->updated_at));
        ## Set dynamic route for action buttons
            $emp['number']      = $row + $i;
            $emp['edit']        = route("sub_admin.edit",$emp["id"]);
            $emp['show']        = route("sub_admin.show",$emp["id"]);
            $emp['delete']      = route("sub_admin.destroy",$emp["id"]);
            
            $data[]             = $emp;
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

    public function create()
    {

        $page_title = trans('sub_admin.create');

        $roles  = Role::where('status','active')->WhereNotIn('name',['customer','admin','developer'])->get();

        
        return view('admin.sub_admin.create', compact('page_title','roles'));
    }


    public function store(Request $request)
    {
        
        $validator = $request->validate([
          'first_name'      => 'required|min:2|max:50',
          'email'           => 'required|email|unique:users,email',
          'mobile_number'   => 'required|digits:10|numeric',
          'password'        => 'required|min:8|max:16|regex:/^(?=.*[A-Za-z])(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,16}$/',
          'role'            => 'required', 
        ]);
       
       DB::beginTransaction();
       try {

          $data = $request->all();
          $user = Auth::user(); 
          
          $users = User::WhereNotIn('user_type',['customer','teacher','developer','admin'])->get();
          //print_r($users); 
          foreach ($users as $user) {
            if($data['mobile_number'] == $user->mobile_number)
            {
              return redirect()->back()->with('error',trans('customers.mobile_number_unique_error'));
            }  
          }

          $data ['added_by'] = $user->id;
          $role_name =  Role::where('id',$data['role'])->first();
          $data ['user_type'] = $role_name['name'];
          $data['status'] = 'active';
          $data ['verified'] = '1';
          $data ['email_verified_at'] = date('Y-m-d');
          $data['password'] = Hash::make($data['password']);

          $sub_admin = new User();
          $sub_admin->fill($data);
          $role_id =  $data['role']; 
          if($sub_admin->save()) {
            
            $sub_admin->assignRole($role_id);
            $title = "Sub Admin Registration";
            $body = "Congratulations !! Admin has registered you as a sub admin in Sam Samyik Ghatnachakra application.<br> Please find below details :<br> <b>Email:</b> ".$data['email']."<br><b>Password:</b> ".$request->password."<br><b>Phone:</b> ".$data['mobile_number']."<br>";
           // $mail = $this->sendMail($sub_admin,$body,$title);

            DB::commit();
            return redirect()->route('sub_admin.index')->with('success',trans('sub_admin.added'));
          } else {
              DB::rollback();
              return redirect()->route('sub_admin.index')->with('error',trans('sub_admin.error'));
          }

         }catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('sub_admin.index')->with('error',$e->getMessage());
          } 
    }


     public function edit($id)
    {   
      $page_title     = trans('sub_admin.update');
      $user   = User::find($id);
      $user['role'] = $user->roles()->pluck('id')->first();
      $roles  = Role::where('status','active')->WhereNotIn('name',['customer','admin','developer'])->get();
      
     
      return view('admin.sub_admin.edit',compact('user','roles','page_title'));
    }

    public function show($id)
    {   
      $page_title     = trans('sub_admin.update');
      $user   = User::find($id);
      $user['role'] = $user->roles()->pluck('name')->first();
      $roles  = Role::where('status','active')->get();
      
     
      return view('admin.sub_admin.show',compact('user','roles','page_title'));
    }


    public function update(Request $request , $id)
    {

        $validator = $request->validate([
        //  'first_name'      => 'required|min:2|max:50|unique:users,first_name',
         // 'email'           => 'required|email|unique:users,email',
          //'first_name'        => 'sometimes|min:2|max:50|unique:users,first_name,' . $id,
          'first_name'        => 'required|min:2|max:50',
          'email'             => 'required|email|unique:users,email,' . $id,
         // 'new_password'      => $request->new_password!=null ?'sometimes|min:8|max:16|regex:/^(?=.*[A-Za-z])(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,16}$/':'' ,
          //'confirm_password'  => $request->new_password!=null ?'sometimes|same:new_password':'',
          'mobile_number' => 'required|digits:10|numeric|unique:users,mobile_number,'.$id,
          'role'            => 'required', 
        ]);

         if(isset($request->new_password) && $request->new_password != "")
        {
            $this->validate($request,[
                'first_name'  =>  'required|string|min:3|max:50',
                'email' =>  'required|email|max:255|unique:users,email,'.$id,
                'mobile_number' => 'required|digits:10|numeric|unique:users,mobile_number,'.$id,
                'old_password' => $request->old_password != null ?'sometimes|min:8|max:14':'',
                'new_password' => $request->new_password != null ?'sometimes|min:8|max:14':'',
                'confirm_password'  => $request->new_password != null ?
                'same:new_password|min:8|max:14':'',
                'profile_image' => 'image|mimes:png,jpg,jpeg,svg|max:10000',
            ]);
        }

          $data = $request->all();
          $sub_admin = User::find($id);

         /*  $users = User::WhereNotIn('user_type',['customer','teacher','developer','admin'])->get();
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
          
         
          if(!empty($data['new_password'])){
            $data['password'] = Hash::make($data['new_password']);
          }
       

          $data ['added_by'] = $user->id;
          $role_name =  Role::where('id',$data['role'])->first();
          $data ['user_type'] = $role_name['name'];
          

          $sub_admin->fill($data);
          $role_id =  $data['role']; 
          if($sub_admin->save()) {
            
            $sub_admin->syncRoles($role_id);
            DB::commit();
            return redirect()->route('sub_admin.index')->with('success',trans('sub_admin.updated'));
          } else {
              DB::rollback();
              return redirect()->route('sub_admin.index')->with('error',trans('sub_admin.error'));
          }

         }catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('sub_admin.index')->with('error',$e->getMessage());
          } 
    }



    public function status(Request $request)
    {
        $sub_admin = User::where('id',$request->id)->update(['status'=>$request->status]);
        if($sub_admin) {
            return response()->json(['success' => trans('sub_admin.status_updated')]);
        } else {
            return response()->json(['error' => trans('sub_admin.error')]);
        }
    }
}
