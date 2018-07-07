<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;


class Module4Controller extends Controller
{

    public function actionMain()
    {
        return $this->render('main');
    }
    
    
    // вывод интерфейса для формирования данных постера 
    public function actionAmountMatches($amount)
    {
        
    }
    
    
    
    
    
    
    
    
    

   
}
