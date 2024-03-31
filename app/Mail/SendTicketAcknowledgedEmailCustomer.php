<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Template;

class SendTicketAcknowledgedEmailCustomer extends Mailable
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
    public function __construct($user,$ticket,$subject)
    {
        $this->user  = $user;
        $this->ticket   = $ticket;
        $this->subject   = $subject;       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        return $this->markdown('mail.ticket_acknowledged_email_customer')
                    ->with(['ticket'=> $this->ticket, 'user'=>$this->user])
                    ->subject($this->subject);
    }
}
