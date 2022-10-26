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
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['name'], 'required', 'message' => 'Отсутствует поле Название'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * ИМенование атрибутов
     */
    public function attributeLabels()
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
