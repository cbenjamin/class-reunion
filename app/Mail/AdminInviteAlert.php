<?php

namespace App\Mail;

use App\Models\InvitationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminInviteAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public InvitationRequest $request,
        public string $eventName,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New invite request — ' . $this->request->full_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.admin.invite-alert',
            with: [
                'request'    => $this->request,
                'eventName'  => $this->eventName,
                'reviewUrl'  => route('admin.invites.index'),
            ],
        );
    }
}
