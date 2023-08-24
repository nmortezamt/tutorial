<?php

namespace Tutorial\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserPhotoRequest extends FormRequest
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
        return [
            'user_photo'=>'required|image|mimes:png,jpg,jpeg'
        ];
    }

    public function attributes()
    {
        return [
            'user_photo' => 'عکس پروفایل'
        ];
    }
}
