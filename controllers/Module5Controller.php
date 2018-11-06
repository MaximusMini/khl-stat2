<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
// класс 
use app\models\module5\PosterMatch;


class Module5Controller extends Controller
{

    public function actionMain()
    {
        return $this->render('main');
    }
    
    
    public function actionGoPoster()
    {
        
        // получение параметров
        $data_request = Yii::$app->request->get();
        
        file_put_contents('module5.txt', $data_request, FILE_APPEND);
        
        $id_team1 = $data_request['team1'];
        $id_team2 = $data_request['team2'];
        
        
        // подключение модели
        $val_team1 = new PosterMatch($data_request);
        //$mod_poster_match_team2 = new PosterMatch();
        
        
        
//        $val_team1->get_value($id_team1,$id_team2);
//        $val_team1->wins_defeats();
//        $js_code = $val_team1->wins_defeats;
        
        
        
        return $this->render('main', [
                                'data_request' => $data_request, 
//                                'id_team1'=>$id_team1,
                                'all_data' => $val_team1->all_data,
//                                'js_code' => $val_team1->wins_defeats,
                            ]);
    }
    
    
   
    
    
    
    
    
    
    
    
    

   
}
