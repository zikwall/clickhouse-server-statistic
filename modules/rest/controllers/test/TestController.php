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
        $app = "com.infolink.LimeHDTV";
        $dayBegin = 1565038800;
        $dayEnd = 1566853200;
        //$eventType = 'all';
        $data = MonitAds::getData($app, $dayBegin, $dayEnd/*, $eventType*/)->rows;
        $arrAdsId = MonitData::getAdsId();


        foreach($data as $k => $v) {
            if (!in_array($v['adsid'], $arrAdsId)) {
                unset($data[$k]);
            }
        }

        return $data;

        //return $this->asJson([
        //    'adsData' => MonitAds::getData($app, $dayBegin, $dayEnd/*, $eventType*/)
        //]);
    }
}