<?php

use yii\db\Migration;

/**
 * Class m221025_000705_add_column_auth_and_access_token_for_users_table
 */
class m221025_000705_add_column_auth_and_access_token_for_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'auth_key', $this->string(512)->notNull());
        $this->addColumn('users', 'password_hash', $this->string(512)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('users', 'password_hash');
        $this->dropColumn('users', 'auth_key');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221025_000705_add_column_auth_and_access_token_for_users_table cannot be reverted.\n";

        return false;
    }
    */
}
