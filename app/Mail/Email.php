<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Email extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        if ($this->data['template'] == 'forgotPassword') {
            return $this->from('email_address@gmail.com', 'NAME OF THE APP') // Set the sender's email and name
                ->subject($this->data['subject']) // Set the email subject
                ->view('emails.forgotPassword') // Set the email view
                ->with('data', $this->data); // Pass data to the view
        }

        // just add another condition for other templates
    }
}
