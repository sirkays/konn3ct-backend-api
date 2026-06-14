@component('mail::message')

# Hello,

{{ "You have been invited by $host to attend $title scheduled as follows:" }}


### Meeting Details
- **Date**: {{ $date }}
- **Time**: {{ $time }} – {{ $endTime }} ({{ $timezone }})
@if(!empty($recurrence))
- **Recurrence**: {{ $recurrence }}
@endif
- **Room**: {{ $roomName }}
@if($accessCode != 'No Access Code')
- **Access Code**: {{ $accessCode }}
@endif
- **Host**: {{ $host }}

@if(!empty($additional))
### Note:
{{ $additional }}
@endif

@component('mail::button', ['url' => $link])
Join Meeting
@endcomponent


---

📱 For a better experience on mobile, download the Konn3ct app:
[Download on Google Play](https://bit.ly/konn3ctapp)

### 🎥 How-To Videos
- [How to Join Meeting Room](https://www.youtube.com/watch?v=mLoHB9cltWs)
- [How to Manage Meeting Room](https://www.youtube.com/watch?v=eCblbRoL4gs)
- [Watch More](https://www.youtube.com/channel/UCt8nu6M8VBWonkFOuUTuHUg)

@slot('subcopy')
You received this email because you were invited to a Konn3ct meeting.
@endslot
@endcomponent
