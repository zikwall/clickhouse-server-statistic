<?php

/*
 * 
 *
 * 
 *
 * 
 * 
 */

namespace app\modules\user\models;

use app\modules\user\Finder;
use app\modules\user\Mailer;
use app\modules\user\traits\ModuleTrait;
use http\Url;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Model for collecting data on password recovery.
 *
 * @property \app\modules\user\Module $module
 *
 * >
 */
class RecoveryForm extends Model
{
    use ModuleTrait;
    /** @var string */
    public $email;

    /** @var string */
    public $password;

    /** @var User */
    protected $user;

    /** @var Mailer */
    protected $mailer;

    /** @var Finder */
    protected $finder;

    /**
     * @param Mailer $mailer
     * @param Finder $finder
     * @param array  $config
     */
    public function __construct(Mailer $mailer, Finder $finder, $config = [])
    {
        $this->mailer = $mailer;
        $this->finder = $finder;
        parent::__construct($config);
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'email'    => Yii::t('user', 'Email'),
            'password' => Yii::t('user', 'Password'),
        ];
    }

    /** @inheritdoc */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return ArrayHelper::merge($scenarios, [
            'request' => ['email'],
            'reset'   => ['password'],
        ]);
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            'emailTrim' => ['email', 'filter', 'filter' => 'trim'],
            'emailRequired' => ['email', 'required'],
            'emailPattern' => ['email', 'email'],
            'emailExist' => [
                'email',
                'exist',
                'targetClass' => $this->module->modelMap['User'],
                'message' => Yii::t('user', 'There is no user with this email address'),
            ],
            'emailUnconfirmed' => [
                'email',
                function ($attribute) {
                    $this->user = $this->finder->findUserByEmail($this->email);
                    if ($this->user !== null && $this->module->enableConfirmation && !$this->user->isConfirmed) {
                        $this->addError($attribute, Yii::t('user', 'You need to confirm your email address'));
                    }
                    if ($this->user->isBlocked) {
                        $this->addError($attribute, Yii::t('user', 'Your account has been blocked'));
                    }
                }
            ],
            'passwordRequired' => ['password', 'required'],
            'passwordLength' => ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Sends recovery message.
     *
     * @return bool
     */
    public function sendRecoveryMessage()
    {
        if ($this->validate()) {
            /** @var Token $token */
            $token = Yii::createObject([
                'class'   => Token::className(),
                'user_id' => $this->user->id,
                'type'    => Token::TYPE_RECOVERY,
            ]);

            if (!$token->save(false)) {
                return false;
            }

            if (!$this->mailer->sendRecoveryMessage($this->user, $token)) {
                return false;
            }

            Yii::$app->session->setFlash('info',
                Yii::t('user', 'An email has been sent with instructions for resetting your password')
            );

            return true;
        }

        return false;
    }

    /**
     * @param Token $token
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function resetPassword(Token $token)
    {
        if (!$this->validate() || $token->user === null) {
            return false;
        }

        if ($token->user->resetPassword($this->password)) {
            Yii::$app->session->setFlash('success', Yii::t('user', 'Your password has been changed successfully.'));
            $token->delete();
            sleep(2);
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('user', 'An error occurred and your password has not been changed. Please try again later.'));
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'recovery-form';
    }
}
