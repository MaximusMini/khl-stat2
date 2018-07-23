<?php

/* @var $this yii\web\View */

use yii\helpers\HTML;

$this->title = 'Формирование постера матча';


// подключение файла с данными для выпадающих списков 
include(Yii::getAlias('@app/web/my_config/module5.php'));
?>





<h2>Формирование постера матча</h2>

    <form action="go-poster" method="get">
        <div class="row">
            <div class="col-lg-2">
                <p class='alert alert-info form-control' style='margin:5px 0px; padding:5px 10px'>Команда 1</p>
                <select class='team1 form-control' name='team1'> <?=$teams ?> </select>
            </div>
            <div class="col-lg-2">
                <p class='alert alert-info form-control' style='margin:5px 0px; padding:5px 10px'>Команда 2</p>
                <select class='team2 form-control' name='team2'> <?=$teams ?> </select>    
            </div>
            <div class="col-lg-2">
                <p class='alert alert-success form-control' style='margin:5px 0px; padding:5px 10px'>Дата</p>
                <input class='form-control' type="date" id='dateMatch' name='dateMatch'>          
            </div>
            <div class="col-lg-2">
                <p class='alert alert-success form-control' style='margin:5px 0px; padding:5px 10px'>Время</p>
                <select class='team2 form-control' name='timeMatch' id='timeMatch'> <?=$time_match ?> </select> 
            </div>
            <div class="col-lg-2">
                <p class='alert alert-success form-control' style='margin:5px 0px; padding:5px 10px'>Ледовая площадка</p>
                <select class='team2 form-control' name='arena' id='arena'> <?=$time_match ?> </select>
            </div>
            <div class="col-lg-2">
                <p class='alert alert-success form-control' style='margin:5px 0px; padding:5px 10px'>Город</p>
                <select class='team2 form-control' name='city' id='city'> <?=$time_match ?> </select>
            </div>
        </div>
        <hr>
        <button type="submit" class="btn btn-success">Формировать постер</button>
    </form>
  <br><br>  

   
<pre>
    <?= print_r($data_request)?>
</pre>


<pre>
    <?= print_r($all_data)?>
</pre>







