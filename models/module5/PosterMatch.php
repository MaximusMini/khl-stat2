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
         $db = new yii\db\Connection([
                    'dsn' => 'mysql:host=localhost;dbname=db_preview',
                    'username' => 'root',
                    'password' => '',
                    'charset' => 'utf8',
                ]);
        //*******************************
        // запрос на получение .....
        // 'SELECT * FROM stat_wins WHERE id_team='.$id_team
        $this->all_data = $db->createCommand('SELECT * FROM stat_wins WHERE id_team='.$id_team)->queryAll();
        
        
    }
    
    
    
    
    
    
}