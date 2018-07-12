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
        // установка соединения с БД 
        $db = new yii\db\Connection([
                    'dsn' => 'mysql:host=localhost;dbname=db_preview',
                    'username' => 'root',
                    'password' => '',
                    'charset' => 'utf8',
                ]);
        //*******************************
        // получаем все данные из $_GET
        $arr_GET = Yii::$app->request->get();
        // определяем таблицу
        $name_table = $arr_GET['name-table'];
        // получаем все данные из таблицы
        $cells = $db->createCommand('SELECT * FROM '.$name_table)->queryAll();
        // передаем данные из таблицы для отображения
        return $this->render('data-table',["name_table"=>$name_table,"cells"=>$cells]);
        
        
    }
    
    
    
    
    
    
    
    
    

   
}
