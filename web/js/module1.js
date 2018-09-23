// проверочная функция
function goGo(){
    //alert('GO');
    $('#qqq').append('<p>Alert</p>');
    
}


// ajax запрос на парсинг результатов команд
/*
    пошагово передается параметр команды
    после парсинга и записи результатов команды выводится запись
*/
function parsResults(id_team){
    // устанавливаем значение id_team по умолчания на случай если параметр не передается
    if(id_team === undefined){id_team = 1;}
    // очистка блока для вывода текста
    if(id_team === 1){$('#qqq').empty();}
    $.ajax({
            url:'../module1/parser-results_2018-model',
            //async:false,
            data:'id_team='+id_team,
            //dataType:'json',
            type:'POST',
            success:function(data, textStatus, jqXHR){   
                    //controlAjaxRequest(data);// функция управления ajax-запросом
                    $('#qqq').append(data+'<br>');
            },
            error:function(jqXHR, srtErr, errorThrown){
                    // srtErr-строка описывающая тип произошедшей ошибки
                    alert('error: '+srtErr+' errorThrown: '+errorThrown);
            }
    });
}

// функция управления ajax-запросом
function controlAjaxRequest(data){
    // вывод сообщения Результаты команды .... собраны
    $('#qqq').append(data.code+'<br>');
    // следующая команда
    var data2 = (Number(data.id_team) + 1);
    if(data2 < 26){
        parsResults(data2);
    }
}