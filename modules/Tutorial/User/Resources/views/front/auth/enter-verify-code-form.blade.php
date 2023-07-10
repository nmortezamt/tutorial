@extends('User::layouts.master')
@section('title','کد بازیابی رمز عیور')
@section('content')
    <div class="account act">
        <form action="{{ route('password.check.verify.code') }}" class="form" method="POST">
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
                <p class="activation-code-title">کد فرستاده شده به ایمیل <span>{{ $user->email }}</span>
                    را وارد کنید . ممکن است ایمیل به پوشه spam فرستاده شده باشد
                </p>
            </div>
            <div class="form-content form-content1">
                <input type="hidden" name="email" value="{{ $user->email }}">
                <input name="verify_code" class="activation-code-input" placeholder="فعال سازی" required>
                @error('verify_code')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
                <br>
                <button class="btn i-t" type="submit">تایید</button>
                <a href="#" onclick="event.preventDefault();
                document.getElementById('resend-code').submit()">ارسال مجدد کد فعالسازی</a>

            </div>
            <div class="form-footer">
                <a href="{{ route('register') }}">صفحه ثبت نام</a>
            </div>
        </form>
        <form id="resend-code" action="{{ route('password.resend',$user) }}" method="post">
        @csrf</form>
    </div>
@endsection
@section('js')
    <script src="/home/js/jquery-3.4.1.min.js"></script>
    <script src="/home/js/activation-code.js"></script>
@endsection
