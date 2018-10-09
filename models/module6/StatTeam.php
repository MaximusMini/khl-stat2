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
    
	
//    public $arr_team = [
//    "1"=>['name'=>'Авангард'],
//    "2"=>"Автомобилист",
//    "3"=>"Адмирал",
//    "4"=>"Ак Барс",
//    "5"=>"Амур",
//    "6"=>"Барыс",
//    "7"=>"Витязь",
//    "8"=>"Динамо М",
//    "9"=>"Динамо Мн",
//    "10"=>"Динамо Р",
//    "11"=>"Йокерит","12"=>"Куньлунь Ред Стар","13"=>"Локомотив","14"=>"Металлург Мг","15"=>"Нефтехимик","16"=>"Салават Юлаев", "17"=>"Северсталь", "18"=>"Сибирь", "19"=>"СКА","20"=>"Слован", "21"=>"Спартак","22"=>"Торпедо", "23"=>"Трактор", "24"=>"ХК Сочи", "25"=>"ЦСКА"];	
    
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
        $this->puck_all_g();                 //количество игр в которых было заброшено 0..7 шайб
        $this->puck_loss_all_g();            //количество игр в которых было пропущено 0..7 шайб
        $this->P_puck();                     //данные по заброшенным шайбам
        //$this->P_puck_loss();                //данные по пропущенным шайбам
    }
    
    
    
     // получение количества проведенных матчей - всех, дома, вгостях
    function all_matches(){
        // формирование запросов
        $query_all_t1 = 'SELECT COUNT(*) FROM result_match WHERE id_team='.$this->id_team_1;
        $query_all_t2 = 'SELECT COUNT(*) FROM result_match WHERE id_team='.$this->id_team_2;
        
        $query_h_t1 = 'SELECT COUNT(*) FROM result_match WHERE id_team='.$this->id_team_1.' AND place=" home"';
        $query_h_t2 = 'SELECT COUNT(*) FROM result_match WHERE id_team='.$this->id_team_2.' AND place=" home"';
        
        $query_g_t1 = 'SELECT COUNT(*) FROM result_match WHERE id_team='.$this->id_team_1.' AND place=" guest"';
        $query_g_t2 = 'SELECT COUNT(*) FROM result_match WHERE id_team='.$this->id_team_2.' AND place=" guest"';
        
        // выполнение запросов
        $this->all_stat['all_g_t1']=$this->id_connect_DB->createCommand($query_all_t1)->queryAll()[0]['COUNT(*)'];
        $this->all_stat['all_g_t2']=$this->id_connect_DB->createCommand($query_all_t2)->queryAll()[0]['COUNT(*)'];
        
        $this->all_stat['all_g_h_t1']=$this->id_connect_DB->createCommand($query_h_t1)->queryAll()[0]['COUNT(*)'];
        $this->all_stat['all_g_h_t2']=$this->id_connect_DB->createCommand($query_h_t2)->queryAll()[0]['COUNT(*)'];
        
        $this->all_stat['all_g_g_t1']=$this->id_connect_DB->createCommand($query_g_t1)->queryAll()[0]['COUNT(*)'];
        $this->all_stat['all_g_g_t2']=$this->id_connect_DB->createCommand($query_g_t2)->queryAll()[0]['COUNT(*)'];

        return;
        
    }
    
    // получение результатов последних пяти игр
    function last_5_games(){
        // формирование запроса
        $query_team_1 = 'SELECT * FROM result_match WHERE id_team='.$this->id_team_1.' ORDER BY date_match DESC LIMIT 5';
        $query_team_2 = 'SELECT * FROM result_match WHERE id_team='.$this->id_team_2.' ORDER BY date_match DESC LIMIT 5';

        // последние 5 игр
        $this->all_stat['last5g_t1']=$this->id_connect_DB->createCommand($query_team_1)->queryAll();
        $this->all_stat['last5g_t2']=$this->id_connect_DB->createCommand($query_team_2)->queryAll();
        
        // последние 5 игр дома
        //SELECT * FROM result_match WHERE id_team=1 AND place=' home' ORDER BY date_match DESC LIMIT 5
        $query_team_1 = 'SELECT * FROM result_match WHERE id_team='.$this->id_team_1.' AND place=" home" ORDER BY date_match DESC LIMIT 5';
		$query_team_2 = 'SELECT * FROM result_match WHERE id_team='.$this->id_team_2.' AND place=" home" ORDER BY date_match DESC LIMIT 5';
        file_put_contents('111.txt', $query_team_1);
		$this->all_stat['last5g_hom_t1']=$this->id_connect_DB->createCommand($query_team_1)->queryAll();
		$this->all_stat['last5g_hom_t2']=$this->id_connect_DB->createCommand($query_team_2)->queryAll();
        
        // последние 5 игр в гостях
        // SELECT * FROM result_match WHERE id_team=1 AND place=' guest' ORDER BY date_match DESC LIMIT 5
		$query_team_1 = 'SELECT * FROM result_match WHERE id_team='.$this->id_team_1.' AND place=" guest" ORDER BY date_match DESC LIMIT 5';
		$query_team_2 = 'SELECT * FROM result_match WHERE id_team='.$this->id_team_2.' AND place=" guest" ORDER BY date_match DESC LIMIT 5';
		$this->all_stat['last5g_gst_t1']=$this->id_connect_DB->createCommand($query_team_1)->queryAll();
		$this->all_stat['last5g_gst_t2']=$this->id_connect_DB->createCommand($query_team_2)->queryAll();
        
        return;
    }
    
    // определение количества игр в которых было ЗАБРОШЕНО 0,1,2,3,4,5,6,7 шайб
    function puck_all_g(){
        // формирование запросов
        for($j=0; $j<8; $j++){
            $q_puck_t1[$j]='SELECT COUNT(*) FROM result_match WHERE id_team='.$this->id_team_1.' AND puck_team='.$j;
            $q_puck_t2[$j]='SELECT COUNT(*) FROM result_match WHERE id_team='.$this->id_team_2.' AND puck_team='.$j;    
        }
        
        // получение данных        
        for ($i=0; $i<8; $i++){
            $this->all_stat['puck_'.$i.'_all_g_t1']=
                $this->id_connect_DB->createCommand($q_puck_t1[$i])->queryAll()[0]['COUNT(*)'];
            $this->all_stat['puck_'.$i.'_all_g_t2']=
                $this->id_connect_DB->createCommand($q_puck_t2[$i])->queryAll()[0]['COUNT(*)'];    
        }
        
        
    }
    
    
    // определение количества игр в которых было ПРОПУЩЕНО 0,1,2,3,4,5,6,7 шайб
    function puck_loss_all_g(){
        // формирование запросов
        for($j=0; $j<8; $j++){
            $q_puck_t1[$j]='SELECT COUNT(*) FROM result_match WHERE id_team='.$this->id_team_1.' AND puck_rival='.$j;
            $q_puck_t2[$j]='SELECT COUNT(*) FROM result_match WHERE id_team='.$this->id_team_2.' AND puck_rival='.$j;    
        }
        
        // получение данных        
        for ($i=0; $i<8; $i++){
            $this->all_stat['puck_loss_'.$i.'_all_g_t1']=
                $this->id_connect_DB->createCommand($q_puck_t1[$i])->queryAll()[0]['COUNT(*)'];
            $this->all_stat['puck_loss_'.$i.'_all_g_t2']=
                $this->id_connect_DB->createCommand($q_puck_t2[$i])->queryAll()[0]['COUNT(*)'];    
        }    
    }
    
    
    // определение вероятности, матожидания, дисперсии количества заброшенных шайб
    function P_puck(){
        // вероятность аброшенных/пропущенных шайб
        
        for($i=0;$i<8;$i++){
            $this->all_stat['p_puck_'.$i.'_t1'] = 
                round($this->all_stat['puck_'.$i.'_all_g_t1'] / $this->all_stat['all_g_t1'],2);
            $this->all_stat['p_puck_'.$i.'_t2'] = 
                round($this->all_stat['puck_'.$i.'_all_g_t2'] / $this->all_stat['all_g_t2'],2);    
        }
        
        for($i=0;$i<8;$i++){
            $this->all_stat['p_puck_loss_'.$i.'_t1'] = 
                round($this->all_stat['puck_loss_'.$i.'_all_g_t1'] / $this->all_stat['all_g_t1'],2);
            $this->all_stat['p_puck_loss_'.$i.'_t2'] = 
                round($this->all_stat['puck_loss_'.$i.'_all_g_t2'] / $this->all_stat['all_g_t2'],2);    
        }
        

        
        
        // расчет матожидания заброшенных шайб
        $this->all_stat['M(X)_puck_t1'] = (0* $this->all_stat['p_puck_0_t1'])+
            (1* $this->all_stat['p_puck_1_t1'])+
            (2* $this->all_stat['p_puck_2_t1'])+
            (3* $this->all_stat['p_puck_3_t1'])+
            (4* $this->all_stat['p_puck_4_t1'])+
            (5* $this->all_stat['p_puck_5_t1'])+
            (6* $this->all_stat['p_puck_6_t1'])+
            (7* $this->all_stat['p_puck_7_t1']);
        $this->all_stat['M(X)_puck_t2'] = (0* $this->all_stat['p_puck_0_t2'])+
            (1* $this->all_stat['p_puck_1_t2'])+
            (2* $this->all_stat['p_puck_2_t2'])+
            (3* $this->all_stat['p_puck_3_t2'])+
            (4* $this->all_stat['p_puck_4_t2'])+
            (5* $this->all_stat['p_puck_5_t2'])+
            (6* $this->all_stat['p_puck_6_t2'])+
            (7* $this->all_stat['p_puck_7_t2']);
        
        
        // расчет матожидания ПРОПУЩЕННЫХ шайб
        $this->all_stat['M(X)_puck_loss_t1'] = (0* $this->all_stat['p_puck_loss_0_t1'])+
            (1* $this->all_stat['p_puck_loss_1_t1'])+
            (2* $this->all_stat['p_puck_loss_2_t1'])+
            (3* $this->all_stat['p_puck_loss_3_t1'])+
            (4* $this->all_stat['p_puck_loss_4_t1'])+
            (5* $this->all_stat['p_puck_loss_5_t1'])+
            (6* $this->all_stat['p_puck_loss_6_t1'])+
            (7* $this->all_stat['p_puck_loss_7_t1']);
        $this->all_stat['M(X)_puck_loss_t2'] = (0* $this->all_stat['p_puck_loss_0_t2'])+
            (1* $this->all_stat['p_puck_loss_1_t2'])+
            (2* $this->all_stat['p_puck_loss_2_t2'])+
            (3* $this->all_stat['p_puck_loss_3_t2'])+
            (4* $this->all_stat['p_puck_loss_4_t2'])+
            (5* $this->all_stat['p_puck_loss_5_t2'])+
            (6* $this->all_stat['p_puck_loss_6_t2'])+
            (7* $this->all_stat['p_puck_loss_7_t2']);
        
        
        
        
        // расчет матожидания квадрата заброшенных шайб (для расчета дисперсии)
        $this->all_stat['M(X)2_puck_t1']= (pow(0,2)* $this->all_stat['p_puck_0_t1'])+
            (pow(1,2)* $this->all_stat['p_puck_1_t1'])+
            (pow(2,2)* $this->all_stat['p_puck_2_t1'])+
            (pow(3,2)* $this->all_stat['p_puck_3_t1'])+
            (pow(4,2)* $this->all_stat['p_puck_4_t1'])+
            (pow(5,2)* $this->all_stat['p_puck_5_t1'])+
            (pow(6,2)* $this->all_stat['p_puck_6_t1'])+
            (pow(7,2)* $this->all_stat['p_puck_7_t1']);
        $this->all_stat['M(X)2_puck_t2']= (pow(0,2)* $this->all_stat['p_puck_0_t2'])+
            (pow(1,2)* $this->all_stat['p_puck_1_t2'])+
            (pow(2,2)* $this->all_stat['p_puck_2_t2'])+
            (pow(3,2)* $this->all_stat['p_puck_3_t2'])+
            (pow(4,2)* $this->all_stat['p_puck_4_t2'])+
            (pow(5,2)* $this->all_stat['p_puck_5_t2'])+
            (pow(6,2)* $this->all_stat['p_puck_6_t2'])+
            (pow(7,2)* $this->all_stat['p_puck_7_t2']);
        
        // расчет матожидания квадрата ПРОПУЩЕННЫХ шайб (для расчета дисперсии)
        $this->all_stat['M(X)2_puck_loss_t1']= (pow(0,2)* $this->all_stat['p_puck_loss_0_t1'])+
            (pow(1,2)* $this->all_stat['p_puck_loss_1_t1'])+
            (pow(2,2)* $this->all_stat['p_puck_loss_2_t1'])+
            (pow(3,2)* $this->all_stat['p_puck_loss_3_t1'])+
            (pow(4,2)* $this->all_stat['p_puck_loss_4_t1'])+
            (pow(5,2)* $this->all_stat['p_puck_loss_5_t1'])+
            (pow(6,2)* $this->all_stat['p_puck_loss_6_t1'])+
            (pow(7,2)* $this->all_stat['p_puck_loss_7_t1']);
        $this->all_stat['M(X)2_puck_loss_t2']= (pow(0,2)* $this->all_stat['p_puck_loss_0_t2'])+
            (pow(1,2)* $this->all_stat['p_puck_loss_1_t2'])+
            (pow(2,2)* $this->all_stat['p_puck_loss_2_t2'])+
            (pow(3,2)* $this->all_stat['p_puck_loss_3_t2'])+
            (pow(4,2)* $this->all_stat['p_puck_loss_4_t2'])+
            (pow(5,2)* $this->all_stat['p_puck_loss_5_t2'])+
            (pow(6,2)* $this->all_stat['p_puck_loss_6_t2'])+
            (pow(7,2)* $this->all_stat['p_puck_loss_7_t2']);
        
        
        // расчет дисперсии 
        $this->all_stat['D(X)_puck_t1'] = $this->all_stat['M(X)2_puck_t1'] - pow($this->all_stat['M(X)_puck_t1'],2);
        $this->all_stat['D(X)_puck_t2'] = $this->all_stat['M(X)2_puck_t2'] - pow($this->all_stat['M(X)_puck_t2'],2);
        
        // расчет дисперсии ПРОПУЩЕННЫХ шайб
        $this->all_stat['D(X)_puck_loss_t1'] = $this->all_stat['M(X)2_puck_loss_t1'] - pow($this->all_stat['M(X)_puck_loss_t1'],2);
        $this->all_stat['D(X)_puck_loss_t2'] = $this->all_stat['M(X)2_puck_loss_t2'] - pow($this->all_stat['M(X)_puck_loss_t2'],2);
        
    }
    
    
    // определение вероятности, матожидания, дисперсии количества заброшенных шайб
    //function P_puck_loss(){
    
	
	// формирование текста для комментария
    function tevplate_comment(){
        $comment='';
    }
    
//    'клуб1 - клуб2. Команды в сезоне сыграли __ и __ матчей соответсвенно. 
//Клуб 1 - занимает __ место в конференции, клуб 2 - __. 
//Клуб 1 (Псевдоним 1 команды 1) из последних пяти матчей выиграл - _, проиграл _. 
//В свою очередь (псевдоним 2 команды 2) из последних пяти матчей выиграли - _, проиграли _. 
//Атакующая линия (псевдоним 3 команды 1) больше всего раз в проведенных матчах забрасывала __ шайб (в __ матчах), 
//(псевдоним 3 команды 1) - __ шайб (в __ матчах).'
    
    
}





/*

$all_stat['all_g_t1']                      все проведенные игры
$all_stat['all_g_h_t1']                    все проведенные игры дома
$all_stat['all_g_g_t1']                    все проведенные игры в гостях

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







--== ВЕРОЯТНОСТИ ==--

$all_stat['p_puck_0_t1']                   вероятность 0 заброшенных шайб командой
.....
$all_stat['p_puck_7_t1']                   вероятность 7 заброшенных шайб командой

$all_stat['p_puck_loss_0_t1']              вероятность 0 пропущенных шайб командой
.....
$all_stat['p_puck_loss_7_t1']              вероятность 7 пропущенных шайб командой


$all_stat['M(X)_puck_t1']                  матожидание количества заброшенных шайб командой
$all_stat['M(X)2_puck_t1']                 матожидание квадрата количества заброшенных шайб (для расчета дисперсии)
$all_stat['D(X)_puck_t1']                  дисперсия количества заброшенных шайб командой


--== ШАЙБЫ ==--

$puks[t1]














*/










