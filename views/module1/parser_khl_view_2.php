<?php


use yii\helpers\HTML;

$this->title = ('Парсер статистических данных команд КХЛ');

?>


    <div class="row">
        <h2>Парсер статистических данных команд КХЛ</h2>
    </div>

    <div class="row">
        <?=Html::a("Парсинг данных", ["module1/parser-khl-model_2?model_parser=ParserKhlStat"],['class'=>'btn btn-success'])?>
        <?=Html::a("Отобразить таблицу", ["module1/parser-khl_2"],['class'=>'btn btn-primary'])?>
        <?=Html::a("Парсеры", ["module1/main"],['class'=>'btn btn-warning'])?>
    </div>
    
    <div class="row">
        <hr>
    </div>
