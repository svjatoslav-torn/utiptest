<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\User;

/**
 * Консольный контроллер. Создание новых юзеров, обычных или админов.
 * 
 * @package app\commands
 * @since 1.0.0.0
 */
class CreateUserController extends Controller
{
    /**
     * Экшен создания пользователя
     * 
     * @param string|null $role - ждем здесь название роль
     * @return void
     */
    public function actionIndex(string $role = null): void
    {
        $name = $this->prompt('Введите Имя:', ['required' => true]);
        $email = $this->prompt('Введите Email:', ['required' => true]);
        $password = $this->prompt('Введите Пароль:', ['required' => true]);

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->setPassword($password);
        $user->generateAuthKey();

        if (!$user->validate()) {
            echo 'Проверьте данные, валидация провалена';
            return ;
        }

        if ($user->save()) {

            $authManager = Yii::$app->getAuthManager();
            $role = $authManager->getRole($role === 'admin' ? 'admin' : 'user');
            $authManager->assign($role, $user->id);

            echo 'Пользователь успешно создан';
            return ;

        } else {
            echo [
                'message' => 'По каким то неизвестным причинам сохранение не произошло',
                'errors' => $user->errors,
            ];
            return ;
        }
    }
}