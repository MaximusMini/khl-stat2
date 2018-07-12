<?php

/* @var $this yii\web\View */

use yii\helpers\HTML;

$this->title = 'Содержание таблицы БД';

?>





<h2>Содержание таблицы БД</h2>
<p>Имя таблицы <?= $name_table; ?></p>


<pre>
	<?= print_r($cells)?> 
</pre>


<?





?>