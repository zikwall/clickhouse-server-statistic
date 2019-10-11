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
        ini_set('memory_limit', '6144M');
        ini_set('max_execution_time', '600');

        $dayBegin = 1567296000;
        $dayEnd = 1569888000;
        $platform = 'android';

        $data = MonitAppUsers::getUserIntersectionAndroid($dayBegin, $dayEnd, $platform);
    }
}