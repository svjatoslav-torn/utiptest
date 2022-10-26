<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

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
class RbacController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
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
 
        $this->stdout('Done!' . PHP_EOL);
    }
}
