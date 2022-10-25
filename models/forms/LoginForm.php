<?php

namespace app\models\forms;

use app\models\Token;
use Yii;
use yii\base\Model;
use app\models\User;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class LoginForm extends Model
{
    public $email;
    public $password;

    private $_user = false;


    /**
     * @return array the validation rules.
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
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            //Проверять хешированный пароль - готово
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Не верный логин или пароль. Проверьте учетные данные!');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function auth()
    {
        $token = new Token();
        $token->user_id = $this->getUser()->id;
        $token->generateToken( time() + 3600 * 24 );

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
