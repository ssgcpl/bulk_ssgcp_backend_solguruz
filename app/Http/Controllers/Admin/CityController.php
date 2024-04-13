<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Validation\Rule;

class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:city-list', ['only' => ['index','show']]);
        $this->middleware('permission:city-create', ['only' => ['create','store']]);
        $this->middleware('permission:city-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:city-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $page_title = trans('cities.title');
        $cities = City::all();
        return view('admin.cities.index',compact('cities','page_title'));
    }

    public function index_ajax(Request $request)
    {
        $query = City::query();
        $totalRecords = $query->count();

        $request         =    $request->all();
        $draw            =    $request['draw'];
        $row             =    $request['start'];
        $length          =    ($request['length'] == -1) ? $totalRecords : $request['length']; 
        $rowperpage     =    $length; // Rows display per page
        $columnIndex     =    $request['order'][0]['column']; // Column index
        $columnName      =    $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder =    $request['order'][0]['dir']; // asc or desc
        $searchValue     =    $request['search']['value']; // Search value
 
 
        ## Total number of record with filtering
        $filter= $query;

        if($searchValue != ''){
        $filter = $filter->whereHas('state',function($query) use ($searchValue){
                        $query->whereHas('country',function($q) use ($searchValue){
                          $q->Where('name','like','%'.$searchValue.'%');
                          })
                          ->orWhere('name','like','%'.$searchValue.'%');
                          })
                        ->orWhere(function($q)use ($searchValue) {
                          $q->orwhere('name','like','%'.$searchValue.'%')
                           ->orwhere('status','like',$searchValue.'%');
                     });
        }

        $filter_data=$filter->count();
        $totalRecordwithFilter = $filter_data;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();
        foreach ($empQuery as $emp) {

        ## Foreign Key Value
        $emp['country_name']= $emp->state->country->name;
        $emp['state_name']= $emp->state->name;

        ## Set dynamic route for action buttons
            $emp['edit']= route("cities.edit",$emp["id"]);
             $emp['show']= route("cities.show",$emp["id"]);
            // $emp['delete'] = route("cities.destroy",$emp["id"]);

            
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
        $page_title = trans('cities.add_new');
        $countries  = Country::where('status','active')->get();
        return view('admin.cities.create', compact('page_title','countries'));
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
            'city_name'  => 'required|regex:/^[\w\-\s]+$/u|max:30|unique:cities,name,NULL,id,state_id,' . $request->state_name,
            'country_name'  => 'required|exists:countries,id',
            'state_name'    => 'required|exists:states,id',
        ]);
        $data = $request->all();
        $data['name'] = $request->city_name;
        $data['state_id'] = $request->state_name;
       
        if(City::create($data)) {
            return redirect()->route('cities.index')->with('success',trans('cities.added'));
        } else {
            return redirect()->route('cities.index')->with('error',trans('cities.error'));
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
        $page_title = trans('cities.show');
        $city       =   City::find($id);
        $state      =   State::where('id',$city->state_id)->first();
        $country    =   Country::where('id',$state->country_id)->first();
        return view('admin.cities.show',compact('city','state','country','page_title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $page_title = trans('cities.update');
        $city       = City::find($id);
        $countries  = Country::where('status','active')->get();
        $country_id = $city->state->country->id;
        $state_id   = $city->state->id;
        return view('admin.cities.edit',compact('city','page_title','countries','country_id','state_id'));
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
        $city = City::find($id);

        if(empty($city)){
            return redirect()->route('cities.index')->with('error',trans('cities.error'));
        }

        $validator= $request->validate([
             'city_name'  => ['required','min:2','max:50','regex:/^[\w\-\s]+$/u','unique:cities,name,'.$city->id.',id,state_id,' . $request->state_name],
             'country_name' => 'required|exists:countries,id',
             'state_name'    => 'required|exists:states,id',
        ]);

         $data['name'] = $request->city_name;
         $data['state_id'] = $request->state_name;

        if($city->update($data)){
            return redirect()->route('cities.index')->with('success',trans('cities.updated'));
        } else {
            return redirect()->route('cities.index')->with('error',trans('cities.error'));
        }
    }

   

    /**
      * Display a listing of the resource.
      *
      * @return \Illuminate\Http\Response
    */
    public function status(Request $request)
    {   
        $cities= City::where('id',$request->id)
               ->update(['status'=>$request->status]);
               
        if($cities) {
            return response()->json(['success' => trans('cities.status_updated')]);
        } else {
            return response()->json(['error' => trans('cities.error')]);
       }
    }



    public function get_city($state_id)
    {
          try {
          $cities = City::where('state_id',$state_id)->where('status','active')->get();
          return response()->json(['success' => '1', 'data' => $cities, 'message' => 'city_list']);
          } 
          catch (Exception $e) {
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
        $city = City::find($id);

        if($city->delete()){
            return redirect()->route('cities.index')->with('success',trans('cities.deleted'));
        }else{
            return redirect()->route('cities.index')->with('error',trans('cities.error'));
        }
    }

    
}