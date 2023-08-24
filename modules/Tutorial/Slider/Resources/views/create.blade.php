<p class="box__title">ایجاد اسلایدر جدید</p>
<form action="{{ route('slider.store') }}" method="POST" class="padding-30" enctype="multipart/form-data">
    @csrf
    <x-file name="image" placeholder="عکس اسلایدر" required />

    <x-input name="priority" type="number" placeholder="اولویت" />
    <x-input name="link" type="text" placeholder="لینک" />

    <p class="box__title margin-bottom-15">وضعیت نمایش</p>
    <x-select name="status">
        <option value="1" selected>فعال</option>
        <option value="0">غیر فعال</option>
    </x-select>
    <button class="btn btn-webamooz_net" type="submit">اضافه کردن</button>
</form>
