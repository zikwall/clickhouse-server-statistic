<?php

use yii\db\Migration;
use yii\db\Schema;

class m000000_000000_core_initial extends Migration
{
    public function up()
    {
        // create base setting table
        $this->createTable('{{%setting}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'value' => $this->string(255)->notNull(),
            'module_id' => $this->string(100)->null(),
        ], '');
        
        // create session table
        try {
            // exist?
            $this->createTable('{{%user_http_session}}', [
                'id' => Schema::TYPE_CHAR . '(32) NOT NULL',
                'expire' => $this->integer(11)->null(),
                'user_id' => $this->integer(11)->null(),
                'data' => 'longblob DEFAULT NULL',
            ], '');

            $this->addPrimaryKey('pk_user_http_session', '{{%user_http_session}}', 'id');

        } catch (Exception $ex) {

        }
    }

    public function down()
    {
        echo "m000000_000000_core_initial does not support migration down.\n";
        return false;
    }
}
