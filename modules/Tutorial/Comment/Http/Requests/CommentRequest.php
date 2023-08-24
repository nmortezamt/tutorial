<?php

namespace Tutorial\Comment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Tutorial\Comment\Rules\ApprovedCommentRule;
use Tutorial\Comment\Rules\CommentableRule;

class CommentRequest extends FormRequest
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
            'body' => 'required|string',
            'comment_id' => ['nullable', new ApprovedCommentRule()],
            'commentable_id' => 'required',
            'commentable_type' => ['required', new CommentableRule()]
        ];
    }

    public function attributes()
    {
        return [
            'body'=>'دیدگاه'
        ];
    }
}
