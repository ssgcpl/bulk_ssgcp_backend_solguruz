<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Template;

class SendOrderPlacedEmailCustomer extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    // public $title;
    // public $body;
    public $otp;
    public $subject;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$order,$subject)
    {
        $this->user  = $user;
        $this->order   = $order;
        $this->subject   = $subject;       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        return $this->markdown('mail.order_placed_email_customer')
                    ->with(['order'=> $this->order, 'user'=>$this->user])
                    ->subject($this->subject);
    }
}
