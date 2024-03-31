<?php
namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = Role::orderBy('id','DESC')->get();
        return view('admin.roles.index',compact('roles'));
    }

        public function index_ajax(Request $request){
        $query = Role::whereNotIn('name',['admin']);
        ## Total number of records without filtering
        $totalRecords = $query->count();

        $request         =    $request->all();
        $draw            =    $request['draw'];
        $row             =    $request['start'];
        $length          =    ($request['length'] == -1) ? $totalRecords : $request['length']; 
        $rowperpage      =    $length; // Rows display per page
        $columnIndex     =    $request['order'][0]['column']; // Column index
        $columnName      =    $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder =    $request['order'][0]['dir']; // asc or desc
        $searchValue     =    $request['search']['value']; // Search value

        

        ## Total number of record with filtering
        if($searchValue != ''){
            $filter =   $query->where(function($q) use ($searchValue) {
                            $q->where('name','like','%'.$searchValue.'%')
                            ->orwhere('id','like','%'.$searchValue.'%')
                              ->orWhere('status','like',$searchValue.'%');
                        })->whereNotIn('name',['admin']);
        }

        $filter = $query;
        $totalRecordwithFilter = $filter->count();

        ## Fetch records
        $empQuery = $filter->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data     = array();
        $i = 1;
        foreach ($empQuery as $emp) {
             ## Set dynamic route for action buttons
            $emp['name'] = $emp->name;
            $emp['edit']        = route("roles.edit",$emp["id"]);
            $emp['show']        = route("roles.show",$emp["id"]);
           /* $emp['delete']      = route("sub_admin.destroy",$emp["id"]);
           */ 
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


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view('admin.roles.create',compact('permission'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            // 'title' => 'required|unique:roles,title',
            'permission' => 'required',
        ]);


        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));
        $role->givePermissionTo($request->input('permission'));


        return redirect()->route('roles.index')
                        ->with('success', trans('roles.add_role_success'));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();


        return view('admin.roles.show',compact('role','rolePermissions'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
        return view('admin.roles.edit',compact('role','permission','rolePermissions'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            // 'title' => 'required',
            'permission' => 'required',
        ]);


        $role = Role::find($id);
        $role->name = $request->input('name');
        // $role->title = $request->input('title');
        $role->save();


        $role->syncPermissions($request->input('permission'));
        $role->givePermissionTo($request->input('permission'));


        return redirect()->route('roles.index')
                        ->with('success', trans('roles.update_role_success'));
    }

    public function status(Request $request)
    {
        $roles = Role::where('id',$request->id)->update(['status'=>$request->status]);
        if($roles) {
            return response()->json(['success' => trans('roles.status_updated')]);
        } else {
            return response()->json(['error' => trans('roles.error')]);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();
        return redirect()->route('roles.index')
                        ->with('success', trans('roles.delete_role_success'));
    }
}