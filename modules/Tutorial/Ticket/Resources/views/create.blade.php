@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('tickets.index') }}" title="تیکت ها">تیکت ها</a></li>
    <li><a href="#" title="ارسال تیکت جدید">ارسال تیکت جدید</a></li>
@endsection
@section('content')
<p class="box__title">ایجاد تیکت جدید</p>
<div class="row no-gutters bg-white">
    <div class="col-12">
        <form action="{{ route('tickets.store') }}" class="padding-30" method="POST" enctype="multipart/form-data">
            @csrf
            <x-input type="text" name="title" placeholder="عنوان تیکت" />
            <x-text-area placeholder="متن تیکت" name="body" />

            <x-file name="attachment" placeholder="آپلود فایل پیوست" />
            <button type="submit" class="btn btn-webamooz_net">ایجاد تیکت</button>
        </form>
    </div>
</div>
@endsection

