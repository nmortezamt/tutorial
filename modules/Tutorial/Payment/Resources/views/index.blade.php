@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('payments.index') }}" title="تراکنش ها">تراکنش ها</a></li>
@endsection
@section('content')
    <div class="row no-gutters  margin-bottom-10">
        <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
            <p>کل فروش ۳۰ روز گذشته سایت </p>
            <p>{{ $last30DaysTotal }} تومان</p>
        </div>
        <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
            <p>درامد خالص ۳۰ روز گذشته سایت</p>
            <p>{{ number_format($last30DaysBenefit) }} تومان</p>
        </div>
        <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
            <p>کل فروش سایت</p>
            <p>{{ $totalSell }} تومان</p>
        </div>
        <div class="col-3 padding-20 border-radius-3 bg-white margin-bottom-10">
            <p> کل درآمد خالص سایت</p>
            <p>{{ $totalBenefit }} تومان</p>
        </div>
    </div>
    <div class="row no-gutters border-radius-3 font-size-13">
        <div class="col-12 bg-white padding-30 margin-bottom-20">
            <div id="container"></div>
        </div>

    </div>
    <div class="d-flex flex-space-between item-center flex-wrap padding-30 border-radius-3 bg-white">
            <p class="margin-bottom-15">همه تراکنش ها</p>
            <div class="t-header-search">
                <form action="
                ">
                    <div class="t-header-searchbox font-size-13">
                        <input type="text" class="text search-input__box font-size-13" placeholder="جستجوی تراکنش">
                        <div class="t-header-search-content ">
                            <input type="text" name="email" value="{{ request()->email }}" class="text"  placeholder="ایمیل">
                            <input type="text" name="amount" value="{{ request()->amount }}" class="text"  placeholder="مبلغ به تومان">
                            <input type="text" name="invoice_id" value="{{ request()->invoice_id }}" class="text" placeholder="شماره">
                            <input type="text" name="start_date" value="{{ request()->start_date }}" class="text" placeholder="از تاریخ : 1399/10/11">
                            <input type="text" name="end_date" value="{{ request()->end_date }}" class="text margin-bottom-20"  placeholder="تا تاریخ : 1399/10/12">
                            <button type="submit" class="btn btn-webamooz_net">جستجو</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <div class="row no-gutters">
        <div class="col-12 margin-left-10 margin-bottom-15 border-radius-3">
            <div class="table__box">
                <table class="table">
                    <thead role="rowgroup">
                        <tr role="row" class="title-row">
                            <th>شناسه</th>
                            <th>شناسه تراکنش</th>
                            <th>نام و نام خانوادگی</th>
                            <th>ایمیل پرداخت کننده</th>
                            <th>مبلغ (تومان)</th>
                            <th>درامد مدرس</th>
                            <th>درامد سایت</th>
                            <th>نام دوره</th>
                            <th>تاریخ و ساعت</th>
                            <th>وضعیت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr role="row">
                                <td><a href="">{{ $payment->id }}</a></td>
                                <td>{{ $payment->invoice_id }}</td>
                                <td>{{ $payment->buyer->name }}</td>
                                <td>{{ $payment->buyer->email }}</td>
                                <td>{{ $payment->amount }}</td>
                                <td>{{ $payment->seller_share }}</td>
                                <td>{{ $payment->site_share }}</td>
                                <td>{{ $payment->paymentable->title }}</td>
                                <td>{{ \Morilog\Jalali\Jalalian::fromCarbon($payment->created_at) }}</td>
                                <td class="{{ $payment->getStatusCssClass() }}">{{ __($payment->status) }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @include('Payment::chart')
@endsection

@section('css')
    <style>
        text{
            font-family: irs
        }
    </style>
@endsection
