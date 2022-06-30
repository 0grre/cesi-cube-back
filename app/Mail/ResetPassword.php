<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    private $token;
    private $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $token, string $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        $href = route('password.reset', ['token' => $this->token, 'email' => $this->email]);

        return $this->subject("Ressources Relationnelles - Nouveau mot de passe")
            ->view('emails.password', ["reset_link" => $href])
            ->to($this->email);
    }
}
