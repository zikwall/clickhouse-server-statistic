<?php
namespace app\modules\statistic\models;

use app\modules\clickhouse\models\CHBaseModel;
use app\modules\rest\helpers\DateHelper;
use Tinderbox\ClickhouseBuilder\Query\From;

class MonitData extends CHBaseModel
{
    const APP = [
        'com.infolink.limeiptv',
        'com.infolink.LimeHDTV',
        'limehd.ru.lite',
        'limehd.ru.ctv',
        'ru.limelime.Tizen',
        'ru.limelime.webOs',
        'ru.limelime.NetCast',
        'web',
        'other',
        'all'

    ];

    public static function getApp()
    {
        return self::APP;
    }
}