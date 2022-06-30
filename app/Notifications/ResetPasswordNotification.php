<?php

namespace App\Notifications;

use App\Mail\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    private $token;
    private $email;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
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
     * @param $notifiable
     * @return ResetPassword
     */
    public function toMail($notifiable): ResetPassword
    {
        return new ResetPassword($this->token, $this->email);
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
