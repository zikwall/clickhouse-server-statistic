<?php

namespace app\modules\core\libs;

use Yii;

class BasePermission extends \yii\base\BaseObject
{
    /**
     * Разрешения
     */
    const STATE_DEFAULT = '';
    const STATE_ALLOW = 1;
    const STATE_DENY = 0;

    /**
     * @var string id разрешения (по умолчанию - имя класса)
     */
    protected $id;

    /**
     * @var string название разрешения
     */
    protected $title = "";

    /**
     * @var string описание разрешения
     */
    protected $description = "";

    /**
     * @var string id модуля, который принадлежит разрешению
     */
    protected $moduleId = "";

    /**
     * Список идентификаторов групп, которые разрешены по умолчанию.
     *
     * @var array
     */
    protected $defaultAllowedGroups = [

    ];

    /**
     * Список идентификаторов групп, которые являются фиксированной группой.
     * См. DefaultState для настройки по умолчанию.
     *
     * @var array
     */
    protected $fixedGroups = [

    ];

    /**
     * Состояние этого разрешения по умолчанию
     *
     * @var string
     */
    protected $defaultState = self::STATE_DENY;

    /**
     * Необязательный экземпляр contentContainer для улучшения заголовка и описания.
     *
     * @var \app\modules\content\components\ContentContainerActiveRecord
     */
    public $contentContainer = null;

    /**
     * permission ID
     */
    public function getId()
    {
        if ($this->id != "") {
            return $this->id;
        }

        return $this->className();
    }

    /**
     * Возвращает заголовок
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Возвращает описание
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Возвращает текущий модуль разрешения
     */
    public function getModuleId()
    {
        return $this->moduleId;
    }

    /**
     * Возвращает состояние разрешения по умолчанию.
     * DefaultState либо определяется установкой атрибута $defaultState
     * или путем перезаписывания $defaultState с помощью параметра param 'defaultPermissions'.
     *
     * Если для параметра $ defaultState установлено значение denied, можно предоставить разрешение для определенных групп,
     * указав массив $defaultAllowedGroups.
     */
    public function getDefaultState($groupId)
    {
        $configuredState = $this->getConfiguredState($groupId);

        if ($configuredState !== null) {
            return $configuredState;
        } else if ($this->defaultState == self::STATE_ALLOW) {
            return self::STATE_ALLOW;
        } else {
            return (int) (in_array($groupId, $this->defaultAllowedGroups));
        }
    }

    /**
     * Возвращает заданное по умолчанию состояние в параметрах конфигурации defaultPermissions.
     * Этот метод возвращает null, если состояние по умолчанию для этого разрешения не установлено в конфигурации.
     */
    protected function getConfiguredState($groupId)
    {
        if (isset(Yii::$app->params['defaultPermissions'][self::class]) && isset(Yii::$app->params['defaultPermissions'][self::class][$groupId])) {
            return Yii::$app->params['defaultPermissions'][self::class][$groupId];
        }

        return null;
    }

    /**
     * Проверяет, можно ли изменить состояние разрешения
     */
    public function canChangeState($groupId) : bool
    {
        return (!in_array($groupId, $this->fixedGroups));
    }

    /**
     * Проверяет, принадлежит ли данный идентификатор этому разрешению
     */
    public function hasId($id) : bool
    {
        return ($this->getId() == $id);
    }

    /**
     * Возвращает метку для данного состояния
     */
    public static function getLabelForState($state) : string
    {
        if ($state === self::STATE_ALLOW) {
            return Yii::t('base', 'Allow');
        } elseif ($state === self::STATE_DENY) {
            return Yii::t('base', 'Deny');
        } elseif ($state == '') {
            return Yii::t('base', 'Default');
        }

        throw new \yii\base\Exception('Invalid permission state');
    }

}
