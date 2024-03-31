<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Helpers\CommonHelper;

class InitiateEmailNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, CommonHelper;

    protected $title;
    protected $body;
    public $timeout = 3600;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($title,$body)
    {
        $this->title = $title;
        $this->body = $body;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $title = $this->title;
        $body = $this->body;

        $allUserId = array();

        $data = User::whereIn('user_type',['retailer','dealer'])
                    ->where('status','active')
                    ->chunk(5000, function($users) use ($title,$body,$allUserId) {
                        foreach ($users as $user) {
                            if(!in_array($user->id, $allUserId)){
                                array_push($allUserId, $user->id);
                                dispatch(new SendBulkEmailNotification($user,$this->title,$this->body));
                            }
                        }
                    });                   
    }
}