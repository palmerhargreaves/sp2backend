<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 16.11.2017
 * Time: 16:24
 */

namespace backend\components\helpers;


class ColorHelper
{
    public static function getColor() {
        $colors = ['deep-purple', 'purple', 'red', 'green', 'blue', 'blue-grey', 'pink', 'indigo', 'light-blue', 'grey'];

        srand();
        $color_ind = mt_rand(0, count($colors) - 1);

        return $colors[$color_ind];
    }
}
