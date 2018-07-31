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
                <select class='team2 form-control' name='arena' id='arena'> <?=$ice_arena ?> </select>
            </div>
            <div class="col-lg-2">
                <p class='alert alert-success form-control' style='margin:5px 0px; padding:5px 10px'>Город</p>
                <select class='team2 form-control' name='city' id='city'> <?=$city_match ?> </select>
            </div>
        </div>
        <hr>
        <button type="submit" class="btn btn-success">Формировать постер</button>
    </form>

<br><br>  

<canvas id='canva' width='800' height='800'> </canvas> 

<script>
    var canvas = document.getElementById("canva");
        canvas.width = 800;
        canvas.height = 800;
    var contCanvas = canvas.getContext("2d");

    // https://code.tutsplus.com/ru/tutorials/how-to-draw-a-pie-chart-and-doughnut-chart-using-javascript-and-html5-canvas--cms-27197

    // функция рисования линии
    function drawLine(ctx, startX, startY, endX, endY){
        /*
        ctx: ссылка на контекст рисунка
        startX: координата X начальной точки линии
        startY: координата Y начальной точки линии
        endX: координата X конечной точки линии
        endY: координата Y конечной точки линии
        */
        ctx.beginPath();
        ctx.moveTo(startX,startY);
        ctx.lineTo(endX,endY);
        ctx.stroke();
    }

    // рисование дуги
    function drawArc(ctx, centerX, centerY, radius, startAngle, endAngle){
        /*
        centerX: координата X центра окружности
        centerY: координата Y центра окружности
        radius: координата X конечной точки линии
        startAngle: угол начала в радианах, где начинается часть круга
        endAngle: конечный угол в радианах, где заканчивается часть круга
        */
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, startAngle, endAngle);
        ctx.stroke();
    }

    // рисование куска пирога
    function drawPieSlice(ctx,centerX, centerY, radius, startAngle, endAngle, color ){
        // color: цвет, используемый для заполнения среза
        ctx.fillStyle = color;
        ctx.beginPath();
        ctx.moveTo(centerX,centerY);
        ctx.arc(centerX, centerY, radius, startAngle, endAngle);
        ctx.closePath();
        ctx.fill();
    }

    // пересчет градусов в радианы
    var rad = function(grad){
        return (grad*Math.PI)/180;
    }


    drawLine(contCanvas, 100, 100, 145, 167);
    drawArc(contCanvas, 200, 200, 50, rad(0), rad(210));
    drawPieSlice(contCanvas, 200, 200, 50, rad(210), rad(250),'#ff0000' );

    // рисование круговой диаграммы с двумя значениями
    drawPieSlice(contCanvas, 300, 300, 150, rad(270), rad(150),'#ff0000' );
    drawPieSlice(contCanvas, 300, 300, 150, rad(150), rad(270),'#00ff00' );
    drawPieSlice(contCanvas, 300, 300, 100, rad(0), rad(380),'#ffffff' );
    
    
    // // рисование показателей побед/поражений
    // function winsDefeats(centerX1, centerY, contCanvas){
    //     // победы/поражения команды 1
    //     var winsTeam1=29;
    //     var defeatsTeam1=27;
    //     var gamesTeam1 = 56;
    //     var percWins1 = Math.round((winsTeam1/gamesTeam1)*100);
    //     var percDefeats1 = Math.round((defeatsTeam1/gamesTeam1)*100);
        
    //     var percWinsRadian1 = Math.round((360*percDefeats1)/100);
    //     var percDefeatsRadian1 = Math.round((360*percDefeats1)/100);
        
    //     //alert (percDefeatsRadian1);
        
    //     // победы/поражения команды 1
    //     var winsTeam2 = 25;
    //     var defeatsTeam2 = 31;
    //     var gamesTeams2 = 56;
    //     var percWins2 = Math.round((winsTeam2/gamesTeams2)*100);
        
    //     contCanvas.lineWidth = 35;
    //     contCanvas.fillStyle = '#ff0000';
    //     contCanvas.strokeStyle = "#8B0000"; // цвет линии
        
    //     contCanvas.beginPath();
    //     contCanvas.arc( centerX1, centerY, 100, rad(270), rad(270+percDefeatsRadian1));
        
    //     contCanvas.stroke();
    //     contCanvas.beginPath();    
    //     contCanvas.strokeStyle = "#008000"; // цвет линии
    //     contCanvas.arc( centerX1, centerY, 100, rad(83), rad(270));
    //     contCanvas.stroke();
    // }

    
    
    
    


</script>

<!-- rll -->
<script><?=$all_data['wins_defeats']?></script>
<script>winsDefeats(200, 200, contCanvas);</script>
<pre>
    <?= print_r($data_request)?>
</pre>

<pre>
    <?= $id_team1?>
</pre>

<pre>
    <?= 'bbb'.print_r($all_data)?>
</pre>

<pre>
    <?= $all_data?>
</pre>







