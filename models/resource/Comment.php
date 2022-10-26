<?php

namespace app\models\resource;

use app\models\Comment as ModelsComment;
use app\models\User;
// use app\models\Post;


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
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::class, ['id' => 'post_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}