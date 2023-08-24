@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('users.index') }}" title="کاربران">کاربران</a></li>
@endsection
@section('content')
    <div class="row no-gutters">
        <div class="col-12 margin-left-10 margin-bottom-15 border-radius-3">
            <p class="box__title">کاربران</p>
            <div class="table__box">
                <table class="table">
                    <thead role="rowgroup">
                        <tr role="row" class="title-row">
                            <th>شناسه</th>
                            <th>نام و نام خانوادگی</th>
                            <th>ایمیل</th>
                            <th>شماره موبایل</th>
                            <th>سطح کاربری</th>
                            <th>تاریخ عضویت</th>
                            <th>ای پی</th>
                            <th>وضعیت حساب</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr role="row">
                                <td><a href="">{{ $user->id }}</a></td>
                                <td><a href="{{ route('users.info',$user->id) }}" target="_blank">{{ $user->name }}</a></td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->mobile ?? '-'}}</td>
                                <td>
                                    <ul>
                                        @foreach ($user->roles as $userRole)
                                            <li>{{ $userRole->name }}
                                                <a
                                                    onclick="deleteItem(event,'{{ route('users.remove_role', ['user'=>$user->id,'role'=>$userRole->name]) }}','li')"
                                                class="item-delete mlg-15" title="حذف"></a></li>
                                        @endforeach
                                    </ul>
                                    <li>
                                        <a href="#select-role" rel="modal:open"
                                            onclick="setFormAction({{ $user->id }})">افزودن نقش کاربری</a>
                                    </li>
                                </td>
                                <td>{{ $user->created_at }}</td>
                                <td>{{ $user->ip }}</td>
                                <td class="confirmation_status">
                                    {!! $user->hasVerifiedEmail() ?
                                    "<span class='text-success'>تایید شده</span>":"<span class='text-danger'>تایید نشده</span>" !!}
                                </td>
                                <td>
                                    <a onclick="deleteItem(event,'{{ route('users.destroy', $user->id) }}')"
                                        class="item-delete mlg-15" title="حذف"></a>

                                        <a onclick="updateConfirmationStatus(event,'{{ route('users.manual.verify',$user->id) }}','آیا از تایید این آیتم اطمینان دارید؟','تایید شده')" class="item-confirm mlg-15" title="تایید"></a>

                                    <a href="" target="_blank" class="item-eye mlg-15" title="مشاهده"></a>

                                    <a href="{{ route('users.edit', $user->id) }}" class="item-edit " title="ویرایش"></a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                <div id="select-role" class="modal">
                    <form action="{{ route('users.add_role', 0) }}" method="POST" id="select-role-form">
                        @csrf
                        <select name="role">
                            <option value="">انتخاب نقش کاربری</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ __($role->name) }}</option>
                            @endforeach
                        </select>
                        <x-validation-error field="role"/>
                        <button class="btn btn-webamooz_net mt-2">افزودن</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <script>
        function setFormAction(userId) {
            let form = $("#select-role-form");
            let action = '{{ route('users.add_role', 0) }}';
            form.attr('action', action.replace('/0/', '/' + userId + '/'))
        }
    </script>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
@endsection
