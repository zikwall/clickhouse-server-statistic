<?php

namespace app\modules\user\commands;

use app\modules\user\models\User;
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
    public function actionIndex($email, $username, $password = null)
    {
        $user = Yii::createObject([
            'class'    => User::className(),
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
}
