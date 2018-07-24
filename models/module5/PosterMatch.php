<?php

namespace app\models\module5;

use Yii;
use yii\base\Model;


class PosterMatch extends Model
{
    
    public $id_team;
    public $logo;                 // ссылка на логотип команды
    // победы -----------------------------------
    public $clear_wins;            // чистых побед
    public $ot_wins;               // побед в овертайме
    public $b_wins;                // побед по буллитам 
    public $wins;                  // всего побед
    // поражения --------------------------------
    public $clear_defeat;          // чистых поражений
    public $ot_defeat;             // поражений в овертайме
    public $b_defeat;              // поражений по буллитам
    public $defeats;               // всего побед

    public $place;


    public $all_data=[];

    function get_value($id_team){
        
        // установка соединения с БД
        $db = Yii::$app->db_preview;
        //*******************************
        // запрос на получение .....
        // 'SELECT * FROM stat_wins WHERE id_team='.$id_team
        // $this->all_data = $db->createCommand('SELECT * FROM stat_wins WHERE id_team='.$id_team)->queryAll();
        // получение ссылки на логотипы
        $this->logo = '_'.$id_team.'png';
        // определение количества побед команды в сезоне - table_conf(clear_wins + ot_wins + b_wins)
        $this->clear_wins=$db->createCommand('SELECT clear_wins FROM table_conf WHERE id_team='.$id_team)->queryAll()[0]['clear_wins'];
        $this->ot_wins=$db->createCommand('SELECT ot_wins FROM table_conf WHERE id_team='.$id_team)->queryAll()[0]['ot_wins'];
        $this->b_wins=$db->createCommand('SELECT b_wins FROM table_conf WHERE id_team='.$id_team)->queryAll()[0]['b_wins'];
        $this->wins=$this->clear_wins+$this->ot_wins+$this->b_wins;
        // определение количества поражений команды в сезоне - table_conf(clear_defeat + ot_defeat + b_defeat)
        $this->clear_defeat=$db->createCommand('SELECT clear_defeat FROM table_conf WHERE id_team='.$id_team)->queryAll()[0]['clear_defeat'];
        $this->ot_defeat=$db->createCommand('SELECT ot_defeat FROM table_conf WHERE id_team='.$id_team)->queryAll()[0]['ot_defeat'];
        $this->b_defeat=$db->createCommand('SELECT b_defeat FROM table_conf WHERE id_team='.$id_team)->queryAll()[0]['b_defeat'];
        $this->defeats=$this->clear_defeat+$this->ot_defeat+$this->b_defeat;
        // место команды в турнирной таблице конфиренции - table_conf(place)[0]['b_defeat']
        $this->place=$db->createCommand('SELECT place FROM table_conf WHERE id_team='.$id_team)->queryAll()[0]['place'];
        
        
        
        $this->all_data = compact($logo, $clear_wins, $ot_wins, $b_wins, $wins,
                                  $clear_defeat, $ot_defeat, $b_defeat, $defeats);
    }
    
    
    
    // количество сыгранных игр - table_conf(games)
    
    // количество совершенных командой бросков - stat_throw(total_throw)
    
    // количество заброшенных командой шайб - stat_puck(throw_puck)
    
    // процент реализации бросков командой 
    
    // количество пропущенных шайб командой - stat_allow_puck(allow_puck)
    
    // реализация большинства - stat_pow_play_pow_kill(total_power_play, goals_power_play, perc_power_play)
    
    // игра в меньшинстве - stat_pow_play_pow_kill(total_power_kill, goals_against_power_kill, perc_power_kill)
    
    
    
    
    // формирование кода JavaScript
    
    
    
}