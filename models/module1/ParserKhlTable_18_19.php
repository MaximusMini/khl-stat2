<?php

namespace app\models\module1;

use Yii;
use yii\base\Model;

use GuzzleHttp\Client; // подключаем Guzzle

class ParserKhlTable_18_19 extends Model
{
    
    public $id_connect_DB;      // дескриптор подключения к БД
    public $all_data=[];        // массив для сбора всех параметров
    
    public $arr_team = ["1"=>"Авангард","2"=>"Автомобилист","3"=>"Адмирал","4"=>"Ак Барс","5"=>"Амур","6"=>"Барыс","7"=>"Витязь","8"=>"Динамо М","9"=>"Динамо Мн","10"=>"Динамо Р","11"=>"Йокерит","12"=>"Куньлунь Ред Стар","13"=>"Локомотив","14"=>"Металлург Мг","15"=>"Нефтехимик","16"=>"Салават Юлаев", "17"=>"Северсталь", "18"=>"Сибирь", "19"=>"СКА","20"=>"Слован", "21"=>"Спартак","22"=>"Торпедо", "23"=>"Трактор", "24"=>"ХК Сочи", "25"=>"ЦСКА"];
    
    
    public function __construct()
    {
        
        // создание подключения к БД
        $this->id_connect_DB = Yii::$app->db_khl_stat_2018;
    }
    
    
    // получение данных таблиц
    public function view_table(){
        // формирование запрос
        $q_w = 'SELECT * FROM table_conf WHERE conf="west"';
        $q_e = 'SELECT * FROM table_conf WHERE conf="east"';
        
        // выполнение запроса
        $this->all_data['table_west']=$this->id_connect_DB->createCommand($q_w)->queryAll();
        $this->all_data['table_east']=$this->id_connect_DB->createCommand($q_e)->queryAll();
        
        return $this->all_data;
        
    }
    
    
    // функция для использования библиотеки curl
	function curl_get ($url, $referer = 'http://google.com'){
		$ch = curl_init();// инициализируем curl
		// задаем параметры (опции) curl 
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HEADER,0);
		curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; rv:42.0) Gecko/20100101 Firefox/42.0');
		curl_setopt($ch, CURLOPT_REFERER,$referer);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true); // результат работы curl возвращается, а не выводиться
		//  выполняем запрос curl
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
    
    // главная функция
    function main(){
        $this->pars_table();    
    }
    
    // функция очистки БД
    function delete_table_team(){
            $query = "TRUNCATE table_conf";
        
            // очистка данных в БД
            $this->id_connect_DB->createCommand($query)->execute();
    }
    
    // функция записи данных в БД
    function write_table_team($team){

            foreach($team as $arr_1){
                //определяем id команды
                $name_team = $arr_1['name'];
                $key_name_team = array_search($name_team,$this->arr_team);
                // в показателе процентов меняем , на .
                $arr_1['percent_scr'] = str_replace(',','.',$arr_1['percent_scr']);
                // формирование запроса
                $query = "INSERT INTO table_conf (id_team, conf, name, place, games, clear_wins, ot_wins, b_wins,clear_defeat,ot_defeat,b_defeat,throw_puck,miss_puck,scores,percent_scr,old_match_1,old_match_2,old_match_3,old_match_4,old_match_5,old_match_6) VALUES (".$key_name_team.",\"".$arr_1['conf']."\", \"".$arr_1['name']."\"," .$arr_1['place']."," .$arr_1['games']."," .$arr_1['clear_wins']. ",".$arr_1['ot_wins'].",". $arr_1['b_wins'].",".$arr_1['clear_defeat'].",".$arr_1['ot_defeat'].",".$arr_1['b_defeat'].",".$arr_1['throw_puck'].",".$arr_1['miss_puck'].",".$arr_1['scores'].",".$arr_1['percent_scr'].",\"".$arr_1['old_match_1']."\",\"".$arr_1['old_match_2']."\",\"".$arr_1['old_match_3']."\",\"".$arr_1['old_match_4']."\",\"".$arr_1['old_match_5']."\",\"" .$arr_1['old_match_6']."\")";
                //echo '<br>'.$query; 
                // запись данных в БД
                $this->id_connect_DB->createCommand($query)->execute();
            }
    }
    
    // формирование массива
    function name_team($t_conf,$team, $conf){
        $result = $t_conf->find('table tr');
    
        $count_team=1;
        foreach($result as $val){
            // избавляемся от пустых строк, которые идут в начале таблицы
            if ($count_team < 4){$count_team++; continue;}
            // формирование массива team
            $count_arr = $count_team-3;
            $team[$count_arr]['conf']           = $conf;
            $team[$count_arr]['name']           = pq($val)->find('td:nth-child(4)')->text();
            $team[$count_arr]['place']          = $count_team-3;
            $team[$count_arr]['games']          = pq($val)->find('td:nth-child(5)')->text();
            $team[$count_arr]['clear_wins']     = pq($val)->find('td:nth-child(6)')->text();
            $team[$count_arr]['ot_wins']        = pq($val)->find('td:nth-child(7)')->text();
            $team[$count_arr]['b_wins']         = pq($val)->find('td:nth-child(8)')->text();
            $team[$count_arr]['b_defeat']       = pq($val)->find('td:nth-child(9)')->text();
            $team[$count_arr]['ot_defeat']      = pq($val)->find('td:nth-child(10)')->text();
            $team[$count_arr]['clear_defeat']   = pq($val)->find('td:nth-child(11)')->text();
            $team[$count_arr]['throw_puck']     = pq($val)->find('td:nth-child(12) span:nth-child(1)')->text();
            $team[$count_arr]['miss_puck']     	= pq($val)->find('td:nth-child(12) span:nth-child(3)')->text();
            $team[$count_arr]['scores']         = pq($val)->find('td:nth-child(13)')->text();
            $team[$count_arr]['percent_scr']    = pq($val)->find('td:nth-child(14)')->text();
            for($w=1; $w<=6; $w++){
                $old_match = 'old_match_'.$w;
                $res = 'td:nth-child(15) a:nth-child('.$w.') span';
                $team[$count_arr][$old_match]    = pq($val)->find($res)->attr('class');
            }
            $count_team++;
        }
    
        $n_fale = $conf.'_'.date('d').'_'.date('m').'_'.date('Y').'.json';

        // запись данных в БД
        write_table_team($team);
    
        return $team;  
    }
    
    
    // парсинг данных таблицы
    function pars_table(){
        
        // очищаем таблицу
        
        
        //создаем объекты класса phpQuery
        $res_curl = curl_get ('https://www.championat.com/hockey/_superleague/2593/table/all.html');
        $tables_khl = phpQuery::newDocument($res_curl);
        // определяем таблицы конференций
        $table_west = $tables_khl->find('div.sport__table table:nth-child(2)');
        $table_east = $tables_khl->find('div.sport__table table:nth-child(3)');
        //создаем объекты класса phpQuery
        $t_west      = phpQuery::newDocument($table_west);
        $t_east      = phpQuery::newDocument($table_east);
        
        // 
        delete_table_team(); //очистки БД
    
        $arr_west = name_team($t_west,$team_west, 'west');
        $arr_east = name_team($t_east,$team_east, 'east');
        
   
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
}