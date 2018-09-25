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
    
    
    
    
    <div class="row">
        <div class="col-lg-4">
            <h3><?= $arr_team[$id_team_1]?></h3>
            <div id='matchs-team1'>
            <?php if($result_query_team_1 != NULL): ?>
            <?php foreach($result_query_team_1 as $val):?>
            <?php
                //echo date_default_timezone_get();
                if(trim($val['place']) == 'home'){
                    echo '<p>'.$val['date_view'].'  '.
                         $arr_team[$val['id_team']].' - '.$val['rival']."  <code>".
                         $val['puck_team'].':'.$val['puck_rival'].   
                         '</code></p>';    
                }else{
                    echo '<p>'.$val['date_view'].'  '.
                         $val['rival'].' - '.$arr_team[$val['id_team']]."  <code>".
                         $val['puck_rival'].':'.$val['puck_team'].
                         '</code></p>';

                }
            ?>
            <?php endforeach?>
            <?php endif;?>
            </div>    
        </div>
        <div class="col-lg-4">
            <div id='matchs-team1'>
            <h3><?= $arr_team[$id_team_2]?></h3>
            <?php if($result_query_team_2 != NULL): ?>
            <?php foreach($result_query_team_2 as $val):?>
            <?php
                //установить часовой пояс по умолчанию
                date_default_timezone_set('Europe/Rome');
                //echo date_default_timezone_get();
                if(trim($val['place']) == 'home'){
                    echo '<p>'.$val['date_view'].'  '.
                         $arr_team[$val['id_team']].' - '.$val['rival']."  <code>".
                         $val['puck_team'].':'.$val['puck_rival'].   
                         '</code></p>';    
                }else{
                    echo '<p>'.$val['date_view'].'  '.
                         $val['rival'].' - '.$arr_team[$val['id_team']]."  <code>".
                         $val['puck_rival'].':'.$val['puck_team'].
                         '</code></p>';

                }
            ?>
            <?php endforeach?>
            <?php endif;?>
            </div>    
        </div>
    </div>
    

    
    
    <p><?php /*printArray($result_query_team_1)*/?></p>
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
    
    
    
    

