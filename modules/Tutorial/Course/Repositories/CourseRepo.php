<?php

namespace Tutorial\Course\Repositories;

use Tutorial\Course\Models\Course;
use Illuminate\Support\Str;
use Tutorial\Course\Models\Lesson;

class CourseRepo
{
    public function store($data)
    {
        return Course::query()->create([
            'title' => $data->title,
            'slug' => Str::slug($data->slug),
            'priority' => $data->priority,
            'price' => $data->price,
            'teacher_percent' => $data->teacher_percent,
            'teacher_id' => $data->teacher_id,
            'type' => $data->type,
            'status' => $data->status,
            'category_id' => $data->category_id,
            'banner_id' => $data->banner_id,
            'body' => $data->body,
        ]);
    }

    public function paginate()
    {
        return Course::query()->latest()->paginate();
    }

    public function getAll(string $status = null)
    {
        $query = Course::query();
        if($status) $query->where('confirmation_status',$status);
        return $query->latest()->get();
    }

    public function findById($id)
    {
        return Course::findOrFail($id);
    }

    public function getCourseByTeacherId($id)
    {
        return Course::where('teacher_id', $id)->get();
    }

    public function delete($id)
    {
        return Course::where('id', $id)->delete();
    }

    public function update($id, $data)
    {
        return Course::where('id', $id)->update([
            'title' => $data->title,
            'slug' => Str::slug($data->slug),
            'priority' => $data->priority,
            'price' => $data->price,
            'teacher_percent' => $data->teacher_percent,
            'teacher_id' => $data->teacher_id,
            'type' => $data->type,
            'status' => $data->status,
            'category_id' => $data->category_id,
            'banner_id' => $data->banner_id,
            'body' => $data->body,
        ]);
    }
    public function updateConfirmationStatus($id, string $status)
    {
        return Course::where('id', $id)->update(['confirmation_status' => $status]);
    }

    public function updateStatus($id, string $status)
    {
        return Course::where('id', $id)->update(['status' => $status]);
    }

    public function addStudentToCourse(Course $course, $studentId)
    {
        if (!$this->getCourseStudentById($course, $studentId))
            $course->students()->attach($studentId);
    }

    public function getCourseStudentById(Course $course, $studentId)
    {
        return $course->students()->where('user_id', $studentId)->first();
    }

    public function latestCourses()
    {
        return Course::where('confirmation_status', Course::CONFIRMATION_STATUS_ACCEPTED)->latest()->take(8)->get();
    }

    public function getDuration($id)
    {
        return $this->getLessonQuery($id)->sum('time');
    }

    public function getLessonsCount($id)
    {
        return $this->getLessonQuery($id)->count();
    }

    public function hasStudent(Course $course, $studentId)
    {
        return $course->students->contains($studentId);
    }

    public function getLessons($courseId)
    {
        return $this->getLessonQuery($courseId)->get();
    }

    private function getLessonQuery($id)
    {
        return Lesson::where('course_id', $id)->where('confirmation_status', Lesson::CONFIRMATION_STATUS_ACCEPTED);
    }
}
