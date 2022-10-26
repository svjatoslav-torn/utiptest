<?php
namespace app\controllers\api\v1;

use app\models\forms\PostForm;
use Yii;
use app\models\resource\Post;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\base\DynamicModel;
use yii\filters\AccessControl;

/**
 * Ресурсный контроллер Постов
 */
class PostController extends ActiveController
{
    public $modelClass = Post::class;

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

    /**
     * Переопределение действий
     */
    public function actions()
    {
        $actions = parent::actions();

        unset($actions['options'], $actions['create'], $actions['update']);

        $actions['index']['dataFilter'] = [
            'class' => \yii\data\ActiveDataFilter::class,
            'searchModel' => (new DynamicModel(['author_id', 'status']))
                        ->addRule(['author_id'], 'integer')
                        ->addRule(['status'], 'boolean'),
        ];

        return $actions;
    }

    /**
     * Экшен отдает КЭШ всех постов. Кэш храниться 2 часа
     * КЭШ решил реализовать тут чтобы показать что хоть что то об этом знаю,
     * так как хз как это сделать в rest/activecontroller - не переопределять ведь теперь все))
     */
    public function actionPostCache()
    {
        if(! $postList = \Yii::$app->cache->get('cache_all_postListeg')){
            $postList = Post::find()->asArray()->all();
            \Yii::$app->cache->set('cache_all_postListeg', $postList, 60 * 60 * 2); //Кэш запроса
        }
        
        Yii::$app->response->statusCode = 200;
        return $postList;
    }

    /**
     * Создание Поста
     */
    public function actionCreate()
    {        
        $formPost = $this->workWithForm();

        if(is_array($formPost)){
            Yii::$app->response->statusCode = 400;
            return $formPost;
        }

        // Готови данные
        $modelPost = $formPost->cookingBeforeSave();
        
        // Model validation and save()
        if($modelPost->validate()){
            if($modelPost->save()){
                //Не понял как сохранять в связанную таблицу. Привет велосипед)
                if(count($tags = explode('|', $formPost->tags)) > 1){
                    for ($i=0; $i < count($tags); $i++) { 
                        \Yii::$app->db->createCommand("INSERT INTO `posts_tags` (`post_id`, `tag_id`) VALUES ('{$modelPost->id}', '{$tags[$i]}')")
                            ->queryAll();
                    }
                }
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

    /**
     * Редактирование Поста
     */
    public function actionUpdate(int $id)
    {
        // Пишем в лог бодипарамс при попытке апдейта Поста на дев окружении
        if(YII_ENV !== 'prod'){
            Yii::info(Yii::$app->request->bodyParams, 'dev_updatePost_log');
        }

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

    // Попытка борьбы с копипастой)
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