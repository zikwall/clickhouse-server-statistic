<?php

namespace app\modules\rest\controllers\channels;

use app\modules\rest\components\BaseController;
use app\modules\statistic\models\MonitLoadChannels;

class LoadController extends BaseController
{
    public function actionDay()
    {
        $time = 0;

        if (\Yii::$app->request->getIsPost()) {
            $time = \Yii::$app->request->post('time', 0);
        }

        return $this->asJson(MonitLoadChannels::getLoad($time)->getRows());
    }
}