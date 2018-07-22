<?

use yii\helpers\HTML;
use yii\widgets\Pjax;

$this->title = 'Работа с Canvas';

?>

<h3>Работа с Canvas</h3>

<canvas id="canva" width='200' height='200'></canvas>

<img src="../../web/images/module2/template_gameday/game_day_1.png" alt="" id='img' style='display:none;'>


<script>
	
//var canvas = document.getElementById('canva');
//var contCanvas = canvas.getContext('2d');



// загрузка картинки
//var img = document.getElementById('img');
//// contCanvas.drawImage(img,0,0);
//contCanvas.drawImage(img,0,0,400,800);
//
//// рисование прямоугольника
//contCanvas.fillStyle = "red"; 
//contCanvas.fillRect(100,100,400,300);



</script>



<?php Pjax::begin(); ?>
     <?= Html::a(
        'Save',
        ['temp/canvas-save?img='],
        ['class' => 'btn btn-lg btn-primary']
    ) ?>
    <p><?=$resultPjax?></p>
<?php Pjax::end(); ?>


<script>
    function saveImg(){
     //            var canvas = document.getElementById('canva');
            var contCanvas = canvas.getContext('2d');
            
            // загрузка картинки
            var img = document.getElementById('img');
            contCanvas.drawImage(img,0,0);
            // рисование прямоугольника
            contCanvas.fillStyle = "yellow"; 
            contCanvas.fillRect(50,50,400,300);
            
            
            var canvasData = canvas.toDataURL("image/png");
		$.ajax({
			url:'canvas-save', 
			type:'POST', 
			data:{
                par:canvasData,
			},
            success: function(){
                alert('success Ajax');
            },
            error:function(jqXHR, srtErr, errorThrown){alert('error: '+srtErr+' errorThrown:'+errorThrown);}
		}); 
    }
</script>

<h1>resultPjax - <?=$resultPjax?></h1>

<a href="javascript:saveImg()" class="btn btn-danger" id='rrr'>Чистый Ajax</a>

<code class="php"><?php Pjax::begin(); ?>
    <?= Html::beginForm(['temp/canvas-save'], 'post', ['data-pjax' => '', 'class' => 'form-inline']); ?>
        
        <input type="text" name='par' id='par'>
        
        <script>
        
//            var canvas = document.getElementById('canva');
//            var contCanvas = canvas.getContext('2d');
//            
//            // загрузка картинки
//            var img = document.getElementById('img');
//            contCanvas.drawImage(img,0,0);
//            // рисование прямоугольника
//            contCanvas.fillStyle = "red"; 
//            contCanvas.fillRect(100,100,400,300);
//            
//            
//            var canvasData = canvas.toDataURL("image/png");
//            document.getElementById('par').value = canvasData;
            
        </script>
        
        
        
        <p><?=$resultPjax?></p>
       <?= Html::submitButton('Вычислить MD5', ['class' => 'btn btn-lg btn-primary']) ?>
    <?= Html::endForm() ?>
    <h3>Результат - <?= $dir ?></h3>
<?php Pjax::end(); ?></code>

<hr>

<?= Html::a('Передать код JS',['temp/code-js'], ['class' => 'btn btn-sm btn-primary']) ?>

<?= $code_js ?>







