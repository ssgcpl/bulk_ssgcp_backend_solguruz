<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Template;

class OtpEmail extends Mailable
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
    public function __construct($user,$otp,$subject)
    {
        $this->user  = $user;
        // $this->title = $title;
        // $this->body  = $body;
        $this->otp   = $otp;
        $this->subject   = $subject;
       
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        $title = trans('notifications.customer_email_verification_greeting', ['user' => $this->user->first_name]);
        return $this->markdown('mail.customer_otp_email')
                    ->subject($this->subject);
    }
}
