<?php

use yii\db\Migration;

/**
 * Class m221024_141148_create_table_posts_tags
 */
class m221024_141148_create_table_posts_tags extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('posts_tags', [
            'post_id' => $this->integer(11),
            'tag_id' => $this->integer(11),
            'PRIMARY KEY(post_id, tag_id)',
        ]);
        echo "Table Posts-Tags many-to-many successful created.\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('posts_tags');
        echo "Table Posts-Tags many-to-many successful deleted.\n";
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221024_141148_create_table_posts_tags cannot be reverted.\n";

        return false;
    }
    */
}
