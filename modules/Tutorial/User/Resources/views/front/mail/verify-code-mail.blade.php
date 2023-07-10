<x-mail::message>
# کد فعال سازی حساب شما در {{ env('APP_NAME') }}

این ایمیل به دلیل ثبت نام شما در سایت {{ env('APP_NAME') }} برای شما ارسال شده است.
<x-mail::panel>
کد فعال سازی شما: {{ $code }}
</x-mail::panel>

باتشکر,<br>
{{ config('app.name') }}
</x-mail::message>
