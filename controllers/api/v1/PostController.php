<?php
namespace app\controllers\api\v1;

use app\models\forms\PostForm;
use Yii;
use app\models\resource\Post;
use app\models\search\PostSearch;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\base\DynamicModel;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class PostController extends ActiveController
{
    public $modelClass = Post::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Auth all action methods
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class,
        ];

        $behaviors['access'] = [
            'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'post-cache'],
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

        // Удаляем для переопределения
        unset($actions['options'], $actions['create'], $actions['update']);

        $actions['index']['dataFilter'] = [
            'class' => \yii\data\ActiveDataFilter::class,
            'searchModel' => (new DynamicModel(['author_id', 'status']))
                        ->addRule(['author_id'], 'integer')
                        ->addRule(['status'], 'boolean'),
        ];

        return $actions;
    }

    public function actionPostCache()
    {
        if($postList = \Yii::$app->cache->get('cache_all_postList')){
            $post = Post::find()->asArray()->all();
            \Yii::$app->cache->set('cache_all_postList', $postList, 60 * 60 * 2); //Кэш запроса
        }
        Yii::$app->response->statusCode = 200;
        return $post;
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

    
}