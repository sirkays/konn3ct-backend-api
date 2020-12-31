@component('mail::message')
{{-- Greeting --}}

# @lang('Hello!')


{{-- Intro Lines --}}
{{ 'We are excited to have you join the growing number of professionals and desision influencers konn3ct-ing with the rest of the world without missing a thing.' }}

{{ 'To get you started on the journey to an improved virtual live, take the next 3 minutes to do the following' }}

{{-- Action Button --}}
@component('mail::button', ['url' => url('/login'), 'color' => "green"])
{{ "Sign In" }}
@endcomponent

@component('mail::button', ['url' => url('/room'), 'color' => "green"])
{{ "Invite friends & colleagues to join your meeting room" }}
@endcomponent

@component('mail::button', ['url' => url('/room'), 'color' => "green"])
{{ 'Click "konn3ct now" to start a meeting' }}
@endcomponent

@component('mail::button', ['url' => url('/room'), 'color' => "green"])
{{ "Share Webcam, Use Shared Notes, Share a Youtube video, and more" }}
@endcomponent

@component('mail::button', ['url' => url('/room'), 'color' => "green"])
{{ "Go to settings to end meeting" }}
@endcomponent

{{-- Outro Lines --}}
{{--{{ '' }}--}}

{{"Visit https://konn3ct.com"}}
{{-- Salutation --}}

@lang('Thank you'),<br>
{{ "The konn3ct Team" }}


{{-- Subcopy --}}
{{--@isset($actionText)--}}
@slot('subcopy')
@lang(
    "Own a personalized room link | Own multiple rooms with a single account | Schedule meetings & events directly from your room | Host up to 250 participants for up to 24 hours straight"
)
@endslot
@endcomponent
