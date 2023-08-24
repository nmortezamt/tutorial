<?php

namespace Tutorial\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Tutorial\Common\Responses\AjaxResponses;
use Tutorial\Course\Http\Requests\SeasonRequest;
use Tutorial\Course\Models\Season;
use Tutorial\Course\Repositories\CourseRepo;
use Tutorial\Course\Repositories\SeasonRepo;

use function Tutorial\Common\newFeedbacks;

class SeasonController extends Controller
{
    private $seasonRepo;
    public function __construct(SeasonRepo $seasonRepo)
    {
        $this->seasonRepo = $seasonRepo;
    }
    public function store($courseId, SeasonRequest $request,CourseRepo $courseRepo)
    {
        $this->authorize('createSeason',$courseRepo->findById($courseId));
        $this->seasonRepo->store($courseId,$request);
        newFeedbacks();
        return back();
    }

    public function edit($seasonId)
    {
        $season = $this->seasonRepo->findById($seasonId);
        $this->authorize('edit',$season);
        return view('Course::season.edit',compact('season'));
    }

    public function update($seasonId,SeasonRequest $request)
    {
        $season = $this->seasonRepo->findById($seasonId);
        $this->authorize('edit',$season);
        $this->seasonRepo->update($seasonId,$request);
        newFeedbacks();
        return redirect()->route('courses.details',$season->course->id);
    }

    public function accept($seasonId)
    {
        $this->authorize('change_confirmation_status',Season::class);
        $this->seasonRepo->updateConfirmationStatus($seasonId,Season::CONFIRMATION_STATUS_ACCEPTED);
        return AjaxResponses::SuccessResponse();
    }

    public function reject($seasonId)
    {
        $this->authorize('change_confirmation_status',Season::class);
        $this->seasonRepo->updateConfirmationStatus($seasonId,Season::CONFIRMATION_STATUS_REJECTED);
        return AjaxResponses::SuccessResponse();
    }

    public function lock($seasonId)
    {
        $this->authorize('change_confirmation_status',Season::class);
        $this->seasonRepo->updateStatus($seasonId,Season::STATUS_LOCKED);
        return AjaxResponses::SuccessResponse();
    }

    public function unlock($seasonId)
    {
        $this->authorize('change_confirmation_status',Season::class);
        $this->seasonRepo->updateStatus($seasonId,Season::STATUS_OPENED);
        return AjaxResponses::SuccessResponse();
    }

    public function destroy($seasonId)
    {
        $this->authorize('delete',$this->seasonRepo->findById($seasonId));
        $this->seasonRepo->delete($seasonId);
        return AjaxResponses::SuccessResponse();
    }
}
