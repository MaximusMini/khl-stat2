<?php


use yii\helpers\HTML;

$this->title = ('Парсеры');

?>



<h2>Парсеры</h2>

    <ul>
        <li><?=Html::a("Парсер турнирной таблицы КХЛ", ["module1/parser-khl-view_1"])?></li>
        <li><?=Html::a("Парсер статистических данных команд КХЛ", ["module1/parser-khl-view_2"])?></li>
        
    </ul>
