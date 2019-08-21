<?php
namespace app\modules\rest\controllers\test;

use app\modules\rest\models\Monit;
use Yii;
use app\modules\rest\helpers\DateHelper;

class TestController extends \yii\rest\Controller
{
    public function actionTimestamp()
    {
        $getBy = Yii::$app->request->get('by', null);

        if ($getBy === null) {
            return $this->asJson([
                'message' => 'Empty timestamp'
            ]);
        }

        return $this->asJson([
           'timestamp' => DateHelper::supportInterfaceBy($getBy)->getTimestamp()
        ]);
    }

    public function actionTestQueryAs()
    {
        return $this->asJson([
            'query' => Monit::autonomousSystems()->getQuery()->getQuery(),
            'time' => 1566345600 - 1566334800
        ]);
    }
}