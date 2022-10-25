<?php

use yii\db\Migration;

/**
 * Class m221025_081603_add_table_token
 */
class m221025_081603_add_table_token extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('token', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'token' => $this->string()->notNull()->unique(),
            'expired_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-token-user_id', 'token', 'user_id');
        $this->addForeignKey('fk-token-user_id', 'token', 'user_id', 'users', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-token-user_id', 'token');
        $this->dropIndex('idx-token-user_id', 'token');
        $this->dropTable('token');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221025_081603_add_table_token cannot be reverted.\n";

        return false;
    }
    */
}
