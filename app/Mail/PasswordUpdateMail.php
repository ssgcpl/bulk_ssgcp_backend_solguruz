<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Template;

class PasswordUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$password)
    {
        $this->user     = $user;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        $title = "Hello ".$this->user->first_name;
        return $this->markdown('mail.customer_password_email')
                    ->subject("Password Update");
    }
}
