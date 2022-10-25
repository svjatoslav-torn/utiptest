<?php

use yii\db\Migration;

/**
 * Class m221025_130142_fast_fix_foreign_keys
 */
class m221025_130142_fast_fix_foreign_keys extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-posts-id-posts_tags-post_id', 'posts');

        $this->createIndex('idx-posts_tag-post_id2', 'posts_tags', 'post_id');
        $this->addForeignKey('fk-posts-id-posts_tags-post_id', 'posts_tags', 'post_id', 'posts', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-posts-id-posts_tags-post_id', 'posts_tags');
        $this->dropIndex('idx-posts_tag-post_id2', 'posts_tags');
        // Внешний ключ на промежуточную таблицу тегов
        $this->addForeignKey('fk-posts-id-posts_tags-post_id', 'posts', 'id', 'posts_tags', 'post_id', 'CASCADE');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221025_130142_fast_fix_foreign_keys cannot be reverted.\n";

        return false;
    }
    */
}
