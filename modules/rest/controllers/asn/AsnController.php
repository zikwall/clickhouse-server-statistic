<?php

namespace app\modules\rest\controllers\asn;

use app\modules\rest\components\BaseController;
use app\modules\rest\models\Monit;

class AsnController extends BaseController
{
    /**
     * @return \yii\web\Response
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