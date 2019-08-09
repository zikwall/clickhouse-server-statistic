<?php

namespace app\modules\rest\helpers;

class DateHelper
{
    public static function getStartOfDay() : int
    {
        return mktime(0, 0, 0);
    }
}