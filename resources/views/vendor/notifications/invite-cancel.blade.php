@component('mail::message')

# Hello,

## Meeting Cancelled: {{ $title }}

The meeting scheduled by {{ $host }} has been cancelled.

### Cancelled Meeting Details
- **Date**: {{ $date }}
- **Time**: {{ $time }} – {{ $endTime }} ({{ $timezone }})
- **Room**: {{ $roomName }}

Thank you,
Visit https://konn3ct.com
...Amazing Virtual Experience

@slot('subcopy')
You received this email because you were invited to a Konn3ct meeting that has now been cancelled.
@endslot
@endcomponent
