<?php

namespace app\modules\rest\helpers;

use \Closure;

class DataHelper
{
    public const ALL = 'all';

    public static function sortByColumn(&$arr, $col, $dir = SORT_ASC)
    {
        $sort_col = [];
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
    }

    final public static function isAll($filed, Closure $callback = null)
    {
        return $filed === self::ALL;
    }
}