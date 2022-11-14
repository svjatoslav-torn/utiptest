<?php
namespace app\models\forms;

use yii\base\Model;
use app\models\Token;
use app\models\User;

/**
 * Форма для данных при логине пользователя.
 * 
 * @property string $email
 * @property string $password
 * 
 * @package app\models\forms
 * @since 1.0.0.0
 */
class LoginForm extends Model
{
    public $email;
    public $password;

    private $_user = false;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['email', 'required', 'message' => 'Пожалуйста отправьте свой Email'],
            ['password', 'required', 'message' => 'Пожалуйста отправьте пароль'],
            ['email', 'email', 'message' => 'Введите нормальную валидную почту'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            //Проверять хешированный пароль - готово
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError(
                    $attribute,
                    'Не верный логин или пароль. Проверьте учетные данные!'
                );
            }
        }
    }

    /**
     * Аутентификация
     * 
     * @return Token|null
     */
    public function auth(): Token|null
    {
        $token = new Token();
        $token->user_id = $this->getUser()->id;
        $token->generateToken(time() + 3600 * 24);

        return $token->save() ? $token : null;
    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByemail($this->email);
        }

        return $this->_user;
    }
}