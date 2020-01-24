<?php

use yii\db\Migration;

/**
 * Class m200114_121738_add_description
 */
class m200114_121738_add_description extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'description', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'description');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200114_121738_add_description cannot be reverted.\n";

        return false;
    }
    */
}
