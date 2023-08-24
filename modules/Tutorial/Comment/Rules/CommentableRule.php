<?php

namespace Tutorial\Comment\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CommentableRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(! class_exists($value) && method_exists($value,'comments'))
        $fail('کرم نریز please');
    }
}
