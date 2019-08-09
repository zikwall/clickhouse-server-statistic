<?php

use yii\db\Migration;

/**
 * Class m190809_062943_init_permissions
 */
class m190809_062943_init_permissions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%permissions}}', [
            'id' => $this->primaryKey(),
            'permission' => $this->string(50)->notNull(),
        ]);

        $this->createTable('{{%user_permissions}}', [
           'id' => $this->primaryKey(),
           'user_id' => $this->integer(11)->notNull(),
           'permission_id' => $this->integer(11)->notNull()
        ]);

        $this->addForeignKey(
            'fk_user_permission',
            '{{%user_permissions}}', 'user_id',
            '{{%user}}', 'id',
            'CASCADE', 'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190809_062943_init_permissions cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190809_062943_init_permissions cannot be reverted.\n";

        return false;
    }
    */
}
