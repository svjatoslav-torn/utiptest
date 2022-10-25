<?php

use yii\db\Migration;

/**
 * Class m221025_084241_drop_col_password_for_table_users
 */
class m221025_084241_drop_col_password_for_table_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('users', 'password');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('users', 'password', $this->string()->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221025_084241_drop_col_password_for_table_users cannot be reverted.\n";

        return false;
    }
    */
}
