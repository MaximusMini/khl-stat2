<?php


use yii\helpers\HTML;


use yii\grid\GridView;
use yii\data\ActiveDataProvider;



use yii\data\SqlDataProvider;


$this->title = ('Парсер статистических данных команд КХЛ сезон 2018/19');

?>


    <div class="row">
        <h2>Парсер статистических данных команд КХЛ</h2>
    </div>

    <div class="row">
        <?=Html::a("Парсинг данных", ["module1/parser-khl-model_2_2018"],['class'=>'btn btn-success'])?>
        <?=Html::a("Отобразить таблицы", ["module1/parser-khl-data_2_2018"],['class'=>'btn btn-primary'])?>
        <?=Html::a("Парсеры", ["module1/main"],['class'=>'btn btn-warning'])?>
    </div>
    
    <div class="row">
        <hr>
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



