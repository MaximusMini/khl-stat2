<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
// класс 
use app\models\module6\StatTeam;


class Module6Controller extends Controller
{

    public function actionMain()
    {
        return $this->render('main');
    }
    
    // выполнение запроса на выборку
    public function actionQuery(){
        
        $stat_team = new StatTeam(Yii::$app->request->get('id_team_1'),Yii::$app->request->get('id_team_2'));
        
        // получение результатов последних пяти игр
        $stat_team->main();

        
        $all_stat=$stat_team->all_stat;
        
        return $this->render('main',
                             ['id_team_1'=>Yii::$app->request->get('id_team_1'),
                              'id_team_2'=>Yii::$app->request->get('id_team_2'),
                              'all_stat'=>$all_stat,
                             ]
                            );    
    }
    
   
    
    
    
    
    
    
    
    
    

   
}
