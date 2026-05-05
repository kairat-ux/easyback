<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContactReplyMail extends Mailable
{
    public function __construct(public string $name) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'We received your message');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.contact_reply');
    }
}
