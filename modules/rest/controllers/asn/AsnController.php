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
        return $this->asJson([
            'result' => Monit::asn()
        ]);
    }
}