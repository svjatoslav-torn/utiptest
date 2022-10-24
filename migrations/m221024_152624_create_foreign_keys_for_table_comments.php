<?php

use yii\db\Migration;

/**
 * Class m221024_152624_create_foreign_keys_for_table_comments
 */
class m221024_152624_create_foreign_keys_for_table_comments extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Внешний ключ на таблицу постов (у одного поста много комментов)
        $this->addForeignKey('fk-comments-post_id-posts-id', 'comments', 'post_id', 'posts', 'id', 'CASCADE');
        $this->addForeignKey('fk-comments-user_id-users-id', 'comments', 'user_id', 'users', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop reverse
        $this->dropForeignKey('fk-comments-user_id-users-id', 'comments');
        $this->dropForeignKey('fk-comments-post_id-posts-id', 'comments');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221024_152624_create_foreign_keys_for_table_comments cannot be reverted.\n";

        return false;
    }
    */
}
