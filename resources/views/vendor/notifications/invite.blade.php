@component('mail::message')
{{-- Greeting --}}

# @lang('Hello,')


{{-- Intro Lines --}}
{{ "You have been invited by $ihost to attend $iroom scheduled as follows:" }}

{{ "Date: $idate" }}
<br />
{{ "Time: $itime" }}

{{ "" }}
Copy this link <a href='{{$ilink}}'>{{$ilink}}</a> to join or copy and paste in your preferred browser.


{{-- Action Button --}}
{{--@component('mail::button', ['url' => $ilink, 'color' => "green"])--}}
{{--{{ "Konn3ct Now" }}--}}
{{--@endcomponent--}}

{{-- Outro Lines --}}
{{--{{ '' }}--}}

{{-- Salutation --}}

@lang('Thank you'),<br />
{{ "..............." }}<br />
{{ "Visit https://konn3ct.com" }}<br />
{{ "...Amazing Virtual Experience" }}


{{-- Subcopy --}}
{{--@isset($actionText)--}}
@slot('subcopy')
@lang(
    "You received this mail because you were invited by a user on konn3ct<br />"
)@lang(
    "$iroom field needs to be added in the form."
)
@endslot
@endcomponent
