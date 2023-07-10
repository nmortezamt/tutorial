<?php

namespace Tutorial\User\Services;

use Illuminate\Support\Facades\Cache;

class VerifyCodeService
{
    private static $min = 100000;
    private static $max = 999999;
    private static $prefix = 'verify_code_';

    public static function generate()
    {
        return random_int(self::$min,self::$max);
    }

    public static function store($id,$code,$time)
    {
        $cache = Cache::put(self::$prefix.$id,$code,$time);
    }

    public static function get($id)
    {
        return Cache::get(self::$prefix.$id);
    }

    public static function has($id)
    {
        return Cache::has(self::$prefix.$id);
    }

    public static function delete($id)
    {
        return Cache::delete(self::$prefix.$id);
    }

    public static function getRule()
    {
        return 'required|numeric|between:'.self::$min.','.self::$max;
    }

    public static function check($id,$code)
    {
        if(self::get($id) != $code)
        return false;

        self::delete($id);
        return true;
    }
}
