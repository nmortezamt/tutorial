@extends('User::layouts.master')
@section('title', 'تغییر رمز عبور')
@section('content')
    <div class="account">
        <form action="{{ route('password.store') }}" class="form" method="POST">
            @csrf
            <a class="account-logo" href="index.html">
                <img src="/home/img/weblogo.png" alt="">
            </a>
            <div class="form-content form-account">

                <input id="password" type="password" class="txt txt-l @error('password') is-invalid @enderror"
                    placeholder="رمز عبور *" name="password" required autocomplete="new-password">

                <input id="password-confirm" type="password" class="txt txt-l @error('password') is-invalid @enderror"
                    placeholder="تایید رمز عبور *" name="password_confirmation" required autocomplete="new-password">

                <span class="rules">رمز عبور باید حداقل ۶ کاراکتر و ترکیبی از حروف بزرگ، حروف کوچک، اعداد و کاراکترهای غیر
                    الفبا مانند !@#$%^&*() باشد.</span>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <br>
                <button class="btn continue-btn">به روزرسانی</button>

            </div>
            <div class="form-footer">
                <a href="{{ route('login') }}">صفحه ورود</a>
            </div>
        </form>
    </div>
@endsection
