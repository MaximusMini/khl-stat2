<?php
// команды
$teams = <<<TEAMS
    <option selected disabled>Выбрать команду</option>
    <option value="1">       1-Авангард</option>
    <option value="2">   2-Автомобилист</option>
    <option value="3">        3-Адмирал</option>
    <option value="4">        4-Ак Барс</option>
    <option value="5">           5-Амур</option>
    <option value="6">          6-Барыс</option>
    <option value="7">         7-Витязь</option>
    <option value="8">       8-Динамо М</option>
    <option value="9">      9-Динамо Мн</option>
    <option value="10">       10-Динамо Р</option>
    <option value="11">        11-Йокерит</option>
    <option value="12">    12-Куньлунь РС</option>
    <option value="13">      13-Локомотив</option>
    <option value="14">   14-Металлург Мг</option>
    <option value="15">     15-Нефтехимик</option>
    <option value="16">  16-Салават Юлаев</option>
    <option value="17">     17-Северсталь</option>
    <option value="18">         18-Сибирь</option>
    <option value="19">            19-СКА</option>                                                
    <option value="20">         20-Слован</option>
    <option value="21">        21-Спартак</option>
    <option value="22">        22-Торпедо</option>
    <option value="23">        23-Трактор</option>
    <option value="24">        24-ХК Сочи</option>
    <option value="25">           25-ЦСКА</option>
TEAMS;
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
// ледовые арены
$ice_arena = <<<ICEARENA
    <option selected disabled>Выбрать арену</option>
    <option value="Арена 2000">     Арена 2000</option>
    <option value="Арена-Металлург">Арена-Металлург</option>
    <option value="Арена-Омск">     Арена-Омск</option>
    <option value="Арена-Рига">     Арена-Рига</option>
    <option value="Барыс-Арена">    Барыс-Арена</option>
    <option value="Большой">        Большой</option>
    <option value="Витязь">         Витязь</option>
    <option value="ВТБ">            ВТБ</option>
    <option value="Ледовый дворец"> Ледовый дворец - Санкт-Петербург</option>
    <option value="Ледовый дворец"> Ледовый дворец - Череповец</option>
    <option value="Мегаспорт">      Мегаспорт</option>
    <option value="Минск-Арена">    Минск-Арена</option>
    <option value="Нагорный">       Нагорный</option>
    <option value="Нефтехим-Арена"> Нефтехим-Арена</option>
    <option value="Платинум Арена"> Платинум Арена</option>
    <option value="Словнафт Арена"> Словнафт Арена</option>
    <option value="Сибирь">         Сибирь</option>
    <option value="Татнефть-Арена"> Татнефть-Арена</option>
    <option value="Трактор">        Трактор</option>
    <option value="Уралец">         Уралец</option>
    <option value="Уфа-Арена">      Уфа-Арена</option>
    <option value="Фетисов-Арена">  Фетисов-Арена</option>
    <option value="Хартвалл-Арена"> Хартвалл-Арена</option>
    <option value="Фэйян">          Фэйян</option>
ICEARENA;
// города
$city_match = <<<CITYMATCH
    <option selected disabled>Выбрать город</option>
    <option value="Омск">           Авангард-Омск</option>
    <option value="Екатеринбург">   Автомобилист-Екатеринбург</option>
    <option value="Владивосток">    Адмирал-Владивосток</option>
    <option value="Казань">         Ак Барс-Казань</option>
    <option value="Хабаровск">      Амур-Хабаровск</option>
    <option value="Астана">         Барыс-Астана</option>
    <option value="Владивосток">    Витязь-Чехов</option>
    <option value="Москва">         Динамо М-Москва</option>
    <option value="Минск">          Динамо Мн-Минск</option>
    <option value="Рига">           Динамо Р-Рига</option>
    <option value="Хельсинки">      Йокерит-Хельсинки</option>
    <option value="Пекин">          Куньлунь РС-Пекин</option>
    <option value="Ярославль">      Локомотив-Ярославль</option>
    <option value="Магнитогорск">   Металлург Мг-Магнитогорск</option>
    <option value="Хельсинки">      Нефтехимик-Нижнекамск</option>
    <option value="Уфа">            Салават Юлаев-Уфа</option>
    <option value="Череповец">      Северсталь-Череповец</option>
    <option value="Новосибирск">    Сибирь-Новосибирск</option>
CITYMATCH;

?>