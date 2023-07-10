<?php

namespace Tutorial\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Tutorial\Category\Repositories\CategoryRepo;
use Tutorial\Category\Responses\AjaxResponses;
use Tutorial\Course\Http\Requests\CourseRequest;
use Tutorial\Course\Models\Course;
use Tutorial\Course\Repositories\CourseRepo;
use Tutorial\Media\Services\MediaFileService;
use Tutorial\User\Repositories\UserRepo;

class CourseController extends Controller
{
    public $courseRepo;

    public function __construct(CourseRepo $courseRepo)
    {
        $this->courseRepo = $courseRepo;
    }
    public function index()
    {
        $courses = $this->courseRepo->paginate();

        return view('Course::index',compact('courses'));
    }

    public function create(UserRepo $userRepo, CategoryRepo $categoryRepo)
    {
        $teachers = $userRepo->getTeachers();
        $categories = $categoryRepo->all();
        return view('Course::create', compact('teachers', 'categories'));
    }

    public function store(CourseRequest $request)
    {
        $request->request->add(['banner_id' => MediaFileService::upload($request->file('image'))->id]);

        $this->courseRepo->store($request);
        return redirect()->route('courses.index');
    }

    public function edit($courseId,UserRepo $userRepo, CategoryRepo $categoryRepo)
    {
        $course = $this->courseRepo->findById($courseId);
        $categories =$categoryRepo->all();
        $teachers = $userRepo->getTeachers();
        return view('Course::edit', compact('teachers', 'categories','course'));
    }

    public function update($courseId,CourseRequest $request)
    {
        $course = $this->courseRepo->findById($courseId);
        if($request->hasFile('image')){
        $request->request->add(['banner_id' => MediaFileService::upload($request->file('image'))->id]);
        $course->banner->delete();
        }else{
            $request->request->add(['banner_id' => $course->banner_id]);
        }
        $this->courseRepo->update($courseId,$request);
        return redirect(route('courses.index'));
    }

    public function destroy($courseId)
    {
        $course = $this->courseRepo->findById($courseId);

        if($course->banner){
            $course->banner->delete();
        }
        $this->courseRepo->delete($courseId);
        return AjaxResponses::SuccessResponse();
    }

    public function accept($courseId)
    {
        if($this->courseRepo->updateConfirmationStatus($courseId,Course::CONFIRMATION_STATUS_ACCEPTED)){
            return AjaxResponses::SuccessResponse();
        }
        return AjaxResponses::FailedResponse();
    }

    public function reject($courseId)
    {
        if($this->courseRepo->updateConfirmationStatus($courseId,Course::CONFIRMATION_STATUS_REJECTED)){
            return AjaxResponses::SuccessResponse();
        }
        return AjaxResponses::FailedResponse();
    }

    public function lock($courseId)
    {
        if($this->courseRepo->updateStatus($courseId,Course::STATUS_LOCKED)){
            return AjaxResponses::SuccessResponse();
        }
        return AjaxResponses::FailedResponse();
    }
    
}
