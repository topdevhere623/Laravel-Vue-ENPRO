// расчет углов и позиции

// ------------------------------------------------------------------
// расчет положения в зависимости от переключателя
export const serviceCalcPosition = (getMapObject) => {

    let getPosition = getMapObject.position;
    let myWidth = getMapObject.width;
    let myHeight = getMapObject.height;

    let myPosX, myPosY, myAlign;

    switch (getMapObject.positionText) {
        case 7:
            myPosX = -5;
            myPosY = -5;
            myAlign = 'end';
            break;
        case 8:
            myPosX = 0 + myWidth / 2;
            myPosY = -5;
            myAlign = 'middle';
            break;
        case 9:
            myPosX = 5 + myWidth;
            myPosY = -5;
            myAlign = 'begin';
            break;

        case 4:
            myPosX = -5;
            myPosY = 0 + myHeight;
            myAlign = 'end';
            break;
        case 6:
            myPosX = 5 + myWidth;
            myPosY = 0 + myHeight;
            myAlign = 'begin';
            break;

        case 1:
            myPosX = -5;
            myPosY = 7 + myHeight;
            myAlign = 'end';
            break;
        case 2:
            myPosX = 0 + myWidth / 2;
            myPosY = 7 + myHeight;
            myAlign = 'middle';
            break;
        case 3:
            myPosX = 5 + myWidth;
            myPosY = 7 + myHeight;
            myAlign = 'begin';
            break;
    }

    myPosX = myPosX - myWidth / 2;
    myPosY = myPosY - myHeight / 2;

    // возвращаемый параметр
    let myReturn = new Map([
        ['position', getPosition],
        ['posX', myPosX],
        ['posY', myPosY],
        ['align', myAlign],
    ]);
    return myReturn;
}
// ------------------------------------------------------------------
// расчет угла пролета / участка
// !!! не забывать, что ось y вверх ногами
export const serviceCalcAnglePolyline = (getMapObject) => {

    let dX = Math.abs(getMapObject.x1 - getMapObject.x2);
    let dY = Math.abs(getMapObject.y1 - getMapObject.y2);

    let rad = Math.atan(dY / dX);
    let myDeg = rad * (180 / Math.PI);

    //console.clear();
    //console.log("угол линии до = " + myDeg);

    if (getMapObject.x2 > getMapObject.x1 && getMapObject.y2 > getMapObject.y1) {
        myDeg += 90;
        //console.log("четверть 1");
    }

    if (getMapObject.x2 > getMapObject.x1 && getMapObject.y2 < getMapObject.y1) {
        myDeg = 90 - myDeg;
        //console.log("четверть 2");
    }

    if (getMapObject.x2 < getMapObject.x1 && getMapObject.y2 < getMapObject.y1) {
        myDeg += 270;
        //console.log("четверть 3");
    }

    if (getMapObject.x2 < getMapObject.x1 && getMapObject.y2 > getMapObject.y1) {
        myDeg = 270 - myDeg;
        //console.log("четверть 4");
    }

    // частные случаи
    if (getMapObject.x2 === getMapObject.x1 && getMapObject.y2 > getMapObject.y1) {
        myDeg = 180;
        //console.log(" --- 180");
    }
    if (getMapObject.x2 === getMapObject.x1 && getMapObject.y2 < getMapObject.y1) {
        myDeg = 0;
        //console.log(" --- 0");
    }
    if (getMapObject.y2 === getMapObject.y1 && getMapObject.x2 > getMapObject.x1) {
        myDeg = 90;
        //console.log(" --- 90");
    }
    if (getMapObject.y2 === getMapObject.y1 && getMapObject.x2 < getMapObject.x1) {
        myDeg = 270;
        //console.log(" --- 270");
    }

    // избежание ошибки
    if (typeof (myDeg) === 'undefined' || myDeg === null || isNaN(myDeg)) myDeg = 0;
    //console.log("угол линии после = " + myDeg);

    // возвращаемый параметр
    return myDeg;
}

// ------------------------------------------------------------------
// расчет угла обьекта от соединяющих пролетов
export const serviceCalcAnglePlacemark = (getMapObject, getPolylines) => {

    // найти все пролеты, где участвует обьект (и учесть с каокйц стороны отрезка он стоит, в начале или конце - угол разный!)
    let myPolylineAngles = [];
    if (getPolylines.length > 0) {
        getPolylines.map(function (item) {
            if (item.mapType === 'polyline') {
                if (item.startMapID === getMapObject.mapID) {
                    let myAngle = serviceCalcAnglePolyline(
                        {
                            x1: item.x1,
                            x2: item.x2,
                            y1: item.y1,
                            y2: item.y2,
                        });
                    myPolylineAngles.push(myAngle);
                }
                if (item.endMapID === getMapObject.mapID) {
                    let myAngle = serviceCalcAnglePolyline(
                        {
                            x1: item.x2,
                            x2: item.x1,
                            y1: item.y2,
                            y2: item.y1,
                        });
                    myPolylineAngles.push(myAngle);
                }
            }
        });
    }
    // максимумальный и минимальный угол
    let myAngleMax = Math.max.apply(null, myPolylineAngles);
    let myAngleMin = Math.min.apply(null, myPolylineAngles);

    // средний угол,но с учетом, что он может откладываться в разные стороны
    let myDeg = 0;
    if ((myAngleMax - myAngleMin) > 180) {
        let myAngleMax360 = 360 - myAngleMax;
        let myAngleAverage = (myAngleMax360 + myAngleMin) / 2;
        myDeg = myAngleMax + myAngleAverage;
        if (myDeg > 360) myDeg = myDeg - 360;
    } else {
        myDeg = (myAngleMax + myAngleMin) / 2;
    }

    // избежание ошибки
    if (typeof (myDeg) === 'undefined' || myDeg === null || isNaN(myDeg)) myDeg = 0;

    if (1 === 3 && getMapObject.current) {
        console.clear();
        console.log("максимальынй угол = " + myAngleMax);
        console.log("минимальный угол = " + myAngleMin);
        console.log("рассчетный угол обьекта = " + myDeg);
    }

    // возвращаемый параметр
    return myDeg;
}

// ------------------------------------------------------------------
// положение и соответствующий угол угол
export const serviceCalcAngleByPosition = (getPosition) => {
    switch (getPosition) {

        case 7:
            return 135;
            break;
        case 8:
            return 180;
            break;
        case 9:
            return 225;
            break;
        case 6:
            return 270;
            break;
        case 3:
            return 315;
            break;
        case 2:
            return 0;
            break;
        case 1:
            return 45;
            break;
        case 4:
            return 90;
            break;
        default:
            return 0;
    }
}
