<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WishSuggestion;
use App\Models\WishSuggestionImage;
use App\Models\DealerRetailer;
use App\Models\Notification;
use Validator,Auth,DB,Hash;
use App\Models\Helpers\CommonHelper;


class WishSuggestionController extends Controller
{
   use CommonHelper;

 	 public function __construct()
    {
      $this->middleware('auth');
      $this->middleware('permission:wish-suggestion-list', ['only' => ['index','show']]);
      $this->middleware('permission:wish-suggestion-create', ['only' => ['create','store']]);
      $this->middleware('permission:wish-suggestion-edit', ['only' => ['edit','update']]);
      $this->middleware('permission:wish-suggestion-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index() {
      $page_title = trans('wish_suggestions.heading');
      return view ('admin.wish_suggestions.index',compact('page_title')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index_ajax(Request $request) {

      //  $query           =    WishList::query()->groupBy('product_id');
    	  $query           = WishSuggestion::query();
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

          ## from/to date filter
          $from_date       =    $request['start_date'];
          $to_date         =    $request['end_date'];
          if($from_date != '' && $to_date != ''){
             $from_date = date("Y-m-d",strtotime($from_date));
            $to_date = date("Y-m-d",strtotime($to_date));
            $query = $query->whereBetween('created_at',[$from_date.' 00:00:00',$to_date.' 23:59:59']);
          }

        ## Total number of record with filtering
        $filter = $query;

        if($searchValue != ''){
       $filter = $filter->where(function($q) use ($searchValue) {
                            $q->where('book_name','like','%'.$searchValue.'%')
                            ->orWhere('subject','like','%'.$searchValue.'%')
                            ->orWhere('mobile_number','like','%'.$searchValue.'%')
                            ->orWhere('description','like','%'.$searchValue.'%')
                            ->orWhere('id','like',$searchValue.'%');
                     })->OrwhereHas('user',function($q1) use($searchValue) {
                          $q1->where('first_name','like','%'.$searchValue."%");
                     });
        }

        $filter_count = $filter->count();
        $totalRecordwithFilter = $filter_count;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();
        $i = 1;
        foreach ($empQuery as $emp) {
            ## Set dynamic route for action buttons
            // $emp['id'] = $i;
            $user = User::find($emp->user_id);
            $emp['user_name'] = $user->first_name;
          	$emp['book_name'] = $emp->book_name;
          	$emp['subject'] = $emp->subject;
          	$emp['description'] = $emp->description;
            $starttimestamp = strtotime($emp->created_at);
            $endtimestamp = strtotime(date("Y-m-d H:i:s"));
            $difference = round(abs($endtimestamp - $starttimestamp)/3600);
            $hours =  $difference;
            if($difference <= 24) {
              $emp['date_time']  = $difference." Hours Ago";
            }else {
              $emp['date_time']  = date("d-m-Y h:i A",strtotime($emp->created_at));
            }

            $emp['show']= route("wish_suggestions.show",$emp->id);
            $data[]=$emp;
            $i++;
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

    public function show(Request $request,$id) {
      $page_title = trans('wish_suggestions.heading');
      $wish_suggestion = WishSuggestion::find($id);
      return view ('admin.wish_suggestions.show',compact('page_title','wish_suggestion')); 
    }

    public function view_wish_suggestion_images(Request $request)
    {
         $page_title = trans('wish_suggestions.wish_suggestion_images_heading');
         return view ('admin.wish_suggestions.wish_suggestion_images',compact('page_title')); 
   
    }

    public function dt_wish_suggestion_images(Request $request) 
    {

        $id              =    $request->wish_suggestion_id; 
        $query           =    WishSuggestionImage::where('wish_suggestion_id',$id);
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
                            $q->where('id','like','%'.$searchValue.'%');
                     });
        }

        $filter_count = $filter->count();
        $totalRecordwithFilter = $filter_count;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();
        $i = 1;
        foreach ($empQuery as $emp) {
            ## Set dynamic route for action buttons
            $emp['number'] = $i;
            $emp['image']  = ($emp->image)? '<a href="'.asset($emp->image).'" target="_blank" ><img src="'.asset($emp->image).'" width="40%" height="40%"/></a>':'';
            $emp['pdf']    = ($emp->pdf)? '<a href="'.asset($emp->pdf).'" style="text-align:center" target="_blank">View PDF</a>':'';
   
            $data[]=$emp;
            $i++;
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

}
