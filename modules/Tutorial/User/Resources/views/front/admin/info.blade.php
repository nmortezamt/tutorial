@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('users.index') }}" title="کاربران">کاربران</a></li>
    <li><a href="" title="اطلاعات کامل حساب کاربری">اطلاعات کامل حساب کاربری</a></li>
@endsection
@section('content')
    <div class="row no-gutters">
        <div class="col-12">
            <p class="box__title"> اطلاعات کامل حساب کاربری :<strong>{{ $user->name }}</strong></p>
            <div class="margin-left-10 padding-20 w-100 bg-white margin-bottom-10 border-radius-3">
                <ul>
                    <li>ایمیل: <strong>{{ $user->email }}</strong></li>
                    <li>نام کاربری: <strong>{{ $user->username ?? '-' }}</strong></li>
                    <li>موبایل: <strong>{{ $user->mobile ?? '-'}}</strong></li>
                    <li>عنوان: <strong>{{ $user->headLine ?? '-' }}</strong></li>
                    <li>بیو: <strong>{{ $user->bio ?? '-' }}</strong></li>
                    <li>موجودی حساب: <strong>{{ number_format($user->balance) }}</strong></li>
                    <li>تاریخ تایید ایمیل: <strong>{{ \Tutorial\Common\createFromCarbon($user->email_verified_at) }}</strong></li>
                </ul>
            </div>
        </div>
        <div class="col-6 margin-left-10 margin-bottom-15 border-radius-3">
            <p class="box__title">دوره های خریدار شده</p>
            <div class="table__box">
                <table class="table">
                    <thead role="rowgroup">
                        <tr role="row" class="title-row">
                            <th>شناسه</th>
                            <th>دوره</th>
                            <th>مبلغ پرداخت شده</th>
                            <th>تاریخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($user->purchases as $purchase)
                            <tr role="row">
                                <td>{{ $purchase->id }}</td>
                                <td><a href="{{$purchase->path()}}">{{ $purchase->title }}</a></td>
                                <td>{{ number_format($purchase->payment->amount) }}</td>
                                <td>{{ \Tutorial\Common\createFromCarbon($purchase->created_at) }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-6 margin-bottom-15 border-radius-3">
            <p class="box__title">دوره های در حال تدریس</p>
            <div class="table__box">
                <table class="table">
                    <thead role="rowgroup">
                        <tr role="row" class="title-row">
                            <th>آیدی</th>
                            <th>دوره</th>
                            <th>وضعیت</th>
                            <th>تاریخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($user->courses as $course)
                            <tr role="row">
                                <td>{{ $course->id }}</td>
                                <td><a href="{{$course->path()}}">{{ $course->title }}</a></td>
                                <td>{{ __($course->status) }}</td>
                                <td>{{ \Tutorial\Common\createFromCarbon($course->created_at) }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-6 margin-left-10 margin-bottom-15 border-radius-3">
            <p class="box__title">پرداخت ها</p>
            <div class="table__box">
                <table class="table">
                    <thead role="rowgroup">
                        <tr role="row" class="title-row">
                            <th>آیدی پرداخت</th>
                            <th>محصول</th>
                            <th>مبلغ</th>
                            <th>وضعیت</th>
                            <th>تاریخ پرداخت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($user->payments as $payment)
                            <tr role="row">
                                <td>{{ $payment->id }}</td>
                                <td><a href="{{$payment->paymentable->path()}}">{{ $payment->paymentable->title }}</a></td>
                                <td>{{ number_format($payment->amount) }}</td>
                                <td>{{ __($payment->status) }}</td>
                                <td>{{ \Tutorial\Common\createFromCarbon($payment->created_at) }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-6 margin-bottom-15 border-radius-3">
            <p class="box__title">درخواست تسویه ها</p>
            <div class="table__box">
                <table class="table">
                    <thead role="rowgroup">
                        <tr role="row" class="title-row">
                            <th>آیدی پرداخت</th>
                            <th>محصول</th>
                            <th>مبلغ</th>
                            <th>وضعیت</th>
                            <th>تاریخ پرداخت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($user->payments as $payment)
                            <tr role="row">
                                <td>{{ $payment->id }}</td>
                                <td><a href="{{$payment->paymentable->path()}}">{{ $payment->paymentable->title }}</a></td>
                                <td>{{ number_format($payment->amount) }}</td>
                                <td>{{ __($payment->status) }}</td>
                                <td>{{ \Tutorial\Common\createFromCarbon($payment->created_at) }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

