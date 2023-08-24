<?php

namespace Tutorial\Ticket\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Tutorial\Media\Services\MediaFileService;

class TicketRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() == true;
    }

    public function rules()
    {
        return [
            'title' => 'required|min:3|max:180',
            'body' => 'required',
            'attachment' => 'nullable|file|mimes:'.MediaFileService::getExtensions().'|max:102400'
        ];

    }

    public function attributes()
    {
        return [
            'title' => 'عنوان تیکت',
            'body' => 'متن تیکت',
            'attachment' => 'فایل پیوست تیکت',
        ];
    }

}
