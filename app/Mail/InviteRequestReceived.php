<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InviteRequestReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $name,
        public ?string $eventName = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We received your request — ' . ($this->eventName ?? config('app.name'))
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.invites.request-received',
            with: [
                'name' => $this->name,
                'eventName' => $this->eventName ?? config('app.name'),
                'appUrl' => config('app.url'),
            ],
        );
    }
}