<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
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

    public function build()
    {
        $subject = config('mail.contact_us.subject');
        return $this->subject($subject)
                    ->view('emails.contact')
                    ->with('data', $this->data);
    }
}
