<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%foreign_keys_for_posts}}`.
 */
class m221024_145953_create_foreign_keys_for_posts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Внешний ключ на таблицу юзеров
        $this->addForeignKey('fk-posts-author_id-users-id', 'posts', 'author_id', 'users', 'id', 'CASCADE');
        
        // Внешний ключ на таблицу категорий постов
        $this->addForeignKey('fk-posts-category_id-categories-id', 'posts', 'category_id', 'categories', 'id', 'CASCADE');
        
        // $this->createIndex('idx-posts_tag-post_id', 'posts_tags', 'post_id');
        // Внешний ключ на промежуточную таблицу тегов
        // $this->addForeignKey('fk-posts-id-posts_tags-post_id', 'posts', 'id', 'posts_tags', 'post_id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // $this->dropForeignKey('fk-posts-id-posts_tags-post_id', 'posts');
        // $this->dropIndex('idx-posts_tag-post_id', 'posts_tags');
        $this->dropForeignKey('fk-posts-category_id-categories-id', 'posts');
        $this->dropForeignKey('fk-posts-author_id-users-id', 'posts');
    }
}
