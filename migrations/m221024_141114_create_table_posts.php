<?php

use yii\db\Migration;

/**
 * Class m221024_141114_create_table_posts
 */
class m221024_141114_create_table_posts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('posts', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer(11)->notNull(),
            'category_id' => $this->integer(11)->notNull(),
            'title' => $this->string(255)->notNull()->defaultValue(''),
            'body' => $this->text()->notNull()->defaultValue(''),
            'img_path' => $this->string(100),
            'status' => $this->boolean()->defaultValue(false)->notNull(),
            'created_at' => $this->dateTime()->defaultValue(new \yii\db\Expression('NOW()')),
        ]);
        echo "Table Posts successful created.\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('posts');
        echo "Table Posts successful deleted.\n";
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221024_141114_create_table_posts cannot be reverted.\n";

        return false;
    }
    */
}
