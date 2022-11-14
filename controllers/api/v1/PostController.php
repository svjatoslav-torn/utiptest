<?php
namespace app\controllers\api\v1;

use Yii;
use yii\rest\ActiveController;
use yii\base\DynamicModel;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\AccessControl;
use app\models\forms\PostForm;
use app\models\resource\Post;

/**
 * Ресурсный контроллер Постов
 * 
 * @property Post $modelClass
 * 
 * @package app\controllers\api\v1
 * @since 1.0.0.0
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
     * 
     * @return yii\web\Response
     */
    public function actionPostCache()
    {
        if (! $postList = \Yii::$app->cache->get('cache_all_postList')) {
            $postList = Post::find()->asArray()->all();
            \Yii::$app->cache->set('cache_all_postList', $postList, 60 * 60 * 2);
        }

        Yii::$app->response->statusCode = 200;
        return $postList;
    }

    /**
     * Создание Поста
     * 
     * @return yii\web\Response
     */
    public function actionCreate()
    {
        $formPost = $this->workWithForm();

        if (is_array($formPost)) {
            Yii::$app->response->statusCode = 400;
            return $formPost;
        }

        // Готовим данные
        $modelPost = $formPost->cookingBeforeSave();

        // Валидация не состоялась отправляем результат и 400 код
        if (!$modelPost->validate()) {
            Yii::$app->response->statusCode = 400;
            return $modelPost;
        }

        if (!$modelPost->save()) {
            Yii::$app->response->statusCode = 500;
            return [
                'message' => 'При сохранении поста произошла ошибка',
                'errors' => $modelPost->errors,
            ];
        }

        if (
            count($tags = explode('|', $formPost->tags)) > 0
            && $tags[0] !== ''
        ) {
            for ($i=0; $i < count($tags); $i++) {
                \Yii::$app->db->createCommand(
                    "INSERT INTO `posts_tags` (`post_id`, `tag_id`) VALUES ('{$modelPost->id}', '{$tags[$i]}')"
                )->queryAll();
            }
        }
        // save success - return id post
        Yii::$app->response->statusCode = 201;
        return [
            'id' => $modelPost->id,
        ];
    }

    /**
     * Редактирование Поста
     * 
     * @param int $id
     * 
     * @return yii\web\Response
     */
    public function actionUpdate(int $id)
    {
        if (YII_ENV !== 'prod') {
            Yii::info(Yii::$app->request->bodyParams, 'dev_updatePost_log');
        }

        $modelPost = Post::findPost($id);

        $formPost = $this->workWithForm();

        if (is_array($formPost)) {
            Yii::$app->response->statusCode = 400;
            return $formPost;
        }

        $modelPost = $formPost->cookingBeforeSave($modelPost);

        if (!$modelPost->validate()) {
            Yii::$app->response->statusCode = 400;
            return $modelPost;
        }

        if (!$modelPost->save()) {
            Yii::$app->response->statusCode = 500;
            return [
                'message' => 'При сохранении поста произошла ошибка',
                'errors' => $modelPost->errors,
            ];
        }

        Yii::$app->response->statusCode = 201;
        return [
            'id' => $modelPost->id,
        ];

    }

    // Попытка борьбы с копипастой)
    private function workWithForm(){
        $formPost = new PostForm();
        $formPost->load(Yii::$app->request->bodyParams, '');

        if (!$formPost->validate()) {
            return $formPost->errors;
        }

        return $formPost;
    }
}