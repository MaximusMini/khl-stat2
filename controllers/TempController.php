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

    
    
    
}