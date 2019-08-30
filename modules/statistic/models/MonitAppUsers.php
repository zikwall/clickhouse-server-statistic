<?php
namespace app\modules\statistic\models;

use app\modules\clickhouse\models\CHBaseModel;
use app\modules\rest\helpers\DataHelper;

class MonitAppUsers extends CHBaseModel
{
    public static function getAppUsersGroupByDay($app, $dayBegin, $dayEnd, $eventType)
    {
        $query = self::find()
            ->select(['day_begin', raw('COUNT(DISTINCT device_id) as ctn')])
            ->from('stat')
            ->where('day_begin', '>=', $dayBegin)
            ->where('day_begin', '<=', $dayEnd)
            ->groupBy('day_begin');

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
}