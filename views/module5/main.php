<?php

/* @var $this yii\web\View */

use yii\helpers\HTML;

$this->title = 'Формирование постера матча';


// подключение файла с данными для выпадающих списков 
include(Yii::getAlias('@app/web/my_config/module5.php'));
// подключение файла с функцией printArray() 
include(Yii::getAlias('@app/web/my_config/module1.php'));
// подключение JS
$this->registerJsFile('web/js/module5.js',['depends' => ['app\assets\AppAsset'],'position' => \yii\web\View::POS_HEAD]);
?>





<h2>Формирование постера матча</h2>

    <div class="row">
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
        <br>
        <button type="submit" class="btn btn-success">Формировать данные для постера</button>
        <hr>
    </form>
    </div>

    
    <div id="img-template">
       
    </div>
    
    <div class="row">
        <div class="row">
            <div class="col-lg-2">
                 <p class='alert alert-info form-control' style='margin:5px 0px; padding:5px 10px; font-size:11px;'>Команда 1 (процент побед)</p>
                 <input class='form-control' type="color" id='colorWinTeam_1' name='colorWinTeam_1' value='#8B0000'>    
            </div>
            <div class="col-lg-2">
                 <p class='alert alert-info form-control' style='margin:5px 0px; padding:5px 10px; font-size:9px;'>Команда 1 (процент поражений)</p>
                 <input class='form-control' type="color" id='colorDefTeam_1' name='colorWinTeam_1' value='#008000'>
            </div>
            <div class="col-lg-2">
                <p class='alert alert-warning form-control' style='margin:5px 0px; padding:5px 10px; font-size:11px;'>Команда 2 (процент побед)</p>
                 <input class='form-control' type="color" id='colorWinTeam_2' name='colorWinTeam_1' value='#8B0000'>
            </div>
            <div class="col-lg-2">
                <p class='alert alert-warning form-control' style='margin:5px 0px; padding:5px 10px; font-size:9px;'>Команда 2 (процент поражений)</p>
                 <input class='form-control' type="color" id='colorDefTeam_2' name='colorWinTeam_1' value='#8B0000'>
            </div>
            <div class="col-lg-2"></div>
            <div class="col-lg-2"></div>
        </div>
        <br>
        <button class='btn btn-primary' onclick="getDrawPict()">Постер</button>
        <hr>
    </div>
    
   
    <div class="row">
        <ul>
            <li>Количестов побед (все)</li>
            <li>Количестов поражений (все)</li>
            <li>Место в конференции - с соседями по таблице (место, клуб, очки)</li>
            <li>Процент набранных очков</li>
            <li>Количество заброшенных шайб</li>
            <li>Количество проведенных бросков</li>
            <li>Процент реализации бросков</li>
            <li>Количество полученного большинства</li>
            <li>Количество реализованного большинства</li>
            <li>Процент реализации большинства</li>
            <li>Количество полученного меньшинства</li>
            <li>Количество реализованного большинства противником</li>
            <li>Процент реализации большинства противником</li>
            <li>Силовые приемы</li>
            <li>Блокированные броски</li>
            <li>Количество вбрасываний</li>
        </ul>
    </div>
       
           
    <?/*отображение данных*/?>
    <?php if($data_request != NULL):?>
        <?php echo printArray($data_request);/**/?>
        <?php echo '<pre>'.printArray($all_data).'</pre>';/**/?>
        
        <div>
             <h4>Холст</h4>
             <canvas id='example'>Обновите браузер</canvas>    
        </div>
       
        
    <script>  
        alert(document.getElmentById('colorWinTeam_1').value);
        <?php /* передача переменных из php в js */?>
        <?php
//        echo 'var dateMatch = '.$all_data['dateMatch'].';';
//        echo 'var timeMatch = '.$all_data['timeMatch'].';';
//        echo 'var dateMatch = '.$all_data['dateMatch'].';';
//        echo 'var dateMatch = '.$all_data['dateMatch'].';';
        
        echo 'var dataPoster={'.
            'dateMatch:"'.$all_data['dateMatch'].'",'.
            'timeMatch:"'.$all_data['timeMatch'].'",'.
            
            'wins_1:'.$all_data['wins_1'].','.
            'wins_2:'.$all_data['wins_2'].','.
            'defeats_1:'.$all_data['defeats_1'].','.
            'defeats_2:'.$all_data['defeats_2'].','.
            'perc_wins_1:'.$all_data['perc_wins_1'].','.
            'perc_defeats_1:'.$all_data['perc_defeats_1'].','.
            'perc_wins_2:'.$all_data['perc_wins_2'].','.
            'perc_defeats_2:'.$all_data['perc_defeats_2'].','.
            'grade_wins_1:'.$all_data['grade_wins_1'].','.
            'grade_defeats_1:'.$all_data['grade_defeats_1'].','.
            
            
            '};';
        
        /*
        
[grade_wins_1] => 57
    [grade_wins_2] => 303


    [arena] => Большой
    [city] => Минск
    [name_team_1] => Адмирал
    [name_team_2] => Металлург Мг
    [place_team_1] => 12
    [place_team_2] => 5
    [conf_1] => east
    [conf_2] => east
    [wins_1] => 5
    [wins_2] => 17
    [defeats_1] => 21
    [defeats_2] => 10
    [percent_scr_1] => 25
    [percent_scr_2] => 64.8
    [throw_puck_1] => 51
    [throw_puck_2] => 72
    [total_throw_1] => 679
    [total_throw_2] => 890
    [total_throw_average_1] => 26.12
    [total_throw_average_2] => 34.23
    [throw_perc_total_1] => 7.5
    [throw_perc_total_2] => 7.6
    [total_power_play_1] => 208
    [total_power_play_2] => 201
    [perc_power_play_1] => 20.7
    [perc_power_play_2] => 16.4
    [total_power_kill_1] => 218
    [total_power_kill_2] => 194
    [perc_power_kill_1] => 80.3
    [perc_power_kill_2] => 74.2
        
        */
        ?>
        
        
        // основная функция рисования постера
        function getDrawPict(){
            // получаем контекст канвы
            var canvas = document.getElementById("example");
            ctx     = canvas.getContext('2d'); // Контекст
            // Создание нового объекта изображения
            var img = new Image();  
            img.src = '../../web/images/module5/temp.png';  // загружаем изображение
            // Событие onLoad, ждём момента пока загрузится изображение 
            img.onload = function() {    
                // меняем размеры холста на размеры загруженного изображения
                canvas.width=img.width;
                canvas.height=img.height;
                // вывод изображения на холст от точки с координатами 0, 0
                ctx.drawImage(img, 0, 0, img.width, img.height);  
                // рисование линии
                //drawLine(ctx, 10,10, 100, 100);
                
                // рисование соотношения побед/поражений
                <?/*
                    отдельно нужно расчитать точки для рисования
                        
                    
                    1.рисование начинается с точки rad(270) - верх-середина
                    
                    2.сначало рисуется процент поражений
                        точка до которой она рисуется нужно рассчитать - в градусах
                        1 процент - 3,6 градусов 
                        perc_defeats_1
                    
                    3.от него рисуется процент побед
                    
                    
                */?>
                
                // отрисовка процента поражений команды 1
                drawArc(ctx, 160, 360, 50, rad(270), rad(270+dataPoster['grade_defeats_1']),25,'#ff0000',"#8B0000" );
                // отрисовка процента побед команды 1
                drawArc(ctx, 160, 360, 50, rad(270+dataPoster['grade_defeats_1']), rad(270), 25,'#ff0000',"#008000" );
                
                // отрисовка процента поражений команды 2
                drawArc(ctx, 260, 360, 50, rad(270), rad(270+dataPoster['grade_defeats_2']),25,'#ff0000',"#8B0000" );
                // отрисовка процента побед команды 2
                drawArc(ctx, 260, 360, 50, rad(270+dataPoster['grade_defeats_2']), rad(270), 25,'#ff0000',"#008000" );
                
                
                
                
                
                
                
                // перевод изображения в base 64
                var scrImg = canvas.toDataURL('image/png').replace(/data:image\/png;base64,/, '');
                // вызов функции для сохранения изображения
                getAjax(scrImg);
            }//img.onload
        }//----getDrawPict()
        // функция сохранения изображения    
        function getAjax(scrImg){
            $.ajax({
                url: "save-poster",
                data:{scrImg: scrImg},
                type:"post",
                success:function(data, textStatus, jqXHR){
                    //alert('success');
                },
                error:function(jqXHR, srtErr, errorThrown){
                    alert('error: '+srtErr+' errorThrown:'+errorThrown);
                }    
            });   
        }//-----getAjax(scrImg)
    </script>

    <?php endif;?>  
        


<script>
   



//    drawLine(contCanvas, 100, 100, 145, 167);
//    drawArc(contCanvas, 200, 200, 50, rad(0), rad(210));
//    drawPieSlice(contCanvas, 200, 200, 50, rad(210), rad(250),'#ff0000' );

    // рисование круговой диаграммы с двумя значениями
//    drawPieSlice(contCanvas, 300, 300, 150, rad(270), rad(150),'#ff0000' );
//    drawPieSlice(contCanvas, 300, 300, 150, rad(150), rad(270),'#00ff00' );
//    drawPieSlice(contCanvas, 300, 300, 100, rad(0), rad(380),'#ffffff' );
    
    
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
        
    //     // победы/поражения команды 2
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
<script><?=$js_code?></script>
<script>
//            //winsDefeats(200, 200, contCanvas);
//
//            var imgTag = document.createElement('img');
//            imgTag.id = 'img-template';
//            imgTag.setAttribute('src','./../web/images/module5/temp.png');
//            imgTag.setAttribute('width','600');
//            var div = document.getElementById('img-template');
//            // $('div#img-template').append(imgTag);
//            document.body.appendChild(imgTag, div);
//            alert(div);
//
//            // document.write
</script>






