@component('mail::message')
# Introduction

Dear {{$name}}, please verify your email

@component('mail::button', ['url' => $link])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
