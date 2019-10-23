<?php

namespace app\modules\user\models;

use Yii;

/**
 * This is the model class for table "new_user_permissions".
 *
 * @property int $id
 * @property int $user_id
 * @property int $permission_id
 *
 * @property User $user
 */
class UserPermissions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_permissions}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'permission_id'], 'required'],
            [['user_id', 'permission_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'permission_id' => 'Permission ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getPermission()
    {
        return $this->hasOne(Permissions::className(), ['id' => 'permission_id']);
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
    
    /**
     * 
     * @param type $userId
     * @return bool|inter
     * @throws Exception
     */
    public function terminate($userId) : ?bool
    {
        $userPermission = static::find()->where(['user_id' =>  $userId])->one();

        if (is_null($userPermission)) {
            throw new Exception('User not exist');
        }

        if (!$userPermission->delete()) {
            return false;
        }

        return $userPermission->permission_id;
    }
}
