@component('mail::message')
{{-- Greeting --}}

# @lang('Hello ' . $data['pname'].", ")


{{-- Intro Lines --}}
{{ 'You have been invited by '.$data['host'].' to the event titled '.$data['event_name']. ', scheduled for '.$data['formatted_date'] . '. The event is scheduled to start at '. $data['formatted_time'].' '. $data['timezone']  }}


# @lang('Please don’t forget to join by clicking the link below:')

{{-- Action Button --}}
@component('mail::button', ['url' => $data['url'], 'color' => "green"])
    {{ "Join Event" }}
@endcomponent

@component('mail::button', ['url' => 'https://www.google.com/calendar/render?action=TEMPLATE&text='.$data['event_name'].'&details=Join+the+event+using+'.$data['url'].'&location='.$data['url'], 'color' => "green"])
    {{ "Add to Google Calender" }}
@endcomponent


# @lang('Find information about this event below')

{{'Host: '.$data['host']}}

{{'Date: '.$data['date']}}

{{'Time: '.$data['time']." ".$data['timezone']}}

{{'Meeting Link: '.$data['url']}}


{{ "Contact the Host: ".$data['hemail'] ." | ".$data['hphone']}}


{{ "Own a personalized room link | Own multiple rooms with a single account | Schedule meetings & events directly from your room | Host up to 250 participants for up to 24 hours straight" }}

{{ "Visit https://konn3ct.com" }}

{{-- Outro Lines --}}
{{--{{ '' }}--}}

{{-- Salutation --}}
<br><br>
@lang('Thank you'),<br>
{{ "The konn3ct Team" }}


{{-- Subcopy --}}
{{--@isset($actionText)--}}
@slot('subcopy')

    <a href="https://www.facebook.com/konn3ctapp"> <img src="{{url('/')}}/assets/images/fb.png" alt="fb Logo"></a>
    <a href="https://www.youtube.com/channel/UCt8nu6M8VBWonkFOuUTuHUg"> <img src="{{url('/')}}/assets/images/yt.png" alt="Youtube Logo"></a>
    <a href="https://twitter.com/konn3ctapp"> <img src="{{url('/')}}/assets/images/tw.png" alt="Twitter Logo"></a>
    <a href="{{url('/')}}"> <img src="{{url('/')}}/assets/images/lk.png" alt="Link Logo"></a>
    <a href="mailto:support@newwavesecosystem.odoo.com"> <img src="{{url('/')}}/assets/images/em.png" alt="Email Logo"></a>
@endslot
@endcomponent
