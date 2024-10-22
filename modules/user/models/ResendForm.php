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
use Yii;
use yii\base\Model;

/**
 * ResendForm gets user email address and validates if user has already confirmed his account. If so, it shows error
 * message, otherwise it generates and sends new confirmation token to user.
 *
 * @property User $user
 *
 * >
 */
class ResendForm extends Model
{
    use ModuleTrait;
    /** @var string */
    public $email;

    /** @var User */
    private $_user;

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

    /**
     * @return User
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = $this->finder->findUserByEmail($this->email);
        }

        return $this->_user;
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            'emailRequired' => ['email', 'required'],
            'emailPattern' => ['email', 'email'],
            'emailExist' => ['email', 'exist', 'targetClass' => $this->module->modelMap['User']],
            'emailConfirmed' => [
                'email',
                function () {
                    if ($this->user != null && $this->user->getIsConfirmed()) {
                        $this->addError('email', Yii::t('user', 'This account has already been confirmed'));
                    }
                }
            ],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('user', 'Email'),
        ];
    }

    /** @inheritdoc */
    public function formName()
    {
        return 'resend-form';
    }

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function resend()
    {
        if (!$this->validate()) {
            return false;
        }
        /** @var Token $token */
        $token = Yii::createObject([
            'class'   => Token::className(),
            'user_id' => $this->user->id,
            'type'    => Token::TYPE_CONFIRMATION,
        ]);
        $token->save(false);
        $this->mailer->sendConfirmationMessage($this->user, $token);
        Yii::$app->session->setFlash('info', Yii::t('user', 'A message has been sent to your email address. It contains a confirmation link that you must click to complete registration.'));

        return true;
    }
}
