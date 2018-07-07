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
echo "<br>свойства объекта - ".get_object_vars($posts);
// echo '<pre>'.print_r(get_object_vars($posts)).'</pre>';

// $settings_DB = get_object_vars($posts);


$settings_DB = (array)$posts;

		
echo "<br>имя БД ".gettype($settings_DB);

		$name_DB = get_object_vars($settings_DB['db'])['dsn'];

		echo "<br>имя БД - ".$name_DB;

?>

