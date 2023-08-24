<div class="comment-main">
    <div class="ct-header">
        <h3>نظرات ( {{ $commentable->approvedCommentsCount() }} )</h3>
        <p>نظر خود را در مورد این مقاله مطرح کنید</p>
    </div>
    <form action="{{ route('comments.store') }}" method="post">
        @csrf
        <input type="hidden" name="commentable_type" value="{{ get_class($commentable) }}">
        <input type="hidden" name="commentable_id" value="{{ $commentable->id }}">
        <div class="ct-row">
            <div class="ct-textarea">
                <x-text-area name="body" placeholder="ارسال نظر..." required/>
            </div>
        </div>
        <div class="ct-row">
            <div class="send-comment">
                <button class="btn i-t" type="submit">ثبت نظر</button>
            </div>
        </div>

    </form>
</div>
