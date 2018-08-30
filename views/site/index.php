<?php

/* @var $this yii\web\View */

use yii\helpers\HTML;

$this->title = 'My Yii Application';
?>
<div class="site-index">
    
    
    <div class="container">
        <ul>
            <li><?=Html::a("Модуль 1 - Парсеры", ["module1/main"])?></li>
            <li><?=Html::a("Модуль 2 - Формирование постера матчей дня", ["module2/main"])?></li>
            <li><?=Html::a("Модуль 3 - Формирование постера результаты матчей дня", ["module3/main"])?></li>
            <li><?=Html::a("Модуль 4 - База данных", ["module4/main"])?></li>
            <li><?=Html::a("Модуль 5 - Формирование постера матча", ["module5/main"])?></li>
        </ul>
        <ul>
            <li><?=Html::a("Временная страница", ["temp/main"])?></li>
            <li><?=Html::a("Работа с Canvas", ["temp/canvas"])?></li>
        </ul>
    </div>

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">


    </div>
</div>
