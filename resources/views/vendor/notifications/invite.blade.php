@component('mail::message')

# @lang('Hello,')

<table style="width: 100%; margin: 24px 0;">
    <tr>
        <td style="padding: 0;">
            <h2 style="color: #1a73e8; font-size: 22px; margin: 0 0 16px 0; font-weight: 600;">{{ $title }}</h2>

            <table style="width: 100%; border: 1px solid #e0e0e0; border-radius: 8px; background-color: #f8fafc; padding: 24px;">
                <tr>
                    <td style="padding: 0 0 16px 0;">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 40px; vertical-align: top; padding-right: 16px;">
                                    <svg style="width: 24px; height: 24px;" viewBox="0 0 24 24" fill="#1a73e8">
                                        <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
                                    </svg>
                                </td>
                                <td style="vertical-align: top;">
                                    <div style="font-size: 16px; font-weight: 500; color: #202124; margin: 0 0 4px 0;">{{ $date }}</div>
                                    <div style="color: #5f6368; font-size: 14px;">
                                        {{ $time }} – {{ $endTime }} ({{ $timezone }})
                                        @if(!empty($recurrence))
                                            <br/>
                                            <span style="color: #1a73e8;">{{ $recurrence }}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0;">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 40px; vertical-align: top; padding-right: 16px;">
                                    <svg style="width: 24px; height: 24px;" viewBox="0 0 24 24" fill="#1a73e8">
                                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 14 12 14z"/>
                                    </svg>
                                </td>
                                <td style="vertical-align: top;">
                                    <div style="font-size: 16px; font-weight: 500; color: #202124; margin: 0 0 4px 0;">{{ $roomName }}</div>
                                    @if($accessCode != 'No Access Code')
                                        <div style="color: #5f6368; font-size: 14px;">Access Code: {{ $accessCode }}</div>
                                    @endif
                                    <div style="font-size: 16px; font-weight: 500; color: #202124; margin-top: 8px;">Host: {{ $host }}</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

@if(!empty($additional))
    <table style="width: 100%; margin: 24px 0;">
        <tr>
            <td style="padding: 0;">
                <h3 style="font-size: 16px; font-weight: 500; margin: 0 0 8px 0; color: #202124;">Description</h3>
                <p style="color: #5f6368; line-height: 1.5; margin: 0;">{{ $additional }}</p>
            </td>
        </tr>
    </table>
@endif

@component('mail::button', ['url' => $link, 'color' => 'primary'])
Join Meeting
@endcomponent

<table style="width: 100%; margin: 24px 0;">
    <tr>
        <td style="padding: 0;">
            <p style="color: #5f6368; font-size: 14px; margin: 0; line-height: 1.5;">
                Or copy and paste this link in your browser:
                <br/>
                <a href="{{ $link }}" style="color: #1a73e8; text-decoration: underline; word-break: break-all;">{{ $link }}</a>
            </p>
        </td>
    </tr>
</table>

<table style="width: 100%; border-top: 1px solid #e0e0e0; border-bottom: 1px solid #e0e0e0; padding: 24px 0; margin: 32px 0;">
    <tr>
        <td style="padding: 0; text-align: center;">
            <p style="color: #5f6368; font-size: 14px; margin: 0 0 16px 0;">
                📱 For a better experience on mobile, download the Konn3ct app:
            </p>
            <a href="https://bit.ly/konn3ctapp" style="display: inline-block; padding: 10px 24px; background-color: #f8fafc; border: 1px solid #e0e0e0; border-radius: 4px; text-decoration: none; color: #1a73e8; font-weight: 500;">
                Download on Google Play
            </a>
        </td>
    </tr>
</table>

<table style="width: 100%; background-color: #f0fdf4; border-radius: 8px; padding: 16px; margin: 24px 0;">
    <tr>
        <td style="padding: 0;">
            <p style="font-size: 16px; font-weight: 500; color: #166534; margin: 0 0 12px 0;">
                🎥 How-To Videos
            </p>
            <ul style="list-style: none; padding-left: 0; margin: 0;">
                <li style="margin: 8px 0;">
                    <a href="https://www.youtube.com/watch?v=mLoHB9cltWs" style="color: #15803d; text-decoration: underline;">How to Join Meeting Room</a>
                </li>
                <li style="margin: 8px 0;">
                    <a href="https://www.youtube.com/watch?v=eCblbRoL4gs" style="color: #15803d; text-decoration: underline;">How to Manage Meeting Room</a>
                </li>
                <li style="margin: 8px 0;">
                    <a href="https://www.youtube.com/channel/UCt8nu6M8VBWonkFOuUTuHUg" style="color: #15803d; text-decoration: underline;">Watch More</a>
                </li>
            </ul>
        </td>
    </tr>
</table>

<table style="width: 100%; margin-top: 32px;">
    <tr>
        <td style="padding: 0; text-align: center;">
            <p style="color: #5f6368; font-size: 14px; margin: 8px 0; line-height: 1.5;">
                @lang('Thank you'),
                <br/>
                <span style="font-weight: 500;">Visit https://konn3ct.com</span>
                <br/>
                ...Amazing Virtual Experience
            </p>
        </td>
    </tr>
</table>

@slot('subcopy')
@lang(
    "You received this email because you were invited to a Konn3ct meeting."
)
@endslot
@endcomponent
