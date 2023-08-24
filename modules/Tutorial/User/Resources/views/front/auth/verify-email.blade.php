@extends('User::layouts.master')
@section('title','کد فعالسازی')
@section('content')
    <div class="account act">
        <form action="{{ route('verification.verify') }}" class="form" method="POST">
            @csrf
            <a class="account-logo" href="index.html">
                <img src="/home/img/weblogo.png" alt="">
            </a>
            @if(session()->has('status'))
            <br>
            <div class="alert-info">
                <p class="text-info">{{ session()->get('status') }}</p>
            </div>
            @endif
            <div class="card-header">
                <p class="activation-code-title">کد فرستاده شده به ایمیل <span>{{ auth()->user()->email }}</span>
                    را وارد کنید .  ممکن است ایمیل به پوشه spam فرستاده شده باشد.
                    ایمیلتان را اشتباه وارد کرده اید؟ <a href="{{ route('users.profile') }}">برای ویرایش ایمیل کلیک کنید</a>
                </p>
            </div>
            <div class="form-content form-content1">
                <input name="verify_code" class="activation-code-input" placeholder="فعال سازی" required>
                @error('verify_code')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
                <br>
                <button class="btn i-t">تایید</button>
                <a href="#" onclick="event.preventDefault();
                document.getElementById('resend-code').submit()">ارسال مجدد کد فعالسازی</a>

            </div>
            <div class="form-footer">
                <a href="{{ route('register') }}">صفحه ثبت نام</a>
            </div>
        </form>
        <form id="resend-code" action="{{ route('verification.send') }}" method="post">
        @csrf</form>
    </div>
@endsection

@section('js')
    <script src="/home/js/jquery-3.4.1.min.js"></script>
    <script src="/home/js/activation-code.js"></script>
@endsection
