<?php
namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\forms\LoginForm;
use app\models\forms\RegisterForm;

/**
 * Класс для авторизации и регистрации пользователей
 * 
 * @package app\controllers
 * @since 1.0.0.0
 */
class AuthController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'register' => ['post'],
                    'login' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action) 
    {
        $this->enableCsrfValidation = false; 
        return parent::beforeAction($action); 
    }

    /**
     * Register action.
     *
     * @return Response|string
     */
    public function actionRegister()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $form = new RegisterForm();
        $form->load(Yii::$app->request->post(), '');
        return $form->register();
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $loginForm = new LoginForm();
        $loginForm->load(Yii::$app->request->bodyParams, '');

        if (!$loginForm->validate()) {
            Yii::$app->response->statusCode = 400;
            return $loginForm->errors; 
        }

        if ($token = $loginForm->auth()) {
            return [
                'token' => $token->token,
                'expired' => date(DATE_RFC3339, $token->expired_at),
                'user_id' => $loginForm->getUser()->id,
            ];
        } else {
            return $loginForm;
        }
    }
}