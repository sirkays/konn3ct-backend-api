@component('mail::message')
    # Email Verification

    Your email verification code is {{$code}}.

    @slot('subcopy')
        You received this email because you attempt to register on {{ config('app.name') }}.
        Note: Do not share this code with anyone.
    @endslot

    Thanks,
    {{ config('app.name') }}
@endcomponent
