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
    
    
    // обработка запроса для вывода данных таблицы из БД 
    public function actionDataTable()
    {
        // получаем все данные из $_GET
        $arr_GET = Yii::$app->request->get();
        // определяем таблицу
        $name_table = $arr_GET['name_table'];
        // передаем имя таблицы для отображения
        
        
    }
    
    
    
    
    
    
    
    
    

   
}
