<?php

use yii\db\Migration;
use \yii\db\Schema;
use yii\rbac\Item;
use zikwall\encore\modules\user\models\User;
use zikwall\encore\modules\user\Module;
use zikwall\encore\modules\core\libs\UUID;

class m131023_164513_initial extends Migration
{
    public function up()
    {
        try {
            $this->createTable('{{%user_http_session}}', [
                'id' => 'char(32) NOT NULL',
                'expire' => 'int(11) DEFAULT NULL',
                'user_id' => 'int(11) DEFAULT NULL',
                'data' => 'longblob DEFAULT NULL',
            ], '');
            $this->addPrimaryKey('pk_user_http_session', '{{%user_http_session}}', 'id');
        } catch (Exception $ex) {
            print_r('Nu et pizdec...');
        }
    }

    public function down()
    {
        echo "m131023_164513_initial does not support migration down.\n";
        return false;
    }

}
