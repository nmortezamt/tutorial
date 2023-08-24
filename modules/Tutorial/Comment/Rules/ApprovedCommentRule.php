<?php

namespace Tutorial\Comment\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Tutorial\Comment\Repositories\CommentRepo;

class ApprovedCommentRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $commentRepo = new CommentRepo();
        if(is_null($commentRepo->findApproved($value)))
        $fail('this comment there not');
    }
}
