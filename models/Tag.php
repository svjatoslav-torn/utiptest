<?php
namespace app\models;

use Yii;
use app\models\Post;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "tag".
 *
 * @property int $id
 * @property string $name
 *
 * @property PostsTags $id0
 */
class Tag extends \yii\db\ActiveRecord
{
    public static function tableName(): string
    {
        return 'tags';
    }

    public function extraFields(): array
    {
        return ['posts'];
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 30],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosts(): ActiveQuery
    {
        return $this->hasMany(Post::class, ['id' => 'post_id'])
            ->viaTable('posts_tags', ['tag_id' => 'id']);
    }
}