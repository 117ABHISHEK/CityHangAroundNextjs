<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user; // Pass user data to the email

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Welcome to Our App')
                    ->view('emails.welcome') // Use the Blade template
                    ->with(['user' => $this->user]) // Pass user data to the view
                    ->cc('cityhangaround@gmail.com'); // Add CC recipient
    }
}
