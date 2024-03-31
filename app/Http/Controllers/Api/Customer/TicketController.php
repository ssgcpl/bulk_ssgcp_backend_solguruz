<?php

namespace App\Http\Controllers\Api\Customer;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Reason;
use App\Models\User;
use App\Http\Resources\Customer\TicketResource;
use App\Http\Resources\Customer\ReasonResource;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use App\Models\Helpers\CommonHelper;
use DB;
use Validator;
use App\Notifications\CustomerTicket;
use Notification;

/**
* @group Customer Endpoints
*
* Customer Apis
*/

class TicketController extends BaseController
{ 
  use CommonHelper;

  /**
    * Customer Support: List
    *
    * @authenticated 
    * 
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "Ticket List found",
      "data": [
          {
              "ticket_number": "2",
              "date": "22-03-2022",
              "time": "11:09 AM",
              "admin_comment": "",
              "reason": {
                  "id": "2",
                  "reason": "Need help for How to do payment"
              },
              "message": "wifi not working in mobile application",
              "status": "Pending",
              "status_original": "pending"
          },
          {
              "ticket_number": "1",
              "date": "22-03-2022",
              "time": "11:09 AM",
              "admin_comment": "",
              "reason": {
                  "id": "2",
                  "reason": "Need help for How to do payment"
              },
              "message": "wifi not working in mobile application",
              "status": "Pending",
              "status_original": "pending"
          }
      ],
      "links": {
          "first": "http://localhost/beauty-fly/public/api/customer/ticket_list?page=1",
          "last": "http://localhost/beauty-fly/public/api/customer/ticket_list?page=1",
          "prev": "",
          "next": ""
      },
      "meta": {
          "current_page": 1,
          "last_page": 1,
          "per_page": 15,
          "total": 2
      }
    }
    */
  public function index(){
    
      $user = auth()->user();
      $ticket = Ticket::where('user_id',$user->id)
          ->latest()
          ->paginate();
      if($ticket) {
          return $this->sendPaginateResponse(TicketResource::collection($ticket),trans('tickets.ticket_list_found'));
      }else {
        return $this->sendPaginateResponse('',trans('tickets.ticket_list_not_found')); 
    }
  }
  
  /**
    * Customer Support: Reasons
    *
    * @authenticated
    * 
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "Reason data found",
      "data": [
          {
              "id": "2",
              "reason": "Need help for How to do payment"
          },
          {
              "id": "4",
              "reason": "I Need help for How to do payment"
          }
      ]
    }
  */
  public function reason_list(){
      $reason = Reason::where('type',Reason::CUSTOMER_TICKET_TYPE)->where('status','active')->get();
      if(count($reason)) {
          return $this->sendResponse(ReasonResource::collection($reason),trans('reasons.reason_found'));
      }else {
        return $this->sendResponse('',trans('reasons.reason_not_found')); 
    }
  }

  /**
    * Customer Support: Send ticket
    *
    * @authenticated
    *
    * @bodyParam reason_id integer required
    * @bodyParam full_name string required max:500
    * @bodyParam email email required max:500
    * @bodyParam mobile_number string required Contact number. Example:1231231231
    * @bodyParam message string required max:500
    * 
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "Enquiry has been submitted successfully.",
      "data": {
          "ticket_number": "2",
          "date": "22-03-2022",
          "time": "11:09 AM",
          "admin_comment": "",
          "reason": {
              "id": "2",
              "reason": "Need help for How to do payment"
          },
          "message": "wifi not working in mobile application",
          "status": "Pending",
          "status_original": "pending"
      }
    }
  */
  public function send_ticket (Request $request){

    $validator=  Validator::make($request->all(),[
      'full_name'     => 'required|min:3|max:50',
      'mobile_number' => 'required|digits:10',
      'email'         => 'required|email|min:3|max:100',
     // 'message'       => 'required|min:3|max:500', 
      'reason_id'     => 'required|max:10000|exists:reasons,id',
      'message'       => 'required|min:3|max:500',
     
    ]);

    if($validator->fails()){
      return $this->sendValidationError('', $validator->errors()->first());
    }

    $user = Auth::guard('api')->user();

    $reason = Reason::find($request->reason_id);
    $data = $request->all();

    if(!$user) {
      $data['user_type'] = 'guest';
    }else {
      $data['user_id'] = $user->id;
      //$data['user_type'] = 'customer';
      $data['user_type'] = $user->user_type;
    }
    $data['full_name'] = $request->full_name;
    $data['email'] = $request->email;
    $data['mobile_number'] = $request->mobile_number;
    $data['status'] = 'pending';
    $ticket = Ticket::create($data);

    

    if($ticket) {
      //###### Admin Ticket Raised Notification ######
      $admin = User::where('user_type','admin')->first();
      if($admin){
          $title    = 'New Ticket Raised By Customer';
          if($user) {
            if($user->first_name){
            $body     = "You have received the new ticket to validate for ".$user->first_name." ".$user->last_name;
            }
          }else{
            $body     = "You have received the new ticket to validate for the customer";
          }
          $slug     = 'admin_ticket_raised';
          $this->sendNotification($admin,$title,$body,$slug,$ticket,null);
      }
      //###### Admin Ticket Raised Notification ######

      return $this->sendResponse(new TicketResource($ticket),trans('tickets.enquiry_submitted'));
    } else {
      return $this->sendResponse('',trans('tickets.enquiry_not_submitted')); 
    }
  }

  /**
    * Customer Support: Ticket details
    *
    * @authenticated
    * 
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "Data Found Successfully",
      "data": {
          "ticket_number": "2",
          "date": "22-03-2022",
          "time": "11:09 AM",
          "admin_comment": "",
          "reason": {
              "id": "2",
              "reason": "Need help for How to do payment"
          },
          "message": "wifi not working in mobile application",
          "status": "Pending",
          "status_original": "pending"
      }
    }
  */
  public function detail($id) {
    $user = auth()->user();
    $ticket = Ticket::where(['id'=>$id, 'user_id' => $user->id])->first();
    if($ticket) {
        return $this->sendResponse(new TicketResource($ticket),trans('common.success'));
    } else {
      return $this->sendResponse('',trans('tickets.ticket_not_found')); 
    }
  }
}



