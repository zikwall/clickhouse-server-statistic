<?php

namespace app\modules\user\models;

use app\modules\core\components\base\ActiveRecord;

/**
 * @property string $id
 * @property integer $expire
 * @property integer $user_id
 * @property string $data
 *
 * @property User[] $user
 *
 */
class Session extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_http_session}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
