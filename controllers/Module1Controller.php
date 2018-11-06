<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
// классы - модели 
use app\models\module1\ParserKhlStat;
use app\models\module1\ParserKhlStat_2018;
use app\models\module1\ViewKhlStat;
use app\models\module1\ParserKhlResults_2018;
use app\models\module1\ParserKhlTable_18_19;


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
    
    //ПАРСИНГ 1 - Парсер турнирной таблицы КХЛ сезон 2018/2019
    //---------------------------------------------------
    
    // главная страница парсера
    public function actionParserKhlTable_18_19()
    {
        return $this->render('parser-khl-table_18_19');
    }
    
    // отображение результатов парсинга - таблицы из БД
    public function actionParslTable_18_19()
    {
        $pars = new ParserKhlTable_18_19();
        $pars->pars_table();
        return $this->render('parser-khl-table_18_19',[
                                'result_pars'=>true,
        ]);
    }

    // отображение результатов парсинга - таблицы из БД
    public function actionViewKhlTable_18_19()
    {
        $all_data = new ParserKhlTable_18_19();
        $table=$all_data->view_table();
        return $this->render('parser-khl-table_18_19',[
                                'table'=>$table,                                                
        ]);
    }

    // формирование постера
    public function actionPosterTable_18_19()
    {
        $poster = new ParserKhlTable_18_19();
        $poster->poster_table();
        return $this->render('parser-khl-table_18_19',[
                            'poster'=>true,
                            'all_data'=>$poster->all_data,
        ]);
    }
    
    
    //======================================================================
    
    

    //ПАРСИНГ 2 - Парсер статистических данных команд КХЛ сезон 2017/2018
    //---------------------------------------------------
    
    // отображение страницы парсинга
    public function actionParserKhlView_2()
    {
        return $this->render('parser_khl_view_2');
    }
    
    
    // парсинг статистических данных
    public function actionParserKhlModel_2()
    {
        // запуск парсера - подключение модели
        $parser_khl_stat = new ParserKhlStat;
        $parser_khl_stat->main();
        
        return $this->render('parser_khl_view_2',[
                                'view_data'=>false,
                            ]);
    }
    
    
    // отображение таблицы с результатами
     public function actionParserKhlData_2()
    {
        
         $view_khl_stat = new ViewKhlStat;
         $view_khl_stat->main();

         return $this->render('parser_khl_view_2',[
                                'view_data'=>true,
                                'arr_tabl'=>$view_khl_stat->arr_table
//                                'nametable'=>$view_khl_stat->nametable,
//                                'provider'=>$provider
                            ]);
    }
    //======================================================================
    
    
    //ПАРСИНГ 2_2018 - Парсер статистических данных команд КХЛ сезон 2017/2018
    //---------------------------------------------------
    
    // отображение страницы парсинга
    public function actionParserKhlView_2_2018()
    {
        return $this->render('parser_khl_view_2_2018');
    }
    
    
    // парсинг статистических данных
    public function actionParserKhlModel_2_2018()
    {
        // запуск парсера - подключение модели
        $parser_khl_stat = new ParserKhlStat_2018;
        $parser_khl_stat->main();
        
        return $this->render('parser_khl_view_2_2018',[
                                'view_data'=>false,
                            ]);
    }
    
    
    // отображение таблицы с результатами
     public function actionParserKhlData_2_2018()
    {
        
         $view_khl_stat = new ViewKhlStat;
         $view_khl_stat->main();

         return $this->render('parser_khl_view_2_2018',[
                                'view_data'=>true,
                                'arr_tabl'=>$view_khl_stat->arr_table
//                                'nametable'=>$view_khl_stat->nametable,
//                                'provider'=>$provider
                            ]);
    }
    
    //ПАРСИНГ - Парсер результатов команд КХЛ сезон 2018/2019
    //---------------------------------------------------
    
    // отображение страницы парсинга
    public function actionParserResults_2018()
    {
        return $this->render('parser_results_2018');
    }
    
    // парсинг результатов команд
    public function actionParserResults_2018Model()
    {
        // запуск парсера - подключение модели
//        $parser_khl_stat = new ParserKhlStat_2018;
//        $parser_khl_stat->main();
//        
//        return $this->render('parser_khl_view_2_2018',[
//                                'view_data'=>false,
//                            ]);
        $ddd=22222;
        
       
        
        $pars = new ParserKhlResults_2018(Yii::$app->request->post('id_team'));
        //$pars = new ParserKhlResults_2018(1);
        
         file_put_contents('../222.txt',$pars);
        
        //return $this->render('parser_results_2018', ['ddd'=>$ddd, 'www'=>$www] );
        return ($pars->main());
    }



    
    
    
    
    
    
    
   
}
