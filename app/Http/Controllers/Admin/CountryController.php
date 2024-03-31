<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Country;
use App\Models\Helpers\CommonHelper;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    use CommonHelper;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:country-list', ['only' => ['index','show']]);
        $this->middleware('permission:country-create', ['only' => ['create','store']]);
        $this->middleware('permission:country-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:country-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $page_title = trans('countries.title');
        $countries  = Country::all();

        return view('admin.countries.index',compact('countries','page_title'));
    }


    public function index_ajax(Request $request){
        $query = Country::query();
        ## Total number of records without filtering
        $totalRecords = $query->count();

        $request         = $request->all();
        $draw            = $request['draw'];
        $row             = $request['start'];
        $length          = ($request['length'] == -1) ? $totalRecords : $request['length'];
        $rowperpage      = $length; // Rows display per page
        $columnIndex     = $request['order'][0]['column']; // Column index
        $columnName      = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue     = $request['search']['value']; // Search value

        

        ## Total number of record with filtering
        if($searchValue != ''){
            $filter =   $query->where(function($q) use ($searchValue) {
                            $q->where('name','like','%'.$searchValue.'%')
                              ->orWhere('country_code','like','%'.$searchValue.'%')
                              ->orWhere('status','like',$searchValue.'%');
                        });
        }

        $filter = $query;
        $totalRecordwithFilter = $filter->count();

        ## Fetch records
        $empQuery = $filter->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data     = array();
        $i = 1;
        foreach ($empQuery as $emp) {

        ## Set dynamic route for action buttons
            $emp['number']      = $row + $i;
            $emp['edit']        = route("country.edit",$emp["id"]);
            $emp['show']        = route("country.show",$emp["id"]);
            $emp['delete']      = route("country.destroy",$emp["id"]);
            $emp['flag']      = "<img src='".asset($emp['flag'])."' style='width:30px'>";

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
    public function create(){

        $page_title = trans('countries.create');

        return view('admin.countries.create',compact('page_title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'country_name'          => 'required|regex:/^[\w\-\s]+$/u|max:30|unique:countries,name',
            'country_code'  => 'required|regex:/^(\+?\d{1,5})$/|unique:countries,country_code',
            'flag'          => 'required|image|mimes:png,jpg,jpeg|max:20000',
        ]);

        $data = $request->all();
         if($data['flag'] != null){
            $type = "flag";
            $data['flag'] = $this->saveMedia($data['flag'],$type);
          }
          $data['name'] = $data['country_name'];
        if(Country::create($data)) {

            return redirect()->route('country.index')->with('success',trans('countries.added'));

        } else {

            return redirect()->route('country.index')->with('error',trans('countries.error'));
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
        $page_title = trans('countries.detail');
        $country    = Country::find($id);

        return view('admin.countries.show',compact('country','page_title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page_title = trans('countries.edit');
        $country    = Country::find($id);

        return view('admin.countries.edit',compact('country','page_title'));
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
        $data    = $request->all();
        $country = Country::find($id);

        if(empty($country)){
            return redirect()->route('countries.index')->with('error','Something went wrong,Please try again');
        }

        $validator = $request->validate([
            'country_name'  =>['required','regex:/^[\w\-\s]+$/u','max:30',Rule::unique('countries','name')->ignore($country->id)],
            'country_code'  => ['required','regex:/^(\+?\d{1,5})$/',Rule::unique('countries','country_code')->ignore($country->id)],
            'flag'          => 'image|mimes:png,jpg,jpeg,gif|max:20000',
          ]);

         $data = $request->all();
         
         if(isset($data['flag'])){
            $type = 'flag';
            $data['flag'] = $this->saveMedia($data['flag'],$type);
          }
          $data['name'] = $data['country_name'];

        if($country->update($data)){

            return redirect()->route('country.index')->with('success',trans('countries.updated'));

        } else {

            return redirect()->route('country.index')->with('error',trans('contries.error'));
        }
    }

    public function status(Request $request)
    {
        $country = Country::where('id',$request->id)->update(['status'=>$request->status]);

        if($country) {
            return response()->json(['success' => trans('countries.status_updated')]);
        } else {
            return response()->json(['error' => trans('countries.error')]);
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
        $country = Country::find($id);

        if($country->delete()){

            return redirect()->route('country.index')->with('success','Country deleted Successfully');

        }else{

            return redirect()->route('country.index')->with('error',"Coudn't delete Country");
        }
    }
}