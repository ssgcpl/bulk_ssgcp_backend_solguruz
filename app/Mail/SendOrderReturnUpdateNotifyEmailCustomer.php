<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOrderReturnUpdateNotifyEmailCustomer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$subject,$data)
    {
          $this->user  = $user;
          $this->subject = $subject;
          $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         return $this->markdown('mail.order_return_update_notify_email_customer')
                    ->with(['data'=> $this->data,'user'=>$this->user])
                    ->subject($this->subject);
    }
}
