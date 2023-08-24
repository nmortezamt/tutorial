@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('slider.index') }}" title="اسلایدر">اسلایدر</a></li>
@endsection
@section('content')
    <div class="row no-gutters">
        <div class="col-8 margin-left-10 margin-bottom-15 border-radius-3">
            <p class="box__title">اسلایدر ها</p>
            <div class="table__box">
                <table class="table">
                    <thead role="rowgroup">
                        <tr role="row" class="title-row">
                            <th>شناسه</th>
                            <th>عکس</th>
                            <th>اولویت</th>
                            <th>لینک</th>
                            <th>وضعیت نمایش</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($slides as $slide)
                            <tr role="row">
                                <td><a href="">{{ $slide->id }}</a></td>
                                <td><img src="{{ $slide->media->thumb }}" alt="" width="100"></td>
                                <td>{{ $slide->priority }}</td>
                                <td>{{ $slide->link }}</td>
                                <td>{{ $slide->status ? 'فعال' : 'غیرفعال' }}</td>
                                <td>
                                    <a
                                        onclick="deleteItem(event,'{{ route('slider.destroy', $slide->id) }}')"
                                        class="item-delete mlg-15" title="حذف"></a>
                                    <a href="{{ route('slider.edit', $slide->id) }}" class="item-edit "
                                        title="ویرایش"></a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-4 bg-white">
            @include('Slider::create')
        </div>
    </div>
@endsection
