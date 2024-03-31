<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Template;
use Validator,Auth,DB,Hash;
use App\Models\Helpers\CommonHelper;
use App\Jobs\SendBulkEmailNotification;
use App\Jobs\InitiateEmailNotificationJob;

class SendEmailController extends Controller
{
     use CommonHelper;

    public function __construct()
    {
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
  
       $page_title = trans('customers.send_email');
       $customer = User::find($request->id);
       //$templates = Template::where('status','active')->get();
       $templates = [];
        if($customer){
            return view('admin.customers.email',compact(['customer','templates','page_title']));
        }else{
            return redirect()->route('customers.index')->with('error', trans('customers.admin_error'));
        }
  }

  public function template_message(Request $request)
  {
    if($request->id!=0){
    $message =Template::find($request->id)->message;
    }
    else
    {
      $message = '';
    }
    return response()->json(['data'=>$message,'success' => trans('data found')]);
    
  }

  public function send_email(Request $request)
  {
  	 $validator = $request->validate([
          'message'     => 'required|min:2|max:500',
          'subject'     => 'required|max:50',
          'template'    => 'required',
      ]);
  	 $body = $request->message;
	   $title = $request->subject;

  	 if($request->user_id == 'all')
     {
      dispatch(new InitiateEmailNotificationJob($title,$body));
        /*$users = User::where('user_type','customer')->get();
          foreach($users as $user)
          {
            $mail = $this->sendMail($user,$body,$title);
          }*/
      } else {
        $user = User::where(['id'=>$request->user_id])->first();
        \Log::info("Email ------- ".$user->email);
        $this->sendMail($user,$body,$title);
      }
     	return redirect()->back()->with('success',trans('customers.email_sent'));
  }
}