<?php

namespace Tutorial\Comment\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tutorial\Comment\Events\CommentApprovedEvent;
use Tutorial\Comment\Events\CommentRejectedEvent;
use Tutorial\Comment\Events\CommentSubmittedEvent;
use Tutorial\Comment\Http\Requests\CommentRequest;
use Tutorial\Comment\Models\Comment;
use Tutorial\Comment\Repositories\CommentRepo;
use Tutorial\Common\Responses\AjaxResponses;
use Tutorial\RolePermissions\Models\Permission;

use function Tutorial\Common\newFeedbacks;

class CommentController extends Controller
{
    private $commentRepo;

    public function __construct(CommentRepo $commentRepo)
    {
        $this->commentRepo = $commentRepo;
    }

    public function store(CommentRequest $request)
    {
        $comment = $this->commentRepo->store($request->all());
        event(new CommentSubmittedEvent($comment));
        newFeedbacks('عملیات موفق', 'دیدگاه شما با موفقیت ثبت شد');
        return back();
    }

    public function index(Request $request)
    {
        $this->authorize('index', Comment::class);
        if (auth()->user()->can(Permission::PERMISSION_MANAGE_COMMENTS)) {
            $comments = $this->commentRepo
                ->searchStatus($request->status)
                ->searchBody($request->body)
                ->searchEmail($request->email)
                ->searchName($request->name)->paginateParents();
        } else {
            $comments = $this->commentRepo
                ->searchStatus($request->status)
                ->searchBody($request->body)
                ->searchEmail($request->email)
                ->searchName($request->name)->paginateParents(auth()->id());
        }

        return view('Comments::index', compact('comments'));
    }

    public function destroy($commentId)
    {
        $this->authorize('manage', Comment::class);
        $this->commentRepo->delete($commentId);
        return AjaxResponses::SuccessResponse();
    }

    public function approve($commentId)
    {
        $this->authorize('manage', Comment::class);
        $comment = $this->commentRepo->findById($commentId);
        if ($comment->status != Comment::STATUS_APPROVED) {
            $this->commentRepo->updateStatus($commentId, Comment::STATUS_APPROVED);
            CommentApprovedEvent::dispatch($comment);
            return AjaxResponses::SuccessResponse();
        }
    }

    public function reject($commentId)
    {
        $this->authorize('manage', Comment::class);
        $comment = $this->commentRepo->findById($commentId);
        if ($comment->status != Comment::STATUS_REJECTED) {
            $this->commentRepo->updateStatus($commentId, Comment::STATUS_REJECTED);
            CommentRejectedEvent::dispatch($comment);
            return AjaxResponses::SuccessResponse();
        }
    }

    public function show($commentId)
    {
        $comment = $this->commentRepo->findWithRelation($commentId);
        $this->authorize('show', $comment);
        return view('Comments::show', compact('comment'));
    }
}
