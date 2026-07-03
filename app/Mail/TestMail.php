<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Laravel SMTP Test',
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: '
                <h2>SMTP is Working 🎉</h2>
                <p>Your Laravel application can now send emails successfully.</p>
                <p>Next step: Forgot Password.</p>
            ',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}