<?php

// подключение библиотеки GD

// подключение файла с дополнительными функциями
include(Yii::getAlias('@app/web/my_config/module2.php'));

?>

<div class="container">
    <div class="row">
        <div class="col-lg-10">
            <h2>Постер матчей дня</h2>    
        </div>
        <div class="col-lg-2">
            <a href="<?=Yii::$app->urlManager->createUrl(['module2/main'])?>" class="btn btn-success">модуль 2</a>
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
    //************************************************************
    // массив с настройками вывода времени начала матчей
    $time_matches = [
        ['font_time'=>27, 'x_pos_time'=>22, 'y_pos_time'=>215],
        ['font_time'=>27, 'x_pos_time'=>50, 'y_pos_time'=>315],
        ['font_time'=>27, 'x_pos_time'=>77, 'y_pos_time'=>415],
        ['font_time'=>27, 'x_pos_time'=>103, 'y_pos_time'=>515],
        ['font_time'=>27, 'x_pos_time'=>129, 'y_pos_time'=>615],
        ['font_time'=>27, 'x_pos_time'=>157, 'y_pos_time'=>712],
        ['font_time'=>27, 'x_pos_time'=>183, 'y_pos_time'=>810],
        ['font_time'=>27, 'x_pos_time'=>209, 'y_pos_time'=>905],
        ['font_time'=>27, 'x_pos_time'=>234, 'y_pos_time'=>1010],
    ];
    //************************************************************
    // проверка количества матчей, формирование пути к шаблону
    $puth_template = str_replace('\\','/','..\..\web\images\module2\template_gameday\game_day_'.$data_poster['amount'].'.png');
    // загрузка изображения - шаблона
    $image = imagecreatefrompng('images\module2\template_gameday\game_day_'.$data_poster['amount'].'.png');
    // установка цвета
    $color_date = imagecolorallocate($image, 196,199,200);
    $color_time = imagecolorallocate($image, 112,214,243);
    $color_text = imagecolorallocate($image, 219,223,224);
    // установка шрифта
    $font_date = 'font\ARIALBD.TTF';
    $font_time = 'font\BigNoodleTitlingCyr.ttf';
    $font_text = 'font\Arciform Sans cyr-lat Regular.otf';
    // дата матча
    $date_matches = $data_poster['day_game'].' '.$data_poster['month_game'];
    imagettftext($image, 30, 0, 535, 85, $color_date, $font_date, $date_matches);
    // заполнение данными матчей
    $c_m = $data_poster['amount'];// количество матчей
    for($i=1; $i<=$c_m; $i++){
        // формирование имен ключей
        $timeM = 'time_'.$i;// название ключа массива времени матча
        $teamFirst = 'team1_'.$i;               // имя первой команды
        $teamSecond = 'team2_'.$i;              // имя второй команды
        $xPosTeams = 'xPosTeam1_'.$i;           // начальная позиция по X надписи команд
        $yPosTeams = 'yPosTeam1_'.$i;           // позиция по Y надписи команд
        $xPosLogo1 = 'xPosLogo1_'.$i;           // позиция по X логотипа команды 1
        $xPosLogo2 = 'xPosLogo2_'.$i;           // позиция по X логотипа команды 2
        $yPosLogos = 'yPosLogo1_'.$i;           // позиция по Y логотипов команд
        $fontSizeTeams = 'fontSizeTeams_'.$i;   // размер шрифта в названии команд
        //время
        imagettftext($image, $time_matches[$i-1]['font_time'], 0, $time_matches[$i-1]['x_pos_time'], $time_matches[$i-1]['y_pos_time'], $color_time , $font_time, $data_poster[$timeM]);
        // формирование общей строки с именами команды + верхний регистр
        $teams = $data_poster[$teamFirst].' - '.$data_poster[$teamSecond];
            //$teams = mb_strtoupper($data_poster[$teamFirst].' - '.$data_poster[$teamSecond]);
        // вывод надписи
        imagettftext($image, $data_poster[$fontSizeTeams], 0, $data_poster[$xPosTeams], $data_poster[$yPosTeams], $color_text , $font_text, $teams);
        // логотипа команды 1
        $path_logo_temp_1 = $logo[$data_poster[$teamFirst]];
        $logo_team_1 = imagecreatefrompng('images\module2\logo\\'.$path_logo_temp_1);
        // логотипа команды 2
        $path_logo_temp_2 = $logo[$data_poster[$teamSecond]];
        $logo_team_2 = imagecreatefrompng('images\module2\logo\\'.$path_logo_temp_2);
        // Копирование и наложение логотипов команд
        imagecopyresized($image, $logo_team_1,$data_poster[$xPosLogo1], $data_poster[$yPosLogos], 0, 0,60, 60, 60,60);
        imagecopyresized($image, $logo_team_2,$data_poster[$xPosLogo2], $data_poster[$yPosLogos], 0, 0,60, 60, 60,60); 
    }
    // сохранение файла
    $name_new_file = 'images\module2\new\gameDay_'.iconv("UTF-8", "Windows-1251//TRANSLIT",$date_matches).'.png';
    $name_new_file2 = 'images\module2\new\gameDay_'.$date_matches.'.png';
    imagepng($image,$name_new_file,9);
    // вывод шаблона
    echo '<div class="container">';
        echo '<div class="col-lg-3">';
        echo '<p>Шаблон</p>';
        echo '<img src="'.$puth_template.'" alt="" width="200">';
        echo '</div>';
    // вывод файла на экран
        echo '<div class="col-lg-3">';
        echo '<p>Результат</p>';
        echo '<a href="../../web/images/module2/new/gameDay_'.$date_matches.'.png" target="_blank"><img src="../../web/images/module2/new/gameDay_'.$date_matches.'.png" alt="" width="200"></a>';
        echo '</div>';
    echo '</div>';  
?>

































