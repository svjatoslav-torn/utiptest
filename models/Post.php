<?php

namespace app\models;

use Yii;
use app\models\Tag;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $author_id
 * @property int $category_id
 * @property string $title
 * @property string $body
 * @property string|null $img_path
 * @property int $status
 * @property string|null $created_at
 *
 * @property User $author
 * @property Category $category
 * @property Comments[] $comments
 * @property PostsTags $id0
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'posts';
    }

    /**
     * Правила валидации
     * 
     * @return array
     */
    public function rules(): array
    {
        return [
            ['category_id', 'required', 'message' => 'Вы не передали идентификатор категории'],
            [['author_id', 'category_id'], 'integer'],
            ['status', 'boolean'],
            [['body'], 'string'],
            [['created_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['img_path'], 'string', 'max' => 100],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * Именование атрибутов
     * 
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'author_id' => 'Автор',
            'category_id' => 'Категория',
            'title' => 'Заголовок',
            'body' => 'Контент',
            'img_path' => 'Путь до изображения',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
        ];
    }

    /**
     * {@inheritdoc}
     * @return PostQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PostQuery(get_called_class());
    }

    /**
     * Получение поста по ID
     */
    public static function findPost($id)
    {
        return static::findOne(['id' => $id]);
    }

    /** 
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['post_id' => 'id']);
    }

    /**
     * Получаем теги
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::class, ['id' => 'post_id'])
            ->viaTable('posts_tags', ['tag_id' => 'id']);
    }
}