<?php

namespace Tutorial\Slider\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SlideRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() == true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'image' => ['required', 'image', 'max:3072'],
            'priority' => ['nullable', 'numeric', 'min:0'],
            'link' => ['nullable','string'],
            'status' => ['required','boolean'],
        ];
        if(request()->getMethod() === 'PATCH'){
            $rules['image'] = 'nullable|image|max:3072';
        }
        return $rules;
    }

    public function attributes()
    {
        return [
            'image' => 'عکس اسلایدر',
            'priority' => 'اولویت اسلایدر',
            'link' => 'لینک اسلایدر',
            'status' => 'وضعیت اسلایدر',
        ];
    }
}
