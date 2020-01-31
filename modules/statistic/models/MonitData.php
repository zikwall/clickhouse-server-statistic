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
        'liteios',
        'limehd.ru.ctv',
        'ctvios',
        'mejortvios',
        'ru.limelime.Tizen',
        'ru.limelime.webOs',
        'ru.limelime.NetCast',
        'web',
        'all'
    ];

    const FIRST_RECORD_DATE = 1564002000;
    const FIRST_RECORD_DATE_PARTNERS = 1575158400;

    const ADS_ID = [
        'regionmedia',
        'yandex',
        'Ruform',
        'getintent',
        'videonow',
        '1xbet',
        'MyTarget',
        'hyperaudience'
    ];

    const BROADCASTERS = [
        'http://172.19.95.111:4020/api/chnls',
        'http://172.19.95.110:4020/api/chnls',
        'http://172.19.95.109:4020/api/chnls',
        'http://172.19.95.108:4020/api/chnls',
        'http://172.19.95.112:4020/api/chnls',
        'http://172.19.95.114:4020/api/chnls',
        'http://api.limehd.tv/cdn'
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

    public static function getPeriod($partner = false)
    {
        if ($partner) {
            return [self::FIRST_RECORD_DATE_PARTNERS, mktime(0,0,0)];
        }

        return [self::FIRST_RECORD_DATE, mktime(0, 0, 0)];
    }

    public static function getAdsId()
    {
        return self::ADS_ID;
    }

    public static function isNIkkitaVesdePobrita($app)
    {
        return $app === self::APP[1] || $app === self::APP[3] || $app === self::APP[5];
    }

    public static function isSmartTv($app)
    {
        return $app === self::APP[6] || $app === self::APP[7] || $app === self::APP[8];
    }
}
