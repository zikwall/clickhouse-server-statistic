<?php

namespace app\modules\rest\controllers\autonomoussystems;

use app\modules\rest\components\BaseController;
use app\modules\rest\helpers\DateHelper;
use app\modules\rest\models\Monit;
use Yii;

class AutonomousSystemController extends BaseController
{
    public function beforeAction($action): bool
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        return parent::beforeAction($action);
    }

    public function actionGeneralInformation()
    {
        ini_set('memory_limit', '2044M');
        ini_set('max_execution_time', '1000');

        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        $timeInterface = Yii::$app->request->get('timeBy', 'day');

        return $this->asJson([
            'data' => Monit::autonomousSystems(
                DateHelper::supportInterfaceBy($timeInterface)->getTimestamp(),
                DateHelper::compareTimeInterface($timeInterface, DateHelper::INTERFACE_HOUR)
            )
        ]);
    }
}