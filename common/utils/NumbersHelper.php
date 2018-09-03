<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 17.06.2017
 * Time: 16:24
 */

namespace common\utils;


class NumbersHelper
{
    public static function numberEnd($number, $titles)
    {
        $cases = array(2, 0, 1, 1, 1, 2);

        return $titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
    }
}
