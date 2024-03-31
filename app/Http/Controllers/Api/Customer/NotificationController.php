<?php

namespace App\Http\Controllers\Api\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\Country;
use App\Models\Notification;
use App\Models\Setting;
use App\Http\Resources\Customer\NotificationResource;
use App\Models\Helpers\CommonHelper;
use Auth;
use DB;
use Validator;


/**
 * @group Customer Endpoints
 *
 * Customer Apis
 */

class NotificationController extends BaseController
{
	
    use CommonHelper;

    /**
    * Notifications: List
    * @authenticated
    * 
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "Cart data",
      "data": [
              {
                  "id": "9",
              },
              {
                  "id": "9",
              },
              {
                  "id": "9",
              },
      ]
    }
    */
    public function notification_list(Request $request){

        $user = Auth::guard('api')->user();

        if($user == null){
            return $this->sendError($this->object,trans('master_api.user'));
        }

        $notifications =  Notification::where('user_id',$user->id)->orderBy('created_at','DESC')->paginate();

        if($notifications->count() > 0){

            foreach($notifications as $no){
                $no->is_read       = '1';
                $no->save();
            }

            $response = NotificationResource::collection($notifications);

            return $this->sendPaginateResponse($response,trans('common.data_found'));

        }else if($notifications->count() == 0){

            return $this->sendResponse([],trans('common.no_data'));

        }else{

            return $this->sendError([],trans('master_api.error'));
        }
    }

    /**
    * Notifications: Unread Count
    * @authenticated
    * 
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "Cart data",
      "data": {
        "count": "9",
      }
    }
    */
    public function unreadNotifCount(Request $request)
    {
        $count = Notification::where('is_read','0')->where('user_id',Auth::guard('api')->user()->id)->count();
        $data['count'] = (string)$count;
        return $this->sendResponse($data, trans('notifications.unread_notification_count'));
    }

    /**
    * Notifications: Delete Notification
    * @authenticated
    * @bodyParam id number 
    * @response
    {
      "success": "1",
      "status": "200",
      "message": "Notification deleted",
    }
    */
    public function delete_notification(Request $request){

        $validator = Validator::make($request->all(),[
            'id'     => 'nullable|exists:notifications,id',
        ]);

        if($validator->fails()){

          return $this->sendValidationError('',$validator->errors()->first());
        }

        $user         = Auth::guard('api')->user();
        if(isset($request->id)) {
          $notification = Notification::where(['id' => $request->id,'user_id' => $user->id]);
        } else {
          $notification = Notification::where(['user_id' => $user->id]);
        }

        if($notification->delete()){

            return $this->sendResponse('', trans('notifications.notification_deleted'));

        }else{

            return $this->sendError('',trans('common.no_data'));
        }

    }

    /**
    * Notifications: Clear all Notifications
    * @authenticated
    * @response
    {
        "success": "1",
        "status": "200",
        "message": "All notifications have been cleared successfully."
    }
    */
    public function clear_all_notifications()
    {
      $notification = Notification::where('user_id', Auth::guard('api')->user()->id)->delete();
      if($notification) {
          return $this->sendResponse('',trans('notifications.notifications_cleared'));
      } else {
          return $this->sendResponse('',trans('notifications.notifications_already_cleared'));
      }
    }

}   
