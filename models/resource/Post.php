<?php

namespace app\models\resource;

use Yii;
use app\models\Comment;
use app\models\resource\Category;
use app\models\Post as ModelsPost;

class Post extends ModelsPost
{
    public function fields()
    {
        return ['id', 'title', 'body', 'status', 'author_id', 'category_id',
            'img_path' => function () {
                if($this->img_path !== null && strlen($this->img_path) > 1){
                    return Yii::$app->request->hostname . '/' . $this->img_path;
                }
                return null;
            },
        ];
    }

    public function extraFields()
    {
        return ['comments', 'category'];
    }

    public function getComments()
    {
        return $this->hasMany(Comment::class, ['post_id' => 'id']);
    }

    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }
}