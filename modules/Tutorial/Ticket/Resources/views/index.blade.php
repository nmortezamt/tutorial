@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('tickets.index') }}" title="تیکت ها">تیکت ها</a></li>
@endsection
@section('content')
    <div class="tab__box">
        <div class="tab__items">
            <a class="tab__item {{ request()->status == '' ? 'is-active' : '' }}" href="{{ route('tickets.index') }}">همه تیکت ها</a>
            @can(\Tutorial\RolePermissions\Models\Permission::PERMISSION_MANAGE_TICKETS)
                <a class="tab__item {{ request()->status == 'open' ? 'is-active' : '' }}" href="?{{ request()->getQueryString() }}&status=open">جدید ها (خوانده نشده)</a>

                <a class="tab__item {{ request()->status == 'replied' ? 'is-active' : '' }}" href="?{{ request()->getQueryString() }}&status=replied">پاسخ داده شده ها</a>

                <a class="tab__item {{ request()->status == 'close' ? 'is-active' : '' }}" href="?{{ request()->getQueryString() }}&status=close">بسته شده</a>

            @endcan
            <a class="tab__item " href="{{ route('tickets.create') }}">ارسال تیکت جدید</a>
        </div>
    </div>
    @can(\Tutorial\RolePermissions\Models\Permission::PERMISSION_MANAGE_TICKETS)
    <div class="bg-white padding-20">
        <div class="t-header-search">
            <form action="">
                <div class="t-header-searchbox font-size-13">
                    <input type="text" name="title" value="{{ request()->title }}"  class="text search-input__box font-size-13" placeholder="جستجوی در تیکت ها">
                    <div class="t-header-search-content ">
                        <input type="text" class="text" name="email" value="{{ request()->email }}"  placeholder="ایمیل">
                        <input type="text" class="text" name="name" value="{{ request()->name }}" placeholder="نام و نام خانوادگی">
                        <input type="text" class="text margin-bottom-20" name="date" value="{{ request()->date }}" placeholder="تاریخ 1402/01/01">
                        <button class="btn btn-webamooz_net" type="submit">جستجو</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endcan
    <div class="row no-gutters">
        <div class="col-12 margin-left-10 margin-bottom-15 border-radius-3">
            <p class="box__title">تیکت ها</p>
            <div class="table__box">
                <table class="table tickets">
                    <thead role="rowgroup">
                        <tr role="row" class="title-row">
                            <th>شناسه</th>
                            <th>عنوان تیکت</th>
                            <th>نام ارسال کننده</th>
                            <th>ایمیل ارسال کننده</th>
                            <th>آخرین بروزرسانی</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets as $ticket)
                            <tr role="row" class="{{ $ticket->status == \Tutorial\Ticket\Models\Ticket::STATUS_CLOSE ? 'close-status' : '' }}">
                                <td><a href="">{{ $ticket->id }}</a></td>
                                <td><a href="{{ route('tickets.show', $ticket->id) }}">{{ $ticket->title }}</a></td>
                                <td><a href="{{ route('users.info', $ticket->user->id) }}"
                                        target="_blank">{{ $ticket->user->name }}</a></td>
                                <td>{{ $ticket->user->email }}</td>
                                <td>{{ \Tutorial\Common\createFromCarbon($ticket->updated_at) }}</td>
                                <td class="status {{ $ticket->getStatusCssClass() }}">{{ __($ticket->status) }}</td>
                                <td>
                                    @can(\Tutorial\RolePermissions\Models\Permission::PERMISSION_MANAGE_TICKETS)
                                        <a onclick="deleteItem(event,'{{ route('tickets.destroy', $ticket->id) }}')"
                                            class="item-delete mlg-15" title="حذف"></a>
                                    @endcan

                                    <a onclick="updateConfirmationStatus(event,'{{ route('tickets.close', $ticket->id) }}',
                                            'آیا از بستن این تیکت اطمینان دارید؟'
                                            ,'بسته','status'
                                            )"
                                        class="item-reject mlg-15" title="بسته"></a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
