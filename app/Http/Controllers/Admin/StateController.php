<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Validation\Rule;
use HasFactory;

class StateController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:states-list', ['only' => ['index','show']]);
        $this->middleware('permission:states-create', ['only' => ['create','store']]);
        $this->middleware('permission:states-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:states-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $page_title = trans('states.title');
        $states = State::all();
        
        return view('admin.states.index',compact('states','page_title'));
    }

    public function index_ajax(Request $request)
    {
        $query = State::query();
        $totalRecords = $query->count();

        $request         =    $request->all();
        $draw            =    $request['draw'];
        $row             =    $request['start'];
        $length          = ($request['length'] == -1) ? $totalRecords : $request['length']; 
        $rowperpage      =    $length; // Rows display per page
        $columnIndex     =    $request['order'][0]['column']; // Column index
        $columnName      =    $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder =    $request['order'][0]['dir']; // asc or desc
        $searchValue     =    $request['search']['value']; // Search value

     
        ## Total number of record with filtering
        $filter = $query;

        if($searchValue != ''){
        $filter= $filter->whereHas('country',function($query)use ($searchValue) {
                            $query->Where('name','like','%'.$searchValue.'%');    
                     })
                      ->orwhere('name','like','%'.$searchValue.'%')
                      ->orwhere('status','like',$searchValue.'%');
        }

        $filter_data=$filter->count();
        $totalRecordwithFilter = $filter_data;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();
        foreach ($empQuery as $emp) {

        ## Foreign Key Value
        $emp['country_name']= Country::find($emp->country_id)->name;
            

        ## Set dynamic route for action buttons
            $emp['edit']= route("states.edit",$emp["id"]);
            $emp['show']= route("states.show",$emp["id"]);
            // $emp['delete'] = route("states.destroy",$emp["id"]);
    
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $page_title = trans('states.add_new');
        $countries = Country::where('status','active')->get();

        return view('admin.states.create', compact('page_title','countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator= $request->validate([
             'state_name'   => 'required|regex:/^[\w\-\s]+$/u|max:30|unique:states,name',
             'country_name' => 'required|exists:countries,id'
        ]);

        $data = $request->all();
        $data['name'] = $request->state_name;
        $data['country_id'] = $request->country_name;
       
        if(State::create($data)) {

            return redirect()->route('states.index')->with('success',trans('states.added'));

        } else {

            return redirect()->route('states.index')->with('error',trans('states.error'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        $page_title = trans('states.show');
        $state = State::find($id);
        $country = Country::where('id',$state->country_id)->first();
        
        return view('admin.states.show',compact('state','country','page_title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $page_title = trans('states.update');
        $state = State::find($id);
        $countries = Country::where('status','active')->get();

        return view('admin.states.edit',compact('state','page_title','countries'));
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
        $data = $request->all();
        $state = State::find($id);

        if(empty($state)){

            return redirect()->route('states.index')->with('error',trans('states.error'));
        }

        $validator= $request->validate([

             'state_name'  =>['required','regex:/^[\w\-\s]+$/u','max:30',Rule::unique('states','name')->ignore($state->id)],
             'country_name' => 'required|exists:countries,id'
        ]);

        $data['name'] = $request->state_name;
        $data['country_id'] = $request->country_name;

        if($state->update($data)){

            return redirect()->route('states.index')->with('success',trans('states.updated'));

        } else {

            return redirect()->route('states.index')->with('error',trans('states.error'));
        }
    }

 
    /**
      * Display a listing of the resource.
      *
      * @return \Illuminate\Http\Response
    */
    public function status(Request $request)
    {
     
        $state = State::where('id',$request->id)->update(['status'=>$request->status]);

        if($state) {

            return response()->json(['success' => trans('states.status_updated')]);

        }else{

            return response()->json(['error' => trans('states.error')]);
        }
    }

   public function get_state($country_id){
      
      try {
        $states = State::where('country_id',$country_id)->where('status','active')->get();
     
          return response()->json(['success' => '1', 'data' => $states, 'message' => 'state_list']);
      } catch (Exception $e) {
          return response()->json(['success' => '0', 'data' => [], 'message' => $e->getMessage()]);
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
        $state = State::find($id);

        if($state->delete()){
            return redirect()->route('states.index')->with('success',trans('states.deleted'));
        }else{
            return redirect()->route('states.index')->with('error',trans('states.error'));
        }
    }
   

    
}