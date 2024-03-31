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

class InitiateNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,CommonHelper;

    protected $user;
    protected $title;
    protected $body;
    protected $slug;
    protected $req_url;
    public $timeout = 3600;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($title,$body,$slug,$req_url)
    {
        $this->title = $title;
        $this->body = $body;
        $this->slug = $slug;
        $this->req_url = $req_url;
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
        $slug = $this->slug;
        $req_url = $this->req_url;

        $allUserId = array();

        $data = User::whereIn('user_type',['retailer','dealer'])
                    ->where('status','active')
                    ->chunk(500, function($users) use ($title,$body,$slug,$req_url,$allUserId) {
                        foreach ($users as $user) {
                            if(!in_array($user->id, $allUserId)){
                                array_push($allUserId, $user->id);
                                dispatch(new SendBulkNotification($user,$this->title,$this->body,$this->slug,$this->req_url));
                            }
                        }
                    });
    }
}