<?php
namespace app\models\resource;

use app\models\Comment as ModelsComment;
use app\models\resource\User;

/**
 * Recource model for Comment model
 * 
 * @package app\models\resource
 * @since 1.0.0.0
 */
class Comment extends ModelsComment
{
    public function fields()
    {
        return ['body', 'user_id'];
    }

    public function extraFields()
    {
        return ['post', 'user'];
    }

    /**
     * Получить пост коммента
     * 
     * @return Post
     */
    public function getPost()
    {
        return $this->hasOne(Post::class, ['id' => 'post_id']);
    }

    /**
     * Получить юзера коммента из ресурса
     * 
     * @return Post
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}