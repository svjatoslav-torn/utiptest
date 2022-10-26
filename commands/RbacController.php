<?php
namespace app\commands;

use yii\console\Controller;
use Yii;

/**
 * Rbac init controller
 */
class RbacController extends Controller
{
    /**
     * Создаем роли и права. Прогнать до сидера
     */
    public function actionIndex()
    {
        $auth = Yii::$app->getAuthManager();
        $auth->removeAll();
 
        $manageContent = $auth->createPermission('manageContent');
        $manageContent->description = 'Can create/update';
        $auth->add($manageContent);
 
        $user = $auth->createRole('user');
        $user->description = 'User';
        $auth->add($user);
 
        $admin = $auth->createRole('admin');
        $admin->description = 'Admin';
        $auth->add($admin);
 
        $auth->addChild($admin, $user);
        $auth->addChild($admin, $manageContent);
 
        $this->stdout('Отлично! Следующая команда  php yii seeder' . PHP_EOL);
    }
}
