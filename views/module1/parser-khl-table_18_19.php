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
        <button class='btn btn-success' onclick='parsResults(1)'>Парсинг данных</button>
        <?=Html::a("Отобразить таблицы", ["module1/view-khl-table_18_19"],['class'=>'btn btn-primary'])?>
    </div>
    
   
  
<?/*отображение результатов парсинга - таблицы из БД*/?>
    <?php if($table != NULL):?>
        <div class="row">
            <h3>Запад</h3>
            <table class="table table-striped table-condense">
                <thead>
                    <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($table['table_west'] as $val):?>
                        <tr>
                        <td><?=$val['place']?></td>
                        <td><?=$val['name']?></td>
                        <td><?=$val['games']?></td>
                        <td><?=$val['scores']?></td>
                        
                    <?php endforeach;?>
                </tbody>
            </table>    
        </div>
        <div class="row">
            <h3>Восток</h3>    
        </div>
    
    <?php endif;?>





<p><?php if($table != NULL){printArray($table);}/**/?></p>