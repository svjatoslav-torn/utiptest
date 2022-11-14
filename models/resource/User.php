<?php
namespace app\models\resource;

use app\models\resource\Post;
use app\models\User as ModelsUser;

/**
 * Resource model for User model
 * 
 * @package app\models\resource
 * @since 1.0.0.0
 */
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

    /**
     * Get all posts for User
     * 
     * @return object|Post[]
     */
    public function getPosts()
    {
        return $this->hasMany(Post::class, ['user_id' => 'id']);
    }
}