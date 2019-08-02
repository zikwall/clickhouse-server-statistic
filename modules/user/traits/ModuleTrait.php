<?php


namespace app\modules\user\traits;

use app\modules\user\Module;

/**
 * Trait ModuleTrait
 * @property-read Module $module
 * @package app\modules\user\traits
 */
trait ModuleTrait
{
    /**
     * @return Module
     */
    public function getModule()
    {
        return \Yii::$app->getModule('user');
    }
}