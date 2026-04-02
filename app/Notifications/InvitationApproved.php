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
        $url = route('login');

        return (new MailMessage)
            ->subject('You\'re in — Your reunion access has been approved!')
            ->greeting('Hi ' . $this->request->full_name . '!')
            ->line('Great news — your request has been approved. You can now log in with the email and password you created.')
            ->action('Log In Now', $url)
            ->line('If you have any trouble logging in, use the "Forgot your password?" link on the login page.');
    }
}
