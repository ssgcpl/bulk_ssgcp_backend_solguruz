<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Template;

class SendOrderManuallyNotifyEmailCustomer extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    // public $title;
    // public $body;
    public $otp;
    public $subject;
    public $customer_note;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$order_note,$subject,$customer_note)
    {
        $this->user  = $user;
        $this->order_note   = $order_note;
        $this->subject   = $subject;       
        $this->customer_note   = $customer_note;       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        return $this->markdown('mail.order_manually_notify_email_customer')
                    ->with(['order_note'=> $this->order_note, 'user'=>$this->user])
                    ->subject($this->subject);
    }
}
