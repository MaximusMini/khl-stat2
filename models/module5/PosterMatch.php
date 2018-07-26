<?php

namespace app\models\module5;

use Yii;
use yii\base\Model;


class PosterMatch extends Model
{
    
    public $id_team1;
    public $id_team2;
    public $logo1;                 // ссылка на логотип команды
    public $logo2;                 // ссылка на логотип команды
    // победы -----------------------------------
    public $clear_wins1;            // чистых побед
    public $ot_wins1;               // побед в овертайме
    public $b_wins1;                // побед по буллитам 
    public $wins1;                  // всего побед
    public $clear_wins2;            // чистых побед
    public $ot_wins2;               // побед в овертайме
    public $b_wins2;                // побед по буллитам 
    public $wins2;                  // всего побед
    // поражения --------------------------------
    public $clear_defeat1;          // чистых поражений
    public $ot_defeat1;             // поражений в овертайме
    public $b_defeat1;              // поражений по буллитам
    public $defeats1;               // всего поражений
    public $clear_defeat2;          // чистых поражений
    public $ot_defeat2;             // поражений в овертайме
    public $b_defeat2;              // поражений по буллитам
    public $defeats2;               // всего побед
    // -------------------------------------------
    public $place1;                 // место команды в конференции
    public $place2;
    // -------------------------------------------
    public $games1;                 // сыгранные командой игры
    public $games2;
    // -------------------------------------------
    public $throw1;                 // количество бросков команды
    public $throw2;
    // -------------------------------------------
    public $puck1;                  // заброшенные командой шайбы
    public $puck2;
    // -------------------------------------------
    public $perc_puck1;             // процент реализации бросков
    public $perc_puck2;
    // -------------------------------------------
    public $allow_puck1;            // количество пропущенных шайб
    public $allow_puck2;
    // реализация большинства --------------------
    public $total_pp1;
    public $goals_pp1;
    public $perc_pp1;
    public $total_pp2;
    public $goals_pp2;
    public $perc_pp2;
    // игра в меньшинстве --------------------
    public $total_pk1;
    public $goals_pk1;
    public $perc_pk1;
    public $total_pk2;
    public $goals_pk2;
    public $perc_pk2;
    // 
    
    function get_value($id_team1, $id_team2){
        // установка соединения с БД
        $db = Yii::$app->db_preview;
        //*******************************
        // id команд 
        $this->id_team1 = $id_team1;
        $this->id_team2 = $id_team2;
        // получение ссылки на логотипы
        $this->logo1 = '_'.$id_team1.'png';
        $this->logo2 = '_'.$id_team2.'png';
        // определение количества побед команды в сезоне - table_conf(clear_wins + ot_wins + b_wins)
        $this->clear_wins1=$db->createCommand('SELECT clear_wins FROM table_conf WHERE id_team='.$id_team1)->queryAll()[0]['clear_wins'];
        $this->ot_wins1=$db->createCommand('SELECT ot_wins FROM table_conf WHERE id_team='.$id_team1)->queryAll()[0]['ot_wins'];
        $this->b_wins1=$db->createCommand('SELECT b_wins FROM table_conf WHERE id_team='.$id_team1)->queryAll()[0]['b_wins'];
        $this->wins1=$this->clear_wins1+$this->ot_wins1+$this->b_wins1;
        //----------------------------------------------------------
        $this->clear_wins2=$db->createCommand('SELECT clear_wins FROM table_conf WHERE id_team='.$id_team2)->queryAll()[0]['clear_wins'];
        $this->ot_wins2=$db->createCommand('SELECT ot_wins FROM table_conf WHERE id_team='.$id_team2)->queryAll()[0]['ot_wins'];
        $this->b_wins2=$db->createCommand('SELECT b_wins FROM table_conf WHERE id_team='.$id_team2)->queryAll()[0]['b_wins'];
        $this->wins2=$this->clear_wins2+$this->ot_wins2+$this->b_wins2;
        // определение количества поражений команды в сезоне - table_conf(clear_defeat + ot_defeat + b_defeat)
        $this->clear_defeat1=$db->createCommand('SELECT clear_defeat FROM table_conf WHERE id_team='.$id_team1)->queryAll()[0]['clear_defeat'];
        $this->ot_defeat1=$db->createCommand('SELECT ot_defeat FROM table_conf WHERE id_team='.$id_team1)->queryAll()[0]['ot_defeat'];
        $this->b_defeat1=$db->createCommand('SELECT b_defeat FROM table_conf WHERE id_team='.$id_team1)->queryAll()[0]['b_defeat'];
        $this->defeats1=$this->clear_defeat1+$this->ot_defeat1+$this->b_defeat1;
        //----------------------------------------------------------
        $this->clear_defeat2=$db->createCommand('SELECT clear_defeat FROM table_conf WHERE id_team='.$id_team2)->queryAll()[0]['clear_defeat'];
        $this->ot_defeat2=$db->createCommand('SELECT ot_defeat FROM table_conf WHERE id_team='.$id_team2)->queryAll()[0]['ot_defeat'];
        $this->b_defeat2=$db->createCommand('SELECT b_defeat FROM table_conf WHERE id_team='.$id_team2)->queryAll()[0]['b_defeat'];
        $this->defeats2=$this->clear_defeat2+$this->ot_defeat2+$this->b_defeat2;
        // место команды в турнирной таблице конфиренции - table_conf(place)[0]['b_defeat']
        $this->place1=$db->createCommand('SELECT place FROM table_conf WHERE id_team='.$id_team1)->queryAll()[0]['place'];
        $this->place2=$db->createCommand('SELECT place FROM table_conf WHERE id_team='.$id_team2)->queryAll()[0]['place'];
        // количество сыгранных игр - table_conf(games)
        $this->games1=$db->createCommand('SELECT games FROM table_conf WHERE id_team='.$id_team1)->queryAll()[0]['games'];
        $this->games2=$db->createCommand('SELECT games FROM table_conf WHERE id_team='.$id_team1)->queryAll()[0]['games'];
        // количество совершенных командой бросков - stat_throw(total_throw)
        $this->throw1=$db->createCommand('SELECT total_throw FROM stat_throw WHERE id_team='.$id_team1)->queryAll()[0]['total_throw'];
        $this->throw2=$db->createCommand('SELECT total_throw FROM stat_throw WHERE id_team='.$id_team2)->queryAll()[0]['total_throw'];
        // количество заброшенных командой шайб - stat_puck(throw_puck)
        $this->puck1=$db->createCommand('SELECT throw_puck FROM stat_puck WHERE id_team='.$id_team1)->queryAll()[0]['throw_puck'];
        $this->puck2=$db->createCommand('SELECT throw_puck FROM stat_puck WHERE id_team='.$id_team2)->queryAll()[0]['throw_puck'];
        // процент реализации бросков командой
        $this->perc_puck1= round(($this->puck1 / $this->throw1) * 100,1);
        $this->perc_puck2= round(($this->puck2 / $this->throw2) * 100,1);
        // количество пропущенных шайб командой - stat_allow_puck(allow_puck)
        $this->allow_puck1=$db->createCommand('SELECT allow_puck FROM stat_allow_puck WHERE id_team='.$id_team1)->queryAll()[0]['allow_puck'];
        $this->allow_puck2=$db->createCommand('SELECT allow_puck FROM stat_allow_puck WHERE id_team='.$id_team2)->queryAll()[0]['allow_puck'];
        // реализация большинства - stat_pow_play_pow_kill(total_power_play, goals_power_play, perc_power_play)
        $this->total_pp1=$db->createCommand('SELECT total_power_play FROM stat_pow_play_pow_kill WHERE id_team='.$id_team1)->queryAll()[0]['total_power_play'];
        $this->goals_pp1=$db->createCommand('SELECT goals_power_play FROM stat_pow_play_pow_kill WHERE id_team='.$id_team1)->queryAll()[0]['goals_power_play'];
        $this->perc_pp1= $db->createCommand('SELECT perc_power_play FROM stat_pow_play_pow_kill WHERE id_team='.$id_team1)->queryAll()[0]['perc_power_play'];
        //----------------------------------------------------------
        $this->total_pp2=$db->createCommand('SELECT total_power_play FROM stat_pow_play_pow_kill WHERE id_team='.$id_team2)->queryAll()[0]['total_power_play'];
        $this->goals_pp2=$db->createCommand('SELECT goals_power_play FROM stat_pow_play_pow_kill WHERE id_team='.$id_team2)->queryAll()[0]['goals_power_play'];
        $this->perc_pp2= $db->createCommand('SELECT perc_power_play FROM stat_pow_play_pow_kill WHERE id_team='.$id_team2)->queryAll()[0]['perc_power_play'];
        // игра в меньшинстве - stat_pow_play_pow_kill(total_power_kill, goals_against_power_kill, perc_power_kill)
        $this->total_pk1=$db->createCommand('SELECT total_power_kill FROM stat_pow_play_pow_kill WHERE id_team='.$id_team1)->queryAll()[0]['total_power_kill'];
        $this->goals_pk1=$db->createCommand('SELECT goals_against_power_kill FROM stat_pow_play_pow_kill WHERE id_team='.$id_team1)->queryAll()[0]['goals_against_power_kill'];
        $this->perc_pk1= $db->createCommand('SELECT perc_power_kill FROM stat_pow_play_pow_kill WHERE id_team='.$id_team1)->queryAll()[0]['perc_power_kill'];
        //----------------------------------------------------------
        $this->total_pk2=$db->createCommand('SELECT total_power_kill FROM stat_pow_play_pow_kill WHERE id_team='.$id_team2)->queryAll()[0]['total_power_kill'];
        $this->goals_pk2=$db->createCommand('SELECT goals_against_power_kill FROM stat_pow_play_pow_kill WHERE id_team='.$id_team2)->queryAll()[0]['goals_against_power_kill'];
        $this->perc_pk2= $db->createCommand('SELECT perc_power_kill FROM stat_pow_play_pow_kill WHERE id_team='.$id_team2)->queryAll()[0]['perc_power_kill'];
    }
    

    // формирование кода JavaScript

    // круговая диаграмма
    function arc(){}
    
    
    
}