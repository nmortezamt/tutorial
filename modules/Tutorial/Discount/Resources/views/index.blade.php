@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('discounts.index') }}" title="تخفیف ها">تخفیف ها</a></li>
@endsection
@section('content')
    <div class="row no-gutters  ">
        <div class="col-8 margin-left-10 margin-bottom-15 border-radius-3">
            <p class="box__title">تخفیف ها</p>
            <div class="table__box">
                <div class="table-box">
                    <table class="table">
                        <thead role="rowgroup">
                            <tr role="row" class="title-row">
                                <th>کد تخفیف</th>
                                <th>درصد</th>
                                <th>محدودیت زمانی</th>
                                <th>توضیحات</th>
                                <th>استفاده شده</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($discounts as $discount)
                            <tr role="row" class="">
                                <td><a href="">{{ $discount->code ?? '-'}}</a></td>
                                <td><a href="">{{ $discount->percent }}%</a> برای {{ __($discount->type) }}</td>
                                <td>{{$discount->expire_at ? \Tutorial\Common\createFromCarbon($discount->expire_at)->format('Y/m/d H:i:s') : 'نامحدود'}}</td>
                                <td>{{ $discount->description ?? '-'}}</td>
                                <td>{{ $discount->uses }} نفر</td>
                                <td>
                                    <a onclick="deleteItem(event,'{{ route('discounts.destroy', $discount->id) }}')"
                                        class="item-delete mlg-15" title="حذف"></a>                                    <a href="{{ route('discounts.edit',$discount->id) }}" class="item-edit " title="ویرایش"></a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-4 bg-white">
            <p class="box__title">ایجاد تخفیف جدید</p>
            <form action="{{route('discounts.store')}}" method="post" class="padding-30">
                @csrf
                <x-input type="text" name="code" placeholder="کد تخفیف" />
                <x-input type="number" name="percent" placeholder="درصد تخفیف" required />
                <x-input type="number" name="usage_limitation" placeholder="محدودیت افراد" class="text" />
                <x-input type="text" id="expire_at" name="expire_at" placeholder="محدودیت زمانی به ساعت" />
                <p class="box__title">این تخفیف برای</p>
                <x-validation-error field="type"/>
                <div class="notificationGroup">
                    <input id="discounts-field-1" class="discounts-field-pn" name="type" value="all" type="radio"/>
                    <label for="discounts-field-1">همه دوره ها</label>
                </div>
                <div class="notificationGroup">
                    <input id="discounts-field-2" class="discounts-field-pn" name="type" value="special" type="radio"/>
                    <label for="discounts-field-2">دوره خاص</label>
                </div>
                <div id="selectCourseContainer" class="d-none">
                    <select name="courses[]" class="my_select2" multiple>
                        @foreach ($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>
                <x-input type="text" placeholder="لینک اطلاعات بیشتر" name="link" />
                <x-input type="text" name="description" placeholder="توضیحات" class="margin-bottom-15" />

                <button type="submit" class="btn btn-webamooz_net">اضافه کردن</button>
            </form>
        </div>
    </div>
@endsection

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
