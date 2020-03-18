<?php

use yii\db\Migration;

/**
 * Class m200305_074430_user_channels_label
 */
class m200305_074430_user_channels_label extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user_channels}}', 'label', $this->string(16)->defaultValue('lime'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user_channels}}', 'label');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200305_074430_user_channels_label cannot be reverted.\n";

        return false;
    }
    */
}
