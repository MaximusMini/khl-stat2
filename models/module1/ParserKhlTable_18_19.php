<?php

namespace app\models\module1;

use Yii;
use yii\base\Model;

use GuzzleHttp\Client; // подключаем Guzzle

class ParserKhlTable_18_19 extends Model
{
    
    public $id_connect_DB;      // дескриптор подключения к БД
    public $all_data=[];        // массив для сбора всех параметров
    
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
    
    // главная функция
    function main(){
        
    }
    
    
    // парсинг данных таблицы
    function pars_table(){
        //создаем объекты класса phpQuery
        $t_west      = phpQuery::newDocument($table_west);
        $t_east      = phpQuery::newDocument($table_east);
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
}