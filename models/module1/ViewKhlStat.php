<?php

namespace app\models\module1;

use Yii;
use yii\base\Model;

use yii\data\SqlDataProvider;


// парсер статистических данных команд КХЛ (с www.championat.com)
class ViewKhlStat extends Model
{
    
    public $id_connect_DB;// дескриптор подключения к БД
    public $arr_table=[]; // массив для хранения названия и данных таблиц
    
    public $nametable; // название таблиц выводимых в виде 
    
    
    // конструктор класса - присвоивание полям класса значений при создании экземпляра класса
    public function __construct(){
        // создание подключения к БД
        $this->id_connect_DB = Yii::$app->db_preview;
    }
    
    // получение данных из таблицы stat_puck - заброшенные шайбы
    public function get_stat_puck(){
        $provider = new SqlDataProvider([
            'db'=> 'db_preview',
            'sql' => 'SELECT * FROM stat_puck',
            'totalCount' => 27,
            'pagination' => [
                'pageSize' => 30,
            ],
            'sort' => [
                 'attributes' => [
                    'id_team' => [
                        'asc' => ['id_team' => SORT_ASC], // от А до Я
                        'desc' => ['id_team' => SORT_DESC], // от Я до А
                    ],
                    'name' => [
                        'asc' => ['name' => SORT_ASC], // от А до Я
                        'desc' => ['name' => SORT_DESC], // от Я до А
                    ],
                    'throw_puck' => [
                        'asc' => ['throw_puck' => SORT_ASC], // от А до Я
                        'desc' => ['throw_puck' => SORT_DESC], // от Я до А
                    ],
                   'throw_puck_home' => [
                        'asc' => ['throw_puck_home' => SORT_ASC], // от А до Я
                        'desc' => ['throw_puck_home' => SORT_DESC], // от Я до А
                    ],
                    'throw_puck_average' => [
                        'asc' => ['throw_puck_average' => SORT_ASC], // от А до Я
                        'desc' => ['throw_puck_average' => SORT_DESC], // от Я до А
                    ],
                   'throw_puck_average_home' => [
                        'asc' => ['throw_puck_average_home' => SORT_ASC], // от А до Я
                        'desc' => ['throw_puck_average_home' => SORT_DESC], // от Я до А
                    ],
                    'throw_puck_average_guest' => [
                        'asc' => ['throw_puck_average_guest' => SORT_ASC], // от А до Я
                        'desc' => ['throw_puck_average_guest' => SORT_DESC], // от Я до А
                    ],
                   'throw_puck_guest' => [
                        'asc' => ['throw_puck_guest' => SORT_ASC], // от А до Я
                        'desc' => ['throw_puck_guest' => SORT_DESC], // от Я до А
                    ], 
                ]          
            ],
        ]);
        array_push($this->arr_table,['nametable'=>'Заброшенные шайбы', 'provider'=>$provider]);
        return;
    }
    
    
    // получение данные из таблицы stat_allow_puck - пропущенные шайбы
    public function get_stat_allow_puck(){
         $provider = new SqlDataProvider([
            'db'=> 'db_preview',
            'sql' => 'SELECT * FROM stat_allow_puck',
            'totalCount' => 27,
            'pagination' => [
                'pageSize' => 30,
            ],
            'sort' => [
                 'attributes' => [
                    'id_team' => [
                        'asc' => ['id_team' => SORT_ASC], // от А до Я
                        'desc' => ['id_team' => SORT_DESC], // от Я до А
                    ],
                    'name' => [
                        'asc' => ['name' => SORT_ASC], // от А до Я
                        'desc' => ['name' => SORT_DESC], // от Я до А
                    ],
                    'allow_puck' => [
                        'asc' => ['allow_puck' => SORT_ASC], // от А до Я
                        'desc' => ['allow_puck' => SORT_DESC], // от Я до А
                    ],
                   'allow_puck_home' => [
                        'asc' => ['allow_puck_home' => SORT_ASC], // от А до Я
                        'desc' => ['allow_puck_home' => SORT_DESC], // от Я до А
                    ],
                    'allow_puck_guest' => [
                        'asc' => ['allow_puck_guest' => SORT_ASC], // от А до Я
                        'desc' => ['allow_puck_guest' => SORT_DESC], // от Я до А
                    ],
                   'allow_puck_averag' => [
                        'asc' => ['allow_puck_averag' => SORT_ASC], // от А до Я
                        'desc' => ['allow_puck_averag' => SORT_DESC], // от Я до А
                    ],
                    'allow_puck_average_home' => [
                        'asc' => ['allow_puck_average_home' => SORT_ASC], // от А до Я
                        'desc' => ['allow_puck_average_home' => SORT_DESC], // от Я до А
                    ],
                   'throw_puck_guest' => [
                        'asc' => ['allow_puck_average_guest' => SORT_ASC], // от А до Я
                        'desc' => ['allow_puck_average_guest' => SORT_DESC], // от Я до А
                    ], 
                ]          
            ],
        ]);
        array_push($this->arr_table,['nametable'=>'Пропущенные шайбы', 'provider'=>$provider]);
        return;
        
    }
    
    
    
    // главная функция 
    function main(){
        $this->get_stat_puck();                 //данных из таблицы stat_puck - заброшенные шайбах
        $this->get_stat_allow_puck();           //данные из таблицы stat_allow_puck - пропущенные шайбы
        return;
    }
    
 
}