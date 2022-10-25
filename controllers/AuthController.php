<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\rest\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\forms\LoginForm;
use app\models\forms\RegisterForm;
use app\models\User;
use app\models\Token;

class AuthController extends Controller
{
    /**
     * {@inheritdoc}
     */
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

        if(!$loginForm->validate()){
            Yii::$app->response->statusCode = 400;
            return $loginForm->errors; 
        }

        if($token = $loginForm->auth()){
            // var_dump($token);die;
            return [
                'token' => $token->token,
                'expired' => date(DATE_RFC3339, $token->expired_at),
                'user_id' => $loginForm->getUser()->id,
            ];
        }else{
            return $loginForm; // выкинет ерорс если не удача
        }
    }
}
