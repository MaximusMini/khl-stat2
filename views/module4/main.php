<?php

/* @var $this yii\web\View */

use yii\helpers\HTML;

$this->title = 'Информация о базе данных';

?>

<div class="container">
    <h2>Информация о базе данных</h2>   
</div>


<?php

$db = new yii\db\Connection([
			'dsn' => 'mysql:host=localhost;dbname=db_preview',
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		]);


$posts = $db->createCommand('show databases');
echo gettype($posts);

?>


