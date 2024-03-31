<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notification;
use App;
use Auth;
use Illuminate\Support\Facades\Storage;
use Validator,DB;
use App\Models\Helpers\CommonHelper;
use Carbon\Carbon;


class NotificationController extends Controller
{   
    use CommonHelper;
		/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware(['auth']);
    }
    
    public function index()
    {
      
      $page_title = trans('notifications.title');
      $user = Auth::user();

     // $read = Notification::where('user_id',$user->id)->update(['is_read'=>'1']);
     
      return view('admin.notifications.index',compact('page_title'));
  
    }

    public function index_ajax(Request $request)
    {   
        $user = Auth::user();
        
        $query = Notification::where('user_id',$user->id);
         if($user->user_type == 'admin') {
         $query = Notification::where('user_type','admin');
        //   $query = Notification::Query();
        }else{
         $query = Notification::where('user_id',$user->id);
        }

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
        $filter= $filter->where('title','like','%'.$searchValue.'%')
                      ->orwhere('content','like',$searchValue.'%')
                      ->orwhere('id','like',$searchValue.'%');
        }

        $filter_data=$filter->count();
        $totalRecordwithFilter = $filter_data;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();
        foreach ($empQuery as $emp) {

            $emp['delete'] = route("notifications.destroy",$emp["id"]);
    
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 

      DB::beginTransaction();
      try{

        $page  = Notification::find($id);
        
        if($page->delete()){
          

          DB::commit();
          return redirect()->route('notifications.index')->with('success',trans('notifications.notification_deleted'));
          
        }else{
          DB::rollback();
          return redirect()->route('notifications.index')->with('error',trans('notifications.error'));
        }
      }catch(\Exception $e){
          DB::rollback();
          return redirect()->route('notifications.index')->with('error',trans('notifications.error'));
      }
    }

   
}
