<?php
namespace app\modules\rest\controllers\ads;

use app\modules\rest\components\BaseController;
use app\modules\statistic\models\MonitAds;
use app\modules\statistic\models\MonitData;
use Yii;

class AdsController extends BaseController
{
    public function beforeAction($action): bool
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        return parent::beforeAction($action);
    }

    public function actionGetAdsData()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        $request = Yii::$app->request;
        $app = $request->post('app');
        $dayBegin = $request->post('dayBegin');
        $dayEnd = $request->post('dayEnd');
        //$eventType = $request->post('eventType');
        $data = MonitAds::getData($app, $dayBegin, $dayEnd/*, $eventType*/)->rows;
        $arrAdsId = MonitData::getAdsId();

        foreach($data as $k => $v) {
            if (!in_array($v['adsid'], $arrAdsId)) {
                unset($data[$k]);
            }
        }


        return $this->asJson([
            'adsData' => $data
        ]);
    }
}