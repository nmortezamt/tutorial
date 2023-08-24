@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('users.index') }}" title="کاربر ها">کاربر ها</a></li>
    <li><a title="پروفایل کاربر">پروفایل کاربر</a></li>
@endsection
@section('content')
    <div class="row no-gutters margin-bottom-20">

        <div class="col-12 bg-white">
            <p class="box__title">پروفایل کاربر</p>
            <x-user-photo />
            <form action="{{ route('users.update.profile') }}" class="padding-30" method="POST">
                @csrf
                <x-input type="text" name="name" placeholder="نام" value="{{ $user->name }}" required />

                <x-input type="email" class="text-left" name="email" placeholder="ایمیل" value="{{ $user->email }}"
                    required />

                <x-input type="text" name="mobile" placeholder="شماره موبایل" value="{{ $user->mobile }}" />

                <x-input type="password" name="password" placeholder="رمز عبور" value="" />
                <p class="rules">رمز عبور باید حداقل 8 کاراکتر و ترکیبی از حروف بزرگ، حروف کوچک، اعداد و کاراکترهای
                    غیر الفبا مانند <strong>!@#$%^&*()</strong> باشد.</p>

                @can(\Tutorial\RolePermissions\Models\Permission::PERMISSION_MANAGE_TEACH)
                    <x-input type="text" name="card_number" placeholder="شماره کارت بانکی"
                        value="{{ $user->card_number }}" />

                    <x-input type="text" name="shaba" placeholder="شماره شبا بانکی" value="{{ $user->shaba }}" />

                    <x-input type="text" name="username" placeholder="نام کاربری و آدرس پروفایل"
                        value="{{ $user->username }}" />

                    <x-input type="text" name="telegram" placeholder="آیدی شما در تلگرام جهت  دریافت نوتیفیکیشن"
                        value="{{ $user->telegram }}" />

                    <p class="input-help text-left margin-bottom-12" dir="ltr">
                        <a href="{{ $user->profilePath() }}">{{ $user->profilePath() }}</a>
                    </p>

                    <x-input type="text" name="headLine" placeholder="عنوان" value="{{ $user->headLine }}" />


                    <br>
                    <x-text-area placeholder="درباره مدرس" name="bio" value="{{ $user->bio }}" />
                @endcan
                <button class="btn btn-webamooz_net">ذخیره تنظیمات</button>
            </form>
        </div>
    </div>

@stop
