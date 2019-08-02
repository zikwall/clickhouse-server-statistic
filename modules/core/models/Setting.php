<?php

namespace app\modules\core\models;

use Yii;

/**
 * @property integer $id
 * @property string $name
 * @property string $value
 * @property string $module_id
 */
class Setting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'module_id'], 'required'],
            ['value', 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'value' => 'Value',
            'module_id' => 'Module ID',
        ];
    }

    /**
     * @param $name
     * @param string $moduleId
     * @return mixed
     * @throws \yii\base\Exception
     */
    public static function IsFixed($name, $moduleId = "")
    {
        return self::getModule($moduleId)->settings->isFixed($name);
    }

    /**
     * @param $moduleId
     * @return null|\yii\base\Module|\yii\console\Application|\yii\web\Application
     * @throws \yii\base\Exception
     */
    public static function getModule($moduleId)
    {
        $module = null;

        if ($moduleId === '' || $moduleId === 'base') {
            $module = Yii::$app;
        } else {
            $module = Yii::$app->getModule($moduleId);
        }
        if ($module === null) {
            throw new \yii\base\Exception("Could not find module: " . $moduleId);
        }

        return $module;
    }

}
