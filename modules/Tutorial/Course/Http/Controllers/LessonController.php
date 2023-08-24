<?php

namespace Tutorial\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tutorial\Common\Responses\AjaxResponses;
use Tutorial\Course\Http\Requests\LessonRequest;
use Tutorial\Course\Models\Course;
use Tutorial\Course\Models\Lesson;
use Tutorial\Course\Repositories\CourseRepo;
use Tutorial\Course\Repositories\LessonRepo;
use Tutorial\Course\Repositories\SeasonRepo;
use Tutorial\Media\Services\MediaFileService;

use function Tutorial\Common\newFeedbacks;

class LessonController extends Controller
{
    private $lessonRepo;
    public function __construct(LessonRepo $lessonRepo)
    {
        $this->lessonRepo = $lessonRepo;
    }

    public function create($courseId,SeasonRepo $seasonRepo,CourseRepo $courseRepo)
    {
        $course = $courseRepo->findById($courseId);
        $this->authorize('createLesson',$course);
        $seasons = $seasonRepo->getCourseSeasons($courseId);
        return view('Course::lesson.create',compact('seasons','course'));
    }

    public function store($courseId,LessonRequest $request,CourseRepo $courseRepo)
    {
        $course = $courseRepo->findById($courseId);
        $this->authorize('createLesson',$course);
        $request->request->add(['media_id'=>MediaFileService::privateUpload($request->lesson_file)->id]);
        $this->lessonRepo->store($courseId, $request);
        newFeedbacks();
        return redirect(route('courses.details',$courseId));
    }

    public function edit($courseId,$lessonId,SeasonRepo $seasonRepo,CourseRepo $courseRepo)
    {
        $lesson = $this->lessonRepo->findById($lessonId);
        $this->authorize('edit',$lesson);
        $course = $courseRepo->findById($courseId);
        $seasons = $seasonRepo->getCourseSeasons($courseId);
        return view('Course::lesson.edit',compact('lesson','course','seasons'));
    }

    public function update($courseId,$lessonId,LessonRequest $request)
    {
        $lesson = $this->lessonRepo->findById($lessonId);
        $this->authorize('edit',$lesson);
        if($request->hasFile('lesson_file')){
            if($lesson->media)
            $lesson->media->delete();
            $request->request->add(['media_id'=>MediaFileService::privateUpload($request->lesson_file)->id]);
        }else{
            $request->request->add(['media_id'=>$lesson->media_id]);
        }
        $this->lessonRepo->update($lessonId,$courseId,$request);
        newFeedbacks();
        return redirect(route('courses.details',$courseId));
    }

    public function accept($lessonId)
    {
        $this->authorize('manage',Course::class);
        $this->lessonRepo->updateConfirmationStatus($lessonId,Lesson::CONFIRMATION_STATUS_ACCEPTED);
        return AjaxResponses::SuccessResponse();
    }

    public function acceptAll($courseId)
    {
        $this->authorize('manage',Course::class);
        $this->lessonRepo->acceptAll($courseId);
        newFeedbacks();
        return back();
    }

    public function acceptMultiple(Request $request)
    {
        $this->authorize('manage',Course::class);
        $ids = explode(',',$request->ids);
        $this->lessonRepo->updateConfirmationStatus($ids,Lesson::CONFIRMATION_STATUS_ACCEPTED);
        newFeedbacks();
        return back();
    }

    public function rejectMultiple(Request $request)
    {
        $this->authorize('manage',Course::class);
        $ids = explode(',',$request->ids);
        $this->lessonRepo->updateConfirmationStatus($ids,Lesson::CONFIRMATION_STATUS_REJECTED);
        newFeedbacks();
        return back();
    }

    public function reject($lessonId)
    {
        $this->authorize('manage',Course::class);
        $this->lessonRepo->updateConfirmationStatus($lessonId,Lesson::CONFIRMATION_STATUS_REJECTED);
        return AjaxResponses::SuccessResponse();
    }

    public function lock($lessonId)
    {
        $this->authorize('manage',Course::class);
        $this->lessonRepo->updateStatus($lessonId,Lesson::STATUS_LOCKED);
        return AjaxResponses::SuccessResponse();
    }

    public function unlock($lessonId)
    {
        $this->authorize('manage',Course::class);
        $this->lessonRepo->updateStatus($lessonId,Lesson::STATUS_OPENED);
        return AjaxResponses::SuccessResponse();
    }

    public function destroy($lessonId)
    {
        $lesson = $this->lessonRepo->findById($lessonId);
        $this->authorize('delete',$lesson);
        if($lesson->media)
        $lesson->media->delete();
        $this->lessonRepo->delete($lessonId);
        return AjaxResponses::SuccessResponse();
    }

    public function destroyMultiple(Request $request)
    {
        $ids = explode(',',$request->ids);
        foreach($ids as $id)
        {
            $lesson = $this->lessonRepo->findById($id);
            $this->authorize('delete',$lesson);
            if($lesson->media)
            $lesson->media->delete();

            $this->lessonRepo->delete($id);
        }
        newFeedbacks();
        return back();

    }
}
