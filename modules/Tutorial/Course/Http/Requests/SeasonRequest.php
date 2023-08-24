<?php

namespace Tutorial\Course\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class SeasonRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() == true;
    }

    public function rules()
    {
        return [
            'title' => 'required|min:3|max:150',
            'number' => 'nullable|numeric|min:0|max:250',
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'عنوان فصل',
            'number' => 'ردیف فصل',
        ];
    }

}
