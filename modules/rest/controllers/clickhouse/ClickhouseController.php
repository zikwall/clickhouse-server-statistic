<?php
namespace app\modules\rest\controllers\clickhouse;

use Yii;
use app\modules\rest\models\Monit;

class ClickhouseController extends \app\modules\rest\components\BaseController
{
    public function beforeAction($action): bool
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        return parent::beforeAction($action);
    }

    public function actionTotal()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        $byPlatform = Yii::$app->request->get('platform', null);

        return $this->asJson([
            'data' => Monit::total($byPlatform)
        ]);
    }
}