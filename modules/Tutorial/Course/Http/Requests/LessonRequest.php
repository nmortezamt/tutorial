<?php

namespace Tutorial\Course\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Tutorial\Course\Rules\ValidSeason;
use Tutorial\Media\Services\MediaFileService;

class LessonRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() == true;
    }

    public function rules()
    {
        $rules = [
            'title' => 'required|min:3|max:180',
            'slug' => 'nullable|min:3|max:180',
            'number' => 'nullable|numeric|min:0',
            'time' => 'required|numeric|min:0',
            'season_id' => ['nullable', new ValidSeason()],
            'is_free' => 'required|boolean',
            'lesson_file' => 'required|file|mimes:'.MediaFileService::getExtensions()
        ];
        if(request()->method() === 'PATCH'){
            $rules['lesson_file'] = 'nullable|file|mimes:'.MediaFileService::getExtensions();
        }
        return $rules;
    }

    public function attributes()
    {
        return [
            'title' => 'عنوان درس',
            'slug' => 'نام انگلیسی درس',
            'number' => 'ردیف درس',
            'time' => 'مدت زمان درس',
            'season_id' => 'سرفصل درس',
            'is_free' => 'رایگان',
            'lesson_file' => 'فایل درس'
        ];
    }

}
