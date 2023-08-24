@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('courses.index') }}" title="دوره ها">دوره ها</a></li>
    <li><a href="{{ route('courses.details',$course->id) }}" title="{{ $course->title }}">{{ $course->title }}</a></li>
    <li><a href="#" title="ایجاد درس">ایجاد درس</a></li>
@endsection
@section('content')
    <div class="row no-gutters">

        <div class="col-12 bg-white">
            <p class="box__title">ایجاد درس</p>
            <form action="{{ route('lessons.store',$course->id) }}" class="padding-30" method="POST" enctype="multipart/form-data">
                @csrf
                <x-input type="text" name="title" placeholder="عنوان درس" required />
                <x-input type="text" name="slug" class="text-left" placeholder="نام انگلیسی درس اختیاری" />
                <x-input type="number" name="time" class="text-left" placeholder="زمان درس" required />
                <x-input type="number" name="number" class="text-left" placeholder="ردیف  درس" />

                    @if (count($seasons))
                    <x-select name="season_id" required>
                        <option value="">انتخاب سرفصل درس</option>
                        @foreach ($seasons as $season)
                        <option value="{{ $season->id }}" {{ $season->id == old('season_id') ? 'selected' : '' }}>{{ $season->title }}</option>
                        @endforeach
                    </x-select>
                    @endif


                <p class="box__title">آیا این درس رایگان است ؟</p>
                <div class="w-50">
                    <div class="notificationGroup">
                        <input id="lesson-upload-field-1" name="is_free" value="0" type="radio" checked />
                        <label for="lesson-upload-field-1">خیر</label>
                    </div>
                    <div class="notificationGroup">
                        <input id="lesson-upload-field-2" name="is_free" value="1" type="radio" />
                        <label for="lesson-upload-field-2">بله</label>
                    </div>
                </div>

                <x-file name="lesson_file" placeholder="آپلود درس" required/>
                <x-text-area placeholder="توضیحات درس" name="body"/>
                <button class="btn btn-webamooz_net">آپلود درس</button>
            </form>

        </div>
    </div>
@endsection

