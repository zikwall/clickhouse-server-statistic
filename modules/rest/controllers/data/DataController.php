<?php
namespace app\modules\rest\controllers\data;

use app\modules\rest\components\BaseController;
use app\modules\rest\models\Monit;

class DataController extends BaseController
{
    public function actionIndex()
    {
        return $this->asJson([
            'data' => Monit::getExample(),
        ]);
    }
}