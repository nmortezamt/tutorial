<?php

namespace Tutorial\Discount\Services;
use Illuminate\Support\Str;

class SettingDate
{
    public static function setJalaliDate(string $date)
    {
        $year = Str::before($date,'/');
        $month = Str::before(Str::after($date,'/'),'/');
        $day = Str::afterLast(Str::before($date,' '),'/');
        $hour = Str::after(Str::before($date,':'),' ');
        $minute = Str::before(Str::after($date,':'),' ');
        if($day <= 9 && strlen($day) < 2) $day = '0'.$day;
        if($month <= 9 && strlen($month) < 2) $month = '0'.$month;
        if($hour <= 9 && strlen($hour) < 2) $hour = '0'.$hour;
        if($minute <= 9 && strlen($minute) < 2) $minute = '0'.$minute;
        $duration = $year.'/'.$month.'/'.$day;
        if(strpos($date,' ') && strpos($date,':'))
        $duration .= ' '.$hour.':'.$minute;

        return $duration;
    }
}
