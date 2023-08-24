<?php

namespace Tutorial\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Tutorial\User\Models\User;
use Tutorial\User\Rules\ValidMobile;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() == true;
    }

    public function rules()
    {
        return [
            'name' => 'required|min:3|max:180',
            'email' => 'required|email|unique:users,email,'.request()->route('user'),
            'mobile' => ['nullable','unique:users,mobile,'.request()->route('user'),new ValidMobile()],
            'username'=>'nullable|unique:users,username,'.request()->route('user'),
            'status' => ['required', Rule::in(User::$statuses)],
            'image' => 'nullable|image|mimes:png,jpg,jpeg',
            'bio'=>'nullable|min:5|max:450'
        ];
    }

    public function attributes()
    {
        return [
            'mobile' => 'شماره کاربر',
            'status' => 'وضعیت کاربر',
            'image' => 'عکس کاربر',
            'bio' => 'بیوگرافی',
        ];
    }


}
