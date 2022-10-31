@component('mail::message')
{{-- Greeting --}}

# @lang('Hello,')


{{-- Intro Lines --}}
{{ "You have been invited by $ihost to attend $imtitle scheduled as follows:" }}

{{ "Meeting Room Name: $iroom" }}
<br />
{{ "Access Code: $iaccesscode" }}
<br />
{{ "Date: $idate" }}
<br />
{{ "Time: $itime" }} {{$itimezone}}

{{ "$iadditional" }}

{{ "" }}
Click this link <a href='{{$ilink}}'>{{$ilink}}</a> to join or copy and paste in your preferred browser.


For a better user experience for Mobile Users. Kindly download konn3ct Mobile App from Google Playstore https://bit.ly/konn3ctapp
You can also see this short video for your education athttps://www.youtube.com/watch?v=iCVI_rYrbMA

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

<br/>
👇👇👇
<br/>
<span style="color: #61be72">
    Watch these <strong>"HOW TO VIDEOS"</strong> to learn more<br/>
-How to Join Meeting Room <a href="https://www.youtube.com/watch?v=mLoHB9cltWs">Watch Now</a><br/>
-How to Manage Meeting Room <a href="https://www.youtube.com/watch?v=eCblbRoL4gs">Watch Now</a><br/>
<a href="https://www.youtube.com/channel/UCt8nu6M8VBWonkFOuUTuHUg">Watch More</a><br/>
</span>



{{-- Subcopy --}}
{{--@isset($actionText)--}}
@slot('subcopy')
@lang(
    "You received this mail because you were invited by a user on konn3ct."
)
@endslot
@endcomponent
