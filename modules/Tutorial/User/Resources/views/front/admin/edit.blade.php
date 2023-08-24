@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('users.index') }}" title="کاربر ها">کاربر ها</a></li>
    <li><a title="ویرایش کاربر">ویرایش کاربر</a></li>
@endsection
@section('content')
    <div class="row no-gutters margin-bottom-20">

        <div class="col-12 bg-white">
            <p class="box__title">ویرایش کاربر</p>
            <form action="{{ route('users.update', $user->id) }}" class="padding-30" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('patch')

                <x-input type="text" name="name" placeholder="نام کاربر" value="{{ $user->name }}" required />

                <x-input type="email" class="text-left" name="email" placeholder="ایمیل کاربر"
                    value="{{ $user->email }}" required />

                <x-input type="text" name="username" placeholder="نام کاربری" value="{{ $user->username }}" />

                <x-input type="text" name="mobile" placeholder="شماره کاربر" value="{{ $user->mobile }}" />

                <x-input type="text" name="headLine" placeholder="عنوان کاربر" value="{{ $user->headLine }}" />

                <x-input type="text" name="telegram" placeholder="تلگرام کاربر" value="{{ $user->telegram }}" />


                <x-select name="status" required>
                    <option value="">وضعیت کاربر</option>
                    @foreach (\Tutorial\User\Models\User::$statuses as $status)
                        <option value="{{ $status }}" {{ $status == $user->status ? 'selected' : '' }}>
                            {{ __($status) }}</option>
                    @endforeach
                </x-select>
                <br>

                <x-file placeholder="آپلود عکس کاربر" name="image" :value="$user->image" accept="image/*" />

                <x-input type="password" name="password" placeholder="پسورد جدید" value="" />

                <x-text-area placeholder="بیو" name="bio" value="{{ $user->bio }}" />
                <button class="btn btn-webamooz_net">ویرایش کاربر</button>
            </form>
        </div>
    </div>

    <div class="row no-gutters">
        <div class="col-6 margin-left-10 margin-bottom-20">
            <p class="box__title">درحال یادگیری</p>
           <div class="table__box">
               <table class="table">
                   <thead role="rowgroup">
                   <tr role="row" class="title-row">
                       <th>شناسه</th>
                       <th>نام دوره</th>
                       <th>نام مدرس</th>
                   </tr>
                   </thead>
                   <tbody>
                   <tr role="row" class="">
                       <td><a href="">1</a></td>
                       <td><a href="">دوره لاراول</a></td>
                       <td><a href="">صیاد اعظمی</a></td>
                   </tr>
                   <tr role="row" class="">
                       <td><a href="">1</a></td>
                       <td><a href="">دوره لاراول</a></td>
                       <td><a href="">صیاد اعظمی</a></td>
                   </tr>
                   </tbody>
               </table>
           </div>
        </div>
        <div class="col-6 margin-bottom-20">
            <p class="box__title">دوره های مدرس</p>
          <div class="table__box">
              <table class="table">
                  <thead role="rowgroup">
                  <tr role="row" class="title-row">
                      <th>شناسه</th>
                      <th>نام دوره</th>
                      <th>نام مدرس</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($user->courses as $course)                        
                    <tr role="row" class="">
                        <td><a href="">{{ $course->id }}</a></td>
                        <td><a href="">{{ $course->title }}</a></td>
                        <td><a href="">{{ $course->teacher->name }}</a></td>
                    </tr>
                    @endforeach
                  </tbody>
              </table>
          </div>
        </div>
    </div> 
@stop

@section('js')
    <script src="/panel/js/tagsInput.js"></script>
@endsection
