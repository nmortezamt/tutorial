<?php

namespace Tutorial\Common\Responses;

use Illuminate\Http\Response;

class AjaxResponses
{
    public static function SuccessResponse()
    {
        return response()->json(['message'=>'عملیات با موفقیت انجام شد'],Response::HTTP_OK);
    }
    public static function FailedResponse()
    {
        return response()->json(['message'=>'عملیات موفقیت آمیز نیود'],Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
