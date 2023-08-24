<div class="col-12 bg-white margin-bottom-15 border-radius-3">
    <p class="box__title">سرفصل ها</p>
    <form action="{{ route('seasons.store', $course->id) }}" method="post" class="padding-30">
        @csrf
        <x-input type="text" name="title" placeholder="عنوان سرفصل" class="text" required />
        <x-input type="text" name="number" placeholder="شماره سرفصل" class="text" />
        <button type="submit" class="btn btn-webamooz_net">اضافه کردن</button>
    </form>
    <div class="table__box padding-30">
        <table class="table">
            <thead role="rowgroup">
                <tr role="row" class="title-row">
                    <th class="p-r-90">شناسه</th>
                    <th>عنوان فصل</th>
                    <th>وضعیت</th>
                    <th>وضعیت تایید</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($course->seasons()->paginate() as $season)
                    <tr role="row" class="">
                        <td><a href="">{{ $season->number }}</a></td>
                        <td><a href="">{{ $season->title }}</a></td>
                        <td class="status">{{ __($season->status) }}</td>
                        <td class="confirmation_status">
                                <span class="{{ $season->getConfirmationStatusCssClass() }}">
                                {{ __($season->confirmation_status) }}</span>
                        </td>
                        <td>
                            <a onclick="deleteItem(event,'{{ route('seasons.destroy', $season->id) }}')"
                                class="item-delete mlg-15" title="حذف"></a>
                            @can(\Tutorial\RolePermissions\Models\Permission::PERMISSION_MANAGE_COURSE)
                                <a onclick="updateConfirmationStatus(event,'{{ route('seasons.reject', $season->id) }}','آیا از رد این آیتم اطمینان دارید؟','رد شده')"
                                    class="item-reject mlg-15" title="رد"></a>

                                <a onclick="updateConfirmationStatus(event,'{{ route('seasons.accept', $season->id) }}','آیا از تایید این آیتم اطمینان دارید؟','تایید شده')"
                                    class="item-confirm mlg-15" title="تایید"></a>

                                @if ($season->status == \Tutorial\Course\Models\Season::STATUS_OPENED)
                                    <a onclick="updateConfirmationStatus(event,'{{ route('seasons.lock', $season->id) }}',
                                    'آیا از قفل این آیتم اطمینان دارید؟'
                                    ,'قفل شده','status'
                                    )"
                                        class="item-lock mlg-15 text-danger" title="قفل"></a>
                                @else
                                    <a onclick="updateConfirmationStatus(event,'{{ route('seasons.unlock', $season->id) }}',
                                    'آیا از باز کردن این آیتم اطمینان دارید؟'
                                    ,'باز','status'
                                    )"
                                        class="item-lock mlg-15 text-success" title="باز کردن"></a>
                                @endif
                            @endcan


                            <a href="{{ route('seasons.edit', $season->id) }}" class="item-edit " title="ویرایش"></a>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>
