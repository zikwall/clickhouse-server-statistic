<?php
namespace app\modules\rest\controllers\user;

use app\modules\rest\components\BaseController;

class UserController extends BaseController
{
    public function actionPermissions()
    {
        return $this->asJson([
            'permissions' => [
                Yii::$app->getUser()->getIdentity()->username
            ]
        ]);
    }
}