<?php

namespace Tutorial\Ticket\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Tutorial\Media\Services\MediaFileService;

class ReplyRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() == true;
    }

    public function rules()
    {
        return [
            'body' => 'required',
            'attachment' => 'nullable|file|mimes:'.MediaFileService::getExtensions().'|max:10240'
        ];

    }

    public function attributes()
    {
        return [
            'body' => 'متن تیکت',
            'attachment' => 'فایل پیوست تیکت',
        ];
    }

}
