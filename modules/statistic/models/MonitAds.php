<?php
namespace app\modules\statistic\models;

use app\modules\clickhouse\models\CHBaseModel;
use app\modules\core\components\date\range\DateRangeInterface;
use app\modules\core\components\date\range\Range;
use app\modules\rest\helpers\DateHelper;
use app\modules\rest\models\Monit;
use Tinderbox\ClickhouseBuilder\Query\Builder;
use Tinderbox\ClickhouseBuilder\Query\From;

class MonitAds extends CHBaseModel
{
    /**
     * @param $app
     * @param $dayBegin
     * @param $dayEnd
     * @param $eventType
     * @return \Tinderbox\Clickhouse\Query\Result
     * @throws \yii\base\InvalidConfigException
     */
    public static function getData($app, $dayBegin, $dayEnd, $eventType)
    {
        $time = Range::supportInterfaceBy(DateRangeInterface::INTERFACE_WEEK);
        $isAllApp = false;
        $isAllEventType = false;

        if ($app === 'all') {
            $isAllApp = true;
        }

        if ($eventType === 'all') {
            $isAllEventType = true;
        }

        $query = Monit::find()
            ->select([
                'adsid',
                raw('groupArray([toString(adsst), toString(countAdsst)]) as groupData')
            ])
            ->from(function (From $from) use ($isAllApp, $isAllEventType, $dayBegin, $dayEnd, $app, $eventType) {
                $from->query()->select([
                    'adsid',
                    'adsst',
                    raw('COUNT(adsst) as countAdsst'),
                ])->from('stat')

                    ->where(function (Builder $query) use ($isAllApp, $app) {
                        if ($isAllApp) {
                            $query->whereIn('app', MonitData::getApp(true));
                        } else {
                            $query->where('app', '=', trim($app));
                        }
                    })

                    // not supported, because EVTP == 666666
                    /*->where(function (Builder $query) use ($isAllEventType, $eventType) {
                        if ($isAllEventType) {
                           $query
                               ->orWhere('evtp', '=', 0)
                               ->orWhere('evtp', '=', 1);
                        } else {
                            $query->where('evtp', '=', $eventType);
                        }
                    })*/

                    ->where(function (Builder $query) {
                        $query
                            ->where('adsid', '!=', '')
                            ->where('adsid', '!=', 'NULL')
                            ->where('adsid', '!=', 'null')
                            ->where('adsst', '!=', 'NULL');
                    })

                    ->where(function (Builder $query) use ($dayBegin, $dayEnd) {
                        $query
                            ->where('day_begin', '>=', $dayBegin)
                            ->where('day_begin', '<=', $dayEnd);
                    })


                    ->groupBy('adsid', 'adsst');
            })->groupBy('adsid');

        return $query->toSql();
        //return self::execute($query);
    }
}