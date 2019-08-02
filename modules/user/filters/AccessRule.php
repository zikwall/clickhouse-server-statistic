<?php

namespace app\modules\user\filters;

class AccessRule extends \yii\filters\AccessRule
{
    protected function matchRole($user)
    {
        if (empty($this->roles)) {
            return true;
        }

        foreach ($this->roles as $role) {
            if ($role === '?') {
                if (\Yii::$app->user->isGuest) {
                    return true;
                }
            } elseif ($role === '@') {
                if (!\Yii::$app->user->isGuest) {
                    return true;
                }
            } elseif ($role === 'administratorRole') {
                if (!\Yii::$app->user->isGuest && \Yii::$app->user->identity->isAdmin) {
                    return true;
                }
            }
        }

        return false;
    }
}