<?php

namespace app\modules\rest\controllers\autonomoussystems;

use app\modules\core\components\date\range\DateRangeInterface;
use app\modules\rest\components\BaseController;
use app\modules\core\components\date\range\Range;
use app\modules\rest\models\Monit;
use Yii;
use yii\helpers\ArrayHelper;

class AutonomousSystemController extends BaseController
{
    public function beforeAction($action): bool
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        $this->allocationOfMemory();

        return parent::beforeAction($action);
    }

    protected function performances(): array
    {
        return ArrayHelper::merge(parent::performances(), [
            'general-information' => [
                'memory' => '4096M',
                'execution_time' => 1000
            ]
        ]);
    }

    /**
     * @return bool|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionGeneralInformation()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        $timeInterface = Yii::$app->request->get('timeBy', DateRangeInterface::INTERFACE_DAY);

        return $this->asJson([
            'data' => Monit::autonomousSystems(Range::supportInterfaceBy($timeInterface))
        ]);
    }
}