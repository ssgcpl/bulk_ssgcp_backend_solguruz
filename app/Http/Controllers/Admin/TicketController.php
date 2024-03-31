<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Reason;
use App\Models\User;
use App\Models\Helpers\CommonHelper;
use Validator,Auth,DB;
use Carbon\Carbon;
use App\Mail\SendTicketAcknowledgedEmailCustomer;
use App\Mail\SendTicketResolvedEmailCustomer;
use Illuminate\Support\Facades\Mail;

class TicketController extends Controller
{
    use CommonHelper;

    public function __construct()
    {
      $this->middleware('auth');
      $this->middleware('permission:ticket-list', ['only' => ['index','show']]);
      $this->middleware('permission:ticket-create', ['only' => ['create','store']]);
      $this->middleware('permission:ticket-edit', ['only' => ['edit','update']]);
      $this->middleware('permission:ticket-delete', ['only' => ['destroy']]);
    }

    public function index()
    {  
      $page_title = trans('tickets.admin_heading');
      return view('admin.tickets.index',compact('page_title'));
    }


    public function index_ajax(Request $request){
        $from_date       =    $request->start_date;
        $to_date         =    date('Y-m-d', strtotime($request->end_date. ' + 1 days'));
        
        $status_filter   =    $request->status;
     //   $type_filter     =    $request->user_type;
        $query = Ticket::query();
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

       
      //  $query = $query->whereBetween('created_at',[$from_date,$to_date]);
        
        

        if($status_filter != null){
          $query = $query->where('status',$status_filter);
        }

        if($from_date != '' && $to_date != ''){
           $from_date = date("Y-m-d",strtotime($from_date));
            $to_date = date("Y-m-d",strtotime($to_date));

        	$query = $query->whereBetween('created_at',[$from_date.' 00:00:00',$to_date.' 23:59:59']);
        }
       
        ## Total number of records without filtering
        $totalRecords = $query->count();

        ## Total number of record with filtering
        if($searchValue != ''){
            $filter =   $query->where(function($q) use ($searchValue) {
                              $q->whereHas('reason',function($q) use ($searchValue){
                                $q->where('name','like','%'.$searchValue.'%');
                              })
                              ->orWhere('id','like','%'.$searchValue.'%')
                              ->orWhere('email','like','%'.$searchValue.'%')
                              ->orWhere('full_name','like','%'.$searchValue.'%')
                              ->orWhere('message','like','%'.$searchValue.'%')
                              ->orWhere('user_type','like','%'.$searchValue.'%')
                              // ->orWhere('resolved_date','like','%'.$searchValue.'%')
                              // ->orWhere('acknowledged_date','like','%'.$searchValue.'%')
                              ->orWhere('created_at','like','%'.$searchValue.'%')
                              ->orWhere('updated_at','like','%'.$searchValue.'%')
                              ->orWhere('status','like',$searchValue.'%');
                        });
        }
        $filter = $query;
        $totalRecordwithFilter = $filter->count();

        ## Fetch records
        $empQuery = $filter->orderBy($columnName, $columnSortOrder)->offset($row)->limit($rowperpage)->get();
        $data = array();
        $i = 1;
        foreach ($empQuery as $emp) {
          $user_type = $emp->user_type;
        
          if($emp->user_type == 'customer'){
            $user_type = trans('tickets.customer');
          }

        ## Set dynamic route for action buttons
          // $emp['number']            = $row + $i;
          $user_id = $emp->user_id;
          $emp['full_name']    = $emp->full_name;
          $emp['email']        = $emp->email;
          $emp['user_type']    = ucfirst($emp->user_type);
          $reason    = Reason::where('id','=',$emp->reason_id)->get()->first();
          $emp['reason']  = @($reason) ? $reason['name'] : '';
          $emp['message'] = $emp->message;
          $emp['created'] =  date('d-m-Y h:i A',strtotime($emp->created_at));
          $emp['updated'] = date('d-m-Y h:i A',strtotime($emp->updated_at));
          $emp['show']= route("tickets.show",$emp->id);
       
          $data[]      = $emp;
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

        public function show($id)
    {   
        $page_title = trans('tickets.show');
        $ticket = Ticket::find($id);
        return view('admin.tickets.show',compact('ticket','page_title'));
    }

    public function status(Request $request)
    {   
        $contact = Ticket::find($request->id);
        if($contact->status == 'resolved') {
          if($request->status == 'new_ticket' || $request->status == 'acknowledged'){
            return response()->json(['error' => trans("tickets.can't_update_resolved"),'type'=>'error']);
          }
        }elseif ($contact->status == 'acknowledged') {
            if($request->status == 'new_ticket'){
             return response()->json(['error' => trans("tickets.sorry_you_not_able_to_change_status_to_new"),'type'=>'error']); 
            }
        } 

        if($request->status == 'new_ticket'){
          $request->status = 'pending';
        }
    
        $contact= Ticket::where('id',$request->id)
               ->update(['status'=>$request->status,'acknowledged_date'=>date('Y-m-d H:i:s')]);
        $contact = Ticket::find($request->id);  
        
        // print_r($contact);die;      
      if($contact){
      /*  if($contact->status != 'resolved'){
        // if($contact->status != 'resolved'){
          //Notify admin for new ticket raised by vendor
        //     $user     = User::where(['id' => $contact->user_id])->first();
        //     $title    = trans('notify.ticket_status_updated_to_'.$contact->status);
        //     $body     = trans('notify.ticket_status_updated_body_to_'.$contact->status);
        //     $slug     = 'ticket_status_updated';
        //     $this->sendNotification($user,$title,$body,$slug,$contact,null);
          
        // }
        */

        return response()->json(['success' =>trans('tickets.status_updated'),'type'=>'success']);
       
      }else{

        return response()->json(['error' => trans('tickets.error'),'type'=>'error']);
       }
    }

    public function r_status(Request $request){
      $validator = Validator::make($request->all(),[

            'id'           => 'required|exists:tickets,id',
            'comment'         => 'required|max:500'

      ]);

      if($validator->fails()){
            return response()->json(['message' => $validator->errors()->first(),'type'=>'error']);
        }
        $tickets   =  Ticket::find($request->id);
        $data['comment']    = $request->comment;
        $data['status']    ='resolved';
        $data['resolved_date'] =  date('Y-m-d H:i:s');
        $tickets->update($data);

        $contact = Ticket::find($request->id);  
        if($contact){

          //###### Customer Ticket Resolved Notification ######
          $title    = 'Ticket Resolved Successfully';
          //$body     = "Your ticket id:".$contact->id." has been resolved. You can find more information in the ticket section of our app.";
          $body     = "Your ticket id:".$contact->id." has been resolved.";
          
          $slug     = 'customer_ticket_resolved';
          $this->sendNotification($contact->user,$title,$body,$slug,$contact,null);
          //###### Customer Ticket Resolved Notification ######

          //###### Customer Ticket Resolved Email ######
          if($contact->user->email) {
              $subject = "Updated ticket status ".$contact->id." to Resolved";
              Mail::to($contact->user->email)->send(new SendTicketResolvedEmailCustomer($contact->user,$contact,$subject));
          }
          //###### Customer Ticket Resolved Email ######

          
          return response()->json(['message' => trans('tickets.status_updated'),'type'=>'success']);

        }else{

          return response()->json(['error' => trans('tickets.error'),'type'=>'error']);
       }
    }

    public function acknowledged_status(Request $request){
      $validator = Validator::make($request->all(),[

            'id'           => 'required|exists:tickets,id',
            'comment'         => 'required|max:500'

      ]);

      if($validator->fails()){
            return response()->json(['message' => $validator->errors()->first(),'type'=>'error']);
        }
        $tickets   =  Ticket::find($request->id);
        $data['acknowledged_comment']    = $request->comment;
        $data['status']    ='acknowledged';
        $data['acknowledged_date'] =  date('Y-m-d H:i:s');
        $tickets->update($data);

        $contact = Ticket::find($request->id);  
        if($contact){

          //###### Customer Ticket Acknowledged Notification ######
          $title    = 'Ticket Acknowledged Successfully';
          $body     = "Your ticket id:".$contact->id." has been acknowledged and have already started working on resolving your query";
          $slug     = 'customer_ticket_acknowledged';
          $this->sendNotification($contact->user,$title,$body,$slug,$contact,null);
          //###### Customer Ticket Acknowledged Notification ######

          //###### Customer Ticket Acknowledged Email ######
          if($contact->user->email) {
              $subject = "Updated ticket status ".$contact->id." to Acknowledged";
              Mail::to($contact->user->email)->send(new SendTicketAcknowledgedEmailCustomer($contact->user,$contact,$subject));
          }
          //###### Customer Ticket Acknowledged Email ######

          
          return response()->json(['message' => trans('tickets.status_updated'),'type'=>'success']);

        }else{

          return response()->json(['error' => trans('tickets.error'),'type'=>'error']);
       }
    }

}
