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
    
    <h3><?= $id_team?></h3>
    
    <p><?= printArray($result_query)?></p>

