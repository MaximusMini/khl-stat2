<?php


// подключение файла с дополнительными функциями
include(Yii::getAlias('@app/web/my_config/module2.php'));

?>


<div class="container">
    <div class="row">
        <div class="col-lg-10">
            <h2>Постер результатов матчей дня</h2>    
        </div>
        <div class="col-lg-2">
            <a href="<?=Yii::$app->urlManager->createUrl(['module3/main'])?>" class="btn btn-success">модуль 3</a>
        </div>
    </div>
    
    <button class="btn btn-primary" data-toggle="collapse" data-target="#array-get">Массив $_GET</button>
    <p></p>
    <div class="container collapse" id="array-get">
        <pre>
           <?= print_r($data_poster)?> 
        </pre>
    </div>
    <p>количество матчей - <?= $data_poster['amount']?></p>
</div>


<?php
    // шаг рисования результатов
    $step = 110; // 20 расстояние между матчами + 90 высота матчей
    // массив с координатами прорисовки результатов матчей****
    $arr_coord = [
        // матч 1
        ['x_logo1'=>160, 'y_logo1'=>210+($step*0), 'x_logo2'=>690, 'y_logo2'=>210+($step*0), 'x_line_up_1'=>250, 'y_line_up_1'=>210+($step*0), 'x_line_up_2'=>780, 'y_line_up_2'=>210+($step*0), 'x_line_down_1'=>250, 'y_line_down_1'=>300+($step*0), 'x_line_down_2'=>780, 'y_line_down_2'=>300+($step*0),],
        // матч 2
        ['x_logo1'=>160, 'y_logo1'=>210+($step*1), 'x_logo2'=>690, 'y_logo2'=>210+($step*1), 'x_line_up_1'=>250, 'y_line_up_1'=>210+($step*1), 'x_line_up_2'=>780, 'y_line_up_2'=>210+($step*1), 'x_line_down_1'=>250, 'y_line_down_1'=>300+($step*1), 'x_line_down_2'=>780, 'y_line_down_2'=>300+($step*1),],
        // матч 3
        ['x_logo1'=>160, 'y_logo1'=>210+($step*2), 'x_logo2'=>690, 'y_logo2'=>210+($step*2), 'x_line_up_1'=>250, 'y_line_up_1'=>210+($step*2), 'x_line_up_2'=>780, 'y_line_up_2'=>210+($step*2), 'x_line_down_1'=>250, 'y_line_down_1'=>300+($step*2), 'x_line_down_2'=>780, 'y_line_down_2'=>300+($step*2),],
        // матч 4
        ['x_logo1'=>160, 'y_logo1'=>210+($step*3), 'x_logo2'=>690, 'y_logo2'=>210+($step*3), 'x_line_up_1'=>250, 'y_line_up_1'=>210+($step*3), 'x_line_up_2'=>780, 'y_line_up_2'=>210+($step*3), 'x_line_down_1'=>250, 'y_line_down_1'=>300+($step*3), 'x_line_down_2'=>780, 'y_line_down_2'=>300+($step*3),],
        // матч 5
        ['x_logo1'=>160, 'y_logo1'=>210+($step*4), 'x_logo2'=>690, 'y_logo2'=>210+($step*4), 'x_line_up_1'=>250, 'y_line_up_1'=>210+($step*4), 'x_line_up_2'=>780, 'y_line_up_2'=>210+($step*4), 'x_line_down_1'=>250, 'y_line_down_1'=>300+($step*4), 'x_line_down_2'=>780, 'y_line_down_2'=>300+($step*4),],
        // матч 6
        ['x_logo1'=>160, 'y_logo1'=>210+($step*5), 'x_logo2'=>690, 'y_logo2'=>210+($step*5), 'x_line_up_1'=>250, 'y_line_up_1'=>210+($step*5), 'x_line_up_2'=>780, 'y_line_up_2'=>210+($step*5), 'x_line_down_1'=>250, 'y_line_down_1'=>300+($step*5), 'x_line_down_2'=>780, 'y_line_down_2'=>300+($step*5),],
        // матч 7
        ['x_logo1'=>160, 'y_logo1'=>210+($step*6), 'x_logo2'=>690, 'y_logo2'=>210+($step*6), 'x_line_up_1'=>250, 'y_line_up_1'=>210+($step*6), 'x_line_up_2'=>780, 'y_line_up_2'=>210+($step*6), 'x_line_down_1'=>250, 'y_line_down_1'=>300+($step*6), 'x_line_down_2'=>780, 'y_line_down_2'=>300+($step*6),],
        // матч 8
        ['x_logo1'=>160, 'y_logo1'=>210+($step*7), 'x_logo2'=>690, 'y_logo2'=>210+($step*7), 'x_line_up_1'=>250, 'y_line_up_1'=>210+($step*7), 'x_line_up_2'=>780, 'y_line_up_2'=>210+($step*7), 'x_line_down_1'=>250, 'y_line_down_1'=>300+($step*7), 'x_line_down_2'=>780, 'y_line_down_2'=>300+($step*7),],
    ];
    //********************************************************
    // данные, получаемые из $_GET массива из вида
    $count_matches = $data_poster['amount']; //количество сыгранных матчей получаем из массива
//    $data_poster = [
//        'logo_team_1'=>[23,24,25,23,23,24,25,23], // имена файло логов команды 1
//        'logo_team_2'=>[24,25,23,24,25,23,24,25], // имена файло логов команды 2
//    ];
        echo  '$data_poster - '.$data_poster['logo_team_1'][0];      
    //********************************************************
    // загрузка изображения - шаблона
    $image = imagecreatefrompng('images\module3\template_final_score\final_score.png');
     // установка цвета
    $color_date = imagecolorallocate($image, 196,199,200);
    $color_time = imagecolorallocate($image, 112,214,243);
    $color_text = imagecolorallocate($image, 219,223,224);
    $color_line = imagecolorallocate($image, 255,255,255);
    // установка шрифта
    $font_date = 'font\ARIALBD.TTF';
    $font_time = 'font\BigNoodleTitlingCyr.ttf';
    $font_text = 'font\Arciform Sans cyr-lat Regular.otf';
    // цикл прорисовки результатов
    for($i=1; $i <= $count_matches; $i++){
        // формирование имен ключей
        $timeM = 'time_'.$i;// название ключа массива времени матча
        $teamFirst = 'team1_'.$i;               // имя первой команды
        $teamSecond = 'team2_'.$i;              // имя второй команды
        
        $score_team1 = 'score_team1_'.$i;        // счет команды 1
        $score_team2 = 'score_team2_'.$i;        // счет команды 2
        
        $count_periods = 'count_periods_'.$i;    // количестов периодов
//        
//        $xPosTeams = 'xPosTeam1_'.$i;           // начальная позиция по X надписи команд
//        $yPosTeams = 'yPosTeam1_'.$i;           // позиция по Y надписи команд
//        $xPosLogo1 = 'xPosLogo1_'.$i;           // позиция по X логотипа команды 1
//        $xPosLogo2 = 'xPosLogo2_'.$i;           // позиция по X логотипа команды 2
//        $yPosLogos = 'yPosLogo1_'.$i;           // позиция по Y логотипов команд
//        $fontSizeTeams = 'fontSizeTeams_'.$i;   // размер шрифта в названии команд
        
        echo '<br>teamFirst - '.$teamFirst.'<br>';
        echo '$teamSecond - '.$teamSecond.'<br>';
        echo 'data_poster[$teamFirst]- '.$data_poster[$teamFirst].'<br>';
        echo 'data_poster[$teamSecond]- '.$data_poster[$teamSecond].'<br>';
        
        // логотипа команды 1
        $path_logo_temp_1 = $logo[$data_poster[$teamFirst]];
        $logo_team_1 = imagecreatefrompng('images\module3\logo\\'.$path_logo_temp_1);
        //  логотипа команды 2
        $path_logo_temp_2 = $logo[$data_poster[$teamSecond]];
        $logo_team_2 = imagecreatefrompng('images\module3\logo\\'.$path_logo_temp_2);
        
        
        
        // вставка логотипа команды 1
        imagecopyresized($image, $logo_team_1, $arr_coord[$i-1]['x_logo1'], $arr_coord[$i-1]['y_logo1'], 0, 0,90, 91, 90,90);           
        // вставка логотипа команды 2
        imagecopyresized($image, $logo_team_2, $arr_coord[$i-1]['x_logo2'], $arr_coord[$i-1]['y_logo2'], 0, 0,90, 90, 90,90);            
        // вставка счета команды 1
        imagettftext($image, 65, 0, 370, ($arr_coord[$i-1]['y_line_down_1']-15), $color_text , $font_time, $data_poster[$score_team1]);
        // вставка счета команды 2
        imagettftext($image, 65, 0, 520, ($arr_coord[$i-1]['y_line_down_1']-15), $color_text , $font_time, $data_poster[$score_team2]);
        
        // вставка линий
        imageline ($image , $arr_coord[$i-1]['x_line_up_1'], $arr_coord[$i-1]['y_line_up_1'], $arr_coord[$i-1]['x_line_up_2'], $arr_coord[$i-1]['y_line_up_2'], $color_line);
        imageline ($image , $arr_coord[$i-1]['x_line_down_1'], $arr_coord[$i-1]['y_line_down_1'], $arr_coord[$i-1]['x_line_down_2'], $arr_coord[$i-1]['y_line_down_2'], $color_line);
        
        
        // вставка шаблона длительности матча
        echo '$data_poster[$count_periods]- '. $count_periods;
        $periods = imagecreatefrompng('images\module3\template_final_score\period_'.$data_poster[$count_periods].'.png');
        imagecopyresized($image, $periods, 425, $arr_coord[$i-1]['y_line_up_1']+15, 0, 0,70, 90, 70,90);
        
    }
    
               
    // вставка логотипа команды 1
//    $logo_team_1 = imagecreatefrompng('images\module3\logo\25.png');
//    imagecopyresized($image, $logo_team_1, 160, 210, 0, 0,90, 91, 90,90);           
    // вставка логотипа команды 2
//    $logo_team_2 = imagecreatefrompng('images\module3\logo\24.png');
//    imagecopyresized($image, $logo_team_2, 690, 210, 0, 0,90, 90, 90,90);            
    // вставка счета команды 1
               
    // вставка счета команды 2
               
    // вставка линий
//    imageline ($image , 250 , 210 , 780 , 210 , $color_line);
//    imageline ($image , 248 , 300 , 780 , 300 , $color_line);
    // вставка шаблона длительности матча
               
    // сохранение файла
    imagepng($image,'images\module3\new\final_score_.png',9);           
               

    // вывод файла на экран
    echo '<div class="container">';
        echo '<div class="col-lg-3">';
        echo '<div class="alert alert-success"><p><strong>Результат</strong></p></div>';
        echo '<a href="../../web/images/module3/new/final_score_.png" target="_blank"><img src="../../web/images/module3/new/final_score_.png" alt="" width="200"></a>';
        echo '</div>';
    echo '</div>';  




?>