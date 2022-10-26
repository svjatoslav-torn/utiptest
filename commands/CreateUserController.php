<?php
namespace app\commands;

use app\models\User;
use yii\console\Controller;
use Yii;

/**
 *  Консольный контроллер. Создание новых юзеров, обычных или админов.
 */
class CreateUserController extends Controller
{
    /**
     * Экшен создания юзера
     * @param string $role
     * @return void
     */
    public function actionIndex(?string $role = null)
    {
        $name = $this->prompt('Введите Имя:', ['required' => true]);
        $email = $this->prompt('Введите Email:', ['required' => true]);
        $password = $this->prompt('Введите Пароль:', ['required' => true]);

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->setPassword($password);
        $user->generateAuthKey();

        if(!$user->validate()){        
            echo 'Проверьте данные, валидация провалена';
        }

        if($user->save()){ 

            $authManager = Yii::$app->getAuthManager();
            $role = $authManager->getRole($role === 'admin' ? 'admin' : 'user');
            $authManager->assign($role, $user->id);  
                    
            echo 'Пользователь успешно создан';

        }else{
            echo [
                'message' => 'По каким то неизвестным причинам сохранение не произошло',
                'errors' => $user->errors,
            ];
        }
    }
}
