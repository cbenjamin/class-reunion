@component('mail::message')
# New Invite Request

Someone just submitted a request to join **{{ $eventName }}**.

| | |
|---|---|
| **Name** | {{ $request->full_name }} |
| **Email** | {{ $request->email }} |
@if($request->grad_year)
| **Grad Year** | {{ $request->grad_year }} |
@endif
@if(!empty($request->answers['maiden_name']))
| **Maiden Name** | {{ $request->answers['maiden_name'] }} |
@endif
@if(!empty($request->answers['interest']))
| **Interest** | {{ $request->answers['interest'] }} |
@endif
| **Submitted** | {{ $request->created_at->format('M j, Y g:i A') }} |

@component('mail::button', ['url' => $reviewUrl, 'color' => 'red'])
Review in Admin Panel
@endcomponent

Thanks,
{{ $eventName }}
@endcomponent
