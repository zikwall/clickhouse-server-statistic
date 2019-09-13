<?php
namespace app\modules\rest\controllers\test;

use app\modules\core\components\date\range\Range;
use app\modules\rest\models\Monit;
use app\modules\statistic\models\MonitAppUsers;
use app\modules\statistic\models\MonitChannels;
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

        $app = "all";
        $dayBegin = 1567285200;
        $dayEnd = 1567544400;

        return MonitAppUsers::getTimeZoneUsers($app, $dayBegin, $dayEnd);
    }
}