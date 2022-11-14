<?php
namespace app\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property int $post_id
 * @property int $user_id
 * @property string $body
 * @property string|null $created_at
 *
 * @property Post $post
 * @property User $user
 */
class Comment extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * Правила валидации
     * 
     * @return array
     */
    public function rules()
    {
        return [
            [['post_id', 'user_id', 'body'], 'required'],
            [['post_id', 'user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['body'], 'string', 'max' => 255],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::class, 'targetAttribute' => ['post_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * Именование атрибутов
     * 
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'ИД поста',
            'user_id' => 'ИД пользователя',
            'body' => 'Текст',
            'created_at' => 'Дата создания',
        ];
    }

    /**
     * Получить пост с комментов
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::class, ['id' => 'post_id']);
    }

    /**
     * Получить юзера
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
