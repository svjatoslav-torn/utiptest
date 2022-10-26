<?php

namespace app\models\forms;

use app\models\User;
use Yii;
use yii\base\Model;

/**
 * Форма для данных при регистрации
 */
class RegisterForm extends Model
{
    public $name;
    public $email;
    public $password;

    /**
     * @return array the validation rules.
     */
    public function rules()
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
     */
    public function register()
    {
        // Кидаем ошибку при провале валидации Формы
        if (! $this->validate()) {
            Yii::$app->response->statusCode = 400;            
            return $this->errors;
        }

        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        // Чекаем валидацию модели (в основном для отлова не уникальности почты в БД)
        if(!$user->validate()){
            Yii::$app->response->statusCode = 418;            
            return $user->errors;
        }

        
        if($user->save()){
            Yii::$app->response->statusCode = 201;  

            $authManager = Yii::$app->getAuthManager();
            $role = $authManager->getRole('user');
            $authManager->assign($role, $user->id);  
                    
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];
        }else{
            Yii::$app->response->statusCode = 520;
            return [
                'message' => 'По каким то неизвестным причинам сохранение не произошло',
                'errors' => $user->errors,
            ];
        }

    }

}
