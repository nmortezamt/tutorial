@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('slider.index') }}" title="اسلایدر ها">اسلایدر ها</a></li>

    <li><a href="#" title="ویرایش اسلایدر">ویرایش اسلایدر</a></li>
@endsection
@section('content')
    <div class="row no-gutters">

        <div class="col-6 bg-white">
            <p class="box__title">به روزرسانی اسلایدر</p>
            <form action="{{ route('slider.update', $slide->id) }}" method="post" class="padding-30"
                enctype="multipart/form-data">
                @csrf
                @method('patch')
                <x-file name="image" placeholder="عکس اسلایدر" :value="$slide->media" />

                <x-input name="priority" type="number" value="{{ $slide->priority }}" placeholder="اولویت" />
                <x-input name="link" type="text" value="{{ $slide->link }}" placeholder="لینک" />

                <p class="box__title margin-bottom-15">وضعیت نمایش</p>
                <x-select name="status">
                    <option value="1" {{ $slide->status == 1 ? 'selected' : '' }}>فعال</option>
                    <option value="0" {{ $slide->status == 0 ? 'selected' : '' }}>غیر فعال</option>
                </x-select>

                <button class="btn btn-webamooz_net">به روز رسانی کردن</button>
            </form>
        </div>
    </div>
@stop
