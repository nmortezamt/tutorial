@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('settlements.index') }}" title="تسویه حساب ها">تسویه حساب ها</a></li>
    <li><a href="#" title="ویرایش درخواست تسویه حساب">درخواست تسویه حساب جدید</a></li>
@endsection
@section('content')
    <form action="{{ route('settlements.update', $settlement->id) }}" method="POST" class="padding-30 bg-white font-size-14">
        @method('patch')
        @csrf
        <x-input type="text" name="from[name]" value="{{ is_array($settlement->from) && array_key_exists('name',$settlement->from) ? $settlement->from['name'] : ''}}" placeholder="نام صاحب کارت فرستنده" />
        <x-input type="text" name="from[card]" placeholder="شماره کارت فرستنده" value="{{ is_array($settlement->from) && array_key_exists('card',$settlement->from) ? $settlement->from['card'] : ''}}"/>

        <x-input type="text" name="to[name]" value="{{ is_array($settlement->to) && array_key_exists('name',$settlement->to) ? $settlement->to['name'] : ''}}" placeholder="نام صاحب کارت گیرنده" required />
        <x-input type="text" name="to[card]" placeholder="شماره کارت گیرنده" value="{{ is_array($settlement->to) && array_key_exists('card',$settlement->to) ? $settlement->to['card'] : ''}}"
            required />
            <x-select name="status">
            @foreach (\Tutorial\Payment\Models\Settlement::$statuses as $status)
                <option value="{{ $status }}" {{ $settlement->status == $status ? 'selected' : '' }}>{{ __($status) }}</option>
            @endforeach
            </x-select>
        <x-input type="text" value="{{ $settlement->amount }}"  name="amount" placeholder="مبلغ به تومان" readonly/>
        <div class="row no-gutters border-2 margin-bottom-15 text-center ">
            <div class="w-50 padding-20 w-50">باقی مانده حساب :‌</div>
            <div class="bg-fafafa padding-20 w-50"> {{ number_format($settlement->user->balance) }} تومان</div>
        </div>
        <button type="submit" class="btn btn-webamooz_net">درخواست تسویه</button>
    </form>
@endsection
