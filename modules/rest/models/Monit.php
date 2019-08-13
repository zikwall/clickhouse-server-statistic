<?php

namespace app\modules\rest\models;

use app\modules\clickhouse\models\CHBaseModel;
use app\modules\rest\helpers\DataHelper;
use app\modules\rest\helpers\DateHelper;
use GeoIp2\Database\Reader;
use Tinderbox\ClickhouseBuilder\Query\Expression;

class Monit extends CHBaseModel
{
    public static function total($byPlatform = null)
    {
        $query = self::find()->from('stat')
            ->select([raw('toDate(day_begin) as date'), raw('count(*) as ctn')])
            ->groupBy('day_begin')
            ->orderBy('day_begin', 'DESC')
            ->limit(7);

        if ($byPlatform !== null && in_array($byPlatform, ['ios', 'android', 'smart'])) {
            $query->where('platform', '=', $byPlatform);
        }

        return self::execute($query);
    }

    public static function getExample()
    {
        // get count users by channels
        $query = self::find()->select(['vcid', raw('count(*) as ctn')])
            ->from('stat')
            ->where('created_at', '>=', mktime(0,0,0))
            ->where('app', 'com.infolink.limeiptv')
            ->groupBy('vcid');

        return self::execute($query);
    }

    /**
     * @throws \MaxMind\Db\Reader\InvalidDatabaseException
     */
    public static function asn()
    {
        $reader = new Reader(dirname(dirname(__DIR__)) . '/core/geo/GeoLite2-ASN.mmdb');
        $cityReader = new Reader(dirname(dirname(__DIR__)) . '/core/geo/GeoLite2-City.mmdb');

        $itemAsn = [
            'as' => null,
            'org' => null,
            'country' => null,
            'city' => null
        ];

        $asnResult = [
            'ASN' => [],
            'countOfAsn' => 0
        ];

        $query = self::find()
            ->select(['ip', 'created_at', new Expression('COUNT(ip) as countAsn')])
            ->from('stat')
            ->where('created_at', '>=', DateHelper::getStartOfDay())
            ->groupBy(['ip', 'created_at'])
            ->limit(10000);

        $records = self::execute($query);

        foreach ($records->getRows() as $record) {
            if (empty($record['ip'])) {
                continue;
            }

            try {
                $record_as = $reader->ASN($record['ip']);
                $recordCity = $cityReader->city($record['ip']);
            } catch (\GeoIp2\Exception\AddressNotFoundException $e) {
                unset($record_as);
                unset($recordCity);
            }

            /**
             * Пропускаем, если не найден ASN
             */
            if (!isset($record_as)) {
                continue;
            }

            $itemAsn = [
                'as' => $record_as->autonomousSystemNumber,
                'org' => $record_as->autonomousSystemOrganization,
            ];

            /**
             * Дополнительная информация
             */
            if (isset($recordCity)) {
                $itemAsn['city'] = $recordCity->city->name;
                $itemAsn['country'] = $recordCity->country->name;
                $asnResult['countOfAsn']++;
            }

            if (!isset($asnResult['ASN'][$itemAsn['as']])) {
                $asnResult['ASN'][$itemAsn['as']]['asn'] = $itemAsn;
                $asnResult['ASN'][$itemAsn['as']]['count'] = 0;
            }

            $asnResult['ASN'][$itemAsn['as']]['data'][] = $record['ip'];
            $asnResult['ASN'][$itemAsn['as']]['count'] += 1;
        }

        DataHelper::sortByColumn($asnResult['ASN'], 'count', SORT_DESC);

        return $asnResult;
    }
}