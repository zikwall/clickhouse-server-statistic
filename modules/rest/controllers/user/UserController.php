<?php
namespace app\modules\rest\controllers\user;

use Yii;
use app\modules\rest\components\BaseController;

class UserController extends BaseController
{
    public function actionPermissions()
    {
        return $this->asJson([
            'permissions' => [
                $this->getUser()->username
            ]
        ]);
    }
}