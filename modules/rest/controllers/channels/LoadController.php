<?php

namespace app\modules\rest\controllers\channels;

use app\modules\rest\components\BaseController;
use app\modules\statistic\models\MonitLoadChannels;

class LoadController extends BaseController
{
    public function actionDay()
    {
        return $this->asJson(MonitLoadChannels::getLoad()->getRows());
    }
}