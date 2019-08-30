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

        $app = 'all';
        $dayBegin = 1566680400;
        $dayEnd = 1567026000;
        $eventType = 'all';
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

        $app = 'all';
        $dayBegin = 1566680400;
        $dayEnd = 1567026000;
        $eventType = 'all';
        $data = MonitAppUsers::getAppUsersGroupByTotal($app, $dayBegin, $dayEnd, $eventType);

        return $this->asJson([
            'appUsersTotal' => $data
        ]);
    }
}