<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCustomerMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $user;
    public $title;
    public $body;

    public function __construct($user,$body,$title)
    {
        $this->user = $user;
       $this->title = $title;
       $this->body = $body; 

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       // echo $this->title.$this->body; die;
        //$title = trans('notifications.customer_email_greeting', ['user' => $this->data->first_name]);
        return $this->markdown('mail.send_customer_mail')
                    ->subject($this->title)->with(['data'=>$this->body]);
    }
}
