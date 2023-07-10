<?php

namespace Tutorial\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Tutorial\User\Services\VerifyCodeService;

class ResetPasswordVerifyCodeRquest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'verify_code'=>VerifyCodeService::getRule(),
            'email'=>'required|email'
        ];
    }
}