@component('mail::message')
# reset password link
@component('mail::button', ['url' => $link])
Reset Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
