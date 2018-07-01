<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class Module2Controller extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionMain()
    {
        return $this->render('main');
    }
    
    
    // вывод интерфейса для формирования данных постера 
    public function actionAmountMatches($amount)
    {
         if ($amount === null) {$amount = 0;}
         return $this->render('main',['amount'=>$amount]);
    }
    
    // формирование и вывод постера с матчами дня
     public function actionPosterGameDay()
    {
         // получаем массив с данными из вида main.php
         $data_poster = Yii::$app->request->get();
         return $this->render('poster',['data_poster'=>$data_poster]);
    }
    
    
    
    
    
    
    
    

   
}
