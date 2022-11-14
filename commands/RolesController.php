<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use yii\console\Exception;
use app\models\User;

/**
 * Управление назначением и удалением ролей у пользователя
 * 
 * @package app\commands
 * @since 1.0.0.0
 */
class RolesController extends Controller
{
    /**
     * Метод для присвоение роли пользователю
     * 
     * @return void
     */
    public function actionAssign()
    {
        $email = $this->prompt('Email:', ['required' => true]);
        $user = $this->findModel($email);
        $roleName = $this->select(
            'Роль:',
            ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description')
        );
        $authManager = Yii::$app->getAuthManager();
        $role = $authManager->getRole($roleName);
        $authManager->assign($role, $user->id);
        $this->stdout('Отлично!' . PHP_EOL);
    }
 
    /**
     * Метод удаление роли у пользователя
     * 
     * @return void
     */
    public function actionRevoke()
    {
        $email = $this->prompt('Email:', ['required' => true]);
        $user = $this->findModel($email);
        $roleName = $this->select('Роль:', ArrayHelper::merge(
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
        $this->stdout('Отлично!' . PHP_EOL);
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