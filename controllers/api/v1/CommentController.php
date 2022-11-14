<?php
namespace app\controllers\api\v1;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\AccessControl;
use app\models\resource\Comment;

/**
 * Ресурсный  контроллер  Коментариев
 * 
 * @property Comment $modelClass
 * 
 * @package app\controllers\api\v1
 * @since 1.0.0.0
 */
class CommentController extends ActiveController
{    
    public $modelClass = Comment::class;

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
                        'actions' => ['index', 'view', 'create', 'update'],
                        'roles' => ['user'],
                    ],
                    // Удалять коменты может только админ
                    [
                        'allow' => true,
                        'actions' => ['delete'],
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