<?php

namespace app\modules\rest\models;

use app\modules\clickhouse\models\CHBaseModel;
use app\modules\rest\helpers\DataHelper;
use app\modules\rest\helpers\DateHelper;
use GeoIp2\Database\Reader;
use Tinderbox\Clickhouse\Query;
use Tinderbox\ClickhouseBuilder\Query\Builder;
use Tinderbox\ClickhouseBuilder\Query\Expression;
use Tinderbox\ClickhouseBuilder\Query\From;

class Monit extends CHBaseModel
{
    public static function total($byPlatform = null)
    {
        $query = self::find()->from('stat')
            ->select([raw('toDate(day_begin) as date'), raw('count(*) as ctn')])
            ->where('day_begin', '>=', DateHelper::supportInterfaceBy(DateHelper::INTERFACE_WEEK)->getTimestamp())
            ->groupBy('day_begin');

        if ($byPlatform !== null && in_array($byPlatform, ['ios', 'android', 'smart'])) {
            $query->where('platform', '=', $byPlatform);
        }

        return self::execute($query);
    }

    public static function autonomousSystems(int $timestamp = 0, bool $byHour = false)
    {
        $query = self::find()
            ->select([
                raw('COUNT(ip) as countIP'),
                raw("groupArray([
                    toString(ip), toString(dictGetUInt32('geoip_asn_blocks_ipv4', 'autonomous_system_number', tuple(IPv4StringToNum(ip)))),
                    toString(dictGetString('geoip_city_locations_en', 'country_name', toUInt64(dictGetUInt32('geoip_city_blocks_ipv4', 'geoname_id', tuple(IPv4StringToNum(ip)))))),
                    toString(dictGetString('geoip_city_locations_en', 'city_name', toUInt64(dictGetUInt32('geoip_city_blocks_ipv4', 'geoname_id', tuple(IPv4StringToNum(ip))))))
                ]) as gr"),
                raw("dictGetString('geoip_asn_blocks_ipv4', 'autonomous_system_organization', tuple(IPv4StringToNum(ip))) AS autonomous_system_organization"),
            ])->from(function (From $from) use ($timestamp)  {
                $from->query()
                    ->select(raw('DISTINCT ip'))
                    ->from('stat')
                    ->where('day_begin', '>=',  $timestamp)
                    ->where('ip', '!=', 'NULL');
            })
            ->groupBy(['autonomous_system_organization'])
            ->orderBy('countIP', 'DESC');

        return self::execute($query);
    }
}