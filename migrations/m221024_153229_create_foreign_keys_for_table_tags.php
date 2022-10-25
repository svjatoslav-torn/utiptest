<?php

use yii\db\Migration;

/**
 * Class m221024_153229_create_foreign_keys_for_table_tags
 */
class m221024_153229_create_foreign_keys_for_table_tags extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-posts_tag-tag_id', 'posts_tags', 'tag_id');
        $this->addForeignKey('fk-tags-id-posts_tags-tag_id', 'tags', 'id', 'posts_tags', 'tag_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-tags-id-posts_tags-tag_id', 'posts_tags');
        $this->dropIndex('idx-posts_tag-tag_id', 'posts_tags');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221024_153229_create_foreign_keys_for_table_tags cannot be reverted.\n";

        return false;
    }
    */
}
