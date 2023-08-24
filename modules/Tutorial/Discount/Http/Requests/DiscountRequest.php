<?php

namespace Tutorial\Discount\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Tutorial\Discount\Rules\ValidJalaliDate;

class DiscountRequest extends FormRequest
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
        $rules = [
            'percent' => 'required|numeric|min:10|max:100',
            'code' => 'nullable|unique:discounts,code|min:3|max:50',
            'usage_limitation' => 'nullable|numeric|min:0|max:1000000000',
            'expire_at' => ['nullable',new ValidJalaliDate()],
            'courses' => 'nullable|array',
            'type' => 'required'
        ];
        if(request()->getMethod() == 'PATCH'){
            $rules['code'] = 'nullable|min:3|max:50|unique:discounts,code,'.request()->route('discount');
        }
        return $rules;
    }
    public function attributes()
    {
        return [
            'percent' => 'درصد مدرس',
            'code' => 'کد تخفیف',
            'usage_limitation' => 'محدودیت افراد ',
            'expire_at' => 'تاریخ منقضی',
            'courses' => 'دوره ها',
            'type' => 'نوع تخفیف'
        ];
    }
}
