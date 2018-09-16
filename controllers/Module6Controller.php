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
        
        $id_team = Yii::$app->request->get('id_team');
        $stat_team = new StatTeam($id_team);
        
        
        // получение результатов последних пяти игр
        $result_query = $stat_team->last_5_games();
        
        return $this->render('main',['id_team'=>$id_team, 'result_query'=>$result_query]);    
            
        
    }
    
   
    
    
    
    
    
    
    
    
    

   
}
