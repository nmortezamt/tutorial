<?php

namespace Tutorial\Comment\Traits;

use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Tutorial\Comment\Models\Comment;

trait HasComments
{
    use HasRelationships;
    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function approvedComments()
    {
        return $this->morphMany(Comment::class,'commentable')
        ->where('status',Comment::STATUS_APPROVED)
        ->whereNull('comment_id')->with('replies')->latest()->get();
    }

    public function approvedCommentsCount()
    {
        return $this->morphMany(Comment::class,'commentable')
        ->where('status',Comment::STATUS_APPROVED)->count();
    }
}
