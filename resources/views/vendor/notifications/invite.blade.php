@component('mail::message')
{{-- Greeting --}}

# @lang('Hello,')


{{-- Intro Lines --}}
{{ "You have been invited by $ihost to attend $imtitle scheduled as follows:" }}

{{ "Meeting Room Name: $iroom" }}
<br />
{{ "Date: $idate" }}
<br />
{{ "Time: $itime" }}

{{ "" }}
Click this link <a href='{{$ilink}}'>{{$ilink}}</a> to join or copy and paste in your preferred browser.


{{-- Action Button --}}
{{--@component('mail::button', ['url' => $ilink, 'color' => "green"])--}}
{{--{{ "Konn3ct Now" }}--}}
{{--@endcomponent--}}

{{-- Outro Lines --}}
{{--{{ '' }}--}}

{{-- Salutation --}}

@lang('Thank you').<br />
{{ "..............." }}<br />
<span class="text-center">Visit https://konn3ct.com</span><br />
<span class="text-center">...Amazing Virtual Experience</span><br />



{{-- Subcopy --}}
{{--@isset($actionText)--}}
@slot('subcopy')
@lang(
    "You received this mail because you were invited by a user on konn3ct."
)
@endslot
@endcomponent
