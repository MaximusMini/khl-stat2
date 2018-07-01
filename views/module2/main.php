<?php

// подключение js
$this->registerJsFile('@web/js/module2.js',['depends' => ['app\assets\AppAsset']]);

/* @var $this yii\web\View */

use yii\helpers\HTML;

$this->title = 'Постер матчей дня';
?>

<h2>Формирование постера матчей дня</h2>
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
        echo "<form action='poster-game-day' method='GET'>";
        echo "<div id='container add-bloks'>";
        echo "<div class='row'>";
            echo "<div class='col-lg-2'>";
                echo '<p id="amount">Количество матчей - '.$_GET['amount'].'</p>';
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
            echo '<input type="hidden" value="'.$_GET['amount'].'" name="amount">';
            echo "<div class='row'>";
                echo "<div class='col-lg-2'><p class='text-success'><b>Время матча</b></p><select class='team1 form-control ' style='font-size: 13px;' name='time_".($i+1)."'>".$time_match."</select></div>";
                echo "<div class='col-lg-2'><p class='text-danger'><b>Команда 1</b></p><select class='team1 form-control' name='team1_".($i+1)."'>".$teams."</select></div>";
                echo "<div class='col-lg-2'><p class='text-primary'><b>Команда 2</b></p><select class='team2 form-control' name='team2_".($i+1)."'>".$teams."</select></div>";
                echo "<div class='col-lg-6'>";    
                    echo "<div class='col-lg-3'>";
                        echo "<p class='text-danger'>Положение по Х</p>";
                        echo '<input type="number" class="form-control" name="xPosTeam1_'.($i+1).'" value="">';
                        echo "<p class='text-danger'>Положение по Y</p>";
                        echo '<input type="number" class="form-control" name="yPosTeam1_'.($i+1).'" value="">';
                        echo '<p style="margin:20px 0 0 0;">размер шрифта</p>';
                    echo "</div>";
                    echo "<div class='col-lg-3'>";
                        echo "<p class='text-primary'>Положение по Х</p>";
                        echo '<input type="number" class="form-control" name="xPosTeam2_'.($i+1).'" value="">';
                        echo "<p class='text-primary'>Положение по Y</p>";
                        echo '<input type="number" class="form-control" name="yPosTeam2_'.($i+1).'" value="">';
                        echo '<input type="number" class="form-control" name="fontSizeTeams_'.($i+1).'" value="" style="margin-top:15px;">';
                    echo "</div>";
                    echo "<div class='col-lg-3'>";
                        echo "<p class='text-danger'>Логотип1 по Х</p>";
                        echo '<input type="number" class="form-control" name="xPosLogo1_'.($i+1).'" value="">';
                        echo "<p class='text-danger'>Логотип1 по Y</p>";
                        echo '<input type="number" class="form-control" name="yPosLogo1_'.($i+1).'" value="">';
                    echo "</div>";
                    echo "<div class='col-lg-3'>";
                        echo "<p class='text-primary'>Логотип2 по Х</p>";
                        echo '<input type="number" class="form-control" name="xPosLogo2_'.($i+1).'" value="">';
                        echo "<p class='text-danger'>Логотип2 по Y</p>";
                        echo '<input type="number" class="form-control" name="yPosLogo2_'.($i+1).'" value="">';
                    echo "</div>";
                echo "</div>";
            echo "</div>";
            echo "<div class='row'><div class='col-lg-12'><hr></div></div>";
        }
        echo '<button type="submit" class="btn btn-success">Формировать постер</button>';
        echo "</div>";
        echo "</form>";
    }
?>



