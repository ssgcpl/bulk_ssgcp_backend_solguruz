<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\City;
use App\Models\State;
use App\Models\PostCode;
use Illuminate\Validation\Rule;

class PostCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:post_code-list', ['only' => ['index','show']]);
        $this->middleware('permission:post_code-create', ['only' => ['create','store']]);
        $this->middleware('permission:post_code-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:post_code-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $page_title = trans('post_codes.title');
        $post_code = PostCode::all();
        return view('admin.post_codes.index',compact('post_code','page_title'));
    }
    
     public function index_ajax(Request $request)
    {
        $query = PostCode::query();
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
        $filter= $query;


        if($searchValue != ''){
        $filter = $filter->whereHas('city',function($query) use ($searchValue){
                        $query->whereHas('state',function($que) use ($searchValue){
                        $que->whereHas('country',function($q) use ($searchValue){
                                   $q->Where('name','like','%'.$searchValue.'%');
                         })
                                 ->orWhere('name','like','%'.$searchValue.'%');
                         })
                                ->orWhere('name','like','%'.$searchValue.'%');
                        })
                        ->orWhere(function($q)use ($searchValue) {
                          $q->orwhere('postcode','like','%'.$searchValue.'%')
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
        $emp['postcode']     = $emp->postcode;
        $emp['country_name'] = $emp->city->state->country->name;
        $emp['state_name']   = $emp->city->state->name;  
        $emp['city_name']    = $emp->city->name; 
     
        ## Set dynamic route for action buttons
            $emp['edit']= route("post_codes.edit",$emp["id"]);
            $emp['show']= route("post_codes.show",$emp["id"]);
            // $emp['delete'] = route("post_codes.destroy",$emp["id"]);

            
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
        $page_title = trans('post_codes.add_new');
        $countries  = Country::where('status','active')->get();
        return view('admin.post_codes.create', compact('page_title','countries'));
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
          'postcode'   => 'required|numeric|digits:6|unique:postcodes,postcode',
          'state_name'    => 'required',
          'city_name'     => 'required',
        ]);
       
        $data = $request->all();
        $data['city_id'] = $request->city_name;
        
        if(PostCode::create($data)) {
            return redirect()->route('post_codes.index')->with('success',trans('post_codes.added'));
        } else {
            return redirect()->route('post_codes.index')->with('error',trans('post_codes.error'));
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
        $page_title  = trans('post_codes.show');
        $post_code   =  PostCode::find($id);
        $city        =  City::find($post_code->city_id);
        $state       =  State::where('id',$city->state_id)->first();
        $country     =  Country::where('id',$state->country_id)->first();
        return view('admin.post_codes.show',compact('post_code','city','state','country','page_title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $page_title = trans('post_codes.update');
        $post_code   = PostCode::find($id);
        $countries  = Country::where('status','active')->get();
        $country_id = $post_code->city->state->country->id;
        $state_id   = $post_code->city->state->id;
        $city_id    = $post_code->city->id;
       
        return view('admin.post_codes.edit',compact('post_code','page_title','countries','country_id','state_id','city_id'));
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
        $post_code = PostCode::find($id);
 
        if(empty($post_code)){
            return redirect()->route('post_codes.index')->with('error',trans('post_codes.error'));
        }

        $validator= $request->validate([
            'postcode'  => ['required','numeric','digits:6','unique:postcodes,postcode,'.$post_code->id.',id'],
            'country_name'   => 'required',
            'state_name'   => 'required',
            'city_name'    => 'required',
        ]);

          $data['city_id'] = $request->city_name;
        

        if($post_code->update($data)){
            return redirect()->route('post_codes.index')->with('success',trans('post_codes.updated'));
        } else {
            return redirect()->route('post_codes.index')->with('error',trans('post_codes.error'));
        }
    }

    /**
      * Display a listing of the resource.
      *
      * @return \Illuminate\Http\Response
    */
    public function status(Request $request)
    {   
        $post_codes= PostCode::where('id',$request->id)
               ->update(['status'=>$request->status]);
        if($post_codes) {
            return response()->json(['success' => trans('post_codes.status_updated')]);
        } else {
            return response()->json(['error' => trans('post_codes.error')]);
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
        $city = PostCode::find($id);
        if($city->delete()){
            return redirect()->route('post_codes.index')->with('success',trans('post_codes.deleted'));
        }else{
            return redirect()->route('post_codes.index')->with('error',trans('post_codes.error'));
        }
    }

}