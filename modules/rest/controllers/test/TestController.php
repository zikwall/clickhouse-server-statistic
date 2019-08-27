<?php
namespace app\modules\rest\controllers\test;

use app\modules\core\components\date\range\Range;
use app\modules\rest\models\Monit;
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
        $app = "ru.limelime.NetCast";
        $dayBegin = 1565038800;
        $dayEnd = 1566853200;
        $eventType = 0;

        return $this->asJson([
            'adsData' => MonitAds::getData($app, $dayBegin, $dayEnd, $eventType)
        ]);
    }
}