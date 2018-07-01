<?php

/* @var $this yii\web\View */

use yii\helpers\HTML;

$this->title = 'Результаты матчей дня';

?>


<h2>Формирование постера результатов игрового дня</h2>
<hr>


<form action="amount-matches">
   <div class="row">
    <div class="form-group col-lg-2">
        <label for="sel1">Количество матчей:</label>
            <select class="form-control" id="matches" name="amount">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
            </select>
    </div>
    <div class="form-group col-lg-2">
        <br>
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
    </div>
</form>
<hr>

<?php
// подключение файла с данными для выпадающих списков
include(Yii::getAlias('@app/web/my_config/module2.php'));

    // формирование полей в зависимости от количества выбранных матчей
    if($_GET['amount']>0){
        echo "<form action='poster-final-score' method='GET'>";
        echo "<div id='container add-bloks'>";
        echo "<div class='row'>";
            echo "<div class='col-lg-2'>";
                echo '<p id="amount">Количество матчей - '.$_GET['amount'].'</p>';
                echo '<input type="hidden" value="'.$_GET['amount'].'" name="amount">';
            echo "</div>";
            echo "<div class='col-lg-2'>";
               echo '<select class="team1 form-control" name="day_game">'.$day_game.'</select>'; 
            echo "</div>";
            echo "<div class='col-lg-2'>";
                echo '<select class="team1 form-control" name="month_game">'.$month_game.'</select>';
            echo "</div>";
        echo "</div>";
        echo '<br>';
        for($i=0; $i<$_GET['amount']; $i++)
        {
                echo "<div class='row'>";
                    echo '<div class="alert alert-warning"><strong> Матч '.($i+1).'</strong></div>';
                    // команда 1
                    echo "<div class='col-lg-2'><p class='text-danger'><b>Команда 1</b></p><select class='team1 form-control' name='team1_".($i+1)."'>".$teams."</select></div>";
                    // команда 2
                    echo "<div class='col-lg-2'><p class='text-primary'><b>Команда 2</b></p><select class='team2 form-control' name='team2_".($i+1)."'>".$teams."</select></div>";
                    // счет команды 1
                    echo "<div class='col-lg-2'><p class='text-danger'><b>Шайбы команды 1</b></p><select class='score_team1 form-control' name='score_team1_".($i+1)."'>".$count_puks."</select></div>";
                    // счет команды 2
                    echo "<div class='col-lg-2'><p class='text-primary'><b>Шайбы команды 2</b></p><select class='score_team2 form-control' name='score_team2_".($i+1)."'>".$count_puks."</select></div>";
                    // время матча - количество периодов
                    echo "<div class='col-lg-2'><p class='text-success'><b>Время матча</b></p><select class='count_periods form-control' name='count_periods_".($i+1)."'>".$count_periods."</select></div>";
                echo "</div>";
            echo "<div class='row'><div class='col-lg-12'><hr></div></div>";
        }
        echo '<button type="submit" class="btn btn-success">Формировать постер</button>';
        echo "</div>";
        echo "</form>";
    }
    
































