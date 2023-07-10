@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('role-permissions.index') }}" title="نقشهای کاربری">نقشهای کاربری</a></li>

    <li><a href="#" title="ویرایش نقشه کاربری">ویرایش نقشه کاربری</a></li>
@endsection
@section('content')
    <div class="row no-gutters">

        <div class="col-6 bg-white">
            <p class="box__title">ایجاد نقش کاربری جدید</p>
            <form action="{{ route('role-permissions.update', $role->id) }}" method="post" class="padding-30">
                @csrf
                @method('patch')
                <input name="name" type="text" placeholder="عنوان" class="text" value="{{ $role->name }}">
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror

                <p class="box__title margin-bottom-15">انتخاب مجوزها</p>
                @foreach ($permissions as $permission)
                <label class="ui-checkbox pt-1">
                    <input type="checkbox" name="permissions[{{ $permission->name }}]" class="sub-checkbox" data-id="2" value="{{$permission->name}}"
                    @if ($role->hasPermissionTo($permission))
                    checked
                    @endif>
                    <span class="checkmark"></span>
                    {{ __($permission->name) }}
                </label>
                @endforeach

                @error('permissions')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <button class="btn btn-webamooz_net mt-2">به روزرسانی کردن</button>
            </form>

        </div>
    </div>
@stop
