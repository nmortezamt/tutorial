@extends('User::layouts.master')
@section('title', 'بازیابی رمز عبور')
@section('content')
    <div class="account">
        <form action="{{ route('password.reset.send') }}"class="form" method="get">
            <a class="account-logo" href="/">
                <img src="/home/img/weblogo.png" alt="">
            </a>
            @error('emailNotFound')
                <br>
                <div class="alert-danger">
                    <p class="text-danger">{{ $message }}</p>
                </div>
            @enderror
            <div class="form-content form-account">
                <input type="text" class="txt-l txt" name="email" placeholder="ایمیل">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <br>
                <button class="btn btn-recoverpass" type="submit">بازیابی</button>
            </div>

            <div class="form-footer">
                <a href="{{ route('login') }}">صفحه ورود</a>
            </div>
        </form>
    </div>
@endsection
