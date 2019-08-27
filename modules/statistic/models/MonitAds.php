<?php
namespace app\modules\statistic\models;

use app\modules\clickhouse\models\CHBaseModel;
use app\modules\rest\helpers\DateHelper;
use Tinderbox\ClickhouseBuilder\Query\From;

class MonitAds extends CHBaseModel
{
    public static function getData($app, $dayBegin, $dayEnd, $eventType)
    {

    }
}