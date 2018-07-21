<?

use yii\helpers\HTML;
use yii\widgets\Pjax;

$this->title = 'Работа с Canvas';

?>

<h3>Работа с Canvas</h3>

<canvas id="canva" width='800' height='800'></canvas>

<img src="../../web/images/module2/template_gameday/game_day_1.png" alt="" id='img' style='display:none;'>


<script>
	
var canvas = document.getElementById('canva');
var contCanvas = canvas.getContext('2d');



// загрузка картинки
var img = document.getElementById('img');
// contCanvas.drawImage(img,0,0);
contCanvas.drawImage(img,0,0,400,800);

// рисование прямоугольника
contCanvas.fillStyle = "red"; 
contCanvas.fillRect(100,100,400,300);



</script>



<?php Pjax::begin(); ?>
     <?= Html::a(
        'Save',
        ['temp/canvas-save'],
        ['class' => 'btn btn-lg btn-primary']
    ) ?>
<?php Pjax::end(); ?>











