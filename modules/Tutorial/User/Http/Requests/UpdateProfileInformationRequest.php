<?php

namespace Tutorial\User\Http\Requests;

use Tutorial\User\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Tutorial\RolePermissions\Models\Permission;
use Tutorial\User\Rules\ValidMobile;
use Tutorial\User\Rules\ValidPassword;

class UpdateProfileInformationRequest extends FormRequest
{

    public function authorize(): bool
    {
        return auth()->check() == true;
    }
    public function rules(): array
    {
        $rules = [
            'name' => 'required|min:3|max:180',
            'email' => 'required|email|unique:users,email,'.auth()->id(),
            'mobile' => ['nullable','unique:users,mobile,'.auth()->id(),new ValidMobile],
            'username'=>'nullable|unique:users,username,'.auth()->id(),
            'password'=>['nullable',new ValidPassword()]
        ];

        if(auth()->user()->hasPermissionTo(Permission::PERMISSION_MANAGE_TEACH))
        {
            $rules += [
                'card_number'=>'required|string|size:16',
                'shaba'=>'required|string|size:24',
                'headLine'=>'required|min:3|max:60',
                'bio'=>'required'
            ];
            
            $rules['username'] = ['required',Rule::unique(User::class,'username')->ignore($this->user()->id)];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'card_number' => 'شماه کارت بانکی',
            'shaba' => 'شماه کارت شبا',
            'headLine' => 'عنوان',
            'bio' => 'بیوگرافی',
            'mobile' => 'شماره موبایل',
        ];
    }
}
