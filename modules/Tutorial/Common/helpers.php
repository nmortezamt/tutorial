<?php

namespace Tutorial\Common;

use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

if (!function_exists('newFeedbacks')) {
    function newFeedbacks($title = 'عملیات موفق', $body = 'عملیات با موفقیت انجام شد', $type = 'success')
    {
        $session = session()->has('feedbacks') ? session()->get('feedbacks') : [];
        $session[] = ['title' => $title, 'body' => $body, 'type' => $type];
        session()->flash('feedbacks', $session);
    }

    if (!function_exists('dateFromJalali')) {
        function dateFromJalali($date, $format = 'Y/m/d')
        {
            try {
                return $date ? Jalalian::fromFormat($format, $date)->toCarbon() : null;
            } catch (\Exception $e) {
                return ["status"=>'invalid','message'=>$e->getMessage()];
            }
            // return $date ? Jalalian::fromFormat($format, $date)->toCarbon() : null;
        }
    }

    if (!function_exists('getJalaliFromFormat')) {
        function getJalaliFromFormat($date, $format = 'Y-m-d')
        {
            return $date ? Jalalian::fromCarbon(Carbon::createFromFormat($format,$date))->format($format) : null;
        }
    }

    if (!function_exists('createFromCarbon')) {
        function createFromCarbon(Carbon $date)
        {
            return $date ? Jalalian::fromCarbon($date) : '-';
        }
    }
}
