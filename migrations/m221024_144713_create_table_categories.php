<?php

use yii\db\Migration;

/**
 * Class m221024_144713_create_table_categories
 */
class m221024_144713_create_table_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('categories', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
        ]);
        echo "Table Categories successful created.\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('categories');
        echo "Table Categories successful deleted.\n";
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221024_144713_create_table_categories cannot be reverted.\n";

        return false;
    }
    */
}
