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
    function name_team($t_conf, $conf){
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
            // последние шесть сыгранных матчей
            for($w=1; $w<=6; $w++){
                $old_match = 'old_match_'.$w;
                $res = 'td:nth-child(15) a:nth-child('.$w.') span';
                $team[$count_arr][$old_match]    = pq($val)->find($res)->attr('class');
            }
            $count_team++;
        }

        // запись данных в БД
        $this->write_table_team($team);
    }
    
    // парсинг данных таблицы
    function pars_table(){
        
        //создаем объекты класса phpQuery
        $res_curl = $this->curl_get ('https://www.championat.com/hockey/_superleague/2593/table/all.html');
        $tables_khl = \phpQuery::newDocument($res_curl);
        // определяем таблицы конференций
        $table_west = $tables_khl->find('div.sport__table table:nth-child(2)');
        $table_east = $tables_khl->find('div.sport__table table:nth-child(3)');
        //создаем объекты класса phpQuery
        $t_west      = \phpQuery::newDocument($table_west);
        $t_east      = \phpQuery::newDocument($table_east);
 
        $this->delete_table_team(); //очистки БД
    
        $arr_west = $this->name_team($t_west,'west');
        $arr_east = $this->name_team($t_east,'east');
        
   
        
    }
    
    // формирование постера
    function poster_table(){
        // получение данных из БД
        $this->view_table();
        // вывод данных на шаблон
        // загрузка изображения - шаблона
    
        $image = imagecreatefrompng('images\module2\template_gameday\game_day_'.$data_poster['amount'].'.png');
    // установка цвета
    $color_date = imagecolorallocate($image, 196,199,200);
    $color_time = imagecolorallocate($image, 112,214,243);
    $color_text = imagecolorallocate($image, 219,223,224);
    // установка шрифта
    $font_date = 'font\ARIALBD.TTF';
    $font_time = 'font\BigNoodleTitlingCyr.ttf';
    $font_text = 'font\Arciform Sans cyr-lat Regular.otf';

    // заполнение данными матчей
    $c_m = $data_poster['amount'];// количество матчей
    for($i=1; $i<=$c_m; $i++){
        // формирование имен ключей
        $timeM = 'time_'.$i;// название ключа массива времени матча
        $teamFirst = 'team1_'.$i;               // имя первой команды
        $teamSecond = 'team2_'.$i;              // имя второй команды
        $xPosTeams = 'xPosTeam1_'.$i;           // начальная позиция по X надписи команд
        $yPosTeams = 'yPosTeam1_'.$i;           // позиция по Y надписи команд
        $xPosLogo1 = 'xPosLogo1_'.$i;           // позиция по X логотипа команды 1
        $xPosLogo2 = 'xPosLogo2_'.$i;           // позиция по X логотипа команды 2
        $yPosLogos = 'yPosLogo1_'.$i;           // позиция по Y логотипов команд
        $fontSizeTeams = 'fontSizeTeams_'.$i;   // размер шрифта в названии команд
        //время
        imagettftext($image, $time_matches[$i-1]['font_time'], 0, $time_matches[$i-1]['x_pos_time'], $time_matches[$i-1]['y_pos_time'], $color_time , $font_time, $data_poster[$timeM]);
        // формирование общей строки с именами команды + верхний регистр
        $teams = $data_poster[$teamFirst].' - '.$data_poster[$teamSecond];
            //$teams = mb_strtoupper($data_poster[$teamFirst].' - '.$data_poster[$teamSecond]);
        // вывод надписи
        imagettftext($image, $data_poster[$fontSizeTeams], 0, $data_poster[$xPosTeams], $data_poster[$yPosTeams], $color_text , $font_text, $teams);
        // логотипа команды 1
        $path_logo_temp_1 = $logo[$data_poster[$teamFirst]];
        $logo_team_1 = imagecreatefrompng('images\module2\logo\\'.$path_logo_temp_1);
        // логотипа команды 2
        $path_logo_temp_2 = $logo[$data_poster[$teamSecond]];
        $logo_team_2 = imagecreatefrompng('images\module2\logo\\'.$path_logo_temp_2);
        // Копирование и наложение логотипов команд
        imagecopyresized($image, $logo_team_1,$data_poster[$xPosLogo1], $data_poster[$yPosLogos], 0, 0,60, 60, 60,60);
        imagecopyresized($image, $logo_team_2,$data_poster[$xPosLogo2], $data_poster[$yPosLogos], 0, 0,60, 60, 60,60); 
    }
    // сохранение файла
    $name_new_file = 'images\module2\new\gameDay_'.iconv("UTF-8", "Windows-1251//TRANSLIT",$date_matches).'.png';
    $name_new_file2 = 'images\module2\new\gameDay_'.$date_matches.'.png';
    imagepng($image,$name_new_file,9);
        // сохранение сформированных постеров
        
        // ссылка на сформированные постреы 
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
}