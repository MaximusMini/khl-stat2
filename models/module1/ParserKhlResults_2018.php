<?php

namespace app\models\module1;

use Yii;
use yii\base\Model;

//use app\web\php\phpQuery;

use GuzzleHttp\Client; // подключаем Guzzle



// парсер статистических данных команд КХЛ (с www.championat.com)
class ParserKhlResults_2018 extends Model
{
    
    public $id_team; // id команды
    public $id_connect_DB;// дескриптор подключения к БД
    
    
    public $arr_temp=[];
    
    public $result_team=[];
    
    // массив с сылками на результаты команд
    public $arr_team = [
        ['id_team'=>'1', 'name' => 'Авангард',      'linl'=>'https://www.championat.com/hockey/_superleague/2593/team/99237/result.html'],
        ['id_team'=>'2', 'name' => 'Автомобилист',  'linl'=>'https://www.championat.com/hockey/_superleague/2593/team/99225/result.html'],
        ['id_team'=>'3', 'name' => 'Адмирал',       'linl'=>'https://www.championat.com/hockey/_superleague/2593/team/99239/result.html'],
        ['id_team'=>'4', 'name' => 'Ак Барс',       'linl'=>'https://www.championat.com/hockey/_superleague/2593/team/99227/result.html'],
        ['id_team'=>'5', 'name' => 'Амур',          'linl'=>'https://www.championat.com/hockey/_superleague/2593/team/99241/result.html'],
        
        ['id_team'=>'6', 'name' => 'Барыс',         'linl'=>'https://www.championat.com/hockey/_superleague/2593/team/99243/result.html'],
        ['id_team'=>'7', 'name' => 'Витязь',        'linl'=>'https://www.championat.com/hockey/_superleague/2593/team/99213/result.html'],
        ['id_team'=>'8', 'name' => 'Динамо М',      'linl'=>'https://www.championat.com/hockey/_superleague/2593/team/99201/result.html'],
        ['id_team'=>'9', 'name' => 'Динамо Мн',     'linl'=>'https://www.championat.com/hockey/_superleague/2593/team/99215/result.html'],
        ['id_team'=>'10', 'name' => 'Динамо Р',     'linl'=>'https://www.championat.com/hockey/_superleague/2593/team/99203/result.html'],
        
        ['id_team'=>'11', 'name' => 'Йокерит',      'linl'=>'https://www.championat.com/hockey/_superleague/2593/team/99205/result.html'],
        ['id_team'=>'12', 'name' => 'Куньлунь РС',  'linl'=>'https://www.championat.com/hockey/_superleague/2593/team/99245/result.html'],
        ['id_team'=>'13', 'name' => 'Локомотив',    'linl'=>'https://www.championat.com/hockey/_superleague/2593/team/99217/result.html'],
        ['id_team'=>'14', 'name' => 'Металлург Мг', 'linl'=>'https://www.championat.com/hockey/_superleague/2593/team/99217/result.html'],
        ['id_team'=>'15', 'name' => 'Нефтехимик',   'linl'=>'https://www.championat.com/hockey/_superleague/2593/team/99231/result.html'],
        
        ['id_team'=>'16', 'name' => 'Салават Юлаев', 'linl'=>'https://www.championat.com/hockey/_superleague/2593/team/99247/result.html'],
        ['id_team'=>'17', 'name' => 'Северсталь',   'linl'=>'https://www.championat.com/hockey/_superleague/2593/team/99207/result.html'],
        ['id_team'=>'18', 'name' => 'Сибирь',       'linl'=>'https://www.championat.com/hockey/_superleague/2593/team/99249/result.html'],
        ['id_team'=>'19', 'name' => 'СКА',          'linl'=>'https://www.championat.com/hockey/_superleague/2593/team/99209/result.html'],
        ['id_team'=>'20', 'name' => 'Слован',       'linl'=>'https://www.championat.com/hockey/_superleague/2593/team/99219/result.html'],
        
        ['id_team'=>'21', 'name' => 'Спартак',      'linl'=>'https://www.championat.com/hockey/_superleague/2593/team/99211/result.html'],
        ['id_team'=>'22', 'name' => 'Торпедо',      'linl'=>'https://www.championat.com/hockey/_superleague/2593/team/99233/result.html'],
        ['id_team'=>'23', 'name' => 'Трактор',      'linl'=>'https://www.championat.com/hockey/_superleague/2593/team/99235/result.html'],
        ['id_team'=>'24', 'name' => 'ХК Сочи',      'linl'=>'https://www.championat.com/hockey/_superleague/2593/team/99221/result.html'],
        ['id_team'=>'25', 'name' => 'ЦСКА',         'linl'=>'https://www.championat.com/hockey/_superleague/2593/team/99223/result.html'],
];
    
    
    // конструктор класса - присвоивание полям класса значений при создании экземпляра класса
    public function __construct($id_team){
        // установка id команды при создании экземпляра класса
        $this->id_team=$id_team;
        // создание подключения к БД
        $this->id_connect_DB = Yii::$app->db_khl_stat_2018;
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
    
    // функция парсинга страницы
	function pars_results($link_page, $id_team, $name_team){
    // запрос страницы
    $data_results = $this->curl_get($link_page);
        //file_put_contents('../1234.txt',$data_results,FILE_APPEND);
    //создание объекта phpQuery
    $doc_Dom = \phpQuery::newDocument($data_results);
        //file_put_contents('../1234.txt',$doc_Dom,FILE_APPEND);
    // парсинг страницы в массив $result_team
    //----------------------------------------
    for ($w=1; $w<90; $w++){
        //$d=$w+1;
        $res = $doc_Dom->find('tr:nth-child('.($w+1).')  td:nth-child(5) a')->text();
        // определяем сыгран ли был матч
        if (trim($res) != '-:-'){
            // порядковый номер матча
            $number_match = $w;
            
            // счет матча
            $puck_team = trim($doc_Dom->find('tr:nth-child('.($w+1).') td:nth-child(5) a:nth-child(1)')->text());
            
            // соперник, место игры, заброшенные шайбы
            // если название первой команды в матче соотвествует названию команды которую парсим, значит игра проходит дома и соперник в названии второй команды 
            if (trim($doc_Dom->find('tr:nth-child('.($w+1).') td:nth-child(4) a:nth-child(1)')->text()) == $name_team){
                $this->result_team[$id_team][$number_match]['rival'] = trim($doc_Dom->find('tr:nth-child('.($w+1).') td:nth-child(4) a:nth-child(2)')->text());
                $this->result_team[$id_team][$number_match]['place'] = 'home';
                $this->result_team[$id_team][$number_match]['puck_team'] = substr($puck_team,0,1);
                $this->result_team[$id_team][$number_match]['puck_rival'] = substr($puck_team,2,1);
            // иначе игра в гостях
            }else {
                $this->result_team[$id_team][$number_match]['rival'] = trim($doc_Dom->find('tr:nth-child('.($w+1).') td:nth-child(4) a:nth-child(1)')->text());
                $this->result_team[$id_team][$number_match]['place'] = 'guest';
                $this->result_team[$id_team][$number_match]['puck_team'] = substr($puck_team,2,1);
                $this->result_team[$id_team][$number_match]['puck_rival'] = substr($puck_team,0,1);
            }
            
            //дата игры
            $this->result_team[$id_team][$number_match]['date_match'] = trim($doc_Dom->find('tr:nth-child('.($w+1).') td:nth-child(2)')->text());
            
            // время окончания матча
            $time_end = trim($doc_Dom->find('tr:nth-child('.($w+1).') td:nth-child(5) a:nth-child(1)')->text());
            if(strpos($time_end, 'ОТ')){
                $this->result_team[$id_team][$number_match]['time_end'] = 'ОТ';}
            elseif(
                strpos($time_end, 'Б')){$this->result_team[$id_team][$number_match]['time_end'] = 'Б';}
            else{
                $this->result_team[$id_team][$number_match]['time_end'] = 'normal';}
            
            // результат игры для команды
            if($this->result_team[$id_team][$number_match]['puck_team'] > $this->result_team[$id_team][$number_match]['puck_rival']){
                $this->result_team[$id_team][$number_match]['result'] = 'win';}
            else{
                $this->result_team[$id_team][$number_match]['result'] = 'lose';}        
        }else{
            
            // матч не сыгран
            break;
        }
        
    } 
    
        
        //$this->$arr_temp=$result_team;
        //file_put_contents('1234.txt',print_r($result_team),FILE_APPEND);
    //printArray($result_team);
    // вызов функции записи в БД
    $this->write_result($this->result_team, $id_team);
    
}
    
    // функция записи данных в БД
	function write_result($result_team, $id_team){
        
    // определяем количество записей для внесения в БД
    $count_record = count($result_team[$id_team]);
    for($rec=1; $rec<=$count_record; $rec++){
      // формирования запроса на добавление данных о заброшенных шайбах
        $query_1 = "INSERT INTO result_match (id_team, date_view, rival, place, date_match, time_end, puck_team, puck_rival, result) VALUES (".$id_team.",'".$result_team[$id_team][$rec]['date_match']."', '".$result_team[$id_team][$rec]['rival']."',' ".$result_team[$id_team][$rec]['place']."', ".strtotime($result_team[$id_team][$rec]['date_match']).",' ".$result_team[$id_team][$rec]['time_end']."',' ".$result_team[$id_team][$rec]['puck_team']."',' ".$result_team[$id_team][$rec]['puck_rival']."',' ".$result_team[$id_team][$rec]['result']."')";
        // добавление данных в БД
        //$id_connect_DB->query($query_1);
        //$this->last5game_team_2=$this->id_connect_DB->createCommand($query_team_1)->queryAll();
        //file_put_contents('1234.txt',$query_1.";\n",FILE_APPEND);
        $this->id_connect_DB->createCommand($query_1)->execute();
    //------------------------------------------------------------------date_view
    } 
        
       
        
}
    
    // главная функция 
    function main(){
        
        // очищаем таблицу result_match
        if($this->id_team == 1){
            $tbl_TRUNCATE = 'TRUNCATE TABLE result_match';// формирование запроса
            $this->id_connect_DB->createCommand($tbl_TRUNCATE)->execute();// выполнение запроса    
        }
        
        
        
        // парсинг страницы с результатами
        $this->pars_results($this->arr_team[($this->id_team)-1]['linl'],
                            $this->arr_team[($this->id_team)-1]['id_team'],
                            $this->arr_team[($this->id_team)-1]['name']);
        
        
        
        $str_result['code'] = '<code>'.$this->arr_team[($this->id_team)-1]['id_team'].'</code> Результаты команды '.$this->arr_team[($this->id_team)-1]['name'].' собраны';
        
        $str_result['id_team']=$this->arr_team[($this->id_team)-1]['id_team'];
        
        file_put_contents('../1234.txt',json_encode($str_result));
        
        return json_encode($str_result); 
        
        //file_put_contents('../1234.txt','json_encode($this->$arr_temp)');
        
        //return json_encode($this->$arr_temp);
        
        //return json_encode($this->result_team);
        
        //echo '<pre>'.print_r($this->result_team,true).'</pre>';
        
    }
    
 
}