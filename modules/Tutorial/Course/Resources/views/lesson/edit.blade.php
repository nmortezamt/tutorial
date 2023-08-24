@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('courses.index') }}" title="دوره ها">دوره ها</a></li>
    <li><a href="{{ route('courses.details',$course->id) }}" title="{{ $course->title }}">{{ $course->title }}</a></li>
    <li><a href="#" title="ویرایش درس">ویرایش درس</a></li>@endsection
@section('content')
    <div class="row no-gutters">

        <div class="col-12 bg-white">
            <p class="box__title">ویرایش درس</p>
            <form action="{{ route('lessons.update',[$course->id,$lesson->id]) }}" class="padding-30" method="POST" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <x-input type="text" name="title" value="{{ $lesson->title }}" placeholder="عنوان درس" required />
                <x-input type="text" name="slug" value="{{ $lesson->slug }}" class="text-left" placeholder="نام انگلیسی درس اختیاری" />

                <x-input type="number" name="time" value="{{ $lesson->time }}" class="text-left" placeholder="زمان درس" required />

                <x-input type="number" name="number" value="{{ $lesson->number }}" class="text-left" placeholder="ردیف  درس" />

                    @if (count($seasons))
                    <x-select name="season_id" required>
                        <option value="">انتخاب سرفصل درس</option>
                        @foreach ($seasons as $season)
                        <option value="{{ $season->id }}" {{ $season->id == $lesson->season_id ? 'selected' : '' }}>{{ $season->title }}</option>
                        @endforeach
                    </x-select>
                    @endif


                <p class="box__title">آیا این درس رایگان است ؟</p>
                <div class="w-50">
                    <div class="notificationGroup">
                        <input id="lesson-upload-field-1" @if (! $lesson->is_free)
                        checked
                        @endif name="is_free" value="0" type="radio" />
                        <label for="lesson-upload-field-1">خیر</label>
                    </div>
                    <div class="notificationGroup">
                        <input id="lesson-upload-field-2" name="is_free" @if ($lesson->is_free)
                        checked
                        @endif value="1" type="radio" />
                        <label for="lesson-upload-field-2">بله</label>
                    </div>
                </div>
                <x-file name="lesson_file" placeholder="آپلود درس" :value="$lesson->media"/>
                <x-text-area placeholder="توضیحات درس" value="{{ $lesson->body }}" name="body"/>
                <button class="btn btn-webamooz_net">آپلود درس</button>
            </form>

        </div>
    </div>
@stop

