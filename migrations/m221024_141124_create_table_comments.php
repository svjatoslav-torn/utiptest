<?php

use yii\db\Migration;

/**
 * Class m221024_141124_create_table_comments
 */
class m221024_141124_create_table_comments extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('comments', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'body' => $this->string(255)->notNull(),
            'created_at' => $this->dateTime()->defaultValue(new \yii\db\Expression('NOW()')),
        ]);
        echo "Table Comments successful created.\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropTable('comments');
        echo "Table Comments successfuldeleted.\n";
    }
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221024_141124_create_table_comments cannot be reverted.\n";

        return false;
    }
    */
}
