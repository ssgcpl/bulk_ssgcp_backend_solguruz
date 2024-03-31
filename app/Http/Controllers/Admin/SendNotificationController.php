<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notification;
use Validator,Auth,DB,Hash;
use App\Models\Helpers\CommonHelper;
use App\Jobs\SendBulkNotification;
use App\Jobs\InitiateNotificationJob;

class SendNotificationController extends Controller
{
    use CommonHelper;
    public function __construct(){
      $this->middleware('auth');
      $this->middleware('permission:customer-list', ['only' => ['index','show']]);
      $this->middleware('permission:customer-create', ['only' => ['create','store']]);
      $this->middleware('permission:customer-edit', ['only' => ['edit','update']]);
      $this->middleware('permission:customer-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
       $page_title = trans('customers.send_notification');
       $customer = User::find($request->id);
        if($customer){
            return view('admin.customers.notification',compact(['customer','page_title']));
        } else {
            return redirect()->route('customers.index')->with('error', trans('customers.admin_error'));
        }
    }
    public function index_ajax(Request $request) {
        $query = Notification::where('user_id',$request["id"]);
        $request         =    $request->all();
        ## Total number of records without filtering
        $totalRecords    =    $query->count();
        $draw            =    $request['draw'];
        $row             =    $request['start'];
        $length = ($request['length'] == -1) ? $totalRecords : $request['length'];
        $rowperpage      =    $length; // Rows display per page
        $columnIndex     =    $request['order'][0]['column']; // Column index
        $columnName      =    $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder =    $request['order'][0]['dir']; // asc or desc
        $searchValue     =    $request['search']['value']; // Search value

        ## Total number of record with filtering
        $total = $query->count();
        $totalRecords = $total;
   
        $filter = $query;
   
        if($searchValue != ''){
            $filter = $filter->where(function($q)use ($searchValue) {
                            $q->where('id','like',$searchValue.'%')
                            ->Orwhere('created_at','like',$searchValue.'%')
                            ->orWhere('content','like','%'.$searchValue.'%');
                     });
        }

        $filter_count = $filter->count();
        $totalRecordwithFilter = $filter_count;

        ## Fetch records
        $empQuery = $filter;
        $empQuery = $empQuery->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();
        foreach ($empQuery as $emp) {
            $emp['message']= $emp["content"];
            $emp['send_by'] = User::find($emp->send_by)->first_name ?? "";
            $emp['created_date']=date("Y-m-d",strtotime($emp['created_at']));
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
    public function send_notification(Request $request){
        $this->validate($request,[
           // 'user_id'           => 'required|exists:users,id',
            'user_id'           => 'required',
            'body'         => 'required|max:300',
            'url'         => 'nullable|url|max:500'
        ]);

        $title    = 'Announcement from Admin';
        $body     = $request->body;
        $send_by = Auth::user()->id; // To add send_by user id in notifications table
        $slug = 'announcement_from_admin';
        if($request->user_id == 'all'){
            dispatch(new InitiateNotificationJob($title,$body,$slug,$request->url));
            /*$users = User::whereIn('user_type',['retailer','dealer'])->get();
            foreach($users as $user){
               $this->sendNotification($user,$title,$body,$slug,null,$request->url,$send_by);
            }*/
        } else {
            $user = User::where(['id'=>$request->user_id])->first();
            $this->sendNotification($user,$title,$body,$slug,null,$request->url,$send_by);
        }
        return redirect()->route('customers.index')->with('success', trans('customers.notification_sent'));
    }
}
