<?php

namespace app\modules\user;

use yii\base\BootstrapInterface;
use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    const STRATEGY_INSECURE = 0;
    const STRATEGY_DEFAULT = 1;
    const STRATEGY_SECURE = 2;

    /** @var bool Whether to show flash messages. */
    public $enableFlashMessages = false;

    /** @var bool Whether to enable registration. */
    public $enableRegistration = true;

    /** @var bool Whether to remove password field from registration form. */
    public $enableGeneratingPassword = false;

    /** @var bool Whether user has to confirm his account. */
    public $enableConfirmation = true;

    /** @var bool Whether to allow logging in without confirmation. */
    public $enableUnconfirmedLogin = false;

    /** @var bool Whether to enable password recovery. */
    public $enablePasswordRecovery = true;

    /** @var int Email changing strategy. */
    public $emailChangeStrategy = self::STRATEGY_DEFAULT;

    /** @var int The time you want the user will be remembered without asking for credentials. */
    public $rememberFor = 1209600; // two weeks

    /** @var int The time before a confirmation token becomes invalid. */
    public $confirmWithin = 86400; // 24 hours

    /** @var int The time before a recovery token becomes invalid. */
    public $recoverWithin = 21600; // 6 hours

    /** @var int Cost parameter used by the Blowfish hash algorithm. */
    public $cost = 10;

    /** @var array An array of administrator's usernames. */
    public $admins = [];
	
	/** @var string The Administrator permission name. */
    public $adminPermission;

    /** @var array Mailer configuration */
    public $mailer = [];

    /** @var array Model map */
    public $modelMap = [];

    /**
     * @var string The prefix for user module URL.
     *
     * @See [[GroupUrlRule::prefix]]
     */
    public $urlPrefix = 'user';

    /** @var array The rules to be used in URL management. */
    public $urlRules = [
        '<id:\d+>'                               => 'profile/show',
        '<action:(login|logout)>'                => 'POST security/<action>',
        '<action:(register222|resend)>'          => 'registration/<action>',
        'invite/join/code/<code:[A-Za-z0-9_-]+>' => 'invite/register',
        'confirm/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'registration/confirm',
        'forgot'                                 => 'recovery/request',
        'recover/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'recovery/reset',
        'settings/<action:\w+>'                  => 'settings/<action>'
    ];
}
