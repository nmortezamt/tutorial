<x-mail::message>
# کد بازیابی رمز عبور حساب شما در {{ env('APP_NAME') }}

این ایمیل جهت بازیابی رمز عبور شما در سایت {{ env('APP_NAME') }} برای شما ارسال شده است.
<x-mail::panel>
کد فعال سازی شما: {{ $code }}
</x-mail::panel>

باتشکر,<br>
{{ config('app.name') }}
</x-mail::message>
