<?php

namespace app\modules\rest\traits;

use app\modules\rest\Module;

trait ModuleTrait
{
    public function getModule() : Module
    {
        return \Yii::$app->getModule('rest');
    }
}