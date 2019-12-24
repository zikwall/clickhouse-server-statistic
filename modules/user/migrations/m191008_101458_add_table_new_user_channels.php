<?php

use yii\db\Migration;
use app\modules\user\models\UserChannels;

/**
 * Class m191008_101458_add_table_new_user_channels
 */
class m191008_101458_add_table_new_user_channels extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(UserChannels::tableName(), [
            'id'            => $this->primaryKey(),
            'user_id'       => $this->integer(),
            'channel_id'    => $this->integer(),
        ]);
        
        $this->createIndex('user_channel_index', 'new_user_channels', ['user_id', 'channel_id'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('user_channel_index', UserChannels::tableName());
        $this->dropTable('new_user_channels');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191008_101458_add_table_new_user_channels cannot be reverted.\n";

        return false;
    }
    */
}
