<?php


use yii\helpers\HTML;

$this->title = ('Парсеры');

?>



<h2>Парсеры</h2>

    <ul>
        <li><?=Html::a("Парсер турнирной таблицы КХЛ 2017/18", ["module1/parser-khl-view_1_2017"])?></li>
        <li><?=Html::a("Парсер турнирной таблицы КХЛ 2018/19", ["module1/parser-khl-table_18_19"])?></li>
        <hr>
        <li><?=Html::a("Парсер статистических данных команд КХЛ 2017/18", ["module1/parser-khl-view_2"])?></li>
        <li><?=Html::a("Парсер статистических данных команд КХЛ 2018/19", ["module1/parser-khl-view_2_2018"])?></li>
        <hr>
        <li><?=Html::a("Парсер результатов команд КХЛ 2018/19", ["module1/parser-results_2018"])?></li>
        <hr>
    </ul>
