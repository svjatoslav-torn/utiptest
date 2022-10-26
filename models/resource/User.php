<?php

namespace app\models\resource;

use app\models\resource\Post;
use app\models\User as ModelsUser;

// use app\models\Post;


class User extends ModelsUser
{
    public function fields()
    {
        return ['id', 'name', 'email'];
    }

    public function extraFields()
    {
        return ['posts'];
    }

    public function getPosts()
    {
        return $this->hasMany(Post::class, ['user_id' => 'id']);
    }
}