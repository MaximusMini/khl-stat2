<?php

namespace app\models\module6;

use Yii;
use yii\base\Model;


class StatTeam extends Model
{
    
    public $id_team;
    public $id_connect_DB;// дескриптор подключения к БД
    
    
    public function __construct($id_team)
    {
        $this->id_team=$id_team;
        
        // создание подключения к БД
        $this->id_connect_DB = Yii::$app->db_khl_stat_2018;
        
        //$ttt=$this->id_connect_DB->createCommand('show databases');
        
        $posts =  Yii::$app->db_preview->createCommand('SELECT * FROM result_match')->queryAll();
        

    }
    
    
    
    // функция запуска
    function func_start(){
        
    }
    
    
    // получение результатов последних пяти игр
    function last_5_games(){
        // формирование запроса
        $query = 'SELECT * FROM result_match WHERE id_team='.$this->id_team;

        
        // выполнение запроса
        return $this->id_connect_DB->createCommand($query)->queryAll();
    }
    
    
}
















