<?php

namespace app\models\resource;

use app\models\Category as ModelsCategory;
use app\models\resource\Post;
// use app\models\Post;


class Category extends ModelsCategory
{
    public function fields()
    {
        return ['id', 'name'];
    }

    public function extraFields()
    {
        return ['posts'];
    }

    public function getPosts()
    {
        return $this->hasMany(Post::class, ['category_id' => 'id']);
    }
}