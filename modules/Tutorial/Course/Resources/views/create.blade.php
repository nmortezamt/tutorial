@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('courses.index') }}" title="دوره ها">دوره ها</a></li>
@endsection
@section('content')
    <div class="row no-gutters">

        <div class="col-12 bg-white">
            <p class="box__title">ایجاد دوره</p>
            <form action="{{ route('courses.store') }}" class="padding-30" method="POST" enctype="multipart/form-data">
                @csrf

                <x-input type="text" name="title" placeholder="عنوان دوره" required />

                <x-input type="text" class="text-left" name="slug" placeholder="نام انگلیسی دوره" required />


                <div class="d-flex multi-text">
                    <x-input type="text" class="text-left mlg-15" name="priority" placeholder="ردیف دوره" />

                    <x-input type="text" name="price" placeholder="مبلغ دوره" class="text-left" required />

                    <x-input type="text" name="teacher_percent" placeholder="درصد مدرس" class="text-left" required />

                </div>

                <div class="d-flex multi-text">
                    <x-input type="text" name="discount" placeholder="قیمت تخفیف خورده" class="text-left" />

                    {{-- <ul class="tags mr-1">
                        <li class="tagAdd taglist">
                            <input type="text" name="tags" id="search-field" placeholder="برچسب">
                        </li>
                    </ul>
                    <br>
                    <x-validation-error field="tags"/> --}}

                </div>

                <x-select name="teacher_id" required>
                    <option value="">انتخاب مدرس دوره</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ $teacher->id == old('teacher_id') ? 'selected' : '' }}>{{ $teacher->name }}</option>
                    @endforeach
                </x-select>

                <x-select name="type" required>
                    <option value="">نوع دوره</option>
                    @foreach (\Tutorial\Course\Models\Course::$types as $type)
                        <option value="{{ $type }}" {{ $type == old('type') ? 'selected' : '' }}>{{ __($type) }}</option>
                    @endforeach
                </x-select>


                <x-select name="status" required>
                    <option value="">وضعیت دوره</option>
                    @foreach (\Tutorial\Course\Models\Course::$statuses as $status)
                        <option value="{{ $status }}" {{ $status == old('status') ? 'selected' : '' }}>{{ __($status) }}</option>
                    @endforeach
                </x-select>


                <x-select name="category_id" required>
                    <option value="">دسته بندی</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $category->id == old('category_id') ? 'selected' : '' }}>{{ $category->title }}</option>
                    @endforeach
                </x-select>
                <br>

                <x-file placeholder="آپلود بنر دوره" name="image" required accept="image/*"/>

                <x-text-area placeholder="توضیحات دوره" name="body" />
                <button class="btn btn-webamooz_net">ایجاد دوره</button>
            </form>

        </div>
    </div>
@stop

@section('js')
    <script src="/panel/js/tagsInput.js"></script>
@endsection
