<?php

use yii\helpers\HTML;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;

$this->title='Парсинг результатов матчей';

$this->registerJsFile('web/js/module1.js',['depends' => ['app\assets\AppAsset']]);

?>



    <div class="row">
        <h2>Парсинг результатов матчей команд КХЛ 2018/2019</h2>
    </div>

    <div class="row">
        <button class='btn btn-success' onclick='parsResults(1)'>Парсинг данных</button>
        <?=Html::a("Отобразить таблицы", ["module1/..........."],['class'=>'btn btn-primary'])?>
        <?=Html::a("Парсеры", ["module1/main"],['class'=>'btn btn-warning'])?>
    </div>
    
    <div class="row" id='qqq'>
        <hr>
    </div>
    
    
    <div class="row">
        <?= $ddd ?>
        <hr>
        <?= $www ?>
    </div>
    
    <div class="row">
        
        <?php
            if($view_data){
                foreach($arr_tabl as $tab){
                    echo "<h3 class='bg-success' style='padding:10px'>".$tab['nametable']."</h3>";
                    echo GridView::widget(['dataProvider' => $tab['provider'],]);
                }
                
                
                
            }
        ?> 
    </div>