<?php

namespace app\modules\rest\controllers\asn;

use app\modules\rest\components\BaseController;
use app\modules\rest\models\Monit;
use Yii;

class AsnController extends BaseController
{
    public function beforeAction($action): bool
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        return parent::beforeAction($action);
    }

    /**
     * @return bool|\yii\web\Response
     * @throws \MaxMind\Db\Reader\InvalidDatabaseException
     */
    public function actionExample()
    {
        ini_set('memory_limit', '2044M');
        ini_set('max_execution_time', '1000');

        return $this->asJson([
            'result' => Monit::asn()
        ]);
    }
}