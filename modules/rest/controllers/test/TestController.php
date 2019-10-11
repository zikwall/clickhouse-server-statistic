<?php
namespace app\modules\rest\controllers\test;

use app\modules\core\components\date\range\Month;
use app\modules\core\components\date\range\Range;
use app\modules\rest\helpers\DataHelper;
use app\modules\rest\models\Monit;
use app\modules\statistic\models\MonitAppUsers;
use app\modules\statistic\models\MonitChannels;
use Tinderbox\ClickhouseBuilder\Query\Builder;
use Tinderbox\ClickhouseBuilder\Query\From;
use Yii;
use app\modules\statistic\models\MonitData;
use app\modules\statistic\models\MonitAds;

class TestController extends \yii\rest\Controller
{
    public function actionTimestamp(int $year, int $month)
    {
        $getBy = Yii::$app->request->get('by', null);

        if ($getBy === null) {
            return $this->asJson([
                'message' => 'Empty timestamp'
            ]);
        }

        return $this->asJson([
           'timestamp' => Range::supportInterfaceBy($getBy)->getTimestamp()
        ]);
    }

    public function actionTestQueryAs()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }
        $month = 10;
        $year = 2019;
        $app = 'com.infolink.limeiptv';

        $d = new \DateTime(sprintf('%d-%s-01', $year, $month));
        $currentMonthBegin = $d->getTimestamp();

        $d->modify('first day of previous month');
        $previousMonthBegin = $d->getTimestamp();

        $d->modify('first day of next month')->modify('first day of next month');
        $nextMonthBegin = $d->getTimestamp();
        echo 'currentMonthBegin ' . $currentMonthBegin . '<br>';
        echo 'previousMonthBegin ' . $previousMonthBegin . '<br>';
        echo 'nextMonthBegin ' . $nextMonthBegin . '<br>';die;
        $query = MonitAppUsers::find()->select([
            'month_begin',
            raw('groupArray(device_id) as groupDevice')
        ])
            ->from(function (From $from) use ($previousMonthBegin, $nextMonthBegin, $app) {
                $subQuery = $from->query()
                    ->select('month_begin', 'device_id')
                    ->from('stat')
                    ->where('month_begin', '>=', $previousMonthBegin)
                    ->where('month_begin', '<', $nextMonthBegin)
                    ->where('app', '=', $app);

                if (DataHelper::isAll($app)) {
                    $subQuery->whereIn('app', MonitData::getApp(true));
                } else {
                    $subQuery->where('app', '=', $app);
                }

                $subQuery->groupBy('month_begin', 'device_id');
            })
            ->groupBy('month_begin');

        return MonitAppUsers::execute($query);
    }
}