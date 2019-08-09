<?php
namespace app\modules\rest\controllers\user;

use Yii;
use app\modules\rest\components\BaseController;

class UserController extends BaseController
{
    public function beforeAction($action): bool
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        return parent::beforeAction($action);
    }

    public function actionAccess()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        return $this->asJson([
            'access' => [
                'permissions' => $this->getUser()->getPermissionList(),
                'groups' => $this->getUser()->getGroupList()
            ]
        ]);
    }
}