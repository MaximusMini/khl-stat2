<?php

/* @var $this yii\web\View */

use yii\helpers\HTML;

$this->title = 'Информация о базе данных';

?>

<div class="container">
    <h2>Информация о базе данных</h2>   
</div>


<?php

// установка соединения с БД 
$db = new yii\db\Connection([
			'dsn' => 'mysql:host=localhost;dbname=db_preview',
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		]);
//*******************************
// получение имени БД
$posts = $db->createCommand('show databases'); // выполнение запроса show databases к БД
$settings_DB = (array)$posts;
$name_DB = get_object_vars($settings_DB['db'])['dsn']; // преобразование свойств объекта в массив
$name_DB = explode('=',$name_DB)[2];
echo "<br>имя БД - ".$name_DB;
//*******************************
// получение имен таблиц
$dbSchema = $db->schema;
$tables = $dbSchema->getTableNames();
//*******************************





?>

  <pre>
           <?= print_r($tables)?> 
        </pre>


