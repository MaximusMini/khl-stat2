<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
// класс 
use app\models\module1\ParserKhlStat;


class Module1Controller extends Controller
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
    
    //ПАРСИНГ 2 - Парсер статистических данных команд КХЛ
    //---------------------------------------------------
    // отображение страницы парсинга
    public function actionParserKhlView_2()
    {
        return $this->render('parser_khl_view_2');
    }
    // парсинг статистических данных
    public function actionParserKhlModel_2()
    {
        // определение модели для парсера данных
//        $model_parser = Yii::$app->request->get('model_parser');
        
        
        // запуск парсера
        
        // подключение модели
        $parser_khl_stat = new ParserKhlStat;
        $parser_khl_stat->main();
        
        
        return $this->render('parser_khl_view_2');
    }
    // отображение таблицы с результатами
     public function actionParserKhlData_2()
    {
        return $this->render('parser_khl_view_2');
    }

    
    
    
    
    
    
    
   
}
