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
use Illuminate\Queue\Middleware\WithoutOverlapping;

class SendBulkNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, CommonHelper;

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
    public function __construct(User $user,$title,$body,$slug,$req_url)
    {
        $this->title = $title;
        $this->body = $body;
        $this->slug = $slug;
        $this->req_url = $req_url;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
        $this->sendNotification($this->user,$this->title,$this->body,$this->slug,null,$this->req_url,'1');
        /*foreach (User::where('user_type','customer')->where('status','active')->offset($this->x)->limit(500)->orderBy('id','desc')->cursor() as $user) {
        }*/
        /*$users = User::where('user_type','customer')
                        ->where('status','active')
                        ->get();
        foreach ($users as $key => $user) {
        }*/
    }
    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        // Send user notification of failure, etc...
    }
    public function middleware(){
        return [new WithoutOverlapping($this->user->id)];
    }
}
