<?php
namespace app\modules\rest\controllers\app_users;

use app\modules\rest\components\BaseController;
use app\modules\statistic\models\MonitAppUsers;
use Yii;
use yii\rest\Controller;

class AppUsersController extends Controller
{
    public function beforeAction($action): bool
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        return parent::beforeAction($action);
    }

    public function actionGetAppUsers()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        $request = Yii::$app->request;
        $app = $request->post('app');
        $dayBegin = $request->post('dayBegin');
        $dayEnd = $request->post('dayEnd');
        $eventType = $request->post('eventType');

        $data = MonitAppUsers::getAppUsersGroupByDay($app, $dayBegin, $dayEnd, $eventType);

        return $this->asJson([
            'appUsers' => $data
        ]);
    }

    public function actionGetAppUsersTotal()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        $request = Yii::$app->request;
        $app = $request->post('app');
        $dayBegin = $request->post('dayBegin');
        $dayEnd = $request->post('dayEnd');
        $eventType = $request->post('eventType');

        $data = MonitAppUsers::getAppUsersGroupByTotal($app, $dayBegin, $dayEnd, $eventType);

        return $this->asJson([
            'appUsersTotal' => $data
        ]);
    }
}