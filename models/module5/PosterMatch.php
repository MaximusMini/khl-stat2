<?php

namespace app\models\module5;

use Yii;
use yii\base\Model;


class PosterMatch extends Model
{
    
    public $id_connect_DB;        // дескриптор подключения к БД
    
    public $id_team1;
    public $id_team2;
    
    public $all_data=[];// массив для сбора всех параметров
    
    public $dateMatch;              // дата матча
    public $timeMatch;              // время матча
    public $arena;                  // арена
    public $city;                   // город
    // _______-----------------------------------
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
    

    public $rad;    // радианы

    // переменные - функции для рисования --------------------
    public $wins_defeats;    // рисование показателей побед/поражений



     public function __construct($data_request)
    {
        // установка id команд при создании экземпляра класса
        $this->all_data['id_team1']=$data_request['team1'];
        $this->all_data['id_team2']=$data_request['team2'];
        // дата матча 
        $this->all_data['dateMatch']=$data_request['dateMatch'];
        // время матча
        $this->all_data['timeMatch']=$data_request['timeMatch'];
        // арена
        $this->all_data['arena']=$data_request['arena'];
        // город
        $this->all_data['city']=$data_request['city'];                
        // создание подключения к БД
        $this->id_connect_DB = Yii::$app->db_khl_stat_2018;
        // название команд
        $this->all_data['name_team_1']=$this->id_connect_DB->createCommand('SELECT name FROM table_conf WHERE id_team='.$this->all_data['id_team1'])->queryAll()[0]['name'];
        $this->all_data['name_team_2']=$this->id_connect_DB->createCommand('SELECT name FROM table_conf WHERE id_team='.$this->all_data['id_team2'])->queryAll()[0]['name']; 
        // занимаемое место в таблице
        $this->all_data['place_team_1']=$this->id_connect_DB->createCommand('SELECT place FROM table_conf WHERE id_team='.$this->all_data['id_team1'])->queryAll()[0]['place'];
        $this->all_data['place_team_2']=$this->id_connect_DB->createCommand('SELECT place FROM table_conf WHERE id_team='.$this->all_data['id_team2'])->queryAll()[0]['place']; 
        // конференция в которой играет команда
        $this->all_data['conf_1']=$this->id_connect_DB->createCommand('SELECT conf FROM table_conf WHERE id_team='.$this->all_data['id_team1'])->queryAll()[0]['conf'];
        $this->all_data['conf_2']=$this->id_connect_DB->createCommand('SELECT conf FROM table_conf WHERE id_team='.$this->all_data['id_team2'])->queryAll()[0]['conf'];
         
         
         $this->get_value();
         $this->position();
         
    }
    

    // получения данных из БД
    function get_value(){
        //Количестов побед
        $query_1='SELECT clear_wins + ot_wins + b_wins FROM table_conf WHERE id_team='.$this->all_data['id_team1'];
        $query_2='SELECT clear_wins + ot_wins + b_wins FROM table_conf WHERE id_team='.$this->all_data['id_team2'];
        $this->all_data['wins_1']=$this->id_connect_DB->createCommand($query_1)->queryScalar();
        $this->all_data['wins_2']=$this->id_connect_DB->createCommand($query_2)->queryScalar();
        // Количестов поражений
        $query_1='SELECT clear_defeat+ot_defeat+b_defeat FROM table_conf WHERE id_team='.$this->all_data['id_team1'];
        $query_2='SELECT clear_defeat+ot_defeat+b_defeat FROM table_conf WHERE id_team='.$this->all_data['id_team2'];
        $this->all_data['defeats_1']=$this->id_connect_DB->createCommand($query_1)->queryScalar();
        $this->all_data['defeats_2']=$this->id_connect_DB->createCommand($query_2)->queryScalar();
        // 
        
        
        
        /*
        <li>Количестов побед (все)</li>
            <li>Количестов поражений (все)</li>
            <li>Место в конференции - с соседями по таблице (место, клуб, очки)</li>
            <li>Процент набранных очков</li>
            <li>Количество заброшенных шайб</li>
            <li>Количество проведенных бросков</li>
            <li>Процент реализации бросков</li>
            <li>Количество полученного большинства</li>
            <li>Количество реализованного большинства</li>
            <li>Процент реализации большинства</li>
            <li>Количество полученного меньшинства</li>
            <li>Количество реализованного большинства противником</li>
            <li>Процент реализации большинства противником</li>
            <li>Силовые приемы</li>
            <li>Блокированные броски</li>
        <li>Количество вбрасываний</li>
       */
    }
    
    
    
    // определение положения команд в турнирной таблице
    function position(){
        /*
        все данные заносим в массив

        all_data['position_1'][0]['place']['team']['scores']
        all_data['position_1'][1]['place']['team']['scores']
        all_data['position_1'][2]['place']['team']['scores']
        
        */
        
        // если первая команда занимает первое место
        if($this->all_data['place_team_1'] == 1){
            for($i=0; $i<=2; $i++){
                // место
                $this->all_data['position_1'][$i]['place']=$i+1;
                // имя команды
                $this->all_data['position_1'][$i]['team']=$this->id_connect_DB->createCommand('SELECT name FROM table_conf WHERE place='.($i+1).' AND conf="'.$this->all_data['conf_1'].'"')->queryAll()[0]['name'];
                // набранные очки
                $this->all_data['position_1'][$i]['scores']=$this->id_connect_DB->createCommand('SELECT scores FROM table_conf WHERE place='.($i+1).' AND conf="'.$this->all_data['conf_1'].'"')->queryAll()[0]['scores'];    
            }   
        }
        // если вторая команда занимает первое место
        if($this->all_data['place_team_2'] == 1){
            for($i=0; $i<=2; $i++){
                // место
                $this->all_data['position_2'][$i]['place']=$i+1;
                // имя команды
                $this->all_data['position_2'][$i]['team']=$this->id_connect_DB->createCommand('SELECT name FROM table_conf WHERE place='.($i+1).' AND conf="'.$this->all_data['conf_2'].'"')->queryAll()[0]['name'];
                // набранные очки
                $this->all_data['position_2'][$i]['scores']=$this->id_connect_DB->createCommand('SELECT scores FROM table_conf WHERE place='.($i+1).' AND conf="'.$this->all_data['conf_2'].'"')->queryAll()[0]['scores'];    
            }   
        }
//        
        
    }
    

    // формирование кода JavaScript

    // круговая диаграмма
    function arc(){}

    // функция пересчета 


    // пересчет градусов в радианы
    function grad($grad){
        return $this->rad = (grad*M_.PI)/180;
    }


    // рисование показателей побед/поражений
    function wins_defeats(){
$winsDefeats = <<<winsDefeats
 function winsDefeats(centerX1, centerY, contCanvas){
        // победы/поражения команды 1
        var winsTeam1=29;
        var defeatsTeam1=27;
        var gamesTeam1 = 56;
        var percWins1 = Math.round((winsTeam1/gamesTeam1)*100);
        var percDefeats1 = Math.round((defeatsTeam1/gamesTeam1)*100);
        
        var percWinsRadian1 = Math.round((360*percDefeats1)/100);
        var percDefeatsRadian1 = Math.round((360*percDefeats1)/100);
        
        //alert (percDefeatsRadian1);
        
        // победы/поражения команды 1
        var winsTeam2 = 25;
        var defeatsTeam2 = 31;
        var gamesTeams2 = 56;
        var percWins2 = Math.round((winsTeam2/gamesTeams2)*100);
        
        contCanvas.lineWidth = 35;
        contCanvas.fillStyle = '#ff0000';
        contCanvas.strokeStyle = "#8B0000"; // цвет линии
        
        contCanvas.beginPath();
        contCanvas.arc( centerX1, centerY, 100, rad(270), rad(270+percDefeatsRadian1));
        
        contCanvas.stroke();
        contCanvas.beginPath();    
        contCanvas.strokeStyle = "#008000"; // цвет линии
        contCanvas.arc( centerX1, centerY, 100, rad(83), rad(270));
        contCanvas.stroke();
    }
winsDefeats;
    $this->wins_defeats = $winsDefeats;
}


    // вывод шаблона
    function draw_template(){
$drawTemplate = <<< drawTemplate
function drawTemplate(){
    
}
drawTemplate;
    }

    // основная функция для формирования всего js кода
    function main_js_code(){
        $this->wins_defeats();// рисование показателей побед/поражений
    }

    
    
    
}