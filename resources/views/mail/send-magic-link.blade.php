@component('mail::message')
# Magic Link

The body of your message.

@component('mail::button', ['url' => $link])
login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
