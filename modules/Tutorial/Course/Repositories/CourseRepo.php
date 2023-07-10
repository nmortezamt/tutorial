<?php

namespace Tutorial\Course\Repositories;

use Tutorial\Course\Models\Course;
use Illuminate\Support\Str;

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
            'discount' => $data->discount,
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
        return Course::paginate();
    }

    public function findById($id)
    {
        return Course::findOrFail($id);
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
            'discount' => $data->discount,
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
}
