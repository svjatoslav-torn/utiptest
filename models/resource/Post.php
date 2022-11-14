<?php
namespace app\models\resource;

use Yii;
use app\models\Tag;
use app\models\Post as ModelsPost;
use app\models\resource\Comment;
use app\models\resource\Category;
use app\models\resource\User;

/**
 * Resource model for Post model
 * 
 * @package app\models\resource
 * @since 1.0.0.0
 */
class Post extends ModelsPost
{
    public function fields()
    {
        return [
            'id',
            'title',
            'body',
            'status',
            'author_id',
            'category_id',
            'img_path' => function() {
                if ($this->img_path !== null && strlen($this->img_path) > 1) {
                    return Yii::$app->request->hostname . '/' . $this->img_path;
                }
                return null;
            },
        ];
    }

    public function extraFields()
    {
        return ['comments', 'category', 'author', 'tags'];
    }

    /**
     * Get all comments for Post
     * 
     * @return object|Comments
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['post_id' => 'id']);
    }
    
    /**
     * Get category for Post
     * 
     * @return Category
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }
    
    /**
     * Get author for Post
     * 
     * @return User
     */
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }
    
    /**
     * Get all tags for Post
     * 
     * @return object|Tags
     */
    public function getTags()
    {
        return $this->hasMany(
                Tag::class,
                ['id' => 'tag_id']
            )->viaTable('posts_tags', ['post_id' => 'id']);
    }
}