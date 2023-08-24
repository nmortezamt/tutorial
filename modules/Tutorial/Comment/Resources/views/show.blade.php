@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('comments.index') }}" title="نظرات">نظرات</a></li>
    <li><a title="مشاهده نظر">مشاهده نظر</a></li>
@endsection
@section('content')
    <div class="show-comment">
        <div class="ct__header">
            <div class="comment-info">
                <a class="back" href="{{ route('comments.index') }}"></a>
                <div>
                    <p class="comment-name"><a href="">{{ $comment->commentable->title }}</a></p>
                </div>
            </div>
        </div>
        <div class="transition-comment">
            <div class="transition-comment-header">
                <span>
                    <img src="{{ $comment->user->thumb }}" class="logo-pic">
                </span>
                <span class="nav-comment-status">
                    <p class="username">کاربر : {{ $comment->user->name }}</p>
                    <p class="comment-date">{{ $comment->created_at->diffForHumans() }}</p>
                    <span class="status status-font-size {{ $comment->getStatusCssClass() }}">{{ __($comment->status) }}</span>
                </span>
            </div>
            <div class="transition-comment-body">
                <pre>{{ $comment->body }}</pre>
            </div>
        </div>
        @foreach ($comment->replies as $reply)
            
        <div class="transition-comment {{ $reply->user_id != $comment->user_id ? 'is-answer' : '' }}">
            <div class="transition-comment-header">
                <span>
                    <img src="{{ $reply->user->thumb }}" class="logo-pic">
                </span>
                <span class="nav-comment-status">
                    <p class="username">کاربر : {{ $reply->user->name }}</p>
                    <p class="comment-date">{{ $reply->created_at->diffForHumans() }}</p>
                    <span class="status status-font-size {{ $reply->getStatusCssClass() }}">{{ __($reply->status) }}</span>
                </span>
                <div class="comment_action">
                    <a onclick="deleteItem(event,'{{ route('comments.destroy', $reply->id) }}','div.transition-comment')"
                        class="item-delete mlg-15" title="حذف"></a>

                    <a onclick="updateConfirmationStatus(event,'{{ route('comments.reject', $reply->id) }}','آیا از رد این آیتم اطمینان دارید؟','رد شده','status','div.transition-comment-header','span.')"
                        class="item-reject mlg-15" title="رد"></a>

                    <a onclick="updateConfirmationStatus(event,'{{ route('comments.approve', $reply->id) }}','آیا از تایید این آیتم اطمینان دارید؟','تایید شده','status','div.transition-comment-header','span.')"
                        class="item-confirm mlg-15" title="تایید"></a>
                </div>
            </div>
            <div class="transition-comment-body">
                <pre>
                    {{ $reply->body }}
                </pre>
            </div>
        </div>
        @endforeach

    </div>
    <div class="answer-comment">
        <p class="p-answer-comment">ارسال پاسخ</p>
        @if ($comment->status == \Tutorial\Comment\Models\Comment::STATUS_APPROVED)
        <form action="{{ route('comments.store') }}" method="post">
            @csrf
            <input type="hidden" name="comment_id" value="{{ $comment->id }}">
            <input type="hidden" name="commentable_type" value="{{ get_class($comment->commentable) }}">
            <input type="hidden" name="commentable_id" value="{{ $comment->commentable->id }}">
            <x-text-area name="body" placeholder="ارسال نظر..." required />
            <button class="btn btn-webamooz_net" type="submit">ارسال پاسخ</button>
        </form>
        @else
        <p class="text-error">جهت ارسال پاسخ به این دیدگاه لطفا ابتدا آن را تایید کنید</p>
        @endif
    </div>
@endsection
