<?php
namespace app\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $name
 *
 * @property Post[] $posts
 */
class Category extends \yii\db\ActiveRecord
{
    public static function tableName(): string
    {
        return 'categories';
    }

    /**
     * Правила валидации
     * 
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name'], 'required', 'message' => 'Отсутствует поле Название'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * ИМенование атрибутов
     * 
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Название категории',
        ];
    }

    /**
     * Получить все посты категории
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::class, ['category_id' => 'id']);
    }
}