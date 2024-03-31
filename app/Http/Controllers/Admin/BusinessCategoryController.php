<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BusinessCategory;
use App\Models\LayoutType;
use App\Models\Helpers\CommonHelper;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;


class BusinessCategoryController extends Controller
{
     use CommonHelper;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:business-category-list', ['only' => ['index','show']]);
        $this->middleware('permission:business-category-create', ['only' => ['create','store']]);
        $this->middleware('permission:business-category-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:business-category-delete', ['only' => ['destroy']]);
    }

    public function index()
    {   
        $page_title = trans('business_categories.business_categories_list');
        $count = BusinessCategory::where('parent_id',null)->get()->count();
      
        return view('admin.business_categories.index',compact('page_title','count'));
    }


    public function index_ajax(Request $request)
    {   
        
        $query           =   BusinessCategory::where('parent_id',null);
      
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
        	             ->Orwhere('status','like',$searchValue.'%')
                       ->orWhere('id','=',$searchValue)
            			     ->where('parent_id',null);
          
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
            $emp['category_image'] = $emp->category_image ? '<img src="'.asset($emp->category_image).'" style="width:50px" "height:30px">' :'';
            $emp['category_name'] = $emp['category_name'];
             if($emp['layout'] == 'url')
             {
                $layout_type = LayoutType::where('name','=',$emp->layout)->first();
               $emp['layout_type'] = '<a href="'.$emp->url.'" target="blank">URL</a>';
             }
             else
             {
              $emp['layout_type'] = ($emp['layout']) ? trans('business_categories.'.$emp['layout']) : "";
            
             }

        

            $emp['is_live'] = $emp['is_live'];
            //$emp['image'] = asset($emp['image']);
            ## Set dynamic route for action buttons
            $emp['edit']    = route("business_categories.edit",$emp["id"]);
            $emp['show']    = route("business_categories.show",$emp["id"]);
            
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

        $page_title = trans('business_categories.create');
        $layouts = LayoutType::where('status','active')->get();
        return view('admin.business_categories.create', compact('page_title','layouts'));
    }


    public function store(Request $request)
    {
        
        $validator = $request->validate([
          'category_name'     => 'required|min:2|max:100|unique:business_categories,category_name',
          'category_image'   => 'required|image|mimes:png,jpg,jpeg|max:20000',
          //'description'      => 'required|max:100000',
          'layout'             => 'required', 
          'display_order' => 'required|numeric|gt:0',
        ]);
       
         if($request['layout'] == 'url' ) // For URL Layout type
          {
            $validator = $request->validate([
                 'url'     => 'required|max:99|url', 
            ]);

          }
       DB::beginTransaction();
       try {

          $data = $request->all();
          $user = Auth::user(); 
          
          if(isset($data['url']))
          {
             $data['url'] = $data['url'];
           }
           else
           {
            $data['url'] = '';
           }

          $count = BusinessCategory::where('parent_id',null)->count();
         // $data['sort_order'] = $count + 1;
        
          if($data['category_image'] != null){
            $data['category_image'] = $this->saveMedia($data['category_image']);
          }
         
          $data['category_name'] = $data['category_name'];
         // $data['description'] = $data['description'];
          $data['layout'] = $data['layout'];

          $data ['added_by'] = $user->id;
          if(isset($data['is_live'])) {
            $data['is_live']="1";
          }
          else  {
            $data['is_live'] = "0";
          }
          //$data['is_live'] = '1';
          $page = new BusinessCategory();
          $page->fill($data);

          if($page->save()) {

            DB::commit();
            return redirect()->route('business_categories.index')
                             ->with('success',trans('business_categories.added'));

          } else {

              DB::rollback();
              return redirect()->route('business_categories.index')
                               ->with('error',trans('business_categories.error'));
          }

         }catch (\Exception $e) {

            DB::rollback();
            return redirect()->route('business_categories.index')
                             ->with('error',$e->getMessage());
        } 
    }

     public function show($id)
    {  
        $page_title = trans('business_categories.show');
        $business_category  =  BusinessCategory::where('id',$id)->first();
        $layouts = LayoutType::where('id',@$business_category->layout)->first();
       
        if(empty($business_category)){

            return redirect()->route('business_categories.index')->with('error',trans('common.no_data'));
        }
        return view('admin.business_categories.show',compact('business_category','page_title','layouts'));

    }

    public function edit($id)
    {   
      $page_title  = trans('business_categories.update');
      $business_category        = BusinessCategory::find($id);
      if(empty($business_category)){
            return redirect()->route('business_categories.index')->with('error',trans('common.no_data'));
      }
      $layouts = LayoutType::where('status','active')->get();
       
      return view('admin.business_categories.edit',compact('business_category','page_title','layouts'));
    }



    public function update(Request $request, $id)
    {
        
        $data       =  $request->all();
        $page       =  BusinessCategory::find($id);

        if(empty($page)){

            return redirect()->route('business_categories.index')->with('error',trans('admin.error'));
        }

         $validator = $request->validate([
              'category_image'   => ($request->category_image!=null)?'sometimes|image|mimes:png,jpg,jpeg,gif|max:20000':'',
              'category_name'   => 'sometimes|min:2|max:100|unique:business_categories,category_name,'.$page->id,
              //'description'     => 'required|max:100000',
               'url'            => ($request->layout!=null && $request->layout=='url')?'required|max:99|url':'',
               'display_order' => 'required|numeric|gt:0'
               ]);
  
        if($data['layout'] == 'url')
        {    $data['url'] = $data['url'];   }
        else
        {    $data['url'] = '';  }

        if(isset($data['category_image'])  && $data['category_image'] != null){
          $this->deleteMedia($page['category_image']);
          $data['category_image'] = $this->saveMedia($data['category_image']);
        }

          if(isset($data['is_live'])) {
            $data['is_live']="1";
          }
          else  {
            $data['is_live'] = "0";
          }
          
        $page->fill($data);

        if($page->save()){

          return redirect()->route('business_categories.index')->with('success',trans('business_categories.updated'));

        } else {

          return redirect()->route('business_categories.index')->with('error',trans('admin.error'));
        }
    }

    

    public function status(Request $request)
    {
        $page = BusinessCategory::where('id',$request->id)
                  ->update(['status'=>$request->status]);

        if($page) {

            return response()->json(['success' => trans('business_categories.status_updated')]);

        } else {

            return response()->json(['error' => trans('business_categories.error')]);
        }
    }

    public function is_live(Request $request)
    {
        $page = BusinessCategory::where('id',$request->id)
                  ->update(['is_live'=>$request->is_live]);

        if($page) {

            return response()->json(['success' => trans('business_categories.is_live_updated')]);

        } else {

            return response()->json(['error' => trans('business_categories.error')]);
        }
    }


}
