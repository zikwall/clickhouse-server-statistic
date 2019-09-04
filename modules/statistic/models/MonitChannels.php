<?php
namespace app\modules\statistic\models;

use app\modules\clickhouse\models\CHBaseModel;
use app\modules\rest\helpers\DataHelper;
use Tinderbox\ClickhouseBuilder\Query\Builder;
use Tinderbox\ClickhouseBuilder\Query\From;

class MonitChannels extends CHBaseModel
{
    public static function getChannelsViewDuration($app, $dayBegin, $dayEnd)
    {
        $query = self::find()
            ->select([
                'vcid',
                raw('groupArray([evtp, ctnarch, ctnonline]) as groupData')
            ])
            ->from(function (From $from) use ($app, $dayBegin, $dayEnd) {
                $from = $from->query();

                $from
                    ->select([
                        'vcid',
                        'evtp',
                        raw('countIf(evtp = 0) as ctnarch'),
                        raw('countIf(evtp = 1) as ctnonline')
                    ])
                    ->from('stat')
                    ->where(function (Builder $query) use ($app) {
                        if (DataHelper::isAll($app)) {
                            $query->whereIn('app', MonitData::getApp(true));
                        } else {
                            $query->where('app', '=', $app);
                        }
                    })

                    ->where('day_begin', '>=', $dayBegin)
                    ->where('day_begin', '<=', $dayEnd)
                    //->where('adsst', '=', 'NULL')
                    ->where('evtp', '!=', 666666)
                    ->groupBy(['vcid', 'evtp']);
            })
        ->groupBy(['vcid']);

        return self::execute($query);
    }

    public static function getStartChannels($app, $dayBegin, $dayEnd)
    {

    }

    public static function getChannelsUniqUsers($app, $dayBegin, $dayEnd)
    {

    }

    public static function getStartApp($app, $dayBegin, $dayEnd)
    {

    }
}