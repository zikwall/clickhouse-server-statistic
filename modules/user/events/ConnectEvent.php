<?php

/*
 * 
 *
 * 
 *
 * 
 * 
 */

namespace app\modules\user\events;

use app\modules\user\models\User;
use app\modules\user\models\Account;
use yii\base\Event;

/**
 * @property User    $model
 * @property Account $account
 * >
 */
class ConnectEvent extends Event
{
    /**
     * @var User
     */
    private $_user;

    /**
     * @var Account
     */
    private $_account;

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->_account;
    }

    /**
     * @param Account $account
     */
    public function setAccount(Account $account)
    {
        $this->_account = $account;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * @param User $form
     */
    public function setUser(User $user)
    {
        $this->_user = $user;
    }
}