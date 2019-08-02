<?php

namespace app\modules\core\libs;

use Yii;
use yii\base\Component;

abstract class BaseSettingsManager extends Component
{
    /**
     * @var string id модуля, к которому принадлежит этот менеджер настроек.
     */
    public $moduleId = null;

    /**
     * @var array|null загруженных настроек
     */
    protected $_loaded = null;

    /**
     * @var string имя класса модели установки
     */
    public $modelClass = 'app\modules\core\models\Setting';

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->moduleId === null) {
            throw new \Exception('Could not determine module id');
        }

        $this->loadValues();

        parent::init();
    }

    /**
     * Устанавливает значение настроек
     *
     * @param string $name
     * @param string $value
     *
     * @throws \yii\base\Exception
     */
    public function set($name, $value)
    {
        if ($value === null) {
            return $this->delete($name);
        }

        // Обновление записи настроек базы данных
        $record = $this->find()->andWhere(['name' => $name])->one();
        if ($record === null) {
            $record = $this->createRecord();
            $record->name = $name;
        }

        if (is_bool($value)) {
            $value = (int) $value;
        }

        $record->value = (string) $value;
        if (!$record->save()) {
            throw new \yii\base\Exception("Could not store setting! (" . print_r($record->getErrors(), 1) . ")");
        }

        // Хранить во время выполнения
        $this->_loaded[$name] = $value;

        $this->invalidateCache();
    }

    /**
     * @param $name
     * @param $value
     * @throws \yii\base\Exception
     */
    public function setSerialized($name, $value)
    {
        $this->set($name, \yii\helpers\Json::encode($value));
    }

    /**
     * Получает значение, которое было сохранено как сериализованное значение.
     *
     * @param string $name
     * @param mixed $default значение настройки или null, если не существует
     *
     * @return mixed|string
     */
    public function getSerialized($name, $default = null)
    {
        $value = $this->get($name, $default);
        if (is_string($value)) {
            $value = \yii\helpers\Json::decode($value);
        }
        return $value;
    }

    /**
     * Возвращает значение настройки
     *
     * @param string $name имя настройки
     * @return string значение настройки или null, если не существует
     */
    public function get($name, $default = null)
    {
        return isset($this->_loaded[$name]) ? $this->_loaded[$name] : $default;
    }

    /**
     * Возвращает значение настройки без кэширования
     */
    public function getUncached($name, $default = null)
    {
        $record = $this->find()->andWhere(['name' => $name])->one();
        return ($record !== null) ? $record->value : $default;
    }

    public function delete($name)
    {
        $record = $this->find()->andWhere(['name' => $name])->one();
        if ($record !== null) {
            $record->delete();
        }

        if (isset($this->_loaded[$name])) {
            unset($this->_loaded[$name]);
        }

        $this->invalidateCache();
    }

    protected function loadValues()
    {
        $cached = Yii::$app->cache->get($this->getCacheKey());
        if ($cached === false) {
            $this->_loaded = [];
            $settings = &$this->_loaded;

            array_map(function ($record) use(&$settings ) {
                $settings[$record->name] = $record->value;
            }, $this->find()->all());

            Yii::$app->cache->set($this->getCacheKey(), $this->_loaded);
        } else {
            $this->_loaded = $cached;
        }
    }

    public function reload()
    {
        $this->invalidateCache();
        $this->loadValues();
    }

    /**
     * Недействительный кеш настроек
     */
    protected function invalidateCache()
    {
        Yii::$app->cache->delete($this->getCacheKey());
    }

    /**
     * Возвращает ключи кеша настроек менеджеров
     *
     * @return string
     */
    protected function getCacheKey()
    {
        return 'settings-' . $this->moduleId;
    }

    /**
     * Возвращает настройки активной записи записи
     */
    protected function createRecord()
    {
        $model = new $this->modelClass;
        $model->module_id = $this->moduleId;

        return $model;
    }

    /**
     * Возвращает ActiveQuery для поиска настроек
     *
     * @return \yii\db\ActiveQuery
     */
    protected function find()
    {
        $modelClass = $this->modelClass;
        return $modelClass::find()->andWhere(['module_id' => $this->moduleId]);
    }

    /**
     * Удаляет все сохраненные настройки
     */
    public function deleteAll()
    {
        foreach ($this->find()->all() as $setting) {
            $this->delete($setting->name);
        }
    }
}
