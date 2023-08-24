<?php

namespace Tutorial\Discount\Services;

class DiscountService
{
    public static function calculateDiscountAmount($price,$percent)
    {
        return $price * ((float) ("0.".$percent));
    }
}
