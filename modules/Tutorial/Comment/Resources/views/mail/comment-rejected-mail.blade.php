<x-mail::message>

# رد دیدگاه

دانشجو گرامی دیدگاه شما برای {{ $comment->commentable->title }} رد شد.



<x-mail::button :url="'{{ $comment->commentable->path() }}'">
مشاهده دوره
</x-mail::button>

با تشکر,<br>
{{ config('app.name') }}
</x-mail::message>
