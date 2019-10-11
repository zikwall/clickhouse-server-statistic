<?php
namespace app\modules\statistic\models;

use app\modules\clickhouse\models\CHBaseModel;
use app\modules\core\components\date\range\Month;
use app\modules\rest\helpers\DataHelper;
use app\modules\statistic\models\MonitData;
use function foo\func;
use Tinderbox\Clickhouse\Query;
use Tinderbox\ClickhouseBuilder\Query\From;

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

    public static function getMonthUsers(string $app, int $year, string $month)
    {
        $month = Month::NameToNumber($month);

        $d = new \DateTime(sprintf('%d-%s-01', $year, $month));
        $currentMonthBegin = $d->getTimestamp();

        $d->modify('first day of previous month');
        $previousMonthBegin = $d->getTimestamp();

        $d->modify('first day of next month')->modify('first day of next month');
        $nextMonthBegin = $d->getTimestamp();

        $query = self::find()->select([
            'month_begin',
            raw('groupArray(device_id) as groupDevice')
        ])
            ->from(function (From $from) use ($previousMonthBegin, $nextMonthBegin, $app) {
                $subQuery = $from->query()
                    ->select('month_begin', 'device_id')
                    ->from('stat')
                    ->where('month_begin', '=>', $previousMonthBegin)
                    ->orWhere('month_begin', '<', $nextMonthBegin);
                    //->where('app', '=', $app);

                    if (DataHelper::isAll($app)) {
                        $subQuery->whereIn('app', MonitData::getApp(true));
                    } else {
                        $subQuery->where('app', '=', $app);
                    }

                $subQuery->groupBy('month_begin', 'device_id');
            })
            ->groupBy('month_begin');

        return self::execute($query);
    }

    private static $groups = [
        'android' => [
            'com.infolink.limeiptv',
            'limehd.ru.lite',
            'limehd.ru.ctv',
        ],
        'ios' => [
            'com.infolink.LimeHDTV',
            'liteios',
            'ctvios',
        ]
    ];

    public static function getUserIntersectionAndroid(int $dayBegin, int $dayEnd, string $platform)
    {
        $query = self::find()->select([
            'app', raw('groupArray(device_id) as groupDevice')
        ])->from(function (From $from) use ($dayBegin, $dayEnd, $platform) {
            $from->query()
                ->select('app', 'device_id')->from('stat')
                ->where('day_begin', '>=', $dayBegin)
                ->where('day_begin', '<=', $dayEnd)
                ->whereIn('app', self::$groups[$platform])
            ->groupBy(['app', 'device_id']);
        })->groupBy('app');

        return self::execute($query);
    }
}