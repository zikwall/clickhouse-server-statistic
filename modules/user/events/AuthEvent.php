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

use app\modules\user\models\Account;
use yii\authclient\ClientInterface;
use yii\base\Event;

/**
 * @property Account         $account
 * @property ClientInterface $client
 * >
 */
class AuthEvent extends Event
{
    /**
     * @var ClientInterface
     */
    private $_client;

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
     * @return ClientInterface
     */
    public function getClient()
    {
        return $this->_client;
    }

    /**
     * @param ClientInterface $client
     */
    public function setClient(ClientInterface $client)
    {
        $this->_client = $client;
    }
}