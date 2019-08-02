<?php

namespace app\modules\user\controllers;

use app\modules\user\Finder;
use app\modules\user\models\RecoveryForm;
use app\modules\user\models\Token;
use app\modules\user\traits\AjaxValidationTrait;
use app\modules\user\traits\EventTrait;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class RecoveryController extends Controller
{
    use EventTrait;

    public $layout = '@app/themes/code/views/layouts/auth';

    /**
     * Event is triggered before requesting password reset.
     * Triggered with \app\modules\user\events\FormEvent.
     */
    const EVENT_BEFORE_REQUEST = 'beforeRequest';

    /**
     * Event is triggered after requesting password reset.
     * Triggered with \app\modules\user\events\FormEvent.
     */
    const EVENT_AFTER_REQUEST = 'afterRequest';

    /**
     * Event is triggered before validating recovery token.
     * Triggered with \app\modules\user\events\ResetPasswordEvent. May not have $form property set.
     */
    const EVENT_BEFORE_TOKEN_VALIDATE = 'beforeTokenValidate';

    /**
     * Event is triggered after validating recovery token.
     * Triggered with \app\modules\user\events\ResetPasswordEvent. May not have $form property set.
     */
    const EVENT_AFTER_TOKEN_VALIDATE = 'afterTokenValidate';

    /**
     * Event is triggered before resetting password.
     * Triggered with \app\modules\user\events\ResetPasswordEvent.
     */
    const EVENT_BEFORE_RESET = 'beforeReset';

    /**
     * Event is triggered after resetting password.
     * Triggered with \app\modules\user\events\ResetPasswordEvent.
     */
    const EVENT_AFTER_RESET = 'afterReset';

    /** @var Finder */
    protected $finder;

    /**
     * @param string           $id
     * @param \yii\base\Module $module
     * @param Finder           $finder
     * @param array            $config
     */
    public function __construct($id, $module, Finder $finder, $config = [])
    {
        $this->finder = $finder;
        parent::__construct($id, $module, $config);
    }

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['request', 'reset', 'message'], 'roles' => ['?']],
                ],
            ],
        ];
    }

    /**
     * @return array|string
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRequest()
    {
        if (!$this->module->enablePasswordRecovery) {
            throw new NotFoundHttpException();
        }

        /** @var RecoveryForm $model */
        $model = Yii::createObject([
            'class'    => RecoveryForm::className(),
            'scenario' => 'request',
        ]);
        $event = $this->getFormEvent($model);

        if(Yii::$app->request->isAjax) {
            return $this->performAjaxValidation($model);
        }

        $this->trigger(self::EVENT_BEFORE_REQUEST, $event);

        if ($model->load(Yii::$app->request->post()) && $model->sendRecoveryMessage()) {
            $this->trigger(self::EVENT_AFTER_REQUEST, $event);
            return $this->render('/message', [
                'title'  => Yii::t('user', 'Recovery message sent'),
                'module' => $this->module,
            ]);
        }

        return $this->render('request', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @param $code
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionReset($id, $code)
    {
        if (!$this->module->enablePasswordRecovery) {
            throw new NotFoundHttpException();
        }

        /** @var Token $token */
        $token = $this->finder->findToken(['user_id' => $id, 'code' => $code, 'type' => Token::TYPE_RECOVERY])->one();
        $event = $this->getResetPasswordEvent($token);

        $this->trigger(self::EVENT_BEFORE_TOKEN_VALIDATE, $event);

        if ($token === null || $token->isExpired || $token->user === null) {
            $this->trigger(self::EVENT_AFTER_TOKEN_VALIDATE, $event);
            Yii::$app->session->setFlash('danger', Yii::t('user', 'Recovery link is invalid or expired. Please try requesting a new one.'));
            return $this->render('/message', [
                'title'  => Yii::t('user', 'Invalid or expired link'),
                'module' => $this->module,
            ]);
        }

        /** @var RecoveryForm $model */
        $model = Yii::createObject([
            'class'    => RecoveryForm::className(),
            'scenario' => 'reset',
        ]);
        $event->setForm($model);

        if(Yii::$app->request->isPost && Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        $this->trigger(self::EVENT_BEFORE_RESET, $event);

        if ($model->load(Yii::$app->getRequest()->post()) && $model->resetPassword($token)) {
            $this->trigger(self::EVENT_AFTER_RESET, $event);
            return $this->render('/message', [
                'title'  => Yii::t('user', 'Password has been changed'),
                'module' => $this->module,
            ]);
        }

        return $this->render('reset', [
            'model' => $model,
        ]);
    }
}
