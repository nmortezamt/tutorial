<?php

namespace Tutorial\Course\Repositories;

use Tutorial\Course\Models\Course;
use Illuminate\Support\Str;
use Tutorial\Course\Models\Season;

class SeasonRepo
{
    public function findByIdAndCourseId($seasonId,$courseId)
    {
        return Season::where('course_id',$courseId)->where('id',$seasonId)->first();
    }
    public function getCourseSeasons($id)
    {
        return Season::where('course_id',$id)->where('confirmation_status',Season::CONFIRMATION_STATUS_ACCEPTED)->orderBy('number','desc')->get();
    }
    
    public function store($id,$data)
    {
        return Season::query()->create([
            'course_id' => $id,
            'user_id' => auth()->id(),
            'title' => $data->title,
            'number' => $this->generateNumber($data->number,$id),
            'confirmation_status' => Season::CONFIRMATION_STATUS_PENDING,
        ]);
    }


    public function findById($id)
    {
        return Season::findOrFail($id);
    }

    public function update($id, $data)
    {
        return Season::where('id', $id)->update([
            'title' => $data->title,
            'number' => $this->generateNumber($data->number,$id),
        ]);
    }
    public function updateConfirmationStatus($id, string $status)
    {
        return Season::where('id', $id)->update(['confirmation_status' => $status]);
    }

    public function updateStatus($id, string $status)
    {
        return Season::where('id', $id)->update(['status' => $status]);
    }

    public function delete($id)
    {
        return Season::where('id', $id)->delete();
    }

    private function generateNumber($number,$id)
    {
        $courseRepo = new CourseRepo();
        if(is_null($number)){
            $number = $courseRepo->findById($id)->seasons()->orderBy('number','desc')->firstOrNew()->number ? : 0;
            $number++;
        }
        return $number;
    }
}
