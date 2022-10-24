<?php

use yii\db\Migration;

/**
 * Class m221024_141132_create_table_tags
 */
class m221024_141132_create_table_tags extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('tags', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull(),
        ]);
        echo "Table Tags successful created.\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('tags');
        echo "Table Tags successful deleted.\n";
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221024_141132_create_table_tags cannot be reverted.\n";

        return false;
    }
    */
}
