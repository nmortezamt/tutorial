@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('tickets.index') }}" title="تیکت ها">تیکت ها</a></li>
    <li><a href="#" title="نمایش تیکت">نمایش تیکت</a></li>
@endsection
@section('content')
    <div class="show-comment">
        <div class="ct__header">
            <div class="comment-info">
                <a class="back" href="{{ route('tickets.index') }}"></a>
                <div>
                    <p class="comment-name"><a href="">{{ $ticket->title }}</a></p>
                </div>
            </div>
        </div>
        @foreach ($ticket->replies as $reply)
            <div class="transition-comment {{ $ticket->user_id == $reply->user_id ? '' : 'is-answer' }}">
                <div class="transition-comment-header">
                    <span>
                        <img src="{{ $reply->user->thumb }}" class="logo-pic">
                    </span>
                    <span class="nav-comment-status">
                        <p class="username">کاربر : {{ $reply->user->name }}</p>
                        <p class="comment-date">{{ $reply->created_at }}</p>
                    </span>
                    <div>

                    </div>
                </div>
                <div class="transition-comment-body">
                    <pre>{!! $reply->body !!}
                        @if ($reply->media_id)
                <div class="border-top margin-top-6">
                    <a href="{{ $reply->attachmentLink() }}" class="text-success">دانلود فایل پیوست</a>
                </div>
                @endif

                </pre>
                </div>
            </div>
        @endforeach
    </div>
    <div class="answer-comment">
        <p class="p-answer-comment">ارسال پاسخ</p>
        <form action="{{ route('tickets.reply', $ticket->id) }}" class="padding-30" method="POST"
            enctype="multipart/form-data">
            @csrf
            <x-text-area placeholder="متن پاسخ" name="body" />

            <x-file name="attachment" placeholder="آپلود فایل پیوست" />
            <button type="submit" class="btn btn-webamooz_net">ارسال پاسخ</button>
        </form>
    </div>
@endsection
