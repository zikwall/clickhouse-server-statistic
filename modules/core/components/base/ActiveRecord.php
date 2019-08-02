<?php

namespace app\modules\core\components\base;

use Yii;
use app\modules\user\models\User;

class ActiveRecord extends \yii\db\ActiveRecord implements \Serializable
{
    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            if ($this->hasAttribute('created_at') && $this->created_at == "") {
                $this->created_at = new \yii\db\Expression('NOW()');
            }

            if (isset(Yii::$app->user) && $this->hasAttribute('created_by') && $this->created_by == "") {
                $this->created_by = Yii::$app->user->id;
            }
        }

        if ($this->hasAttribute('updated_at')) {
            $this->updated_at = new \yii\db\Expression('NOW()');
        }
        if (isset(Yii::$app->user) && $this->hasAttribute('updated_by')) {
            $this->updated_by = Yii::$app->user->id;
        }

        return parent::beforeSave($insert);
    }

    /**
     * @return string
     */
    public function getUniqueId()
    {
        return str_replace('\\', '', get_class($this)) . "_" . $this->primaryKey;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, [
            'id' => 'created_by'
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, [
            'id' => 'updated_by'
        ]);
    }

    /**
     * @param null $attribute
     * @return string
     */
    public function getErrorMessage($attribute = null)
    {
        $message = '';
        foreach ($this->getErrors($attribute) as $attribute => $errors) {
            $message .= $attribute . ': ' . implode(', ', $errors) . ', ';
        }

        return $message;
    }

    /**
     * Serializes attributes and oldAttributes of this record.
     *
     * Note: Subclasses have to include $this->getAttributes() and $this->getOldAttributes()
     * in the serialized array.
     *
     * @link http://php.net/manual/en/function.serialize.php
     * @since 1.2
     * @return string
     */
    public function serialize()
    {
        return serialize([
            'attributes' => $this->getAttributes(),
            'oldAttributes' => $this->getOldAttributes()
        ]);
    }

    /**
     * Unserializes the given string, calls the init() function and sets the attributes and oldAttributes.
     *
     * Note: Subclasses have to call $this->init() if overwriting this function.
     *
     * @link http://php.net/manual/en/function.unserialize.php
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $this->init();
        $unserializedArr = unserialize($serialized);
        $this->setAttributes($unserializedArr['attributes'],false);
        $this->setOldAttributes($unserializedArr['oldAttributes'],false);
    }

}
