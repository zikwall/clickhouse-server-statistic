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

        $request = Yii::$app->request;
        $year = 2019;
        $month = 10;
        $app = 'com.infolink.limeiptv';

        $data = MonitAppUsers::getMonthUsers($app, $year, $month);

        return $this->asJson([
            'timeZoneUsers' => $data
        ]);
    }
}