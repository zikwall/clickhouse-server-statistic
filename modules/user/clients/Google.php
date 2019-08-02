<?php

/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * .md
 * 
 */

namespace app\modules\user\clients;

use yii\authclient\clients\GoogleOAuth as BaseGoogle;

/**
 * >
 */
class Google extends BaseGoogle implements ClientInterface
{
    /** @inheritdoc */
    public function getEmail()
    {
        return isset($this->getUserAttributes()['emails'][0]['value'])
            ? $this->getUserAttributes()['emails'][0]['value']
            : null;
    }

    /** @inheritdoc */
    public function getUsername()
    {
        return;
    }
}
