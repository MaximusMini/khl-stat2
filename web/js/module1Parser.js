function doItMaxCount(xh, callback, maxcount) { 
    var count = 0; 
    xh.onreadystatechange = function(){ 
        if (xmlhttp.readyState == 4) { 
            callback(count); 
            count++; 
        } 
        if (count == maxcount) 
            return; 
        xh.send(); 
    } 
    xh.send(); 
}

var xmlhttp = new XMLHttpRequest; 
xmlhttp.open("GET", "./module1/parser-results_2018-model", true); 
doItMaxCount(xmlhttp, function(i) {alert(i)}, 5);



/*

свойство onreadystatechange объекта XMLHttpRequest - обрабатывает ответ, полученный от сервера



*/