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

use app\modules\user\models\Profile;
use yii\base\Event;

/**
 * @property Profile $model
 * >
 */
class ProfileEvent extends Event
{
    /**
     * @var Profile
     */
    private $_profile;

    /**
     * @return Profile
     */
    public function getProfile()
    {
        return $this->_profile;
    }

    /**
     * @param Profile $form
     */
    public function setProfile(Profile $form)
    {
        $this->_profile = $form;
    }
}