<?php

namespace app\models\module6;

use Yii;
use yii\base\Model;


class StatTeam extends Model
{
    
    public $id_team_1;
    public $id_team_2;
    public $last5game_team_1;
    public $last5game_team_2;
    public $id_connect_DB;// дескриптор подключения к БД
    
    
    public function __construct($id_team_1, $id_team_2)
    {
        // установка id команд при создании экземпляра класса
        $this->id_team_1=$id_team_1;
        $this->id_team_1=$id_team_1;
        
        // создание подключения к БД
        $this->id_connect_DB = Yii::$app->db_khl_stat_2018;
    }
    
    
    
    // главная функция
    function main(){
        
    }
    
    
    // получение результатов последних пяти игр
    function last_5_games(){
        // формирование запроса
        $query_team_1 = 'SELECT * FROM result_match WHERE id_team='.$this->id_team_1.' ORDER BY date_match DESC LIMIT 5';
        $query_team_2 = 'SELECT * FROM result_match WHERE id_team='.$this->id_team_2.' ORDER BY date_match DESC LIMIT 5';
        // выполнение запроса
        $this->last5game_team_1=$this->id_connect_DB->createCommand($query_team_1)->queryAll();
        $this->last5game_team_2=$this->id_connect_DB->createCommand($query_team_1)->queryAll();
        return;
    }
    
    
    
}
















