@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('discounts.index') }}" title="تخفیف ها">تخفیف ها</a></li>
    <li><a title="ویرایش تخفیف">ویرایش تخفیف</a></li>
@endsection
@section('content')
    <div class="row no-gutters">

        <div class="col-12 bg-white">
            <p class="box__title">ویرایش تخفیف</p>
            <form action="{{route('discounts.update',$discount->id)}}" method="post" class="padding-30">
                @csrf
                @method('patch')
                <x-input type="text" name="code" value="{{ $discount->code }}" placeholder="کد تخفیف" />
                <x-input type="number" name="percent" value="{{ $discount->percent }}" placeholder="درصد تخفیف" required />
                <x-input type="number" name="usage_limitation" value="{{ $discount->usage_limitation }}" placeholder="محدودیت افراد" class="text" />
                <x-input type="text" id="expire_at" name="expire_at" value="{{ $discount->expire_at ? \Tutorial\Common\createFromCarbon($discount->expire_at)->format('Y/m/d H:i') : '' }}" placeholder="محدودیت زمانی به ساعت" />
                <p class="box__title">این تخفیف برای</p>
                <x-validation-error field="type"/>
                <div class="notificationGroup">
                    <input id="discounts-field-1" class="discounts-field-pn" name="type" value="all" {{ $discount->type == \Tutorial\Discount\Models\Discount::TYPE_ALL ? 'checked' : ''}} type="radio"/>
                    <label for="discounts-field-1">همه دوره ها</label>
                </div>
                <div class="notificationGroup">
                    <input id="discounts-field-2" class="discounts-field-pn" name="type" value="special" {{ $discount->type == \Tutorial\Discount\Models\Discount::TYPE_SPECIAL ? 'checked' : ''}} type="radio"/>
                    <label for="discounts-field-2">دوره خاص</label>
                </div>
                <div id="selectCourseContainer" class="{{ $discount->type == \Tutorial\Discount\Models\Discount::TYPE_ALL ? 'd-none' : '' }}">
                    <select name="courses[]" class="my_select2" multiple>
                        @foreach ($courses as $course)
                        <option value="{{ $course->id }}" {{ $discount->courses->contains($course->id) ? 'selected' : '' }}>{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>
                <x-input type="text" value="{{ $discount->link }}" placeholder="لینک اطلاعات بیشتر" name="link" />
                <x-input type="text" value="{{ $discount->description }}" name="description" placeholder="توضیحات" class="margin-bottom-15" />

                <button type="submit" class="btn btn-webamooz_net">ویرایش کردن</button>
            </form>

        </div>
    </div>
@stop

@section('js')
<script src="/panel/persianDatePicker/js/persianDatepicker.min.js"></script>
<script src="/panel/js/select2.min.js"></script>
<script>
    $(function() {
        $("#expire_at").persianDatepicker({
            formatDate: "YYYY/0M/0D 0h:0m",
        });
    });

    $('.my_select2').select2({
        placeholder:'انتخاب یک یا چند دوره'
    });
</script>
@endsection

@section('css')
<link rel="stylesheet" href="/panel/persianDatePicker/css/persianDatepicker-default.css" />
<link rel="stylesheet" href="/panel/css/select2.min.css" />
@endsection
