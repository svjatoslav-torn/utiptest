<?php

namespace app\models\resource;

use app\models\Comment as ModelsComment;
use app\models\resource\User;

class Comment extends ModelsComment
{
    public function fields()
    {
        return ['body', 'user_id'];
    }

    public function extraFields()
    {
        return ['post'];
    }

    /**
     * Получить пост коммента
     */
    public function getPost()
    {
        return $this->hasOne(Post::class, ['id' => 'post_id']);
    }

    /**
     * Получить юзера коммента из ресурса
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}