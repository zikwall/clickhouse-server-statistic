<?php

namespace app\modules\user\commands;

use app\modules\user\models\Permissions;
use app\modules\user\models\User;
use app\modules\user\models\UserPermissions;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class CreateController extends Controller
{
    /**
     * @param $email
     * @param $username
     * @param null $password
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex(string $email, string $username, $password = null)
    {
        $user = Yii::createObject([
            'class'    => User::class,
            'scenario' => 'create',
            'email'    => $email,
            'username' => $username,
            'password' => $password,
        ]);

        if ($user->create()) {
            $this->stdout(Yii::t('user', 'User has been created') . "!\n", Console::FG_GREEN);
        } else {
            $this->stdout(Yii::t('user', 'Please fix following errors:') . "\n", Console::FG_RED);
            foreach ($user->errors as $errors) {
                foreach ($errors as $error) {
                    $this->stdout(' - ' . $error . "\n", Console::FG_RED);
                }
            }
        }
    }

    /**
     * @param $name
     * @throws \yii\base\InvalidConfigException
     */
    public function actionPermission(string $name)
    {
        /**
         * @var $permission Permissions
         */
        $permission = Yii::createObject([
            'class'    => Permissions::class,
            'permission'    => $name,
        ]);

        if($permission->create()) {
            $this->stdout('Permission created!' . "!\n", Console::FG_GREEN);
        } else {
            $this->stdout(Yii::t('user', 'Please fix following errors:') . "\n", Console::FG_RED);
            foreach ($permission->errors as $errors) {
                foreach ($errors as $error) {
                    $this->stdout(' - ' . $error . "\n", Console::FG_RED);
                }
            }
        }
    }

    /**
     * @param int $userId
     * @param int $permissionId
     * @throws \yii\base\InvalidConfigException
     */
    public function actionLink(int $userId, int $permissionId)
    {
        /**
         * @var $permissionLink UserPermissions
         */
        $permissionLink = Yii::createObject([
            'class'    => UserPermissions::class,
            'user_id'  => $userId,
            'permission_id' => $permissionId
        ]);

        if(!$permissionLink->create()) {
            $this->stdout(Yii::t('user', 'Please fix following errors:') . "\n", Console::FG_RED);
            foreach ($permissionLink->errors as $errors) {
                foreach ($errors as $error) {
                    $this->stdout(' - ' . $error . "\n", Console::FG_RED);
                }
            }
        }

        $this->stdout('Permission to user linked!' . "!\n", Console::FG_GREEN);
    }
}
