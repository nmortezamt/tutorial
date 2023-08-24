<?php

namespace Tutorial\Discount\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Morilog\Jalali\Jalalian;
use Tutorial\Discount\Services\SettingDate;

class ValidJalaliDate implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
        Jalalian::fromFormat('Y/m/d H:i',SettingDate::setJalaliDate($value))->toCarbon();
        } catch (\Exception $e) {
            $fail('یک تاریخ معتبر شمسی انتخاب کنید');
        }
    }
}
