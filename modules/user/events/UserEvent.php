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
use yii\base\Event;

/**
 * @property User $model
 * >
 */
class UserEvent extends Event
{
    /**
     * @var User
     */
    private $_user;

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
    public function setUser(User $form)
    {
        $this->_user = $form;
    }
}