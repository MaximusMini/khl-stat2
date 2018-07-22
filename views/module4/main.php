<?php

/* @var $this yii\web\View */

use yii\helpers\HTML;

$this->title = 'Информация о базе данных';

?>
<?php
        // установка соединения с БД 
        $db = new yii\db\Connection([
                    'dsn' => 'mysql:host=localhost;dbname=db_preview',
                    'username' => 'root',
                    'password' => '',
                    'charset' => 'utf8',
                ]);
        //*******************************
        // получение имени БД
        $posts = $db->createCommand('show databases'); // выполнение запроса show databases к БД
        $settings_DB = (array)$posts;
        $name_DB = get_object_vars($settings_DB['db'])['dsn']; // преобразование свойств объекта в массив
        $name_DB = explode('=',$name_DB)[2];
        // echo "<br>имя БД - ".$name_DB;
        //*******************************
        // получение имен таблиц
        $dbSchema = $db->schema;
        $tables = $dbSchema->getTableNames();
        //*******************************
?>


<div class="container">
    <h2>База данных приложения</h2>   
    <div class="col-lg-5">
        <div class="panel panel-primary">
            <div class="panel-heading">
            <h4 style="margin:0; padding:0;">Имя базы данных</h4>    
            </div>
            <div class="panel-body">
            <p><strong><?=$name_DB?></strong></p>    
            </div>
        </div>
        <!-- таблицы БД -->
        <div class="panel panel-info">
            <div class="panel-heading">
            <h4 style="margin:0; padding:0;">Таблицы базы данных</h4>    
            </div>
            <div class="panel-body">
            <?php foreach($tables as $val):?>
            <p>
            <form action="<?=Yii::$app->urlManager->createUrl(['module4/data-table'])?>" method="get">
                <div class="row">
                <li>
                    <div class="col-lg-8">
                        <?=$val?>
                        <input type="hidden" name='name-table' value="<?=$val?>">
                    </div>
                    <div class="col-lg-2">
                        <input type="submit" class='btn btn-info'>
                    </div>
                </li>
                </div>
                <hr>
            </form>
            <?php endforeach;?>    
            </div>
        </div>
    </div><!--  class="ol-lg-4"-->
</div> <!--  class="container"-->



