<?php

namespace Tutorial\Course\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Tutorial\Course\Models\Course;
use Tutorial\Course\Rules\ValidTeacher;

class CourseRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() == true;
    }

    public function rules()
    {
        $rules = [
            'title' => 'required|min:3|max:180',
            'slug' => 'required|min:3|max:180|unique:courses,slug',
            'priority' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0|max:10000000',
            'teacher_percent' => 'required|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0|max:10000000',
            'teacher_id' => ['required', 'exists:users,id', new ValidTeacher()],
            'type' => ['required', Rule::in(Course::$types)],
            'status' => ['required', Rule::in(Course::$statuses)],
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:png,jpg,jpeg'
        ];
        if(request()->method() === 'PATCH'){
            $rules['image'] = 'nullable|image|mimes:png,jpg,jpeg';
            $rules['slug'] = 'required|min:3|max:180|unique:courses,slug,'.request()->route('course');
        }
        return $rules;
    }

    public function attributes()
    {
        return [
            'price' => 'قیمت',
            'priority' => 'ردیف دوره',
            'discount' => 'تخفیف قیمت',
            'teacher_percent' => 'درصد مدرس',
            'teacher_id' => 'مدرس',
            'category_id' => 'دسته بندی',
            'type' => 'نوع دوره',
            'status' => 'وضعیت دوره',
            'body' => 'توضیح دوره',
        ];
    }

    public function messages()
    {
        return [
            // 'priority.numeric' => trans('Courses::Validation.priority_numeric'),
        ];
    }
}
