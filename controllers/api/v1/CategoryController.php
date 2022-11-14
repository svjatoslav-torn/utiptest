<?php
namespace app\controllers\api\v1;

use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\filters\AccessControl;
use app\models\resource\Category;

/**
 * Ресурсный контроллер Категорий
 * 
 * @property Category $modelClass
 * 
 * @package app\controllers\api\v1
 * @since 1.0.0.0
 */
class CategoryController extends ActiveController
{
    public $modelClass = Category::class;

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class,
        ];

        $behaviors['access'] = [
            'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view'],
                        'roles' => ['user'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete'],
                        'roles' => ['admin'],
                    ],
                ],
            ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['options']);
        return $actions;
    }
}