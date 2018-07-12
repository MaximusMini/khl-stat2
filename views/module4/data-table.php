<?php

/* @var $this yii\web\View */

use yii\helpers\HTML;

$this->title = 'Содержание таблицы БД';

?>





<h2>Содержание таблицы БД</h2>
<p>Имя таблицы: <?= $name_table; ?></p>
<?
// таблица result_match
if($name_table == 'result_match'){
	echo '<table class="table"><tbody>';
	echo '<tr><th>id команды</th><th>Соперник</th><th>Место</th><th>Дата</th><th>Длительность</th><th>Шайбы команды</th><th>Шайбы соперника</th><th>Результат</th></tr>';
	foreach($cells as $val){
		// foreach($one_cell as $val){
			$date = date('d.m.Y',$val['date_match']);
			echo '<tr>';
			echo '<td>'.$val['id_team'].'</td><td>'.$val['rival'].'</td><td>'.$val['place'].'</td><td>'.$date.'</td><td>'.$val['time_end'].'</td>';
			echo '<td>'.$val['puck_team'].'</td><td>'.$val['puck_rival'].'</td><td>'.$val['result'].'</td>';
			echo '</tr>';
			// echo '<pre>';
			// echo print_r($val);
			// echo $val['id_team'];

			// echo '</pre>';
		// }
	}
	echo '</tbody></table>';
}
//*************************************************

// таблица stat_allow_puck
//*************************************************

// таблица stat_attacks
//*************************************************

// таблица stat_defenders
//*************************************************

// таблица stat_faceoff
//*************************************************

// таблица stat_goalies
//*************************************************

// таблица stat_loss
//*************************************************

// таблица stat_penalty
//*************************************************

// таблица stat_pow_play_pow_kill
//*************************************************

// таблица stat_puck
//*************************************************

// таблица stat_throw
//*************************************************

// таблица stat_throw_rival
//*************************************************

// таблица stat_trow_percent
//*************************************************

// таблица stat_wins
//*************************************************

// таблица table_conf
//*************************************************

// таблица team
//*************************************************

?>