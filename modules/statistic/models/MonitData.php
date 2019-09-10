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
        'ctvios',
        'ru.limelime.Tizen',
        'ru.limelime.webOs',
        'ru.limelime.NetCast',
        'web',
        'all'
    ];

    const FIRST_RECORD_DATE = 1564002000;

    const ADS_ID = [
        'regionmedia',
        'yandex',
        'Ruform',
        'getintent',
        'videonow',
        '1xbet'
    ];

    public static function getApp($withoutAll = false)
    {
        if ($withoutAll) {
            $all = self::APP;
            unset($all[count($all)-1]);
            return $all;
        }

        return self::APP;
    }

    public static function getPeriod()
    {
        return [self::FIRST_RECORD_DATE, mktime(0, 0, 0)];
    }

    public static function getAdsId()
    {
        return self::ADS_ID;
    }

    public static function isNIkkitaVesdePobrita($app)
    {
        return $app === self::APP[1];
    }
}