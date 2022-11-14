<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use Yii;

class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'users';
    }

    public function rules(): array
    {
        return [
            [['name', 'email', 'password_hash'], 'required'],
            ['name', 'string', 'max' => 120],
            ['email', 'email'],
            ['email', 'unique', 'message' => 'Пользователь с таким Email уже зарегистрирован'],
            [['auth_key'], 'safe'],
            ['password_hash', 'string'],
        ];
    }

    /**
     *  Получение юзера по id
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     *  Ауф по токену, с помощью BearerAuth
     * 
     * @param string $token
     * @param string|null $type
     * 
     * @return Token
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()
            ->joinWith('tokens t')
            ->andWhere(['t.token' => $token])
            ->andWhere(['>', 't.expired_at', time()]) //старше сейчас
            ->one();
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByemail($email)
    {
        return static::findOne(['email' => $email]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public function setPassword(string $password)
    {
        return $this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        return $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Validates password
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }

    /**
     * Gets posts this user
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::class, ['author_id' => 'id']);
    }
    /**
     * Gets comments this user
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['user_id' => 'id']);
    }

    /**
     * Gets tokens
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTokens()
    {
        return $this->hasOne(Token::class, ['user_id' => 'id']);
    }
}