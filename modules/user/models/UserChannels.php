<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\user\models;

use app\modules\core\components\base\ActiveRecord;

/**
 * Description of UserChannels
 *
 * @author user
 */
class UserChannels extends ActiveRecord
{   
    public static function tableName() {
        return '{{%user_channels}}';
    }
    
    public function rules()
    {
        return [
            [['user_id', 'channel_id'], 'integer'],
            [['user_id', 'channel_id'], 'required'],
            [['user_id', 'channel_id'], 'unique', 'targetAttribute' => ['user_id', 'channel_id']],
        ];
    }

}
