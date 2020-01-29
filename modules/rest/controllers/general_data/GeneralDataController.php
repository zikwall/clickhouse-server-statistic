<?php
namespace app\modules\rest\controllers\general_data;

use app\modules\rest\components\BaseController;
use app\modules\statistic\models\MonitData;
use app\modules\user\models\User;
use Yii;

class GeneralDataController extends BaseController
{
    public function beforeAction($action): bool
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        return parent::beforeAction($action);
    }

    public function actionGetApp()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        return $this->asJson([
            'app' => MonitData::getApp()
        ]);
    }

    public function actionGetPeriod()
    {   
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }
        
        $user = User::findOne($this->user->id);
        $partner = false;
                
        if (!in_array('canViewDashboard', $user->getPermissionList())) {
            $partner = true;
        }

        return $this->asJson([
            'period' => MonitData::getPeriod($partner)
        ]);
    }
}