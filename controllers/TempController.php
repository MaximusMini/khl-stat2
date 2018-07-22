<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

class TempController extends Controller
{
    
    public function actionMain()
    {
        return $this->render('main');
    }

    public function actionCanvas()
    {
        return $this->render('canvas');
    }

    // сохранение картинки на диск из canvas через ajax запрос 
    public function actionCanvasSave()
    {
        // получаем массив с данными 
        $data_ajax = Yii::$app->request->post();
        // определяем изображение, которое получено в base64
        $image = $data_ajax['par'];
        // ликвидируем лишние символы
		$image = substr($image,strpos($image,",")+1);
        // декодируем
		$image = base64_decode($image);
		// сохраняем изображение
		file_put_contents('../web/121.png', $image);
        $dir = __DIR__;
        return $this->render('canvas',['resultPjax'=>$data_ajax['par'], 'dir'=>$dir] );
    }
    
    // передача параметров в JS из PHP
    public function actionCodeJs()
    {
        $code_js = '
        <script>
            //alert("ho-ho");
            var canvas = document.getElementById("canva");
            var contCanvas = canvas.getContext("2d");
            
            // загрузка картинки
            var img = document.getElementById("img");
            contCanvas.drawImage(img,0,0);
            // рисование прямоугольника
            contCanvas.fillStyle = "blue"; 
            contCanvas.fillRect(10,10,40,30);
            
            
            var canvasData = canvas.toDataURL("image/png");
            document.getElementById("par").value = canvasData;

            $.ajax({
            url:"canvas-save", 
            type:"POST", 
            // data:{
            //     par:canvasData,
            // },
            success: function(){
                alert("success Ajax");
            },
            error:function(jqXHR, srtErr, errorThrown){alert("error:" +srtErr+ " errorThrown:" +errorThrown);}
        }); 


        </script>
        ';
        
        return $this->render('canvas',['code_js'=>$code_js]);
    }

    
    
    
}