<?php
namespace app\modules\rest\controllers\data;

use app\modules\rest\components\BaseController;

class DataController extends BaseController
{
    public function actionIndex()
    {
        return $this->asJson([
            'success' => true,
        ]);
    }
}