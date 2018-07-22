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
		file_put_contents('../web/111.png', $image);
        $dir = __DIR__;
        return $this->render('canvas',['resultPjax'=>$data_ajax['par'], 'dir'=>$dir] );
    }

    
    
    
}