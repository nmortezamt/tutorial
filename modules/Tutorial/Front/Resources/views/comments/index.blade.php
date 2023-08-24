<div class="container">
    <div class="comments">
        @auth
        @include('Front::comments.create')
            @else
            <div class="comment-main">
                <div class="ct-header">
                    <p>برای ثبت دیدگاه باید ابتدا <a href="{{ route('login') }}" class="text-success">وارد سایت</a> شوید</p>
                </div>
            </div>
        @endauth
        <div class="comments-list">
            @include('Front::comments.reply')
            @foreach ($commentable->approvedComments() as $comment)
                <ul class="comment-list-ul">
                    @auth
                    <div class="div-btn-answer">
                        <button class="btn-answer" onclick="setCommentId({{ $comment->id }})">پاسخ به دیدگاه</button>
                    </div>
                    @endauth
                    <li class="is-comment">
                        <div class="comment-header">
                            <div class="comment-header-avatar">
                                <img src="{{ $comment->user->thumb }}" alt="{{ $comment->user->name }}">
                            </div>
                            <div class="comment-header-detail">
                                <div class="comment-header-name">کاربر : {{ $comment->user->name }}</div>
                                <div class="comment-header-date">{{ $comment->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        <div class="comment-content">
                            <p>
                                {{ $comment->body }}
                            </p>
                        </div>
                    </li>
                    @foreach ($comment->approvedReplies() as $reply)
                        <li class="{{ $comment->user_id != $reply->user_id ? 'is-answer' : '' }}">
                            <div class="comment-header">
                                <div class="comment-header-avatar">
                                    <img src="{{ $reply->user->thumb }}">
                                </div>
                                <div class="comment-header-detail">
                                    <div class="comment-header-name">مدیر سایت : {{ $reply->user->name }}</div>
                                    <div class="comment-header-date">{{ $reply->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            <div class="comment-content">
                                <p>
                                    {{ $reply->body }}
                                </p>
                            </div>
                        </li>
                    @endforeach

                </ul>
            @endforeach
        </div>
    </div>
</div>
