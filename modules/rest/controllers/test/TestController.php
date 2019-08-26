<?php
namespace app\modules\rest\controllers\test;

use app\modules\core\components\date\range\Range;
use app\modules\rest\models\Monit;
use Yii;

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
           'timestamp' => Range::supportInterfaceBy($getBy)->getTimestamp()
        ]);
    }

    public function actionTestQueryAs()
    {
        return $this->asJson([
            'query' => Monit::autonomousSystems(Range::supportInterfaceBy('day'))->getQuery()->getQuery(),
            'time' => mktime(0, 0, 0)
        ]);
    }
}