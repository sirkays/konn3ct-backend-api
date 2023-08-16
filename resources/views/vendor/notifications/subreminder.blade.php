@component('mail::message')
    {{-- Greeting --}}

    # @lang('Hello ' . $jobi['user']->lastname.", ")

    {{-- Intro Lines --}}
    {{ 'This is a friendly reminder that your '.strtoupper($plan->name).' plan subscription will expire in '. $jobi['days'] . ' day(s).'}}


    {{-- Action Button --}}
    {{--    @component('mail::button', ['url' => route('payments'), 'color' => "green"])--}}
    {{--        {{ "Pay Now" }}--}}
    {{--    @endcomponent--}}

    {{ "If you have any questions, kindly visit our support site"}}

    <br>
    @lang('Thank you'),<br>
    {{ "The konn3ct Team" }}

    @slot('subcopy')

        <a href="https://www.facebook.com/konn3ctapp"> <img src="{{url('/')}}/assets/images/fb.png" alt="fb Logo"></a>
        <a href="https://www.youtube.com/channel/UCt8nu6M8VBWonkFOuUTuHUg"> <img src="{{url('/')}}/assets/images/yt.png"
                                                                                 alt="Youtube Logo"></a>
        <a href="https://twitter.com/konn3ctapp"> <img src="{{url('/')}}/assets/images/tw.png" alt="Twitter Logo"></a>
        <a href="{{url('/')}}"> <img src="{{url('/')}}/assets/images/lk.png" alt="Link Logo"></a>
        <a href="mailto:support@newwavesecosystem.odoo.com"> <img src="{{url('/')}}/assets/images/em.png"
                                                                  alt="Email Logo"></a>
    @endslot
@endcomponent
