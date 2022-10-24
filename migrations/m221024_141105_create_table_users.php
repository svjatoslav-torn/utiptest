<?php

use yii\db\Migration;

/**
 * Class m221024_141105_create_table_users
 */
class m221024_141105_create_table_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'name' => $this->string(120)->notNull(),
            'email' => $this->string(100)->unique()->notNull(),
            'password' => $this->string()->notNull(),
        ]);
        echo "Table Users successful created.\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('users');
        echo "Table Users successful deleted.\n";
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221024_141105_create_table_users cannot be reverted.\n";

        return false;
    }
    */
}
