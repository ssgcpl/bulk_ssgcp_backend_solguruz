<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendVerificationEmail extends Notification
{
    use Queueable;

    public $verification_code;
    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($verification_code, $user)
    {
        $this->verification_code = $verification_code;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
        // ->from('test@pempered.com', config('adminlte.name'))
        ->subject(trans('notifications.customer_email_verification_subject'))
        ->markdown('mail.verify_email', ['verification_code' => $this->verification_code,'customer_name' => $this->user->name]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
