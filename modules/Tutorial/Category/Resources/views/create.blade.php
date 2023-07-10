<p class="box__title">ایجاد دسته بندی جدید</p>
<form action="{{ route('categories.store') }}" method="post" class="padding-30">
    @csrf
    <input name="title" type="text" placeholder="نام دسته بندی" class="text">
    @error('title')
        <span class="text-danger">{{ $message }}</span>
    @enderror
    <input name="slug" type="text" placeholder="نام انگلیسی دسته بندی" class="text">
    @error('slug')
        <span class="text-danger">{{ __($message) }}</span>
    @enderror
    <p class="box__title margin-bottom-15">انتخاب دسته پدر</p>
    <select name="parent_id" id="parent_id">
        <option value="">ندارد</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}">{{ $category->title }}</option>
        @endforeach
    </select>
    @error('parent_id')
        <span class="text-danger">{{ $message }}</span>
    @enderror
    <button class="btn btn-webamooz_net">اضافه کردن</button>
</form>
