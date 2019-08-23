<?php
namespace app\modules\rest\controllers\general_data;

use app\modules\rest\components\BaseController;
use app\modules\statistic\models\MonitData;
use Yii;

class GeneralDataController extends BaseController
{
    public function beforeAction($action): bool
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        return parent::beforeAction($action);
    }

    public function actionGetApp()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        return $this->asJson([
            'app' => MonitData::getApp()
        ]);
    }
}