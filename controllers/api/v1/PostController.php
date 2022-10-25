<?php
namespace app\controllers\api\v1;

use app\models\forms\PostForm;
use Yii;
use app\models\Post;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;

class PostController extends ActiveController
{
    public $modelClass = Post::class;

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

        unset($actions['options'], $actions['create'], $actions['update']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function actionCreate()
    {
        $formPost = $this->workWithForm();

        if(is_array($formPost)){
            Yii::$app->response->statusCode = 400;
            return $formPost;
        }

        // Cooking data
        $modelPost = $formPost->cookingBeforeSave();
        
        // Model validation and save()
        if($modelPost->validate()){
            if($modelPost->save()){
                // save success - return id post
                Yii::$app->response->statusCode = 201;
                return [
                    'id' => $modelPost->id,
                ];
            }else{
                // don't save - why?
                Yii::$app->response->statusCode = 500;
                return [
                    'message' => 'При сохранении поста произошла ошибка',
                    'errors' => $modelPost->errors,
                ];
            }
        }else{
            // bad validated model
            Yii::$app->response->statusCode = 400;
            return $modelPost;
        }
    }

    public function actionUpdate(int $id)
    {
        $modelPost = Post::findPost($id);

        $formPost = $this->workWithForm();

        if(is_array($formPost)){
            Yii::$app->response->statusCode = 400;
            return $formPost;
        }

        // Cooking data
        $modelPost = $formPost->cookingBeforeSave($modelPost);

        // Model validation and save()
        if($modelPost->validate()){
            if($modelPost->save()){
                // save success - return id post
                Yii::$app->response->statusCode = 201;
                return [
                    'id' => $modelPost->id,
                ];
            }else{
                // don't save - why?
                Yii::$app->response->statusCode = 500;
                return [
                    'message' => 'При сохранении поста произошла ошибка',
                    'errors' => $modelPost->errors,
                ];
            }
        }else{
            // bad validated model
            Yii::$app->response->statusCode = 400;
            return $modelPost;
        }
    }

    private function workWithForm(){
        $formPost = new PostForm();
        $formPost->load(Yii::$app->request->bodyParams, '');

        // Form validation
        if(! $formPost->validate()){
        
            return $formPost->errors;
        }

        return $formPost;
    }

    public function prepareDataProvider($data)
    {   
// var_dump($data->pagination);die;
        $query = [$this->modelClass, 'find'];
            return new ActiveDataProvider([
                'query' => $query(),
                'pagination' => [
                    'pageSize' => Yii::$app->request->post('per-page'),
                    // 'offset' => Yii::$app->request->post('offset'),
                ],
            ]);
        // return 'hello';
    }
}