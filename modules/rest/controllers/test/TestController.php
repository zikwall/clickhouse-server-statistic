<?php
namespace app\modules\rest\controllers\test;

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
    public function actionTimestamp()
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

        $dayBegin = 1567285200;
        $dayEnd = 1567544400;

        $query = MonitChannels::find()->select(['app', raw('COUNT(*) as ctn')])->from('stat')
            ->where('day_begin', '>=', $dayBegin)
            ->where('day_begin', '<=', $dayEnd)
            ->where('action', '=', 'start-app')
            //->where('window', '=', 'loading')
            ->whereIn('app', MonitData::getApp(true))
            ->where('action', '=', 'start-app')
            ->groupBy(['app']);

        return MonitChannels::execute($query);

        echo '<pre>';
        print_r($channelsUniqUsersWithEvtp);
        //print_r(MonitChannels::execute($query));

        //return MonitChannels::execute($query);

        //return MonitAppUsers::getTimeZoneUsers($app, $dayBegin, $dayEnd);
    }
}