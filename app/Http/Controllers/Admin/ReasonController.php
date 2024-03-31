<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\sub;
use App\Models\User;
use App\Models\Reason;
use App\Models\Helpers\CommonHelper;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;


class ReasonController extends Controller
{
    
    use CommonHelper;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:reason-list', ['only' => ['index','show']]);
        $this->middleware('permission:reason-create', ['only' => ['create','store']]);
        $this->middleware('permission:reason-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:reason-delete', ['only' => ['destroy']]);
    }

    public function index() 
    {   
        $page_title = trans('reasons.admin_heading');
        return view('admin.reasons.index',compact('page_title'));
    }


    public function index_ajax(Request $request)
    {   
        
        $query           =   Reason::query();
      
        $totalRecords    =    $query->count();
        $request         =    $request->all();
        $draw            =    $request['draw'];
        $row             =    $request['start'];
        $length = ($request['length'] == -1) ? $totalRecords : $request['length']; 
        $rowperpage      =    $length; // Rows display per page
        $columnIndex     =    $request['order'][0]['column']; // Column index
        $columnName      =    $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder =    $request['order'][0]['dir']; // asc or desc
        $searchValue     =    $request['search']['value']; // Search value
        ## Total number of record with filtering
        $filter= $query;

        if($searchValue != ''){
        $filter  = $query->where('name','like','%'.$searchValue.'%')
        	             ->Orwhere('id','like',$searchValue.'%')          
                       ->Orwhere('status','like',$searchValue.'%');          
        }


        $filter_data           = $filter->count();
        $totalRecordwithFilter = $filter_data;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();

        $i = 1;
        foreach ($empQuery as $emp) { 

            //$emp['image'] = asset($emp['image']);
            ## Set dynamic route for action buttons
            $emp['ticket_type']   = $emp->type == 'customer_ticket' ? 'Customer Ticket'  : "" ;
          //  $emp['ticket_type'] = 'customer_ticket';
            $emp['edit']    = route("reasons.edit",$emp["id"]);
            $emp['show']    = route("reasons.show",$emp["id"]);
            
            //$emp['delete']  = route("category.destroy",$emp["id"]);
  

          $data[] = $emp;
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

        $page_title = trans('reasons.create');
      
        return view('admin.reasons.create', compact('page_title'));
    }


    public function store(Request $request)
    {
        
        $validator = $request->validate([
          'name'            => 'required|min:2|max:50',
        ]);
       
       DB::beginTransaction();
       try {

          $data = $request->all();
          $user = Auth::user(); 
        
          $data ['added_by'] = $user->id;
          $data ['type'] = 'customer_ticket';

          $reason = new Reason();
          $reason->fill($data);

          if($reason->save()) {
            DB::commit();
            return redirect()->route('reasons.index')->with('success',trans('reasons.added'));
          } else {
              DB::rollback();
              return redirect()->route('reasons.index')->with('error',trans('reasons.error'));
          }

         }catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('reasons.index')->with('error',$e->getMessage());
          } 
    }


    public function show($id)
    {  
        $page_title = trans('reasons.show');
        $reason  =  Reason::where('id',$id)->first();
        if(empty($reason)){
            return redirect()->route('reasons.index')->with('error',trans('common.no_data'));
        }
        return view('admin.reasons.show',compact('reason','page_title'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $data       =  $request->all();
        $validator = $request->validate([
          'name'            => 'required|min:2|max:50',
        ]);

        $reason = Reason::find($id);

        if(empty($reason)){
            return redirect()->route('reasons.index')->with('error',trans('admin.error'));
        }

        $reason->fill($data);

        if($reason->save()){
           DB::commit();
          return redirect()->route('reasons.index')->with('success',trans('reasons.updated'));
        } else {
          DB::rollback();
          return redirect()->route('reasons.index')->with('error',trans('admin.error'));
        }
    }


    public function edit($id)
    {   
      $page_title  = trans('reasons.update');
      $reason        = Reason::find($id);   
      if(empty($reason)){
            return redirect()->route('reasons.index')->with('error',trans('common.no_data'));
      }  
      return view('admin.reasons.edit',compact('reason','page_title'));
    }

    public function status(Request $request)
    {
        $reason = Reason::where('id',$request->id)->update(['status'=>$request->status]);

        if($reason) {
            return response()->json(['success' => trans('reasons.status_updated')]);
        } else {
            return response()->json(['error' => trans('reasons.error')]);
        }
    }

}
 