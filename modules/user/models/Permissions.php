<?php

namespace app\modules\user\models;

use Yii;

/**
 * This is the model class for table "new_permissions".
 *
 * @property int $id
 * @property string $permission
 */
class Permissions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%permissions}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['permission'], 'required'],
            [['permission'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'permission' => 'Permission',
        ];
    }

    public function create() : bool
    {
        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        if (!$this->save()) {
            return false;
        }

        return true;
    }
}
