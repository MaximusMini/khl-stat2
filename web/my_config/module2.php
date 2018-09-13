<?php
// время начало игры
$time_match = <<<TIMEMATCH
    <option selected disabled>Выбрать время</option>
    <option value="10.00">10.00</option>
    <option value="10.30">10.30</option>
    <option value="11.00">11.00</option>
    <option value="11.30">11.30</option>
    <option value="12.00">12.00</option>
    <option value="12.30">12.30</option>
    <option value="13.00">13.00</option>
    <option value="13.30">13.30</option>
    <option value="14.00">14.00</option>
    <option value="14.30">14.30</option>
    <option value="15.00">15.00</option>
    <option value="15.30">15.30</option>
    <option value="16.00">16.00</option>
    <option value="16.30">16.30</option>
    <option value="17.00">17.00</option>
    <option value="17.30">17.30</option>
    <option value="18.00">18.00</option>
    <option value="18.30">18.30</option>
    <option value="19.00">19.00</option>
    <option value="19.30">19.30</option>
    <option value="20.00">20.00</option>
    <option value="20.30">20.30</option>
    <option value="21.00">21.00</option>
    <option value="21.30">21.30</option>
TIMEMATCH;
// команды
$teams = <<<TEAMS
    <option selected disabled>Выбрать команду</option>
    <option value="Авангард">       1-Авангард</option>
    <option value="Автомобилист">   2-Автомобилист</option>
    <option value="Адмирал">        3-Адмирал</option>
    <option value="Ак Барс">        4-Ак Барс</option>
    <option value="Амур">           5-Амур</option>
    <option value="Барыс">          6-Барыс</option>
    <option value="Витязь">         7-Витязь</option>
    <option value="Динамо М">       8-Динамо М</option>
    <option value="Динамо Мн">      9-Динамо Мн</option>
    <option value="Динамо Р">       10-Динамо Р</option>
    <option value="Йокерит">        11-Йокерит</option>
    <option value="Куньлунь РС">    12-Куньлунь РС</option>
    <option value="Локомотив">      13-Локомотив</option>
    <option value="Металлург Мг">   14-Металлург Мг</option>
    <option value="Нефтехимик">     15-Нефтехимик</option>
    <option value="Салават Юлаев">  16-Салават Юлаев</option>
    <option value="Северсталь">     17-Северсталь</option>
    <option value="Сибирь">         18-Сибирь</option>
    <option value="СКА">            19-СКА</option>                                                
    <option value="Слован">         20-Слован</option>
    <option value="Спартак">        21-Спартак</option>
    <option value="Торпедо">        22-Торпедо</option>
    <option value="Трактор">        23-Трактор</option>
    <option value="ХК Сочи">        24-ХК Сочи</option>
    <option value="ЦСКА">           25-ЦСКА</option>
TEAMS;
// день игр - число месяца
$day_game = <<<DAYGAME
    <option selected disabled>Чило месяца</option>
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
    <option value="6">6</option>
    <option value="7">7</option>
    <option value="8">8</option>
    <option value="9">9</option>
    <option value="10">10</option>
    <option value="11">11</option>
    <option value="12">12</option>
    <option value="13">13</option>
    <option value="14">14</option>
    <option value="15">15</option>
    <option value="16">16</option>
    <option value="17">17</option>
    <option value="18">18</option>
    <option value="19">19</option>
    <option value="20">20</option>
    <option value="21">21</option>
    <option value="22">22</option>
    <option value="23">23</option>
    <option value="24">24</option>
    <option value="25">25</option>
    <option value="26">26</option>
    <option value="27">27</option>
    <option value="28">28</option>
    <option value="29">29</option>
    <option value="30">30</option>
    <option value="31">31</option>
DAYGAME;
// месяц игры
$month_game = <<<MONTHGAME
    <option selected disabled>Месяц</option>
    <option value="января">января</option>
    <option value="февраля">февраля</option>
    <option value="марта">марта</option>
    <option value="апреля">апреля</option>
    <option value="мая">мая</option>
    <option value="июня">июня</option>
    <option value="июля">июля</option>
    <option value="августа">августа</option>
    <option value="сентября">сентября</option>
    <option value="октября">октября</option>
    <option value="ноября">ноября</option>
    <option value="декабря">декабря</option>
MONTHGAME;
// количество шайб
$count_puks = <<<COUNTPUKS
    <option selected disabled>Шайбы</option>
    <option value="0">0</option>
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
    <option value="6">6</option>
    <option value="7">7</option>
    <option value="8">8</option>
    <option value="9">9</option>
    <option value="10">10</option>
COUNTPUKS;
// время матча - количество периодов
$count_periods = <<<COUNTPERIODS
    <option selected disabled>Время матча</option>
    <option value="1">Основное время (3 п.)</option>
    <option value="2">Овертайм</option>
    <option value="3">Буллиты</option>
COUNTPERIODS;
// масссив сопоставления именик команды и файла логотипа
$logo = [
    'Авангард'=>        '1.png',
    'Автомобилист'=>    '2.png',
    'Адмирал'=>         '3.png',
    'Ак Барс'=>         '4.png',
    'Амур'=>            '5.png',
    'Барыс'=>           '6.png',
    'Витязь'=>          '7.png',
    'Динамо М'=>        '8.png',
    'Динамо Мн'=>       '9.png',
    'Динамо Р'=>        '10.png',
    'Йокерит'=>         '11.png',
    'Куньлунь РС'=>     '12.png',
    'Локомотив'=>       '13.png',
    'Металлург Мг'=>    '14.png',
    'Нефтехимик'=>      '15.png',
    'Салават Юлаев'=>   '16.png',
    'Северсталь'=>      '17.png',
    'Сибирь'=>          '18.png',
    'СКА'=>             '19.png',
    'Слован'=>          '20.png',
    'Спартак'=>         '21.png',
    'Торпедо'=>         '22.png',
    'Трактор'=>         '23.png',
    'ХК Сочи'=>         '24.png',
    'ЦСКА'=>            '25.png',
];





// функция определения начала надписи команд
function x_pos_teams($strlen_teams,$num_match){
    if($num_match == 1){
        // 102 - самая крайняя точка по х
        // 405 - вся длина, имеющаяся для надписи
        // 8,8 - пикселей примерно занимает один символ
        $x_pos_team = 102 + (405 - ceil($strlen_teams * 8.8))/2;
        return $x_pos_team;
    }
    if($num_match == 2){return $x_pos_team = 129 + (405 - ceil($strlen_teams * 8.8))/2;}
}



/*
                        <option value="1">1-Авангард</option>
                        <option value="2">2-Автомобилист</option>
                        <option value="3">3-Адмирал</option>
                        <option value="4">4-Ак Барс</option>
                        <option value="5">5-Амур</option>
                        <option value="6">6-Барыс</option>
                        <option value="7">7-Витязь</option>
                        <option value="8">8-Динамо М</option>
                        <option value="9">9-Динамо Мн</option>
                        <option value="10">10-Динамо Р</option>
                        <option value="11">11-Йокерит</option>
                        <option value="12">12-Куньлунь РС</option>
                        <option value="13">13-Локомотив</option>
                        <option value="14">14-Металлург Мг</option>
                        <option value="15">15-Нефтехимик</option>
                        <option value="16">16-Салават Юлаев</option>
                        <option value="17">17-Северсталь</option>
                        <option value="18">18-Сибирь</option>
                        <option value="19">19-СКА</option>                                                
                        <option value="20">20-Слован</option>
                        <option value="21">21-Спартак</option>
                        <option value="22">22-Торпедо</option>
                        <option value="23">23-Трактор</option>
                        <option value="24">24-ХК Сочи</option>
                        <option value="25">25-ЦСКА</option>


*/



?>




























