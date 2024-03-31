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
use App\Models\Setting;
use App\Models\Order;
use App\Models\User;
use App\Models\Payment;
use App\Models\OrderItem;
use App\Models\Address;
use DB;

class GetOrderStatusFromCCAvenue implements ShouldQueue
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
    public function handle() {
        \Log::info("Job Get Order Status From CCAvenue started: ".$this->order_id);
        $cart = Order::where('id',$this->order_id)->where('is_payment_attempt','1')->where('payment_status','pending')->where('payment_type','ccavenue')->whereNotNull('order_id')->latest()->first();
        if(isset($cart) && $cart != ""){
        \Log::info("ORDER IDDDD : ".$cart->order_id);
            $user = User::find($cart->user_id);
            $starttimestamp = strtotime($cart->placed_at);
            $endtimestamp = strtotime(date("Y-m-d H:i:s"));
            $difference = round(abs($endtimestamp - $starttimestamp)/60);
            \Log::info("difference".$difference);
            if($difference > 40){
                $this->markOrderStatusAsFailed($cart->id,$user,null,0);
            }
        } else {
            \Log::info("No data found for : ".$this->order_id);
        }
    }
}