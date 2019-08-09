<?php

use yii\db\Migration;

/**
 * Class m190809_074455_init_user_groups
 */
class m190809_074455_init_user_groups extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%groups}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'description' => $this->text()->null(),
            'is_admin_group' => $this->integer(1)->defaultValue(0),
            'created_at' => $this->integer(11)->null()
        ]);

        $this->insert('{{%groups}}', [
            'name' => 'Administrator',
            'description' => 'Administrator Group',
            'is_admin_group' => '1',
            'created_at' => time()
        ]);

        $this->createTable('{{%group_permission}}', [
            'permission_id' => $this->string(150)->notNull(),
            'group_id' => $this->integer(),
        ]);

        $this->addPrimaryKey('permission_pk', '{{%group_permission}}', [
            'permission_id', 'group_id',
        ]);

        $this->createTable('{{%group_user}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'group_id' => $this->integer(11)->notNull(),
            'is_group_manager' => 'tinyint(1) NOT NULL DEFAULT 0',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190809_074455_init_user_groups cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190809_074455_init_user_groups cannot be reverted.\n";

        return false;
    }
    */
}
