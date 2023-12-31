@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('courses.index') }}" title="دوره ها">دوره ها</a></li>
    <li><a title="ویرایش دوره">ویرایش دوره</a></li>
@endsection
@section('content')
    <div class="row no-gutters">

        <div class="col-12 bg-white">
            <p class="box__title">ویرایش دوره</p>
            <form action="{{ route('courses.update',$course->id) }}" class="padding-30" method="POST" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <x-input type="text" name="title" placeholder="عنوان دوره" value="{{ $course->title }}" required />

                <x-input type="text" class="text-left" name="slug" placeholder="نام انگلیسی دوره" value="{{ $course->slug }}" required />


                <div class="d-flex multi-text">
                    <x-input type="text" class="text-left mlg-15" name="priority" placeholder="ردیف دوره" value="{{ $course->priority }}"/>

                    <x-input type="text" name="price" placeholder="مبلغ دوره" class="text-left" required value="{{ $course->price }}"/>

                    <x-input type="text" name="teacher_percent" placeholder="درصد مدرس" class="text-left" required value="{{ $course->teacher_percent }}"/>

                </div>

                <x-select name="teacher_id" required>
                    <option value="">انتخاب مدرس دوره</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ $teacher->id == $course->teacher_id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                    @endforeach
                </x-select>

                <x-select name="type" required>
                    <option value="">نوع دوره</option>
                    @foreach (\Tutorial\Course\Models\Course::$types as $type)
                        <option value="{{ $type }}" {{ $type == $course->type ? 'selected' : '' }}>{{ __($type) }}</option>
                    @endforeach
                </x-select>


                <x-select name="status" required>
                    <option value="">وضعیت دوره</option>
                    @foreach (\Tutorial\Course\Models\Course::$statuses as $status)
                        <option value="{{ $status }}" {{ $status == $course->status ? 'selected' : '' }}>{{ __($status) }}</option>
                    @endforeach
                </x-select>


                <x-select name="category_id" required>
                    <option value="">دسته بندی</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $category->id == $course->category_id ? 'selected' : '' }}>{{ $category->title }}</option>
                    @endforeach
                </x-select>
                <br>

                <x-file placeholder="آپلود بنر دوره" name="image" :value="$course->banner" accept="image/*"/>

                <x-text-area placeholder="توضیحات دوره" name="body" value="{{ $course->body }}"/>
                <button class="btn btn-webamooz_net">ویرایش دوره</button>
            </form>

        </div>
    </div>
@stop

@section('js')
    <script src="/panel/js/tagsInput.js"></script>
@endsection
