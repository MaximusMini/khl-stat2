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

$nameTable = $tables[15];
$cells = $db->createCommand('SELECT * FROM '.$nameTable)
            ->queryAll();


 echo '<table class="table">';

foreach ($cells as $val){
//	for ($i=0;$i<=count($val);$i++){
//		echo key($val[$i]);
//	}
//	echo '<br>!!!'.count($val);
    
   echo '<tr>'; 
    while (current($val)) {
//        echo key($val).'<br />';
//        
        echo '<td>';
        echo $val[key($val)];
        echo '</td>';
//        echo '<hr>!!!';
        next($val);
    }
    echo '</tr>'; 
    
    
}

echo '</table>';
?>

<?/*= foreach($cells as $val):*/?>
    
<?/*= endforeach;*/?>



<pre>
	<?= print_r($tables)?>
	<a href="<?= ?>" class="btn btn-inf">Показать</a> 
</pre>

<pre>
	<?= print_r($cells)?> 
</pre>


