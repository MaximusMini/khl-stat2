<?php
// команды
$id_teams = <<<IDTEAMS
    <option selected disabled>Выбрать команду</option>
    <option value="1">  1-Авангард</option>
    <option value="2">  2-Автомобилист</option>
    <option value="3">  3-Адмирал</option>
    <option value="4">  4-Ак Барс</option>
    <option value="5">  5-Амур</option>
    <option value="6">  6-Барыс</option>
    <option value="7">  7-Витязь</option>
    <option value="8">  8-Динамо М</option>
    <option value="9">  9-Динамо Мн</option>
    <option value="10"> 10-Динамо Р</option>
    <option value="11"> 11-Йокерит</option>
    <option value="12"> 12-Куньлунь РС</option>
    <option value="13"> 13-Локомотив</option>
    <option value="14"> 14-Металлург Мг</option>
    <option value="15"> 15-Нефтехимик</option>
    <option value="16"> 16-Салават Юлаев</option>
    <option value="17"> 17-Северсталь</option>
    <option value="18"> 18-Сибирь</option>
    <option value="19"> 19-СКА</option>                                                
    <option value="20"> 20-Слован</option>
    <option value="21"> 21-Спартак</option>
    <option value="22"> 22-Торпедо</option>
    <option value="23"> 23-Трактор</option>
    <option value="24"> 24-ХК Сочи</option>
    <option value="25"> 25-ЦСКА</option>
IDTEAMS;
// названия команд с id
$arr_team = [
    "1"=>"Авангард",
    "2"=>"Автомобилист",
    "3"=>"Адмирал",
    "4"=>"Ак Барс",
    "5"=>"Амур",
    "6"=>"Барыс",
    "7"=>"Витязь",
    "8"=>"Динамо М",
    "9"=>"Динамо Мн",
    "10"=>"Динамо Р","11"=>"Йокерит","12"=>"Куньлунь Ред Стар","13"=>"Локомотив","14"=>"Металлург Мг","15"=>"Нефтехимик","16"=>"Салават Юлаев", "17"=>"Северсталь", "18"=>"Сибирь", "19"=>"СКА","20"=>"Слован", "21"=>"Спартак","22"=>"Торпедо", "23"=>"Трактор", "24"=>"ХК Сочи", "25"=>"ЦСКА"];

function printArray($arr){
    echo '<pre>'.print_r($arr,true).'</pre>';
}

?>