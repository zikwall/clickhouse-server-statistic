<?php

namespace app\modules\rest\components;

use app\modules\rest\controllers\auth\AuthController;
use app\modules\rest\traits\ModuleTrait;
use app\modules\user\models\User;
use Firebase\JWT\JWT;
use Yii;
use yii\web\HttpException;

class BaseController extends \yii\rest\Controller
{
    use ModuleTrait;

    public static $moduleId = '';

    /**
     * @var User
     */
    private $user;

    public function init() : void
    {
        parent::init();
        $this->enableCsrfValidation = \false;
        $this->layout = \false;
    }

    private $_verbs = ['POST','OPTIONS'];

    public function actionOptions ()
    {
        if (Yii::$app->getRequest()->getMethod() !== 'OPTIONS') {
            Yii::$app->getResponse()->setStatusCode(405);
        }

        $options = $this->_verbs;
        Yii::$app->getResponse()->getHeaders()->set('Allow', implode(', ', $options));
    }

    /**
     * @param $action
     * @return bool
     * @throws HttpException
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action) : bool
    {
        $user = $this->authWithJwt();

        if ($user === null && $this->getModule()->enableBasicAuth) {
            list($username, $password) = Yii::$app->request->getAuthCredentials();
            $user = AuthController::authByUserAndPassword($username, $password);
        }

        if ($user === null) {
            throw new HttpException('401', 'Invalid token!');
        }

        if ($this->isUserDisabled($user)) {
            throw new HttpException('401', 'Invalid user!');
        }

        if (Yii::$app->user->getIsGuest()) {
            // Yii::$app->user->login($user);
        }

        return parent::beforeAction($action);
    }

    /**
     * @return array|null|\yii\db\ActiveRecord
     * @throws HttpException
     */
    final private function authWithJwt()
    {
        $authHeader = Yii::$app->request->getHeaders()->get('Authorization');

        if (!empty($authHeader) && preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {

            $token = $matches[1];

            try {
                $validData = JWT::decode($token, $this->getModule()->jwtKey, ['HS256']);

                if (!empty($validData->uid)) {
                    return $this->user = User::find()->where(['id' => $validData->uid])->one();
                }
            } catch (\Exception $e) {
                throw new HttpException(401, $e->getMessage());
            }
        }

        return null;
    }

    /**
     * @param User $user
     * @return bool
     */
    final private function isUserDisabled(User $user) : bool
    {
        if ($this->getModule()->enabledForAllUsers) {
            return false;
        }

        if (in_array($user->id, (array) $this->getModule()->disabledUsers)) {
            return true;
        }

        return false;
    }

    /**
     * @return User|bool
     */
    final public function getUser()
    {
        return $this->user instanceof User ? $this->user : false;
    }
}