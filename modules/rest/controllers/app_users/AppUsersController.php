<?php
namespace app\modules\rest\controllers\app_users;

use app\modules\rest\components\BaseController;
use app\modules\statistic\models\MonitAppUsers;
use Yii;

class AppUsersController extends BaseController
{
    public function beforeAction($action): bool
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        return parent::beforeAction($action);
    }

    public function actionGetAdsData()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        $app = 'com.infolink.limeiptv';
        $dayBegin = 1566680400;
        $dayEnd = 1567026000;
        $eventType = 0;
        $data = MonitAppUsers::getAppUsers($app, $dayBegin, $dayEnd, $eventType);

        return $this->asJson([
            'appUsers' => $data
        ]);
    }
}