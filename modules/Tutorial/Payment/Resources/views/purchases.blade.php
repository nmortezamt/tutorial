@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('payments.index') }}" title="تراکنش ها">تراکنش ها</a></li>
@endsection
@section('content')
<div class="table__box">
    <table class="table">
        <thead>
        <tr class="title-row">
            <th>عنوان دوره</th>
            <th>تاریخ پرداخت</th>
            <th>مبلغ پرداختی</th>
            <th>وضعیت پرداخت</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($payments as $payment)
        <tr>
            <td><a href="{{ $payment->paymentable->path() }}">{{ $payment->paymentable->title }}</a></td>
            <td>{{ \Tutorial\Common\createFromCarbon($payment->created_at)->format('Y-m-d') }}</td>
            <td>{{ number_format($payment->amount) }} تومان</td>
            <td class="{{ $payment->getStatusCssClass() }}">{{ __($payment->status) }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
