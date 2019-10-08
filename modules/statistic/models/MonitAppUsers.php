<?php
namespace app\modules\statistic\models;

use app\modules\clickhouse\models\CHBaseModel;
use app\modules\rest\helpers\DataHelper;
use app\modules\statistic\models\MonitData;

class MonitAppUsers extends CHBaseModel
{
    public static function getAppUsersGroupByDay($app, $dayBegin, $dayEnd, $eventType)
    {
        $query = self::find();

            if (MonitData::isSmartTv($app)) {
                $query->select(['day_begin', raw('COUNT(DISTINCT guid) as ctn')]);
            } else {
                $query->select(['day_begin', raw('COUNT(DISTINCT device_id) as ctn')]);
            }

            $query->from('stat')
            ->where('day_begin', '>=', $dayBegin)
            ->where('day_begin', '<=', $dayEnd)
            ->groupBy('day_begin')
            ->orderBy('day_begin' );

        if (DataHelper::isAll($app)) {
            $query->whereIn('app', MonitData::getApp(true));
        } else {
            $query->where('app', '=', $app);
        }

        if (DataHelper::isAll($eventType)) {
            $query->whereIn('evtp', [0, 1]);
        } else {
            $query->where('evtp', '=', $eventType);
        }

        return self::execute($query);
    }

    public static function getAppUsersGroupByTotal($app, $dayBegin, $dayEnd, $eventType)
    {
        $query = self::find()->select([raw('COUNT(DISTINCT device_id) as ctn')])
                ->from('stat')
                ->where('day_begin', '>=', $dayBegin)
                ->where('day_begin', '<=', $dayEnd);

        if (DataHelper::isAll($app)) {
            $query->whereIn('app', MonitData::getApp(true));
        } else {
            $query->where('app', '=', $app);
        }

        if (DataHelper::isAll($eventType)) {
            $query->whereIn('evtp', [0, 1]);
        } else {
            $query->where('evtp', '=', $eventType);
        }

        return self::execute($query);
    }

    public static function getAllTimezones()
    {
        return [
            '-11', '-10', '-9', '-8', '-7', '-6', '-5', '-4', '-3', '-2', '-1',
            '0',
            '1', '2', '3', '4', '5', '5.5', '6', '7', '8', '9', '10', '11', '12'
        ];
    }

    public static function getTimeZoneUsers($app, $dayBegin, $dayEnd, $eventType)
    {
        $query = self::find()->select([
            'tz', raw('COUNT(DISTINCT device_id) as ctn')
        ])->from('stat')
            ->where('day_begin', '>=', $dayBegin)
            ->where('day_begin', '<=', $dayEnd)
            ->whereIn('tz', self::getAllTimezones())
            ->groupBy('tz');

        if (DataHelper::isAll($app)) {
            $query->whereIn('app', MonitData::getApp(true));
        } else {
            $query->where('app', '=', $app);
        }

        if (DataHelper::isAll($eventType)) {
            $query->whereIn('evtp', [0, 1]);
        } else {
            $query->where('evtp', '=', $eventType);
        }

        return self::execute($query);
    }
}