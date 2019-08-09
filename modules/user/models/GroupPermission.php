<?php

namespace app\modules\user\models;

/**
 * This is the model class for table "group_permission".
 *
 * @property string $permission_id
 * @property integer $group_id
 * @property string $module_id
 * @property string $class
 * @property integer $state
 */
class GroupPermission extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group_permission}}';
    }
    
    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['permission_id', 'group_id'], 'required'],
            [['group_id'], 'integer'],
            [['permission_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'permission_id' => 'Permission ID',
            'group_id' => 'Community ID',
        ];
    }
}
