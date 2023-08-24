<x-mail::message>
@if ($isTeacher)
# یک کامنت جدید برای {{ $comment->commentable->title }} ارسال شده است.

مدرس گرامی یک کامنت جدید برای {{ $comment->commentable->title }} ارسال شده است. لطفا در اسرع وقت پاسخ مناسب ارسال نمایید.
@else
# یک پاسخ به دیدگاه شما برای {{ $comment->commentable->title }} ارسال شده است.

دانشجو گرامی یک پاسخ جدید به دیدگاه شما برای {{ $comment->commentable->title }} ارسال شده است..
@endif


<x-mail::button :url="'{{ $comment->commentable->path() }}'">
مشاهده دوره
</x-mail::button>

با تشکر,<br>
{{ config('app.name') }}
</x-mail::message>
