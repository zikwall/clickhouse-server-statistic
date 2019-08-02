<?php

namespace app\modules\core\components\managers;

use Yii;
use app\modules\core\libs\BaseSettingsManager;

class SettingsManager extends BaseSettingsManager
{
    /**
     * Указывает, что этот параметр зафиксирован в файле конфигурации и не может быть изменен во время работы приложения.
     *
     * @param string $name
     * @return bool
     */
    public function isFixed(string $name) : bool
    {
        return isset(Yii::$app->params['fixed-settings'][$this->moduleId][$name]);
    }

    /**
     * @inheritdoc
     */
    public function get($name, $default = null)
    {
        if ($this->isFixed($name)) {
            return Yii::$app->params['fixed-settings'][$this->moduleId][$name];
        }

        return parent::get($name, $default);
    }
}
