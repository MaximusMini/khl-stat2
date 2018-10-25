<?php

use yii\helpers\HTML;

$this->title = ('Парсер таблицы КХЛ сезон 2018/19');



// подключение файла
include(Yii::getAlias('@app/web/my_config/module1.php'));
?>


    <div class="row alert alert-info lead">
        Парсер таблицы КХЛ сезон 2018/19
        <?=Html::a("Парсеры", ["module1/main", "id_team"=>"1"],['class'=>'btn btn-warning pull-right'])?>
    </div>


    <div class="row">
        <?=Html::a("Парсинг данных", ["module1/parsl-table_18_19"],['class'=>'btn btn-success'])?>
        <?=Html::a("Отобразить таблицы", ["module1/view-khl-table_18_19"],['class'=>'btn btn-primary'])?>
        <?=Html::a("Формировать постер", ["module1/poster-table_18_19"],['class'=>'btn btn-danger'])?>
    </div>
    
   
  
<?/*отображение таблицы из БД*/?>
    <?php if($table != NULL):?>
        <div class="row">
            <h3>Запад</h3>
            <table class="table table-striped table-condense">
                <thead>
                    <tr class="bg-info">
                    <td></td>
                    <td class="text-center" style='width:238px'><strong>Команда</strong></td>
                    <td class="text-center"><strong>Игры</strong></td>
                    <td class="bg-success text-center"><strong>В</strong></td>
                    <td class="bg-success text-center"><strong>В(ОТ)</strong></td>
                    <td class="bg-success text-center"><strong>В(Б)</strong></td>
                    <td class="bg-danger text-center"><strong>П</strong></td>
                    <td class="bg-danger text-center"><strong>П(ОТ)</strong></td>
                    <td class="bg-danger text-center"><strong>П(Б)</strong></td>
                    <td class="bg-primary text-center"><strong>Шайбы</strong></td>
                    <td class="bg-warning text-center"><strong>Очки</strong></td>
                    <td class="bg-info text-center"><strong>%</strong></td>
                    <td class="bg-success text-center"><strong>Форма</strong></td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($table['table_west'] as $val):?>
                        <tr>
                        <td><?=$val['place']?></td>
                        <td><?=$val['name']?></td>
                        <td class="bg-info text-center"><?=$val['games']?></td>
                        <td class="bg-success text-center"><?=$val['clear_wins']?></td>
                        <td class="bg-success text-center"><?=$val['ot_wins']?></td>
                        <td class="bg-success text-center"><?=$val['b_wins']?></td>
                        <td class="bg-danger text-center"><?=$val['clear_defeat']?></td>
                        <td class="bg-danger text-center"><?=$val['ot_defeat']?></td>
                        <td class="bg-danger text-center"><?=$val['b_defeat']?></td>
                        <td class="bg-primary text-center"><?=$val['throw_puck']?> - <?=$val['miss_puck']?></td>
                        <td class="bg-warning text-center"><?=$val['scores']?></td>
                        <td class="bg-info text-center"><?=$val['percent_scr']?></td>
                        <td class="bg-success text-center">
                            <?php for($i=1; $i<=6; $i++):?>
                                <?php
                                    if($val['old_match_'.$i] == '_win'){
                                        echo "<span style='display:inline-block; height:10px; width:10px; background-color:#006600; border-radius:50%;' class='span-tooltip'></span>";    
                                    }else{
                                        echo "<span style='display:inline-block; height:10px; width:10px; background-color:#CC0000; border-radius:50%;'></span>";    
                                    }
                                ?>
                            <?php endfor;?>
                        </td>
                    <?php endforeach;?>
                </tbody>
            </table>    
        </div>
        <div class="row">
            <h3>Восток</h3>
            <table class="table table-striped table-condense">
                <thead>
                    <tr class="bg-info">
                    <td></td>
                    <td class="text-center"><strong>Команда</strong></td>
                    <td class="text-center"><strong>Игры</strong></td>
                    <td class="bg-success text-center"><strong>В</strong></td>
                    <td class="bg-success text-center"><strong>В(ОТ)</strong></td>
                    <td class="bg-success text-center"><strong>В(Б)</strong></td>
                    <td class="bg-danger text-center"><strong>П</strong></td>
                    <td class="bg-danger text-center"><strong>П(ОТ)</strong></td>
                    <td class="bg-danger text-center"><strong>П(Б)</strong></td>
                    <td class="bg-primary text-center"><strong>Шайбы</strong></td>
                    <td class="bg-warning text-center"><strong>Очки</strong></td>
                    <td class="bg-info text-center"><strong>%</strong></td>
                    <td class="bg-success text-center"><strong>Форма</strong></td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($table['table_east'] as $val):?>
                        <tr>
                        <td><?=$val['place']?></td>
                        <td><?=$val['name']?></td>
                        <td class="bg-info text-center"><?=$val['games']?></td>
                        <td class="bg-success text-center"><?=$val['clear_wins']?></td>
                        <td class="bg-success text-center"><?=$val['ot_wins']?></td>
                        <td class="bg-success text-center"><?=$val['b_wins']?></td>
                        <td class="bg-danger text-center"><?=$val['clear_defeat']?></td>
                        <td class="bg-danger text-center"><?=$val['ot_defeat']?></td>
                        <td class="bg-danger text-center"><?=$val['b_defeat']?></td>
                        <td class="bg-primary text-center"><?=$val['throw_puck']?> - <?=$val['miss_puck']?></td>
                        <td class="bg-warning text-center"><?=$val['scores']?></td>
                        <td class="bg-info text-center"><?=$val['percent_scr']?></td>
                        <td class="bg-success text-center">
                            <?php for($i=1; $i<=6; $i++):?>
                                <?php
                                    if($val['old_match_'.$i] == '_win'){
                                        echo "<span style='display:inline-block; height:10px; width:10px; background-color:#006600; border-radius:50%;' class='span-tooltip'></span>";    
                                    }else{
                                        echo "<span style='display:inline-block; height:10px; width:10px; background-color:#CC0000; border-radius:50%;'></span>";    
                                    }
                                ?>
                            <?php endfor;?>
                        </td>
                    <?php endforeach;?>
                </tbody>
            </table>    
        </div>
    
    <?php endif;?>
    

<?/*отображение парсинга*/?>
    <?php if($result_pars != NULL):?>
        <h4><code>Парсинг завершен</code></h4>
    <?php endif;?>
    





<p><?/*php if($table != NULL){printArray($table);}*/?></p>