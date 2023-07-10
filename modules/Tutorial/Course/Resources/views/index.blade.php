@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('courses.index') }}" title="دوره ها">دوره ها</a></li>
@endsection
@section('content')
    <div class="row no-gutters">
        <div class="col-12 margin-left-10 margin-bottom-15 border-radius-3">
            <p class="box__title">دوره ها</p>
            <div class="table__box">
                <table class="table">
                    <thead role="rowgroup">
                        <tr role="row" class="title-row">
                            <th>ردیف</th>
                            <th>آیدی</th>
                            <th>بنر</th>
                            <th>عنوان</th>
                            <th>مدرس</th>
                            <th>قیمت</th>
                            <th>درصد مدس</th>
                            <th>وضعیت</th>
                            <th>وضعیت تایید</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($courses as $course)
                            <tr role="row">
                                <td><a href="">{{ $course->priority }}</a></td>
                                <td><a href="">{{ $course->id }}</a></td>
                                <td><img src="{{ $course->banner->thumb }}" alt="" width="80"></td>
                                <td>{{ $course->title }}</td>
                                <td>{{ $course->teacher->name }}</td>
                                <td>{{ number_format($course->price) }}</td>
                                <td>{{ $course->teacher_percent }}</td>
                                <td class="status">{{ __($course->status) }}</td>
                                <td class="confirmation_status">{{ __($course->confirmation_status) }}</td>
                                <td>
                                    <a
                                        onclick="deleteItem(event,'{{ route('courses.destroy', $course->id) }}')"
                                        class="item-delete mlg-15" title="حذف"></a>
                                        <a href="" target="_blank" class="item-eye mlg-15" title="مشاهده"></a>

                                        <a onclick="updateConfirmationStatus(event,'{{ route('courses.lock',$course->id) }}',
                                        'آیا از قفل این آیتم اطمینان دارید؟'
                                        ,'قفل شده','status'
                                        )" class="item-lock mlg-15" title="قفل"></a>

                                        <a onclick="updateConfirmationStatus(event,'{{ route('courses.reject',$course->id) }}','آیا از رد این آیتم اطمینان دارید؟','رد شده')" class="item-reject mlg-15" title="رد"></a>

                                        <a onclick="updateConfirmationStatus(event,'{{ route('courses.accept',$course->id) }}','آیا از تایید این آیتم اطمینان دارید؟','تایید شده')" class="item-confirm mlg-15" title="تایید"></a>

                                    <a href="{{ route('courses.edit', $course->id) }}" class="item-edit "
                                        title="ویرایش"></a>
                                    </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
