<?php
namespace app\models\resource;

use app\models\Category as ModelsCategory;
use app\models\resource\Post;

/**
 * Resources model for Category model
 * 
 * @package app\models\resource
 * @since 1.0.0.0
 * @author Svjatoslav Larshin
 */
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