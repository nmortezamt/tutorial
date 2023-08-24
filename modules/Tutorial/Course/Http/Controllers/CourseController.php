<?php

namespace Tutorial\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Tutorial\Category\Repositories\CategoryRepo;
use Tutorial\Common\Responses\AjaxResponses;
use Tutorial\Course\Http\Requests\CourseRequest;
use Tutorial\Course\Models\Course;
use Tutorial\Course\Repositories\CourseRepo;
use Tutorial\Course\Repositories\LessonRepo;
use Tutorial\Media\Services\MediaFileService;
use Tutorial\Payment\GateWays\GateWay;
use Tutorial\Payment\Services\PaymentService;
use Tutorial\RolePermissions\Models\Permission;
use Tutorial\User\Repositories\UserRepo;

use function Tutorial\Common\newFeedbacks;

class CourseController extends Controller
{
    public $courseRepo;

    public function __construct(CourseRepo $courseRepo)
    {
        $this->courseRepo = $courseRepo;
    }
    public function index()
    {
        $this->authorize('index', Course::class);
        if(auth()->user()->hasAnyPermission([Permission::PERMISSION_MANAGE_COURSE,Permission::PERMISSION_SUPER_ADMIN])){
            $courses = $this->courseRepo->paginate();
        }else{
            $courses = $this->courseRepo->getCourseByTeacherId(auth()->id());
        }
        return view('Course::index', compact('courses'));
    }

    public function create(UserRepo $userRepo, CategoryRepo $categoryRepo)
    {
        $this->authorize('create', Course::class);
        $teachers = $userRepo->getTeachers();
        $categories = $categoryRepo->all();
        return view('Course::create', compact('teachers', 'categories'));
    }

    public function store(CourseRequest $request)
    {
        $this->authorize('create', Course::class);
        $request->request->add(['banner_id' => MediaFileService::publicUpload($request->file('image'))->id]);
        $this->courseRepo->store($request);
        return redirect()->route('courses.index');
    }

    public function edit($courseId, UserRepo $userRepo, CategoryRepo $categoryRepo)
    {
        $course = $this->courseRepo->findById($courseId);
        $this->authorize('edit', $course);
        $categories = $categoryRepo->all();
        $teachers = $userRepo->getTeachers();
        return view('Course::edit', compact('teachers', 'categories', 'course'));
    }

    public function update($courseId, CourseRequest $request)
    {
        $course = $this->courseRepo->findById($courseId);
        $this->authorize('edit', $course);
        if ($request->hasFile('image')) {
            $request->request->add(['banner_id' => MediaFileService::publicUpload($request->file('image'))->id]);
            if ($course->banner)
                $course->banner->delete();
        } else {
            $request->request->add(['banner_id' => $course->banner_id]);
        }
        $this->courseRepo->update($courseId, $request);
        return redirect(route('courses.index'));
    }

    public function details($courseId,LessonRepo $lessonRepo)
    {
        $course = $this->courseRepo->findById($courseId);
        $this->authorize('details', $course);
        $lessons = $lessonRepo->paginate($courseId);
        return view('Course::details',compact('course','lessons'));
    }

    public function destroy($courseId)
    {
        $this->authorize('delete', Course::class);
        $course = $this->courseRepo->findById($courseId);

        if ($course->banner) {
            $course->banner->delete();
        }
        $this->courseRepo->delete($courseId);
        return AjaxResponses::SuccessResponse();
    }

    public function accept($courseId)
    {
        $this->authorize('change_confirmation_status', Course::class);
        if ($this->courseRepo->updateConfirmationStatus($courseId, Course::CONFIRMATION_STATUS_ACCEPTED)) {
            return AjaxResponses::SuccessResponse();
        }
        return AjaxResponses::FailedResponse();
    }

    public function reject($courseId)
    {
        $this->authorize('change_confirmation_status', Course::class);
        if ($this->courseRepo->updateConfirmationStatus($courseId, Course::CONFIRMATION_STATUS_REJECTED)) {
            return AjaxResponses::SuccessResponse();
        }
        return AjaxResponses::FailedResponse();
    }

    public function lock($courseId)
    {
        $this->authorize('change_confirmation_status', Course::class);
        if ($this->courseRepo->updateStatus($courseId, Course::STATUS_LOCKED)) {
            return AjaxResponses::SuccessResponse();
        }
        return AjaxResponses::FailedResponse();
    }

    public function downloadLinks($courseId)
    {
        $course = $this->courseRepo->findById($courseId);
        $this->authorize('download',$course);
        return implode("<br>",$course->downloadLinks());
    }

    public function buy($courseId)
    {
        $course = $this->courseRepo->findById($courseId);
        if(! $this->courseCanBePurchased($course))
        return back();

        if(! $this->authUserCanPurchaseCourse($course))
        return back();

        [$amount,$discounts] = $course->finalPrice(request()->code,true);
        if($amount <= 0){
            $this->courseRepo->addStudentToCourse($course,auth()->id());
            newFeedbacks("عملیات موفق","شما با موفقیت در دوره ثبت نام کردید");
            return back();
        }
        $payment = PaymentService::generate($amount,$course,auth()->user(),$course->teacher_id,$discounts);
        resolve(GateWay::class)->redirect();
    }

    private function courseCanBePurchased(Course $course)
    {
        if($course->type == Course::TYPE_FREE){
            newFeedbacks("عملیات ناموفق","دوره های رایگان قابل خریداری نیستند","error");
            return false;
        }
        if($course->status == Course::STATUS_LOCKED){
            newFeedbacks("عملیات ناموفق","این دوره قفل شده است و فعلا قابل خریداری نیست","error");
            return false;
        }
        if($course->confirmation_status != Course::CONFIRMATION_STATUS_ACCEPTED){
            newFeedbacks("عملیات ناموفق","این دوره هنوز تایید نشده است","error");
            return false;
        }

        return true;
    }

    private function authUserCanPurchaseCourse(Course $course)
    {
        if(auth()->id() == $course->teacher_id){
            newFeedbacks("عملیات ناموفق","شما مدرس این دوره هستید","error");
            return false;
        }
        if(auth()->user()->can('download',$course))
        {
            newFeedbacks("عملیات ناموفق","شما به این دوره دسترسی دارید","error");
            return false;
        }
        return true;
    }
}
