<?php
namespace app\modules\rest\controllers\app_users;

use app\modules\rest\components\BaseController;
use app\modules\statistic\models\MonitAppUsers;
use app\modules\statistic\models\MonitChannels;
use Yii;

class AppUsersController extends BaseController
{
    public function beforeAction($action): bool
    {
        set_time_limit(600);
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

    public function actionGetTimeZoneUsers()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        $request = Yii::$app->request;
        $app = $request->post('app');
        $dayBegin = $request->post('dayBegin');
        $dayEnd = $request->post('dayEnd');
        $eventType = $request->post('eventType');

        $data = MonitAppUsers::getTimeZoneUsers($app, $dayBegin, $dayEnd, $eventType);

        return $this->asJson([
            'timeZoneUsers' => $data
        ]);
    }

    public function actionGetMonthUsers()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        $request = Yii::$app->request;
        $year = 2019;
        $month = 10;

        $data = MonitAppUsers::getMonthUsers($year, $month);

        return $this->asJson([
            'timeZoneUsers' => $data
        ]);
    }
}