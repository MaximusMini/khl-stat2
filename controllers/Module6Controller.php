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
        $stat_team->last_5_games();
        $result_query_team_1 = $stat_team->last5game_team_1;
        $result_query_team_2 = $stat_team->last5game_team_2;
        
        return $this->render('main',
                             ['id_team_1'=>Yii::$app->request->get('id_team_1'),
                              'id_team_2'=>Yii::$app->request->get('id_team_2'),
                              'result_query_team_1'=>$result_query_team_1, 
                              'result_query_team_2'=>$result_query_team_2]
                            );    
    }
    
   
    
    
    
    
    
    
    
    
    

   
}
