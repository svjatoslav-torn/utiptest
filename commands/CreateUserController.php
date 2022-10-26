<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\forms\RegisterForm;
use app\models\User;
use yii\console\Controller;
use yii\console\ExitCode;
use Yii;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CreateUserController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
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
