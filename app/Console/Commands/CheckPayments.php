<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Jobs\GetOrderStatusFromPayu;
use App\Jobs\GetOrderStatusFromCCAvenue;
use Carbon\Carbon;

class CheckPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check_status_of_payment_for_under_process_orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will excecute every 2 hour which gets the status of payment for under process orders.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //->where('placed_at',)
        \Log::info("Under Process Order Cron Excecuted started");
        $allOrderId = array();
            
        $orders = Order::where('is_payment_attempt','1')
                        ->whereNotNull('order_id')
                        ->whereNotNull('user_id')
                        ->whereTime('placed_at', '<', Carbon::now()->subMinutes(40)->toTimeString())
                        ->chunk(500, function($orders) use ($allOrderId){
                            foreach ($orders as $key => $value) {
                                \Log::info("payment type : ".$value->payment_type);
                                \Log::info("Order ID : ".$value->id);
                                if(!in_array($value->id, $allOrderId)){
                                    array_push($allOrderId, $value->id);
                                    if($value->payment_type == 'payu'){
                                        dispatch(new GetOrderStatusFromPayu($value->id));
                                    } else {
                                        dispatch(new GetOrderStatusFromCCAvenue($value->id));
                                    }
                                }
                            }
                        });
        \Log::info("Under Process Order Cron Excecuted Ended");
    }
}