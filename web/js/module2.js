

$(document).ready(function(){
    //alert('module2');
});


    // обработка 
    $('select').change(function(e){
        // массив с данными о расположении на постере используемыми по умолчанию
        var arrDefaultPosition = [
            // матч 1
            {'x_pos_team1':103, 'y_pos_team1':220, 'x_pos_team2':300, 'y_pos_team2':220, 'x_pos_logo1':200, 'y_pos_logo1':135, 'x_pos_logo2':400, 'y_pos_logo2':150, 'font_size_teams': 19},
            // матч 2
            {'x_pos_team1':128, 'y_pos_team1':320, 'x_pos_team2':320, 'y_pos_team2':220, 'x_pos_logo1':200, 'y_pos_logo1':237, 'x_pos_logo2':400, 'y_pos_logo2':150, 'font_size_teams': 19 },
            // матч 3
            {'x_pos_team1':155, 'y_pos_team1':420, 'x_pos_team2':355, 'y_pos_team2':220, 'x_pos_logo1':200, 'y_pos_logo1':337, 'x_pos_logo2':400, 'y_pos_logo2':150, 'font_size_teams': 19 },
            // матч 4
            {'x_pos_team1':182, 'y_pos_team1':520, 'x_pos_team2':300, 'y_pos_team2':220, 'x_pos_logo1':200, 'y_pos_logo1':437, 'x_pos_logo2':400, 'y_pos_logo2':150, 'font_size_teams': 19 },
            // матч 5
            {'x_pos_team1':207, 'y_pos_team1':620, 'x_pos_team2':300, 'y_pos_team2':220, 'x_pos_logo1':200, 'y_pos_logo1':540, 'x_pos_logo2':400, 'y_pos_logo2':150, 'font_size_teams': 19 },
            // матч 6
            {'x_pos_team1':234, 'y_pos_team1':717, 'x_pos_team2':300, 'y_pos_team2':220, 'x_pos_logo1':200, 'y_pos_logo1':637, 'x_pos_logo2':400, 'y_pos_logo2':150, 'font_size_teams': 19 },
            // матч 7
            {'x_pos_team1':260, 'y_pos_team1':815, 'x_pos_team2':300, 'y_pos_team2':220, 'x_pos_logo1':200, 'y_pos_logo1':737, 'x_pos_logo2':400, 'y_pos_logo2':150, 'font_size_teams': 19 },
            // матч 8
            {'x_pos_team1':287, 'y_pos_team1':915, 'x_pos_team2':300, 'y_pos_team2':220, 'x_pos_logo1':200, 'y_pos_logo1':834, 'x_pos_logo2':400, 'y_pos_logo2':150, 'font_size_teams': 19 },
            // матч 9
            {'x_pos_team1':314, 'y_pos_team1':1015, 'x_pos_team2':300, 'y_pos_team2':220, 'x_pos_logo1':200, 'y_pos_logo1':933, 'x_pos_logo2':400, 'y_pos_logo2':150, 'font_size_teams': 19 }
            ];
        // массив с данными о длине имени команды
        var arrTeamLength = [
            {'Авангард':113, 'Автомобилист':193, 'Адмирал':112, 'Ак Барс':100, 'Амур':65, 'Барыс':75, 'Витязь':82, 'Динамо М':128,
            'Динамо Мн':142, 'Динамо Р':121, 'Йокерит':113, 'Куньлунь РС':149, 'Локомотив':146, 'Металлург Мг':195, 'Нефтехимик':166, 'Салават Юлаев':210, 'Северсталь':157, 'Сибирь':89, 'СКА':52, 'Слован':130, 'Спартак':115, 'Торпедо':92, 'Трактор':90, 'ХК Сочи':93, 'ЦСКА':69,}
        ];
        
        // определяем в каком по счету матче произошел выбор команды
        var numberMatch = ($(this).attr('name')).length; // определяем длину
        numberMatch = $(this).attr('name')[numberMatch-1];
        // формируем имена select для проверки
        var nameSelect1 = 'team1_'+ numberMatch; //alert(nameSelect1); alert($('select[name="'+nameSelect1+'"]').val());
        var nameSelect2 = 'team2_'+ numberMatch; //alert(nameSelect2); alert($('select[name="'+nameSelect2+'"]').val());
        // проверяем наличе выбора обеих команд
        if ( $('select[name="'+nameSelect1+'"]').val() != null && $('select[name="'+nameSelect2+'"]').val() != null)
            {
                // выбраны обе команды - alert('выбраны обе команды');
                // расчет позиций с учетом длины названия команды
                culcPosition(numberMatch, $('select[name="'+nameSelect1+'"]').val(), $('select[name="'+nameSelect2+'"]').val(), arrDefaultPosition, arrTeamLength);
            }else 
            {
                // обе команды не выбраны - alert('обе команды не выбраны');
                // установка значений по умолчанию из массива arrDefaultPosition
                setupSettingsDefault(numberMatch, arrDefaultPosition);
            }

    })


// функция установки значений по умолчанию
function setupSettingsDefault(numberMatch, arrDefaultPosition){
    $('input[name="xPosTeam1_'+numberMatch+'"]').val(arrDefaultPosition[numberMatch-1]['x_pos_team1']);
    $('input[name="yPosTeam1_'+numberMatch+'"]').val(arrDefaultPosition[numberMatch-1]['y_pos_team1']);
    $('input[name="xPosTeam2_'+numberMatch+'"]').val(arrDefaultPosition[numberMatch-1]['x_pos_team2']);
    $('input[name="yPosTeam2_'+numberMatch+'"]').val(arrDefaultPosition[numberMatch-1]['y_pos_team2']);
    $('input[name="xPosLogo1_'+numberMatch+'"]').val(arrDefaultPosition[numberMatch-1]['x_pos_logo1']);
    $('input[name="yPosLogo1_'+numberMatch+'"]').val(arrDefaultPosition[numberMatch-1]['y_pos_logo1']);
    $('input[name="xPosLogo2_'+numberMatch+'"]').val(arrDefaultPosition[numberMatch-1]['x_pos_logo2']);
    $('input[name="yPosLogo2_'+numberMatch+'"]').val(arrDefaultPosition[numberMatch-1]['y_pos_logo2']);
    $('input[name="fontSizeTeams_'+numberMatch+'"]').val('21');
}

// функция расчета позиций названия команд и логотипов
function culcPosition(numberMatch, nameTeam1, nameTeam2,arrDefaultPosition, arrTeamLength){
    /* алгоритм расчета
    нужно расчитать с какой позиции начинать надписи команд - по Х
     - крайняя левая точка по Х в каждом матче берется из массива
     - сначало высчитывается сколько занимает место надпись обеих команд - 18 примерно занимает одна буква
     - это значение отнимается от полной ширины места для надписи и делится на два
     - после деления, полученное значение прибавляется к крайней левой точке
    */
    var xNewPos1; // искомая величина команды 1
    var xNewPos2; // искомая величина команды 2
    var logoT1
    // расчет xNewPos1
    var startPosX = arrDefaultPosition[numberMatch-1]['x_pos_team1']; // крайняя левая точка по Х
    var titleCountTeams = Math.ceil((nameTeam1.length + nameTeam2.length )*18);
    xNewPos1 = startPosX + Math.ceil((405 - titleCountTeams)/2); // новая позиция по Х
    if(xNewPos1 < startPosX){xNewPos1 = startPosX};// если название будет выходить за крайнюю левую допустимую границу
    // расчет xNewPos2
    xNewPos2 = xNewPos1 + 29;
    //alert ('xNewPos2 '+xNewPos2);
    // расчет положения логотипа команды 1 - ширина логотипа 57рх
        // нужно знать начало имени команды - xNewPos1
        // длину имени в рх
//        alert ('имя '+ nameTeam1); 
//        alert ('длина имени в рх '+ arrTeamLength[0][nameTeam1]);
        var lengthTeam1 = arrTeamLength[0][nameTeam1];
//        alert ('lengthTeam1 '+ arrTeamLength[0][nameTeam1]);
        // от имени отнять ширину логотипа и разделить это на 2
        var lengthWithLogo1 =  (lengthTeam1 - 57)/2; 
        // полученное значение прибавить к началу имени
        var xPosLogo1 = Math.ceil(xNewPos1 + lengthWithLogo1);
    // расчет положения логотипа команды 2
       
        xNewPos2 = lengthTeam1 + xNewPos1 + 30;
//        alert ('xNewPos2 '+ xNewPos2); 
        var lengthTeam2 = arrTeamLength[0][nameTeam2];
//        alert ('lengthTeam2 '+ lengthTeam2);
        var lengthWithLogo2 =  (lengthTeam2 - 57)/2;
//        alert ('lengthWithLogo2 '+ lengthWithLogo2);
        var xPosLogo2 = Math.ceil(xNewPos2 + lengthWithLogo2);
//        alert ('xPosLogo2 '+ xPosLogo2);
    // установка новых расчитанных значений
    $('input[name="xPosTeam1_'+numberMatch+'"]').val(xNewPos1);
    $('input[name="xPosTeam2_'+numberMatch+'"]').val(xNewPos2);
    $('input[name="xPosLogo1_'+numberMatch+'"]').val(xPosLogo1);
    $('input[name="xPosLogo2_'+numberMatch+'"]').val(xPosLogo2);
    
    // остальные значения остаются по умолчанию
    $('input[name="yPosTeam1_'+numberMatch+'"]').val(arrDefaultPosition[numberMatch-1]['y_pos_team1']);
    $('input[name="yPosTeam2_'+numberMatch+'"]').val(arrDefaultPosition[numberMatch-1]['y_pos_team2']);
    $('input[name="yPosLogo1_'+numberMatch+'"]').val(arrDefaultPosition[numberMatch-1]['y_pos_logo1']);
    $('input[name="yPosLogo2_'+numberMatch+'"]').val(arrDefaultPosition[numberMatch-1]['y_pos_logo2']); 
    
}



// обработка выбора количества матчей
function selectMatches(element){
    alert(element.val());
    var countElement = element.val();
    // добавялем необходимое количество элементов
    for(var i=1; i<=countElement; i++){
       $('#add-bloks').appendTo('\
        <div class="row">\
            <div class="col-lg-2">\
                <p>Время матча</p>\
        </div>\
            <div class="col-lg-2">\
                <p>Команда 1</p>\
        </div>\
            <div class="col-lg-2">\
                <p>Команда 2</p>\
            </div>\
        </div>'
       );
    } 
}