@component('mail::message')
# You've been invited!

You have been invited to join a team on Konn3ct.

Click the button below to activate your team membership.

@component('mail::button', ['url' => $activationLink])
Activate Team Membership
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
