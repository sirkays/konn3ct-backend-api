@component('mail::message')
{{$content}}
@slot('subcopy')
@lang(
    "This email was sent from konn3ct application"
)
@endslot
@endcomponent
