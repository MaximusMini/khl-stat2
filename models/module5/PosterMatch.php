<?php

namespace app\models\module5;

use Yii;
use yii\base\Model;


class PosterMatch extends Model
{
    
    public $id_team;
    public $all_data;
//    public $id_team2;
    
    
    
    
    function get_value($id_team){
        
        // установка соединения с БД
        $db = Yii::$app->db_preview;
        //*******************************
        // запрос на получение .....
        // 'SELECT * FROM stat_wins WHERE id_team='.$id_team
        $this->all_data = $db->createCommand('SELECT * FROM stat_wins WHERE id_team='.$id_team)->queryAll();
        
        
        
        
        // 
        
        
        
    }
    
    
    // получение ссылки на логотипы
    
    // определение количества побед команды в сезоне - table_conf(clear_wins + ot_wins + b_wins)
    
    // определение количества поражений команды в сезоне - table_conf(clear_defeat + ot_defeat + b_defeat)
    
    // место команды в турнирной таблице конфиренции - table_conf(place)
    
    // количество сыгранных игр - table_conf(games)
    
    // количество совершенных командой бросков - stat_throw(total_throw)
    
    // количество заброшенных командой шайб - stat_puck(throw_puck)
    
    // процент реализации бросков командой 
    
    // количество пропущенных шайб командой - stat_allow_puck(allow_puck)
    
    // реализация большинства - stat_pow_play_pow_kill(total_power_play, goals_power_play, perc_power_play)
    
    // игра в меньшинстве - stat_pow_play_pow_kill(total_power_kill, goals_against_power_kill, perc_power_kill)
    
    
    
    
    // формирование кода JavaScript
    
    
    
}