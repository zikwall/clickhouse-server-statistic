<?php

namespace app\modules\rest\controllers\auth;

use app\modules\user\models\LoginForm;
use app\modules\rest\components\BaseController;
use Firebase\JWT\JWT;
use Yii;
use yii\web\IdentityInterface;

class AuthController extends BaseController
{
    public function beforeAction($action) : bool
    {
        return true;
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $post = json_decode(Yii::$app->getRequest()->getRawBody(), true);
        $user = static::authByUserAndPassword($post['username'], $post['password']);

        if ($user === null) {
            return $this->response([
                'status' => 400,
                'message' => 'Wrong username or password'
            ], 400);
        }

        Yii::$app->user->login($user);

        $jwt = $this->jwt($user);

        return $this->response([
            'token' => $jwt['token'],
            'token_expired' => date('Y-m-d H:i:s', $jwt['payload']['exp']),
            'user' => [
                'username' => $user->username,
                'email' => $user->email,
            ]
        ], 200);
    }

    /**
     * @param IdentityInterface $user
     * @return array
     */
    final protected function jwt(IdentityInterface $user) : array
    {
        $payload = [
            'iss' => "yii-rest-jwt",
            'iat' => time(),
            'uid' => $user->getId(),
            'exp' => time()
        ];

        if (!empty($this->getModule()->jwtExpire)) {
            $payload['exp'] += $this->getModule()->jwtExpire;
        }

        return [
            'token' => JWT::encode($payload, $this->getModule()->jwtKey),
            'payload' => $payload
        ];
    }

    /**
     * @param string $username
     * @param string $password
     * @return \app\modules\user\models\User|null
     * @throws \yii\base\InvalidConfigException
     */
    final public static function authByUserAndPassword(string $username, string $password)
    {
        /**
         * @var $login LoginForm
         */
        $login = Yii::createObject(LoginForm::class);

        if (!$login->load(['username' => $username, 'password' => $password], '') || !$login->validate()) {
            return null;
        }

        return $login->getUser();
    }

    /**
     * @param array $content
     * @param int $status
     * @return array
     */
    public function response(array $content, int $status) : array
    {
        Yii::$app->response->statusCode = $status;
        return $content;
    }
}