<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use App\Models\DeviceDetail;
use App\Models\MobileVerification;
use App\Models\PasswordResetCode;
use App\Models\Notification;
use App\Models\Setting;
use Twilio\Rest\Client;



class BaseController extends Controller
{
    protected $object, $array;

    public function __construct()
    {
        $this->object =  new \stdClass();
        $this->array  =  array();
    }
  /**
   * success response method.
   *
   * @return \Illuminate\Http\Response
   */
  public function sendResponse($result = [],$message,$status= '200'){
    $response = [
                  'success' => "1",
                  'status'  => $status,
                  'message' => $message, 
                ];

    if($result != ''){
      $response['data'] = $result;
    }
    return response()->json($response, 200);
  }
  /**
   * return error response.
   *
   * @return \Illuminate\Http\Response
   */
  public function sendError($result = [],$message, $code = 200 , $status= '201'){
    $response = [
                  'success' => "0",
                  'status'  => $status,
                  'message' => $message,
                ];

    if($result != ''){
        $response['data'] = $result;
    }
    return response()->json($response, $code);
  }
  /**
  * return validation error response.
  *
  * @return \Illuminate\Http\Response
  */
  public function sendValidationError($result = [],$message, $code = 200 , $status= '201'){
    $response = [
                  'success' => "0",
                  'status'  => $status,
                  'message' => $message,
                ];

    if(!empty($result)){
      $response['data'] = $result;
    }

    return response()->json($response, $code);
  }
 
  /**
   * Special conditions
   *
   * @return \Illuminate\Http\Response
   */
  public function sendException($result = [],$message,$status= '202'){
    $response = [
        'success' => "1",
        'status'  => $status,
        'message' => $message, 
    ];

    if($result != ''){
        $response['data'] = $result;
    }

    return response()->json($response, 200);
  }

    /**
   * success response method.
   *
   * @return \Illuminate\Http\Response
   */
  public function sendPaginateResponse($result = [],$message,$status= '200'){
    // return $result;
    $response = [
                  'success' => "1",
                  'status'  => $status,
                  'message' => $message, 
                ];

    if(!empty($result)){
      $response['data'] = $result->items();
      $response['links'] = [
          'first' => $result->url(1),
          'last' => $result->url($result->lastPage()),
          'prev' => ($result->previousPageUrl()) ? $result->previousPageUrl() : "",
          'next' => ($result->nextPageUrl()) ? $result->nextPageUrl() : "",
        ];
      $response['meta'] = [
          'current_page' => $result->currentPage(), 
          // 'from' => '', 
          'last_page' => $result->lastPage(), 
          // 'path' => $result->url(1), 
          'per_page' => $result->perPage(), 
          // 'to' => '', 
          'total' => $result->total(), 
        ];
    }
    return response()->json($response, 200);
  }

  
  

}
