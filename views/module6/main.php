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
	
	<div class="row">
	    <div class="col-lg-5">
	    <div class="alert alert-info alert-font" role="alert"><?=$arr_team[$id_team_1]?></div>    
	    </div>
	    <div class="col-lg-5">
	    <div class="alert alert-info alert-font" role="alert"><?=$arr_team[$id_team_2]?></div>    
	    </div>   
	</div>
	<!-- Проведенные матчи================================================================= -->
	<div class="row">
    <h4> Проведенные матчи </h4>
	    <div class="col-lg-5">
	        <table class="table">
                <tr><td><samp>Все матчи</samp></td><td class="lead-m"><?= $all_stat['all_g_t1']?></td></tr>
	            <tr><td><samp>Матчи дома</samp></td><td class="lead-m"><?= $all_stat['all_g_h_t1']?></td></tr>
	            <tr><td><samp>Матчи в гостях</samp></td><td class="lead-m"><?= $all_stat['all_g_g_t1']?></td></tr>
	            <tr><td></td><td></td></tr>
	        </table>    
	    </div>
	    <div class="col-lg-5">
	        <table class="table">
                <tr><td><samp>Все матчи</samp></td><td class="lead-m"><?= $all_stat['all_g_t2']?></td></tr>
	            <tr><td><samp>Матчи дома</samp></td><td class="lead-m"><?= $all_stat['all_g_h_t2']?></td></tr>
	            <tr><td><samp>Матчи в гостях</samp></td><td class="lead-m"><?= $all_stat['all_g_g_t2']?></td></tr>
	            <tr><td></td><td></td></tr>
	        </table>     
	    </div>   
	</div>
	<hr>
	<!-- Последние 5 игр=================================================================== -->
	<div class="row"><?php /*Последние 5 игр*/?>
	<h4> Последние 5 игр</h4>
		<?php /*ПЕРВАЯ КОМАНДА*/?>
		<div class="col-lg-5">
			<table class="table table-dark">
				<tbody>
				<?php foreach($all_stat['last5g_t1'] as $val):?>
				    <?php /*установка цвета ячеек: победа - проигрыш команды*/?>
                    <?php if($val['puck_team'] > $val['puck_rival']){
                        echo '<tr class="success-table">';
                    }else{
                        echo '<tr class="danger">';
                    }
                    ?>
						<td><?=$val['date_view']?></td> <!-- дата-->
						<?php/*-------------------------------------------------*/?>
						<?php if(trim($val['place']) == 'home'): /*игра дома*/?>
							<?php switch (trim($val['time_end'])): ?>
<?php case 'normal': ?>
									<td><strong><?=$arr_team[$val['id_team']]?></strong></td>
									<td><?=$val['rival']?></td>
									<td><code><?=$val['puck_team'].':'.$val['puck_rival']?></code></td>
								<?php break;?>
								<?php case 'ОТ': ?>
									<td><strong><?=$arr_team[$val['id_team']]?></strong></td>
									<td><?=$val['rival']?></td>
									<td><code><?=$val['puck_team'].':'.$val['puck_rival'].' OT'?></code></td>
								<?php break;?>
								<?php case 'Б': ?>
									<td><strong><?=$arr_team[$val['id_team']]?></strong></td>
									<td><?=$val['rival']?></td>
									<td><code><?=$val['puck_team'].':'.$val['puck_rival'].' Б'?></code></td>
								<?php break;?>
							<?php endswitch ?>
						<?php endif; /*if(trim($val['place'])) == 'home')*/?>
						<?php/*-------------------------------------------------*/?>
						<?php if(trim($val['place']) == 'guest'): /*игра в гостях*/?> 
							<?php switch (trim($val['time_end'])): ?>
<?php case 'normal': ?>
									<td><?=$val['rival']?></td>
									<td><strong><?=$arr_team[$val['id_team']]?></strong></td>
									<td><code><?=$val['puck_rival'].':'.$val['puck_team']?></code></td>
								<?php break;?>
								<?php case 'ОТ': ?>
									<td><?=$val['rival']?></td>
									<td><strong><?=$arr_team[$val['id_team']]?></strong></td>
									<td><code><?=$val['puck_rival'].':'.$val['puck_team'].' OT'?></code></td>
								<?php break;?>
								<?php case 'Б': ?>
									<td><?=$val['rival']?></td>
									<td><strong><?=$arr_team[$val['id_team']]?></strong></td>
									<td><code><?=$val['puck_rival'].':'.$val['puck_team'].' Б'?></code></td>
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
		<div class="col-lg-5">
			<table class="table">
				<tbody>
				<?php foreach($all_stat['last5g_t2'] as $val):?>
				    <?php /*установка цвета ячеек: победа - проигрыш команды*/?>
                    <?php if($val['puck_team'] > $val['puck_rival']){
                        echo '<tr class="success-table">';
                    }else{
                        echo '<tr class="danger">';
                    }
                    ?>
						<td><?=$val['date_view']?></td> <!-- дата-->
						<?php/*-------------------------------------------------*/?>
						<?php if(trim($val['place']) == 'home'): /*игра дома*/?>
							<?php switch (trim($val['time_end'])): ?>
<?php case 'normal': ?>
									<td><strong><?=$arr_team[$val['id_team']]?></strong></td>
									<td><?=$val['rival']?></td>
									<td><code><?=$val['puck_team'].':'.$val['puck_rival']?></code></td>
								<?php break;?>
								<?php case 'ОТ': ?>
									<td><strong><?=$arr_team[$val['id_team']]?></strong></td>
									<td><?=$val['rival']?></td>
									<td><code><?=$val['puck_team'].':'.$val['puck_rival'].' OT'?></code></td>
								<?php break;?>
								<?php case 'Б': ?>
									<td><strong><?=$arr_team[$val['id_team']]?></strong></td>
									<td><?=$val['rival']?></td>
									<td><code><?=$val['puck_team'].':'.$val['puck_rival'].' Б'?></code></td>
								<?php break;?>
							<?php endswitch ?>
						<?php endif; /*if(trim($val['place'])) == 'home')*/?>
						<?php/*-------------------------------------------------*/?>
						<?php if(trim($val['place']) == 'guest'): /*игра в гостях*/?> 
							<?php switch (trim($val['time_end'])): ?>
<?php case 'normal': ?>
									<td><?=$val['rival']?></td>
									<td><strong><?=$arr_team[$val['id_team']]?></strong></td>
									<td><code><?=$val['puck_rival'].':'.$val['puck_team']?></code></td>
								<?php break;?>
								<?php case 'ОТ': ?>
									<td><?=$val['rival']?></td>
									<td><strong><?=$arr_team[$val['id_team']]?></strong></td>
									<td><code><?=$val['puck_rival'].':'.$val['puck_team'].' OT'?></code></td>
								<?php break;?>
								<?php case 'Б': ?>
									<td><?=$val['rival']?></td>
                                    <td><strong><?=$arr_team[$val['id_team']]?></strong></td>
									<td><code><?=$val['puck_rival'].':'.$val['puck_team'].' Б'?></code></td>
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
	<hr>
	<!-- Последние 5 игр дома============================================================== -->
	<div class="row"><?php /*Последние 5 игр дома - $all_stat['last5g_hom_t1']*/?>
	<h4> Последние 5 игр дома</h4>
		<?php /*ПЕРВАЯ КОМАНДА*/?>
		<div class="col-lg-5">
			<table class="table">
				<tbody>
				<?php foreach($all_stat['last5g_hom_t1'] as $val):?>
					<?php if($val['puck_team'] > $val['puck_rival']){
                        echo '<tr class="success-table">';
                    }else{
                        echo '<tr class="danger">';
                    }
                    ?>
						<td><?=$val['date_view']?></td> <!-- дата-->
						<?php switch (trim($val['time_end'])): ?>
<?php case 'normal': ?>
								<td><?=$arr_team[$val['id_team']]?></td>
								<td><?=$val['rival']?></td>
								<td><code><?=$val['puck_team'].':'.$val['puck_rival']?></code></td>
							<?php break;?>
							<?php case 'ОТ': ?>
								<td><?=$arr_team[$val['id_team']]?></td>
								<td><?=$val['rival']?></td>
								<td><code><?=$val['puck_team'].':'.$val['puck_rival'].' OT'?></code></td>
							<?php break;?>
							<?php case 'Б': ?>
								<td><?=$arr_team[$val['id_team']]?></td>
								<td><?=$val['rival']?></td>
								<td><code><?=$val['puck_team'].':'.$val['puck_rival'].' Б'?></code></td>
							<?php break;?>
						<?php endswitch ?>
					</tr>
				<?php endforeach?>
				</tbody>
			</table>
		</div><!--class="col-lg-4"-->
		<?php /*ВТОРАЯ КОМАНДА*/?>
		<div class="col-lg-5">
			<table class="table">
				<tbody>
				<?php foreach($all_stat['last5g_hom_t2'] as $val):?>
					<?php if($val['puck_team'] > $val['puck_rival']){
                        echo '<tr class="success-table">';
                    }else{
                        echo '<tr class="danger">';
                    }
                    ?>
						<td><?=$val['date_view']?></td> <!-- дата-->
						<?php switch (trim($val['time_end'])): ?>
<?php case 'normal': ?>
								<td><?=$arr_team[$val['id_team']]?></td>
								<td><?=$val['rival']?></td>
								<td><code><?=$val['puck_team'].':'.$val['puck_rival']?></code></td>
							<?php break;?>
							<?php case 'ОТ': ?>
								<td><?=$arr_team[$val['id_team']]?></td>
								<td><?=$val['rival']?></td>
								<td><code><?=$val['puck_team'].':'.$val['puck_rival'].' OT'?></code></td>
							<?php break;?>
							<?php case 'Б': ?>
								<td><?=$arr_team[$val['id_team']]?></td>
								<td><?=$val['rival']?></td>
								<td><code><?=$val['puck_team'].':'.$val['puck_rival'].' Б'?></code></td>
							<?php break;?>
						<?php endswitch ?>
					</tr>
				<?php endforeach?>
				</tbody>
			</table>
		</div><!--class="col-lg-4"-->
	</div><!--class="row"-->
	<hr>
	<!-- Последние 5 игр в гостях========================================================== -->
	<div class="row"><?php /*Последние 5 игр в гостях - $all_stat['last5g_gst_t1']*/?>
	<h4> Последние 5 игр в гостях</h4>
		<?php /*ПЕРВАЯ КОМАНДА*/?>
		<div class="col-lg-5">
			<table class="table">
				<tbody>
				<?php foreach($all_stat['last5g_gst_t1'] as $val):?>
					<?php if($val['puck_team'] > $val['puck_rival']){
                        echo '<tr class="success-table">';
                    }else{
                        echo '<tr class="danger">';
                    }
                    ?>
						<td><?=$val['date_view']?></td> <!-- дата-->
						<?php switch (trim($val['time_end'])): ?>
<?php case 'normal': ?>
								<td><?=$val['rival']?></td>
								<td><?=$arr_team[$val['id_team']]?></td>
								<td><code><?=$val['puck_rival'].':'.$val['puck_team']?></code></td>
							<?php break;?>
							<?php case 'ОТ': ?>
								<td><?=$val['rival']?></td>
								<td><?=$arr_team[$val['id_team']]?></td>
								<td><code><?=$val['puck_rival'].':'.$val['puck_team'].' OT'?></code></td>
							<?php break;?>
							<?php case 'Б': ?>
								<td><?=$val['rival']?></td>
								<td><?=$arr_team[$val['id_team']]?></td>
								<td><code><?=$val['puck_rival'].':'.$val['puck_team'].' Б'?></code></td>
							<?php break;?>
						<?php endswitch ?>
					</tr>
				<?php endforeach?>
				</tbody>
			</table>
		</div><!--class="col-lg-4"-->
		<?php /*ВТОРАЯ КОМАНДА*/?>
		<div class="col-lg-5">
			<table class="table">
				<tbody>
				<?php foreach($all_stat['last5g_gst_t2'] as $val):?>
					<?php if($val['puck_team'] > $val['puck_rival']){
                        echo '<tr class="success-table">';
                    }else{
                        echo '<tr class="danger">';
                    }
                    ?>
						<td><?=$val['date_view']?></td> <!-- дата-->
						<?php switch (trim($val['time_end'])): ?>
<?php case 'normal': ?>
								<td><?=$val['rival']?></td>
								<td><?=$arr_team[$val['id_team']]?></td>
								<td><code><?=$val['puck_rival'].':'.$val['puck_team']?></code></td>
							<?php break;?>
							<?php case 'ОТ': ?>
								<td><?=$val['rival']?></td>
								<td><?=$arr_team[$val['id_team']]?></td>
								<td><code><?=$val['puck_rival'].':'.$val['puck_team'].' OT'?></code></td>
							<?php break;?>
							<?php case 'Б': ?>
								<td><?=$val['rival']?></td>
								<td><?=$arr_team[$val['id_team']]?></td>
								<td><code><?=$val['puck_rival'].':'.$val['puck_team'].' Б'?></code></td>
							<?php break;?>
						<?php endswitch ?>
					</tr>
				<?php endforeach?>
				</tbody>
			</table>
		</div><!--class="col-lg-4"-->
	</div><!--class="row"-->
	<hr>
	<!-- Заброшенные шайбы================================================================= -->
	<div class="row">
        <div class="col-lg-10 alert alert-info lead" role="alert"><strong>Заброшенные шайбы</strong></div>
	    <div class="col-lg-5 ">
            
            <p class="text-primary lead">Всего: <?=$all_stat['puck_all_g_t1']?> <code>(<?=$all_stat['puck_all_g_clear_t1']?>)</code></p>
            <p class="text-success lead">Дома: <?=$all_stat['puck_all_hom_t1']?> <code>(<?=$all_stat['puck_all_hom_clear_t1']?>)</code></p>
            <p class="text-danger lead">В гостях: <?=$all_stat['puck_all_gst_t1']?> <code>(<?=$all_stat['puck_all_gst_clear_t1']?>)</code></p>
               
            <table class="table table-striped">
                <tr>
                    <td><samp>0 шайб</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_0_all_g_t1']?></strong></td>
                    <td><samp>P(0) = </samp><?= $all_stat['p_puck_0_t1']?></td>
                </tr>
                <tr>
                    <td><samp>1 шайба</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_1_all_g_t1']?></strong></td>
                    <td><samp>P(1) = </samp><?= $all_stat['p_puck_1_t1']?></td>
                </tr>
                <tr>
                    <td><samp>2 шайбы</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_2_all_g_t1']?></strong></td>
                    <td><samp>P(2) = </samp><?= $all_stat['p_puck_2_t1']?></td>
                </tr>
                <tr>
                    <td><samp>3 шайбы</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_3_all_g_t1']?></strong></td>
                    <td><samp>P(3) = </samp><?= $all_stat['p_puck_3_t1']?></td>
                </tr>
                <tr>
                    <td><samp>4 шайбы</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_4_all_g_t1']?></strong></td>
                    <td><samp>P(4) = </samp><?= $all_stat['p_puck_4_t1']?></td>
                </tr>
                <tr>
                    <td><samp>5 шайб</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_5_all_g_t1']?></strong></td>
                    <td><samp>P(5) = </samp><?= $all_stat['p_puck_5_t1']?></td>
                </tr>
                <tr>
                    <td><samp>6 шайб</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_6_all_g_t1']?></strong></td>
                    <td><samp>P(6) = </samp><?= $all_stat['p_puck_6_t1']?></td>
                </tr>
                <tr>
                    <td><samp>7 шайб</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_7_all_g_t1']?></strong></td>
                    <td><samp>P(7) = </samp><?= $all_stat['p_puck_7_t1']?></td>
                </tr>
	        </table>
	        <p><strong>Математическое ожидание заброшенных шайб:</strong></p>
            <p><samp>M(з.ш.) = </samp><?=$all_stat['M(X)_puck_t1']?> <br> <samp>M(з.ш.<sup>2</sup>) = </samp><?=$all_stat['M(X)2_puck_t1']?></p>
	        <p><strong>Дисперсия заброшенных шайб:</strong></p>
	        <p><samp></samp>D(з.ш.) = <?=$all_stat['M(X)2_puck_t1']?> - (<?=$all_stat['M(X)_puck_t1']?>)<sup>2</sup> = <?=$all_stat['D(X)_puck_t1']?></p>     
	    </div><!--class="col-lg-5"-->
	    <div class="col-lg-5">
	        
             <p class="text-primary lead">Всего: <?=$all_stat['puck_all_g_t2']?> <code>(<?=$all_stat['puck_all_g_clear_t2']?>)</code></p>
              <p class="text-success lead">Дома: <?=$all_stat['puck_all_hom_t2']?> <code>(<?=$all_stat['puck_all_hom_clear_t2']?>)</code></p>
              <p class="text-danger lead">В гостях: <?=$all_stat['puck_all_gst_t2']?> <code>(<?=$all_stat['puck_all_gst_clear_t2']?>)</code></p>
            
            <table class="table table-striped">
                 <tr>
                    <td><samp>0 шайб</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_0_all_g_t2']?></strong></td>
                    <td><samp>P(0) = </samp><?= $all_stat['p_puck_0_t2']?></td>
                </tr>
                <tr>
                    <td><samp>1 шайба</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_1_all_g_t2']?></strong></td>
                    <td><samp>P(1) = </samp><?= $all_stat['p_puck_1_t2']?></td>
                </tr>
                <tr>
                    <td><samp>2 шайбы</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_2_all_g_t2']?></strong></td>
                    <td><samp>P(2) = </samp><?= $all_stat['p_puck_2_t2']?></td>
                </tr>
                <tr>
                    <td><samp>3 шайбы</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_3_all_g_t2']?></strong></td>
                    <td><samp>P(3) = </samp><?= $all_stat['p_puck_3_t2']?></td>
                </tr>
                <tr>
                    <td><samp>4 шайбы</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_4_all_g_t2']?></strong></td>
                    <td><samp>P(4) = </samp><?= $all_stat['p_puck_4_t2']?></td>
                </tr>
                <tr>
                    <td><samp>5 шайб</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_5_all_g_t2']?></strong></td>
                    <td><samp>P(5) = </samp><?= $all_stat['p_puck_5_t2']?></td>
                </tr>
                <tr>
                    <td><samp>6 шайб</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_6_all_g_t2']?></strong></td>
                    <td><samp>P(6) = </samp><?= $all_stat['p_puck_6_t2']?></td>
                </tr>
                <tr>
                    <td><samp>7 шайб</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_7_all_g_t2']?></strong></td>
                    <td><samp>P(7) = </samp><?= $all_stat['p_puck_7_t2']?></td>
                </tr>
	        </table>
	        <p><strong>Математическое ожидание заброшенных шайб:</strong></p>
            <p><samp>M(з.ш.) = </samp><?=$all_stat['M(X)_puck_t2']?> <br> <samp>M(з.ш.<sup>2</sup>) = </samp><?=$all_stat['M(X)2_puck_t2']?></p>
	        <p><strong>Дисперсия заброшенных шайб:</strong></p>
	        <p><samp></samp>D(з.ш.) = <?=$all_stat['M(X)2_puck_t2']?> - (<?=$all_stat['M(X)_puck_t2']?>)<sup>2</sup> = <?=$all_stat['D(X)_puck_t2']?></p>
	        </table>     
	    </div><!--class="col-lg-5"-->       
	</div><!--class="row"-->
	<hr>
	<!-- Пропущенные шайбы================================================================= -->
	<div class="row">
	<div class="col-lg-10 alert alert-warning lead" role="alert"><strong>Пропущенные шайбы</strong></div>
	    <div class="col-lg-5">
	        
                <p class="text-primary lead">Всего: <?=$all_stat['puck_loss_all_g_t1']?> <code>(<?=$all_stat['puck_loss_all_g_clear_t1']?>)</code></p>
                <p class="text-success lead">Дома: <?=$all_stat['puck_loss_all_hom_t1']?> <code>(<?=$all_stat['puck_loss_all_hom_clear_t1']?>)</code></p>
                <p class="text-danger lead">В гостях: <?=$all_stat['puck_loss_all_gst_t1']?> <code>(<?=$all_stat['puck_loss_all_gst_clear_t1']?>)</code></p>
            
               <table class="table table-striped">
                <tr>
                    <td><samp>0 шайб</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_loss_0_all_g_t1']?></strong></td>
                    <td><samp>P(0) = </samp><?= $all_stat['p_puck_loss_0_t1']?></td>
                </tr>
                <tr>
                    <td><samp>1 шайба</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_loss_1_all_g_t1']?></strong></td>
                    <td><samp>P(1) = </samp><?= $all_stat['p_puck_loss_1_t1']?></td>
                </tr>
                <tr>
                    <td><samp>2 шайбы</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_loss_2_all_g_t1']?></strong></td>
                    <td><samp>P(2) = </samp><?= $all_stat['p_puck_loss_2_t1']?></td>
                </tr>
                <tr>
                    <td><samp>3 шайбы</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_loss_3_all_g_t1']?></strong></td>
                    <td><samp>P(3) = </samp><?= $all_stat['p_puck_loss_3_t1']?></td>
                </tr>
                <tr>
                    <td><samp>4 шайбы</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_loss_4_all_g_t1']?></strong></td>
                    <td><samp>P(4) = </samp><?= $all_stat['p_puck_loss_4_t1']?></td>
                </tr>
                <tr>
                    <td><samp>5 шайб</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_loss_5_all_g_t1']?></strong></td>
                    <td><samp>P(5) = </samp><?= $all_stat['p_puck_loss_5_t1']?></td>
                </tr>
                <tr>
                    <td><samp>6 шайб</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_loss_6_all_g_t1']?></strong></td>
                    <td><samp>P(6) = </samp><?= $all_stat['p_puck_loss_6_t1']?></td>
                </tr>
                <tr>
                    <td><samp>7 шайб</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_loss_7_all_g_t1']?></strong></td>
                    <td><samp>P(7) = </samp><?= $all_stat['p_puck_loss_7_t1']?></td>
                </tr>
	        </table>
	        <p><strong>Математическое ожидание пропущенных шайб:</strong></p>
            <p><samp>M(п.ш.) = </samp><?=$all_stat['M(X)_puck_loss_t1']?> <br> <samp>M(п.ш.<sup>2</sup>) = </samp><?=$all_stat['M(X)2_puck_loss_t1']?></p>
	        <p><strong>Дисперсия пропущенных шайб:</strong></p>
	        <p><samp></samp>D(з.ш.) = <?=$all_stat['M(X)2_puck_loss_t1']?> - (<?=$all_stat['M(X)_puck_loss_t1']?>)<sup>2</sup> = <?=$all_stat['D(X)_puck_loss_t1']?></p>     
	    </div><!--class="col-lg-5"-->
	    <div class="col-lg-5">
	        
            <p class="text-primary lead">Всего: <?=$all_stat['puck_loss_all_g_t2']?> <code>(<?=$all_stat['puck_loss_all_g_clear_t2']?>)</code></p>
            <p class="text-success lead">Дома: <?=$all_stat['puck_loss_all_hom_t2']?> <code>(<?=$all_stat['puck_loss_all_hom_clear_t2']?>)</code></p>
            <p class="text-danger lead">В гостях: <?=$all_stat['puck_loss_all_gst_t2']?> <code>(<?=$all_stat['puck_loss_all_gst_clear_t2']?>)</code></p>
                    
            <table class="table table-striped">
                 <tr>
                    <td><samp>0 шайб</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_loss_0_all_g_t1']?></strong></td>
                    <td><samp>P(0) = </samp><?= $all_stat['p_puck_loss_0_t2']?></td>
                </tr>
                <tr>
                    <td><samp>1 шайба</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_loss_1_all_g_t2']?></strong></td>
                    <td><samp>P(1) = </samp><?= $all_stat['p_puck_loss_1_t2']?></td>
                </tr>
                <tr>
                    <td><samp>2 шайбы</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_loss_2_all_g_t2']?></strong></td>
                    <td><samp>P(2) = </samp><?= $all_stat['p_puck_loss_2_t2']?></td>
                </tr>
                <tr>
                    <td><samp>3 шайбы</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_loss_3_all_g_t2']?></strong></td>
                    <td><samp>P(3) = </samp><?= $all_stat['p_puck_loss_3_t2']?></td>
                </tr>
                <tr>
                    <td><samp>4 шайбы</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_loss_4_all_g_t2']?></strong></td>
                    <td><samp>P(4) = </samp><?= $all_stat['p_puck_loss_4_t2']?></td>
                </tr>
                <tr>
                    <td><samp>5 шайб</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_loss_5_all_g_t2']?></strong></td>
                    <td><samp>P(5) = </samp><?= $all_stat['p_puck_loss_5_t2']?></td>
                </tr>
                <tr>
                    <td><samp>6 шайб</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_loss_6_all_g_t2']?></strong></td>
                    <td><samp>P(6) = </samp><?= $all_stat['p_puck_loss_6_t2']?></td>
                </tr>
                <tr>
                    <td><samp>7 шайб</samp></td>
                    <td><samp>игр: </samp><strong><?= $all_stat['puck_loss_7_all_g_t2']?></strong></td>
                    <td><samp>P(7) = </samp><?= $all_stat['p_puck_loss_7_t2']?></td>
                </tr>
	        </table>
	        <p><strong>Математическое ожидание пропущенных шайб:</strong></p>
            <p><samp>M(п.ш.) = </samp><?=$all_stat['M(X)_puck_loss_t2']?> <br> <samp>M(п.ш.<sup>2</sup>) = </samp><?=$all_stat['M(X)2_puck_loss_t2']?></p>
	        <p><strong>Дисперсия пропущенных шайб:</strong></p>
	        <p><samp></samp>D(п.ш.) = <?=$all_stat['M(X)2_puck_loss_t2']?> - (<?=$all_stat['M(X)_puck_loss_t2']?>)<sup>2</sup> = <?=$all_stat['D(X)_puck_loss_t2']?></p>
	        </table>     
	    </div><!--class="col-lg-5"-->       
	</div><!--class="row"-->
	<!-- Cводная таблица=================================== -->
	<div class="row">
	<div class="col-lg-10 alert alert-success lead" role="alert"><strong>Cводная таблица</strong></div>
	    <div class="col-lg-6">
	        
            
               <table class="table table-hover table-striped">
                <thead>
                    <td class="success"></td>
                    <td class="success"><?=$arr_team[$id_team_1]?></td>
                    <td class="success"><?=$arr_team[$id_team_2]?></td>
                </thead>
                <tr>
                    <td class="warning">M(з.ш.)</td>
                    <td><?=$all_stat['M(X)_puck_t1']?></td>
                    <td><?=$all_stat['M(X)_puck_t2']?></td>    
                </tr>
                <tr>
                    <td class="warning">D(з.ш.)</td>
                    <td><?=$all_stat['D(X)_puck_t1']?></td>
                    <td><?=$all_stat['D(X)_puck_t2']?></td>    
                </tr>
                <tr>
                    <td class="warning">M(п.ш.)</td>
                    <td><?=$all_stat['M(X)_puck_loss_t1']?></td>
                    <td><?=$all_stat['M(X)_puck_loss_t2']?></td>    
                </tr>
                <tr>
                    <td class="warning">D(п.ш.)</td>
                    <td><?=$all_stat['D(X)_puck_loss_t1']?></td>
                    <td><?=$all_stat['D(X)_puck_loss_t2']?></td>    
                </tr>
                
	        </table>
   
	    </div><!--class="col-lg-5"-->
	           
	</div><!--class="row"-->
<?php endif; /*if($all_stat != NULL): - проверка наличия данных*/?>
        
        
    

    
    
    <p><?php printArray($all_stat)/**/?></p>
   
    
    
    
    

