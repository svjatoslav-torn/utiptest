<?php
namespace app\controllers\api\v1;

use app\models\Tag;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\filters\AccessControl;

class TagController extends ActiveController
{    
    public $modelClass = Tag::class;

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