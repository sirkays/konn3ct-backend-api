@component('mail::message')

# Hello,

## Meeting Updated: {{ $title }}

The meeting scheduled by {{ $host }} has been updated.

### Updated Meeting Details
- **Date**: {{ $date }}
- **Time**: {{ $time }} – {{ $endTime }} ({{ $timezone }})
@if(!empty($recurrence))
- **Recurrence**: {{ $recurrence }}
@endif
- **Room**: {{ $roomName }}
@if($accessCode != 'No Access Code')
- **Access Code**: {{ $accessCode }}
@endif

@if(!empty($additional))
### Description
{{ $additional }}
@endif

@component('mail::button', ['url' => $link])
Join Meeting
@endcomponent

Or copy and paste this link in your browser:
{{ $link }}

---

📱 For a better experience on mobile, download the Konn3ct app:
[Download on Google Play](https://bit.ly/konn3ctapp)

Thank you,
Visit https://konn3ct.com
...Amazing Virtual Experience

@slot('subcopy')
You received this email because you were invited to a Konn3ct meeting that has been updated.
@endslot
@endcomponent
