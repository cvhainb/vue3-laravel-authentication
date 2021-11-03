<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginCredentials extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The notify instance.
     *
     */
    public $notify;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($notify)
    {
        $this->notify = $notify;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcome to MCFeede')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view("emails.login_credentials");
    }
}
