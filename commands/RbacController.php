<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

/**
 * Rbac init controller
 * 
 * @package app\commands
 * @since 1.0.0.0
 */
class RbacController extends Controller
{
    /**
     * Создаем роли и права. Прогнать до сидера
     * 
     * @return void
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

        echo 'Отлично! Следующая команда  php yii seeder';
        echo PHP_EOL;
        return ;
    }
}