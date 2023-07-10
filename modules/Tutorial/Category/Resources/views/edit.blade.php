@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('categories.index') }}" title="دسته بندی">دسته بندی</a></li>

    <li><a href="#" title="ویرایش دسته بندی">ویرایش دسته بندی</a></li>
@endsection
@section('content')
    <div class="row no-gutters">

        <div class="col-6 bg-white">
            <p class="box__title">به روزرسانی دسته بندی</p>
            <form action="{{ route('categories.update', $category->id) }}" method="post" class="padding-30">
                @csrf
                @method('patch')
                <input name="title" type="text" placeholder="نام دسته بندی" class="text"
                    value="{{ $category->title }}">
                @error('title')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <input name="slug" type="text" placeholder="نام انگلیسی دسته بندی" class="text"
                    value="{{ $category->slug }}">
                @error('slug')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <p class="box__title margin-bottom-15">انتخاب دسته پدر</p>

                <select name="parent_id" id="parent_id">
                    <option value="">ندارد</option>
                    @foreach ($categories as $categoryItem)
                        <option value="{{ $categoryItem->id }}"
                            {{ $categoryItem->id == $category->parent_id ? 'selected' : '' }}>{{ $categoryItem->title }}
                        </option>
                    @endforeach
                </select>
                @error('parent_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <button class="btn btn-webamooz_net">به روز رسانی کردن</button>
            </form>
        </div>
    </div>
@stop
