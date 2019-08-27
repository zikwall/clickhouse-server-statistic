<?php
namespace app\modules\rest\controllers\ads;

use app\modules\rest\components\BaseController;
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
        $eventType = $request->post('eventType');

        return $this->asJson([
            'adsData' => [$app, $dayBegin, $dayEnd, $eventType]
        ]);
    }
}