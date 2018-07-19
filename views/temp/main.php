<?php

/* @var $this yii\web\View */

use yii\helpers\HTML;

$this->title = 'Временная страница';

?>



<h3>Работа с библиотекой GD</h3>
<p class='alert alert-success'>Информация о текущей установленной GD библиотеке</p>

<pre><?=print_r(gd_info())?></pre>

   
   
<?
    function func_DG(){
        // создание изображения
        $image = imagecreate(600, 600);
    }

    // создаем изображение 200*200
$img = imagecreatetruecolor(600, 600);
$img = imagecreatefrompng('images\module2\template_gameday\game_day_1.png');

// создаем несколько цветов
$white = imagecolorallocate($img, 255, 255, 255);
$red   = imagecolorallocate($img, 255,   0,   0);
$green = imagecolorallocate($img,   0, 255,   0);
$blue  = imagecolorallocate($img,   0,   0, 255);

imagesetthickness($img, 1);


// рисуем голову
imagearc($img, 100, 100, 100, 200,  0, 360, $white);
// рот
imagearc($img, 100, 100, 150, 150, 25, 155, $red);
// глаза
imagearc($img,  60,  75,  50,  50,  0, 360, $green);
imagearc($img, 140,  75,  50,  50,  0, 360, $blue);



// дуга 1
imagearc($img, 400, 400, 50, 50, 90, 270, $red);
// дуга 2
imagearc($img, 400, 400, 50, 50, 270, 90, $green);


// дуга 1
imagefilledarc($img, 400, 600, 150, 150, 90, 270, $red, IMG_ARC_PIE);
// дуга 2
imagefilledarc($img, 400, 600, 150, 150, 270, 90, $green, IMG_ARC_PIE);


imagerectangle($img, 814, 814, 985, 985, $blue);


imagepng($img,'images\temp\Duga.png',9);

// освобождаем память
imagedestroy($img);  

?>




<?
// создание изображения
$image = imagecreatetruecolor(100, 100);

// определение цветов
$white    = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
$gray     = imagecolorallocate($image, 0xC0, 0xC0, 0xC0);
$darkgray = imagecolorallocate($image, 0x90, 0x90, 0x90);
$navy     = imagecolorallocate($image, 0x00, 0x00, 0x80);
$darknavy = imagecolorallocate($image, 0x00, 0x00, 0x50);
$red      = imagecolorallocate($image, 0xFF, 0x00, 0x00);
$darkred  = imagecolorallocate($image, 0x90, 0x00, 0x00);

// делаем эффект 3Д
for ($i = 60; $i > 50; $i--) {
   imagefilledarc($image, 50, $i, 100, 50, 0, 45, $darknavy, IMG_ARC_PIE);
   imagefilledarc($image, 50, $i, 100, 50, 45, 75 , $darkgray, IMG_ARC_PIE);
   imagefilledarc($image, 50, $i, 100, 50, 75, 360 , $darkred, IMG_ARC_PIE);
}

imagefilledarc($image, 50, 50, 100, 50, 0, 45, $navy, IMG_ARC_PIE);
imagefilledarc($image, 50, 50, 100, 50, 45, 75 , $gray, IMG_ARC_PIE);
imagefilledarc($image, 50, 50, 100, 50, 75, 360 , $red, IMG_ARC_PIE);


// вывод изображения
imagepng($image,'images\temp\Duga2.png',9);

?>

