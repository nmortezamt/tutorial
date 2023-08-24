<?php

namespace Tutorial\Comment\Repositories;

use Tutorial\Comment\Models\Comment;
use Tutorial\Course\Models\Course;
use Tutorial\RolePermissions\Models\Permission;

class CommentRepo
{

    private $query;

    public function __construct()
    {
        $this->query = Comment::query();
    }

    public function paginate()
    {
        return Comment::query()->latest()->paginate();
    }

    public function store($data)
    {
        return Comment::query()->create([
            'user_id' => auth()->id(),
            'comment_id' => array_key_exists('comment_id', $data) ? $data['comment_id'] : null,
            'body' => $data['body'],
            'commentable_id' => $data['commentable_id'],
            'commentable_type' => $data['commentable_type'],
            'status' => auth()->user()->hasAnyPermission([
                Permission::PERMISSION_MANAGE_TEACH, Permission::PERMISSION_MANAGE_COMMENTS,
                Permission::PERMISSION_SUPER_ADMIN
            ]) ?
                Comment::STATUS_APPROVED :
                Comment::STATUS_NEW
        ]);
    }

    public function findApproved($id)
    {
        return Comment::query()
            ->where('status', Comment::STATUS_APPROVED)
            ->where('id', $id)->first();
    }

    public function delete($id)
    {
        return Comment::query()->where('id', $id)->delete();
    }

    public function paginateParents($userId = null)
    {
        $this->query->whereNull('comment_id');
        if (!is_null($userId)) {
            $this->query->whereHasMorph('commentable',[Course::class], function ($query) use ($userId) {
                return $query->where('teacher_id', $userId);
            })->where('status',Comment::STATUS_APPROVED);
        }
        return $this->query->withCount('notApprovedComment as notApprovedCount')
            ->latest()->paginate();
    }

    public function updateStatus($id, string $status)
    {
        return Comment::query()->where('id', $id)->update(['status' => $status]);
    }

    public function findWithRelation($id)
    {
        return Comment::query()->where('id', $id)->with('user', 'commentable', 'replies')->first();
    }

    public function searchStatus($status)
    {
        if (!is_null($status)) {
            $this->query->where('status', $status);
        }
        return $this;
    }

    public function searchBody($body)
    {
        if (!is_null($body)) {
            $this->query->where('body', 'like', "%{$body}%");
        }
        return $this;
    }

    public function searchEmail($email)
    {
        if (!is_null($email)) {
            $this->query->whereHas('user', function ($query) use ($email) {
                return $query->where('email', 'like', "%{$email}%");
            });
        }
        return $this;
    }

    public function searchName($name)
    {
        if (!is_null($name)) {
            $this->query->whereHas('user', function ($query) use ($name) {
                return $query->where('name', 'like', "%{$name}%");
            });
        }
        return $this;
    }

    public function findById($id)
    {
        return Comment::query()->findOrFail($id);
    }
}
