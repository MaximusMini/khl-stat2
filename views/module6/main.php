<?php


$this->title='Статистика команд';



// подключение файла с данными для выпадающих списков
include(Yii::getAlias('@app/web/my_config/module6.php'));
?>


    <h2>Получение статистических данных команды</h2>
    
    <form action="query">
        <div class="row">
            <div class="form-group col-lg-2">
                <label for=""></label>
                <select class="form-control" id="teams" name="id_team_1">
                    <?= $id_teams ?>
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for=""></label>
                <select class="form-control" id="teams" name="id_team_2">
                    <?= $id_teams ?>
                </select>
            </div>
            <div class="form-group col-lg-2">
            <br>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
    
    
    
<?php if($all_stat != NULL): /*проверка наличия данных*/?> 
	
	<div class="row"><?php /*Последние 5 игр*/?>
	<h4> Последние 5 игр</h4>
		<?php /*ПЕРВАЯ КОМАНДА*/?>
		<div class="col-lg-4">
			<table class="table">
				<tbody>
				<?php foreach($all_stat['last5g_t1'] as $val):?>
					<tr>
						<td><?=$val['date_view']?></td> <!-- дата-->
						<?php/*-------------------------------------------------*/?>
						<?php if(trim($val['place'])) == 'home'): /*игра дома*/?>
							<?php switch (trim($val['time_end'])): ?>
								<?php case 'normal': ?>
									<td><?=$arr_team[$val['id_team']]?></td>
									<td><?=$val['rival']?></td>
									<td><code><?=$val['puck_team'].':'.$val['puck_rival']?></code></td>
								<?php break;?>
								<?php case 'OT': ?>
									<td><?=$arr_team[$val['id_team']]?></td>
									<td><?=$val['rival']?></td>
									<td><code><?=$val['puck_team'].':'.$val['puck_rival'].'OT'?></code></td>
								<?php break;?>
								<?php case 'B': ?>
									<td><?=$arr_team[$val['id_team']]?></td>
									<td><?=$val['rival']?></td>
									<td><code><?=$val['puck_team'].':'.$val['puck_rival'].'Б'?></code></td>
								<?php break;?>
							<?php endswitch ?>
						<?php endif; /*if(trim($val['place'])) == 'home')*/?>
						<?php/*-------------------------------------------------*/?>
						<?php if(trim($val['place'])) == 'guest'): /*игра в гостях*/?> 
							<?php switch (trim($val['time_end'])): ?>
								<?php case 'normal': ?>
									<td><?=$val['rival']?></td>
									<td><?=$arr_team[$val['id_team']]?></td>
									<td><code><?=$val['puck_rival'].':'.$val['puck_team']?></code></td>
								<?php break;?>
								<?php case 'OT': ?>
									<td><?=$val['rival']?></td>
									<td><?=$arr_team[$val['id_team']]?></td>
									<td><code><?=$val['puck_rival'].':'.$val['puck_team'].'OT'?></code></td>
								<?php break;?>
								<?php case 'B': ?>
									<td><?=$val['rival']?></td>
									<td><?=$arr_team[$val['id_team']]?></td>
									<td><code><?=$val['puck_rival'].':'.$val['puck_team'].'Б'?></code></td>
								<?php break;?>
							<?php endswitch ?>
						<?php endif; /*if(trim($val['guest'])) == 'home')*/?>
						<?php/*-------------------------------------------------*/?>
					</tr>
				<?php endforeach?>
				</tbody>
			</table>
		</div><!--class="col-lg-4"-->
		<?php /*ВТОРАЯ КОМАНДА*/?>
		<div class="col-lg-4">
			<table class="table">
				<tbody>
				<?php foreach($all_stat['last5g_t2'] as $val):?>
					<tr>
						<td><?=$val['date_view']?></td> <!-- дата-->
						<?php/*-------------------------------------------------*/?>
						<?php if(trim($val['place'])) == 'home'): /*игра дома*/?>
							<?php switch (trim($val['time_end'])): ?>
								<?php case 'normal': ?>
									<td><?=$arr_team[$val['id_team']]?></td>
									<td><?=$val['rival']?></td>
									<td><code><?=$val['puck_team'].':'.$val['puck_rival']?></code></td>
								<?php break;?>
								<?php case 'OT': ?>
									<td><?=$arr_team[$val['id_team']]?></td>
									<td><?=$val['rival']?></td>
									<td><code><?=$val['puck_team'].':'.$val['puck_rival'].'OT'?></code></td>
								<?php break;?>
								<?php case 'B': ?>
									<td><?=$arr_team[$val['id_team']]?></td>
									<td><?=$val['rival']?></td>
									<td><code><?=$val['puck_team'].':'.$val['puck_rival'].'Б'?></code></td>
								<?php break;?>
							<?php endswitch ?>
						<?php endif; /*if(trim($val['place'])) == 'home')*/?>
						<?php/*-------------------------------------------------*/?>
						<?php if(trim($val['place'])) == 'guest'): /*игра в гостях*/?> 
							<?php switch (trim($val['time_end'])): ?>
								<?php case 'normal': ?>
									<td><?=$val['rival']?></td>
									<td><?=$arr_team[$val['id_team']]?></td>
									<td><code><?=$val['puck_rival'].':'.$val['puck_team']?></code></td>
								<?php break;?>
								<?php case 'OT': ?>
									<td><?=$val['rival']?></td>
									<td><?=$arr_team[$val['id_team']]?></td>
									<td><code><?=$val['puck_rival'].':'.$val['puck_team'].'OT'?></code></td>
								<?php break;?>
								<?php case 'B': ?>
									<td><?=$val['rival']?></td>
									<td><?=$arr_team[$val['id_team']]?></td>
									<td><code><?=$val['puck_rival'].':'.$val['puck_team'].'Б'?></code></td>
								<?php break;?>
							<?php endswitch ?>
						<?php endif; /*if(trim($val['guest'])) == 'home')*/?>
						<?php/*-------------------------------------------------*/?>
					</tr>
				<?php endforeach?>
				</tbody>
			</table>
		</div><!--class="col-lg-4"-->
	</div><!--class="row"-->
	<!-- ================================================================================== -->
	<div class="row"><?php /*Последние 5 игр дома - $all_stat['last5g_hom_t1']*/?>
	<h4> Последние 5 игр дома</h4>
		<?php /*ПЕРВАЯ КОМАНДА*/?>
		<div class="col-lg-4">
			<table class="table">
				<tbody>
				<?php foreach($all_stat['last5g_hom_t1'] as $val):?>
					<tr>
						<td><?=$val['date_view']?></td> <!-- дата-->
						<?php switch (trim($val['time_end'])): ?>
							<?php case 'normal': ?>
								<td><?=$arr_team[$val['id_team']]?></td>
								<td><?=$val['rival']?></td>
								<td><code><?=$val['puck_team'].':'.$val['puck_rival']?></code></td>
							<?php break;?>
							<?php case 'OT': ?>
								<td><?=$arr_team[$val['id_team']]?></td>
								<td><?=$val['rival']?></td>
								<td><code><?=$val['puck_team'].':'.$val['puck_rival'].'OT'?></code></td>
							<?php break;?>
							<?php case 'B': ?>
								<td><?=$arr_team[$val['id_team']]?></td>
								<td><?=$val['rival']?></td>
								<td><code><?=$val['puck_team'].':'.$val['puck_rival'].'Б'?></code></td>
							<?php break;?>
						<?php endswitch ?>
					</tr>
				<?php endforeach?>
				</tbody>
			</table>
		</div><!--class="col-lg-4"-->
		<?php /*ВТОРАЯ КОМАНДА*/?>
		<div class="col-lg-4">
			<table class="table">
				<tbody>
				<?php foreach($all_stat['last5g_hom_t2'] as $val):?>
					<tr>
						<td><?=$val['date_view']?></td> <!-- дата-->
						<?php switch (trim($val['time_end'])): ?>
							<?php case 'normal': ?>
								<td><?=$arr_team[$val['id_team']]?></td>
								<td><?=$val['rival']?></td>
								<td><code><?=$val['puck_team'].':'.$val['puck_rival']?></code></td>
							<?php break;?>
							<?php case 'OT': ?>
								<td><?=$arr_team[$val['id_team']]?></td>
								<td><?=$val['rival']?></td>
								<td><code><?=$val['puck_team'].':'.$val['puck_rival'].'OT'?></code></td>
							<?php break;?>
							<?php case 'B': ?>
								<td><?=$arr_team[$val['id_team']]?></td>
								<td><?=$val['rival']?></td>
								<td><code><?=$val['puck_team'].':'.$val['puck_rival'].'Б'?></code></td>
							<?php break;?>
						<?php endswitch ?>
					</tr>
				<?php endforeach?>
				</tbody>
			</table>
		</div><!--class="col-lg-4"-->
	</div><!--class="row"-->
	<!-- ================================================================================== -->
	<div class="row"><?php /*Последние 5 игр в гостях - $all_stat['last5g_gst_t1']*/?>
	<h4> Последние 5 игр в гостях</h4>
		<?php /*ПЕРВАЯ КОМАНДА*/?>
		<div class="col-lg-4">
			<table class="table">
				<tbody>
				<?php foreach($all_stat['last5g_gst_t1'] as $val):?>
					<tr>
						<td><?=$val['date_view']?></td> <!-- дата-->
						<?php switch (trim($val['time_end'])): ?>
							<?php case 'normal': ?>
								<td><?=$val['rival']?></td>
								<td><?=$arr_team[$val['id_team']]?></td>
								<td><code><?=$val['puck_rival'].':'.$val['puck_team']?></code></td>
							<?php break;?>
							<?php case 'OT': ?>
								<td><?=$val['rival']?></td>
								<td><?=$arr_team[$val['id_team']]?></td>
								<td><code><?=$val['puck_rival'].':'.$val['puck_team'].'OT'?></code></td>
							<?php break;?>
							<?php case 'B': ?>
								<td><?=$val['rival']?></td>
								<td><?=$arr_team[$val['id_team']]?></td>
								<td><code><?=$val['puck_rival'].':'.$val['puck_team'].'Б'?></code></td>
							<?php break;?>
						<?php endswitch ?>
					</tr>
				<?php endforeach?>
				</tbody>
			</table>
		</div><!--class="col-lg-4"-->
		<?php /*ВТОРАЯ КОМАНДА*/?>
		<div class="col-lg-4">
			<table class="table">
				<tbody>
				<?php foreach($all_stat['last5g_gst_t2'] as $val):?>
					<tr>
						<td><?=$val['date_view']?></td> <!-- дата-->
						<?php switch (trim($val['time_end'])): ?>
							<?php case 'normal': ?>
								<td><?=$val['rival']?></td>
								<td><?=$arr_team[$val['id_team']]?></td>
								<td><code><?=$val['puck_rival'].':'.$val['puck_team']?></code></td>
							<?php break;?>
							<?php case 'OT': ?>
								<td><?=$val['rival']?></td>
								<td><?=$arr_team[$val['id_team']]?></td>
								<td><code><?=$val['puck_rival'].':'.$val['puck_team'].'OT'?></code></td>
							<?php break;?>
							<?php case 'B': ?>
								<td><?=$val['rival']?></td>
								<td><?=$arr_team[$val['id_team']]?></td>
								<td><code><?=$val['puck_rival'].':'.$val['puck_team'].'Б'?></code></td>
							<?php break;?>
						<?php endswitch ?>
					</tr>
				<?php endforeach?>
				</tbody>
			</table>
		</div><!--class="col-lg-4"-->
	</div><!--class="row"-->
	
<?php endif; /*if($all_stat != NULL): - проверка наличия данных*/?>
        
        
    

    
    
    <p><?php printArray($all_stat)/**/?></p>
    <p>
        <?php

        //// Мадрид, Испания
        //ini_set('date.timezone', 'Europe/Madrid');
        //echo date('Y-m-d H:i:s'); // 2011-12-28 18:24:45
        //echo '<br>';
        //// Лос-Анджелес, США
        //ini_set('date.timezone', 'America/Los_Angeles');
        //echo date('Y-m-d H:i:s'); // 2011-12-28 09:24:45
        //echo '<br>';
        //// Токио, Япония
        //ini_set('date.timezone', 'Asia/Tokyo');
        //echo date('Y-m-d H:i:s'); // 2011-12-29 02:24:45

        ?>
    </p>
    
    
    
    

