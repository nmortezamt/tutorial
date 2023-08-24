<?php

namespace Tutorial\Payment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Tutorial\Payment\Models\Settlement;
use Tutorial\Payment\Repositories\SettlementRepo;

class SettlementRequest extends FormRequest
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
        $min = 10000;
        if(request()->getMethod() === 'PATCH'){
            $settlement = resolve(SettlementRepo::class)->findById(request()->route('settlement'));
            return [
                'from.name' =>'required_if:status,'.Settlement::STATUS_SETTLED,
                'from.card' =>'required_if:status,'.Settlement::STATUS_SETTLED,
                'to.name' =>'required_if:status,'.Settlement::STATUS_SETTLED,
                'to.card' =>'required_if:status,'.Settlement::STATUS_SETTLED,
                'status' => Rule::in(Settlement::$statuses),
                'amount' => 'required|numeric|min:'.$min
            ];
        }
        return [
            'name'=>'required|string',
            'card'=>'required|numeric',
            'amount'=>'required|numeric|min:'.$min.'|max:'.auth()->user()->balance
        ];
    }

    public function attributes()
    {
        return [
            'status'=>'وضعیت',
            'card' => 'شماره کارت',
            'amount' => 'مبلغ تسویه حساب',
        ];
    }

    public function messages()
    {
        return [
            'from.name.required_if' => 'تکمیل گزینه نام صاحب کارت فرستنده زمانی که وضعیت دارای مقدار تسویه شده است الزامی می باشد',
            'from.card.required_if' => 'تکمیل گزینه شماره کارت فرستنده زمانی که وضعیت دارای مقدار تسویه شده است الزامی می باشد',

            'to.name.required_if' => 'تکمیل گزینه نام صاحب کارت گیرنده زمانی که وضعیت دارای مقدار تسویه شده است الزامی می باشد',
            'to.card.required_if' => 'تکمیل گزینه شماره کارت گیرنده زمانی که وضعیت دارای مقدار تسویه شده است الزامی می باشد',
        ];
    }
}
