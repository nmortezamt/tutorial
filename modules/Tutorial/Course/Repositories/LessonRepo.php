<?php

namespace Tutorial\Course\Repositories;

use Illuminate\Support\Str;
use Tutorial\Course\Models\Lesson;

class LessonRepo
{
    public function store($courseId,$data)
    {
        return Lesson::query()->create([
            'title' => $data->title,
            'slug' => $data->slug ? Str::slug($data->slug) : Str::slug($data->title),
            'number' => $this->generateNumber($data->number,$courseId),
            'time' => $data->time,
            'course_id'=>$courseId,
            'user_id'=>auth()->id(),
            'season_id' => $data->season_id,
            'media_id' => $data->media_id,
            'is_free' => $data->is_free,
            'body' => $data->body,
        ]);
    }

    public function paginate($courseId)
    {
        return Lesson::where('course_id',$courseId)->orderBy('number')->paginate();
    }

    public function findById($id)
    {
        return Lesson::findOrFail($id);
    }

    public function delete($id)
    {
        return Lesson::where('id', $id)->delete();
    }

    public function update($id,$courseId,$data)
    {
        return Lesson::where('id',$id)->update([
            'title' => $data->title,
            'slug' => $data->slug ? Str::slug($data->slug) : Str::slug($data->title),
            'number' => $this->generateNumber($data->number,$courseId),
            'time' => $data->time,
            'season_id' => $data->season_id,
            'media_id' => $data->media_id,
            'is_free' => $data->is_free,
            'body' => $data->body,
        ]);
    }
    public function updateConfirmationStatus($id, string $status)
    {
        if(is_array($id)){
        return Lesson::whereIn('id', $id)->update(['confirmation_status' => $status]);
        }
        return Lesson::where('id', $id)->update(['confirmation_status' => $status]);
    }

    public function updateStatus($id, string $status)
    {
        return Lesson::where('id', $id)->update(['status' => $status]);
    }

    public function acceptAll($courseId)
    {
        return Lesson::where('course_id',$courseId)->update(['confirmation_status'=>Lesson::CONFIRMATION_STATUS_ACCEPTED]);
    }

    public function getAcceptedLessons($id)
    {
        return Lesson::where('course_id',$id)->where('confirmation_status',Lesson::CONFIRMATION_STATUS_ACCEPTED)->get();
    }

    public function getFirstLesson(int $id)
    {
        return Lesson::where('course_id',$id)->where('confirmation_status',Lesson::CONFIRMATION_STATUS_ACCEPTED)->orderBy('number','asc')->first();
    }

    public function getLesson(int $courseId , int $lessonId)
    {
        return Lesson::where('course_id',$courseId)->where('id',$lessonId)->first();
    }

    private function generateNumber($number,$id)
    {
        $courseRepo = new CourseRepo();
        if(is_null($number)){
            $number = $courseRepo->findById($id)->lessons()->orderBy('number','desc')->firstOrNew()->number ? : 0;
            $number++;
        }
        return $number;
    }
}
