<x-mail::message>

# تایید دیدگاه

دانشجو گرامی دیدگاه شما برای {{ $comment->commentable->title }} تایید شد.



<x-mail::button :url="'{{ $comment->commentable->path() }}'">
مشاهده دوره
</x-mail::button>

با تشکر,<br>
{{ config('app.name') }}
</x-mail::message>
