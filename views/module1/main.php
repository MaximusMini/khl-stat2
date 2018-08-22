<?php


use yii\helpers\HTML;

$this->title = ('Парсеры');

?>



<h2>Парсеры</h2>

<ul>
            <li><?=Html::a("Парсер турнирной таблицы КХЛ", ["module1/main"])?></li>
            <li><?=Html::a("Парсер статистических данных команд КХЛ", ["module2/main"])?></li>
            <li><?=Html::a("", ["module3/main"])?></li>
            <li><?=Html::a("Модуль 4 - База данных", ["module4/main"])?></li>
            <li><?=Html::a("Модуль 5 - Формирование постера матча", ["module5/main"])?></li>
        </ul>
        <ul>
            <li><?=Html::a("Временная страница", ["temp/main"])?></li>
            <li><?=Html::a("Работа с Canvas", ["temp/canvas"])?></li>
        </ul>
