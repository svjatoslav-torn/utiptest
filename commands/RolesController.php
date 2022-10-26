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
use yii\helpers\ArrayHelper;
use app\models\User;
use yii\console\Exception;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class RolesController extends Controller
{
    /**
     * Adds role to user
     */
    public function actionAssign()
    {
        $email = $this->prompt('Email:', ['required' => true]);
        $user = $this->findModel($email);
        $roleName = $this->select('Role:', ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description'));
        $authManager = Yii::$app->getAuthManager();
        $role = $authManager->getRole($roleName);
        $authManager->assign($role, $user->id);
        $this->stdout('Done!' . PHP_EOL);
    }
 
    /**
     * Removes role from user
     */
    public function actionRevoke()
    {
        $email = $this->prompt('Email:', ['required' => true]);
        $user = $this->findModel($email);
        $roleName = $this->select('Role:', ArrayHelper::merge(
            ['all' => 'All Roles'],
            ArrayHelper::map(Yii::$app->authManager->getRolesByUser($user->id), 'name', 'description'))
        );
        $authManager = Yii::$app->getAuthManager();
        if ($roleName == 'all') {
            $authManager->revokeAll($user->id);
        } else {
            $role = $authManager->getRole($roleName);
            $authManager->revoke($role, $user->id);
        }
        $this->stdout('Done!' . PHP_EOL);
    }
 
    /**
     * @param string $email
     * @throws \yii\console\Exception
     * @return User the loaded model
     */
    private function findModel($email)
    {
        if (!$model = User::findOne(['email' => $email])) {
            throw new Exception('User is not found');
        }
        return $model;
    }
}
