@component('mail::message')
# Thanks, {{ $name }}!

We received your request for an invitation to **{{ $eventName }}**.

**What happens next?**
- An organizer will review your request.
- If approved, you’ll get an email with a secure link to set your password and finish registration.

If you didn’t submit this request, you can ignore this email.

@component('mail::button', ['url' => $appUrl])
Visit {{ $eventName }}
@endcomponent

Thanks,<br>
{{ config('app.name') }} Team
@endcomponent