<?php
namespace app\controllers\api\v1;

use app\models\Category;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class CategoryController extends ActiveController
{    
    public $modelClass = Category::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class,
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