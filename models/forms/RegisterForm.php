<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\User;

/**
 * Форма для данных при регистрации
 * 
 * @property string $name
 * @property string $email
 * @property string $password
 * 
 * @package app\models\forms
 * @since 1.0.0.0
 */
class RegisterForm extends Model
{
    public string $name;
    public string $email;
    public string $password;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            ['name', 'required', 'message' => 'Поле Имя обязательно для заполнения'],
            ['email', 'required', 'message' => 'Поле Email обязательно для заполнения'],
            ['password', 'required', 'message' => 'Поле Пароль обязательно для заполнения'],
            ['name', 'string', 'max' => 120],
            ['email', 'email', 'message' => 'Адрес электронно почты в формате sefkiss.torn@yandex.ru'],
            ['password', 'string', 'min' => 8],
        ];
    }

    /**
     * Регистрация
     * 
     * @return yii\web\Response
     */
    public function register()
    {
        if (!$this->validate()) {
            Yii::$app->response->statusCode = 400;
            return $this->errors;
        }

        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        // Чекаем валидацию модели (в основном для отлова не уникальности почты в БД)
        if (!$user->validate()) {
            Yii::$app->response->statusCode = 418;
            return $user->errors;
        }

        if (!$user->save()) {
            Yii::$app->response->statusCode = 520;
            return [
                'message' => 'По каким то неизвестным причинам сохранение не произошло',
                'errors' => $user->errors,
            ];
        }

        Yii::$app->response->statusCode = 201;
        $authManager = Yii::$app->getAuthManager();
        $role = $authManager->getRole('user');
        $authManager->assign($role, $user->id);  

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];
    }
}