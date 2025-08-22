<?php
namespace App\Notifications;

use App\Models\InvitationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvitationApproved extends Notification
{
    use Queueable;

    public function __construct(public InvitationRequest $request) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('invite.set-password', $this->request->approval_token);

        return (new MailMessage)
            ->subject('You’re invited — Finish your reunion signup')
            ->greeting('Hi '.$this->request->full_name.'!')
            ->line('Your request has been approved. Set your password to finish signup.')
            ->action('Set Password', $url)
            ->line('If the button doesn’t work, paste this into your browser:')
            ->line($url);
    }
}