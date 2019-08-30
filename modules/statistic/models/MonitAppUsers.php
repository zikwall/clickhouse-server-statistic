<?php
namespace app\modules\statistic\models;

use app\modules\clickhouse\models\CHBaseModel;

class MonitAppUsers extends CHBaseModel
{
    public static function getAppUsers($app, $dayBegin, $dayEnd, $eventType)
    {
        return true;
    }
}