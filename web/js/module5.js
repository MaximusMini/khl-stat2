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
    function drawArc(ctx, centerX, centerY, radius, startAngle, endAngle, lineWidth, fillStyle, strokeStyle){
        /*
        centerX: координата X центра окружности
        centerY: координата Y центра окружности
        radius: координата X конечной точки линии
        startAngle: угол начала в радианах, где начинается часть круга
        endAngle: конечный угол в радианах, где заканчивается часть круга
        */
        // параметры линии
        ctx.lineWidth = lineWidth;
        ctx.fillStyle = fillStyle;
        ctx.strokeStyle = strokeStyle; // цвет линии
        // рисование
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

    // рисование индикатора заброшенных шайб команды 1
    function throwPuck_1(ctx,throwPuck,xStart,yStart,lineWidth,fillStyle,strokeStyle){
        /*
        throwPuck - количество заброшенных шайб
        xPos - начальная позиция по X
        yPos - начальная позиция по Y
        */
        ctx.beginPath();
        ctx.moveTo(xStart,yStart);// 400,400
        ctx.lineTo(xStart,yStart+10);// 400,400
        ctx.lineTo(xStart-throwPuck,yStart+10);// 400,400
        ctx.lineTo(xStart-throwPuck,yStart);// 400,400
        ctx.lineTo(xStart,yStart);// 400,400
        ctx.closePath();
        ctx.fill();
        
    }

    // рисование индикатора заброшенных шайб команды 1
    
    // пересчет градусов в радианы
    var rad = function(grad){
        return (grad*Math.PI)/180;
    }




