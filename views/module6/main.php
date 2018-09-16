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
            <select class="form-control" id="teams" name="id_team">
                <?= $id_teams?>
            </select>
            </div>
            <div class="form-group col-lg-2">
            <br>
            <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
    
    <h3><?= $arr_team[$id_team]?></h3>
    
    
    
    <div id='matchs-team1'>
        <?php foreach($result_query as $val):?>
        <?php
            //установить часовой пояс по умолчанию
            date_default_timezone_set('Europe/Rome');
            //echo date_default_timezone_get();
            if(trim($val['place']) == 'home'){
                echo '<p>'.gmdate("d.m.Y",$val['date_match']).'  '.
                     $arr_team[$val['id_team']].' - '.$val['rival']."  <code>".
                     $val['puck_team'].':'.$val['puck_rival'].   
                     '</code></p>';    
            }else{
                echo '<p>'.gmdate("d.m.Y",$val['date_match']).'  '.
                     $val['rival'].' - '.$arr_team[$val['id_team']]."  <code>".
                     $val['puck_rival'].':'.$val['puck_team'].
                     '</code></p>';
                
            }
        ?>
        <?php endforeach?>
    </div>
    
    
    <p><?php /*printArray($result_query)*/?></p>
    
    
    
    
    

