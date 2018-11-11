    // функция рисования линии
    function drawLine(ctx, startX, startY, endX, endY){
        /*
        ctx: ссылка на контекст рисунка
        startX: координата X начальной точки линии
        startY: координата Y начальной точки линии
        endX: координата X конечной точки линии
        endY: координата Y конечной точки линии
        */
        ctx.beginPath();
        ctx.moveTo(startX,startY);
        ctx.lineTo(endX,endY);
        ctx.stroke();
    }

    // рисование дуги
    function drawArc(ctx, centerX, centerY, radius, startAngle, endAngle){
        /*
        centerX: координата X центра окружности
        centerY: координата Y центра окружности
        radius: координата X конечной точки линии
        startAngle: угол начала в радианах, где начинается часть круга
        endAngle: конечный угол в радианах, где заканчивается часть круга
        */
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, startAngle, endAngle);
        ctx.stroke();
    }

    // рисование куска пирога
    function drawPieSlice(ctx,centerX, centerY, radius, startAngle, endAngle, color ){
        // color: цвет, используемый для заполнения среза
        ctx.fillStyle = color;
        ctx.beginPath();
        ctx.moveTo(centerX,centerY);
        ctx.arc(centerX, centerY, radius, startAngle, endAngle);
        ctx.closePath();
        ctx.fill();
    }
    
    // пересчет градусов в радианы
    var rad = function(grad){
        return (grad*Math.PI)/180;
    }




