<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\Helpers\CommonHelper;
use App\Models\Helpers\PaymentHelper;
use App\Mail\SendOrderPlacedEmailCustomer;
use App\Mail\SendOrderFailedEmailAdmin;
use App\Models\Order;
use App\Models\User;
use App\Models\Payment;
use App\Models\OrderItem;
use App\Models\Setting;
use App\Models\Address;
use DB;

class GetOrderStatusFromPayu implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use CommonHelper;
    public $timeout = 3600;
    protected $order_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order_id)
    {
         $this->order_id = $order_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      \Log::info("Job Get Order Status From PAYU for started: ".$this->order_id);
      $cart = Order::where('id',$this->order_id)->where('is_payment_attempt','1')->where('payment_type','payu')->where('payment_status','pending')->whereNotNull('order_id')->latest()->first();
      
      if($cart)
      {
          $data = $this->verifyPaymentForPayu($cart);
          $user = User::find($cart->user_id);
          if($data){
              if($data['status'] == 'success'){
                 \Log::info("Cart Id in success status ".$cart->id);
                $this->markOrderStatusAsSuccess($cart->id,$user,$data,0);
              }else if($data['status'] == 'failure'){
                \Log::info("Cart Id in failure status ".$cart->id);
                $this->markOrderStatusAsFailed($cart->id,$user,$data,0);
                DB::commit();    
              }else if($data['status'] == 'pending'){
                /*if($data['unmappedstatus'] == 'intiated'){
                }else if($data['unmappedstatus'] == 'in progress'){*/
                  // Call job after 5 min delay 
                  $job = (new GetOrderStatusFromPayu($cart->id))->delay(300);
                  dispatch($job); 
                \Log::info("Status ".$data['status']." order Id ".$cart->id);
              }else {
                $this->markOrderStatusAsFailed($cart->id,$user,null,0);
                DB::commit();   
              }
             }else {
              \Log::info("Response not found so mark transaction as bounced for cart ID: ".$cart->id);
              // Call job after 5 min delay 
              $starttimestamp = strtotime($cart->placed_at);
              $endtimestamp = strtotime(date("Y-m-d H:i:s"));
              $difference = round(abs($endtimestamp - $starttimestamp)/60);
              \Log::info("difference".$difference);
              if($difference > 40){
                $this->markOrderStatusAsFailed($cart->id,$user,null,0);
              }else {
                $job = (new GetOrderStatusFromPayu($cart->id))->delay(300);
                dispatch($job);   
              }
              
             }
      }else {
        \Log::info('No order found');
      }
    }

 
}
