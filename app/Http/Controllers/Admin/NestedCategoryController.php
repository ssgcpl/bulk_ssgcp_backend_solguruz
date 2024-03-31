<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Category;
use App\Models\LayoutType;
use App\Models\Helpers\CommonHelper;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Validator,DB;
use Auth;
use Carbon\Carbon;

class NestedCategoryController extends Controller
{
    use CommonHelper;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:nested-category-list', ['only' => ['index','show']]);
        $this->middleware('permission:nested-category-create', ['only' => ['create','store']]);
        $this->middleware('permission:nested-category-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:nested-category-delete', ['only' => ['destroy']]);
    }

    public function index()
    {   

        $page_title = trans('nested_categories.nested_categories_list');
        //$count = Category::where('parent_id',null)->get()->count();
      
        return view('admin.nested_categories.index',compact('page_title'));
    }


    public function index_ajax(Request $request)
    {   
        
        $query           =   Category::query();
      
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

        if($request['filter_type'] !=''){
             $query = $query->where('status',$request['filter_type']);
        }

        if($searchValue != ''){
        $filter  = $query->where('category_name','like','%'.$searchValue.'%')
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

            $emp['number']              =  $row + $i;
            $emp['category_name'] = $emp['category_name'];
            $emp['parent_category'] = ($emp->parent) ? $emp->parent->category_name : '-';
            

            $emp['is_live'] = $emp['is_live'];
            //$emp['image'] = asset($emp['image']);
            ## Set dynamic route for action buttons
            $emp['edit']    = route("nested_categories.edit",$emp["id"]);
            $emp['show']    = route("nested_categories.show",$emp["id"]);
            
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

    public function create(){

        $page_title = trans('nested_categories.create');
        $master_categories = Category::where('parent_id',null)->get();
        return view('admin.nested_categories.create', compact('page_title','master_categories'));
    }

    public function get_category_childs(Request $request)
    {
        $category = Category::find($request->category_id);
        if($category) {
            return response()->json(['success' => $category->childs()]);
        } else {
            return response()->json(['success' => []]);
        }
    }


    public function store(Request $request)
    {
      // print_r($request->all()); die;
        $validator = Validator::make($request->all(),[
          "category_name"    => "required|array|min:1",
          "category_name.*"  => "required|string|min:3|max:100", //unique:categories,category_name
          'parent_id'         => 'nullable|exists:categories,id', 
          'level'             => 'required', 
        ], [
                'category_name.*.min' => 'Category name must be more than 2 characters',
              ]); 
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->first()]);
        }

       DB::beginTransaction();
       try {

          $data = $request->all();
          // print_r($data);die;

          $user = Auth::user(); 
          
         
          foreach ($data['category_name'] as $category) {
            $data['category_name'] = $category;
            $data['parent_id'] = @$data['parent_id'];
            $data ['added_by'] = $user->id;
            $data['is_live'] = isset($data['publish']) ? (string)$data['publish'] : '0';
            $data['level'] = $data['level'];

            $page = new Category();
            $page->fill($data);
            $page->save();
          }

          if($page) {

            DB::commit();
            return response()->json(['success' => trans('nested_categories.added')]);
            
          } else {

              DB::rollback();
              return response()->json(['error' => trans('nested_categories.error')]);
          }

         }catch (\Exception $e) {

            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        } 
    }

    public function edit($id)
    {   
      $page_title  = trans('nested_categories.update');
      $nested_category        = Category::find($id);
      if(empty($nested_category)){

            return redirect()->route('nested_categories.index')->with('error',trans('common.no_data'));
        }
      $master_categories = Category::where('parent_id',null)->get();
       
      return view('admin.nested_categories.edit',compact('nested_category','page_title','master_categories'));
    }

    public function show($id)
    {  
        $page_title = trans('nested_categories.show');
        $nested_category  =  Category::where('id',$id)->first();
        
        if(empty($nested_category)){

            return redirect()->route('nested_categories.index')->with('error',trans('common.no_data'));
        }
        return view('admin.nested_categories.show',compact('nested_category','page_title'));

    }

    public function update(Request $request, $id)
    {
       // print_r($request->all()); die;
        $validator = Validator::make($request->all(),[
          "category_name"     => "required|min:2|max:100",
          'parent_id'         => 'nullable|exists:categories,id', 
          'level'             => 'required', 
        ]); 
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->first()]);
        }
        DB::beginTransaction();
        try {

          $req_data = $request->all();

          $page = Category::find($id);
          if(!$page) {
            return response()->json(['error' => trans('common.no_data')]);
          }

          if(!isset($req_data['parent_id'])) {
            $req_data['parent_id'] = $page->parent_id;  
          }
          // print_r($data);die;

          $user = Auth::user(); 
         
            $data['category_name'] = $req_data['category_name'];
            $data['parent_id'] = @$req_data['parent_id'];
            $data ['added_by'] = $user->id;
            $data['is_live'] = isset($req_data['publish']) ? (string)$req_data['publish'] : '0';
            $data['level'] = $req_data['level'];

            $page->fill($data);
            $page->save();
          

          if($page->save()) {

            DB::commit();
            return response()->json(['success' => trans('nested_categories.updated')]);
            
          } else {

              DB::rollback();
              return response()->json(['error' => trans('nested_categories.error')]);
          }

        }catch (\Exception $e) {

            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }  
    }

    public function status(Request $request)
    {
        $page = Category::where('id',$request->id)
                  ->update(['status'=>$request->status]);

        if($page) {

            return response()->json(['success' => trans('nested_categories.status_updated')]);

        } else {

            return response()->json(['error' => trans('nested_categories.error')]);
        }
    }

    public function is_live(Request $request)
    {
        $page = Category::where('id',$request->id)
                  ->update(['is_live'=>$request->is_live]);

        if($page) {

            return response()->json(['success' => trans('nested_categories.is_live_updated')]);

        } else {

            return response()->json(['error' => trans('nested_categories.error')]);
        }
    }

   

}
