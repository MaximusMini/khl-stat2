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
    
    
    public $all_stat=[];// массив для сбора всех параметров
    
    public $puks=[];// массив для сбора данных о заброшенных/пропущенных шайбах
    
    
    
    public $id_connect_DB;// дескриптор подключения к БД
    
    
    public function __construct($id_team_1, $id_team_2)
    {
        // установка id команд при создании экземпляра класса
        $this->id_team_1=$id_team_1;
        $this->id_team_2=$id_team_2;
        
        // создание подключения к БД
        $this->id_connect_DB = Yii::$app->db_khl_stat_2018;
    }
    
    
    
    // главная функция
    function main(){
        $this->all_matches();                //получение количества проведенных матчей - всех, дома, вгостях
        $this->last_5_games();               //получение результатов последних пяти игр
        
    }
    
    
    // получение результатов последних пяти игр
    function last_5_games(){
        // формирование запроса
        $query_team_1 = 'SELECT * FROM result_match WHERE id_team='.$this->id_team_1.' ORDER BY date_match DESC LIMIT 5';
        $query_team_2 = 'SELECT * FROM result_match WHERE id_team='.$this->id_team_2.' ORDER BY date_match DESC LIMIT 5';
        // выполнение запроса
        $this->last5game_team_1=$this->id_connect_DB->createCommand($query_team_1)->queryAll();
        $this->last5game_team_2=$this->id_connect_DB->createCommand($query_team_2)->queryAll();
        
        // последние 5 игр
        $this->all_stat['last5g_t1']=$this->id_connect_DB->createCommand($query_team_1)->queryAll();
        $this->all_stat['last5g_t2']=$this->id_connect_DB->createCommand($query_team_2)->queryAll();
        
        // последние 5 игр дома
        
        //SELECT * FROM result_match WHERE id_team=1 AND place=' home' ORDER BY date_match DESC LIMIT 5
        
        
        // последние 5 игр в гостях
        // SELECT * FROM result_match WHERE id_team=1 AND place=' guest' ORDER BY date_match DESC LIMIT 5
        
        
        
        
        
        return;
    }
    
    
    // получение количества проведенных матчей - всех, дома, вгостях
    function all_matches(){
        // запрос - все матчи
        $query_team_1 = 'SELECT COUNT(*) FROM result_match WHERE id_team='.$this->id_team_1;
        $query_team_2 = 'SELECT COUNT(*) FROM result_match WHERE id_team='.$this->id_team_2;
        $this->all_stat['all_g_t1']=$this->id_connect_DB->createCommand($query_team_1)->queryAll()[0]['COUNT(*)'];
        $this->all_stat['all_g_t2']=$this->id_connect_DB->createCommand($query_team_2)->queryAll()[0]['COUNT(*)'];
        
        
        //SELECT COUNT(*) FROM result_match WHERE id_team=1
        
        // запрос - все матчи дома
        
        // запрос - все матчи в гостях
        
        return;
        
    }
    
    
}





/*

$all_stat['all_g_t1']                      все проведенные игры
$all_stat['all_g_hom_t1']                  все проведенные игры дома
$all_stat['all_g_gst_t1']                  все проведенные игры в гостях

$all_stat['last10g_t1']                    последних 10 проведенных игр
$all_stat['last10g_hom_t1']                последних 10 проведенных игр дома
$all_stat['last10g_gst_t1']                последних 10 проведенных игр в гостях

$all_stat['last5g_t1']                     последних 5 проведенных игр
$all_stat['last5g_hom_t1']                 последних 5 проведенных игр дома
$all_stat['last5g_gst_t1']                 последних 5 проведенных игр в гостях

$all_stat['puks_0_all_g_t1']               количество игры в которых было заброшено 0 шайб
    SELECT * FROM result_match WHERE id_team=1 AND puck_team=0
$all_stat['puck_1_all_g_t1']               количество игры в которых было заброшено 1 шайба
$all_stat['puck_2_all_g_t1']               количество игры в которых было заброшено 2 шайбы
$all_stat['puck_3_all_g_t1']               количество игры в которых было заброшено 3 шайбы
$all_stat['puck_4_all_g_t1']               количество игры в которых было заброшено 4 шайбы
$all_stat['puck_5_all_g_t1']               количество игры в которых было заброшено 5 шайб
$all_stat['puck_6_all_g_t1']               количество игры в которых было заброшено 6 шайб
$all_stat['puck_7_all_g_t1']               количество игры в которых было заброшено 7 шайб

$all_stat['puck_loss_0_all_g_t1']          количество игры в которых было пропущена 0 шайб
    SELECT * FROM result_match WHERE id_team=1 AND puck_rival=0
$all_stat['puck_loss_1_all_g_t1']          количество игры в которых было пропущена 1 шайба
$all_stat['puck_loss_2_all_g_t1']          количество игры в которых было пропущена 2 шайбы
$all_stat['puck_loss_3_all_g_t1']          количество игры в которых было пропущена 3 шайбы
$all_stat['puck_loss_4_all_g_t1']          количество игры в которых было пропущена 4 шайбы
$all_stat['puck_loss_5_all_g_t1']          количество игры в которых было пропущена 5 шайб
$all_stat['puck_loss_6_all_g_t1']          количество игры в которых было пропущена 6 шайб
$all_stat['puck_loss_7_all_g_t1']          количество игры в которых было пропущена 7 шайб


--== ШАЙБЫ ==--

$puks[t1]














*/










