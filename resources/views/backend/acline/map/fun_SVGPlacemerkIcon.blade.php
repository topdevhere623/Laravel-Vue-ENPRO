<script type="text/javascript">

    // SVG-иконки обьектов карты

    // ---------------------------------------------------------------
    // создать иконку svg
    function funSVGmake(getMapID) {

        //console.log("Создание иконки для mapID = " + getMapID);

        // проверка переданного параметра
        if (typeof (getMapID) === 'undefined' || isNaN(getMapID) || getMapID == null) return; // досрочный выход

        // текущий обьект
        let myObjectCurrent = mmObjects[getMapID];

        // параметры, упоминающиеся здесь в коде больше одного раза
        let myObjectType = myObjectCurrent.type;
        let myObjectStrut = myObjectCurrent.strut;
        let myObjectStrutN = myObjectCurrent.strutN;

        // проверка, активная эта точка в данный момент или нет
        let myColorActive = myObjectCurrent.isActive ? mmObjectsProperties[myObjectType]['colorActive'] : mmObjectsProperties[myObjectType]['color'];
        // линии разных классов напряжения
        let myColorFill = (myObjectCurrent.eqOtherLine === 1) ? '#202346' : '#fff';
        let myColorFill2 = '#202346';

        let myFigure = '';
        let mySmezhenX = 50; // смещение от начала координат, чтоб запас был
        let mySmezhenY = 50;
        let myWidthMin = mySmezhenX; // минимальная ширина основной иконки + обводка
        let myWidthMax = mySmezhenX + 18 + 1; // максимальная ширина
        let myHeightMax = mySmezhenY + 18 + 1; // максимальная высота
        let myScaleMap = myMap.getZoom(); // текущий масштаб карты
        let myScaleToPoint = 15; // масштаб, когда просто отображать точкой и без надписи
        let myScaleSVG = 0.7; // приемлимый масштаб иконок (от 18*18)
        let myCorrection = 0; // финальная коррекция
        let myStrokaCoordinat = '';
        let i = 0;
        let myNeedViewName = true;

        if (myScaleMap > myScaleToPoint) {
            switch (myObjectType) {
                case 'tower':

                    // оттяжка
                    switch (myObjectCurrent.guy) {
                        case 'left':
                            myFigure +=
                                '<polyline points="' +
                                (mySmezhenX + 9) + ',' + (mySmezhenY + 9) + ' ' +
                                (mySmezhenX - 10) + ',' + (mySmezhenY + 9) +
                                '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' +
                                '<polyline points="' +
                                (mySmezhenX - 10) + ',' + (mySmezhenY + 3) + ' ' +
                                (mySmezhenX - 10) + ',' + (mySmezhenY + 15) +
                                '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>';
                            break;
                        case 'right':
                            myFigure +=
                                '<polyline points="' +
                                (mySmezhenX + 9) + ',' + (mySmezhenY + 9) + ' ' +
                                (mySmezhenX + 28) + ',' + (mySmezhenY + 9) +
                                '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' +
                                '<polyline points="' +
                                (mySmezhenX + 28) + ',' + (mySmezhenY + 3) + ' ' +
                                (mySmezhenX + 28) + ',' + (mySmezhenY + 15) +
                                '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>';

                            if ((mySmezhenX + 28) > myWidthMax) myWidthMax = (mySmezhenX + 28);
                            break;
                    }

                    // подкос - треугольники или ромбики вверх
                    if (myObjectStrut === 'concrete' || myObjectStrut === 'wood' || myObjectStrut === 'metal') {
                        if (myObjectStrutN > 0)
                            myFigure +=
                                '<polyline points="' +
                                (mySmezhenX + 9) + ',' + (mySmezhenY + 0) + ' ' +
                                (mySmezhenX + 9) + ',' + (mySmezhenY - 10) + '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>';
                        for (i = 1; i <= myObjectStrutN; i++) {

                            // подкос железобетон - труегольник
                            if (myObjectStrut === 'concrete') {
                                myFigure +=
                                    '<polyline points="' +
                                    (mySmezhenX + 9) + ',' + ((mySmezhenY - 10) - (i - 1) * 8) + ' ' +
                                    (mySmezhenX + 17) + ',' + ((mySmezhenY - 10) - (i - 1) * 8) + ' ' +
                                    (mySmezhenX + 9) + ',' + ((mySmezhenY - 20) - (i - 1) * 8) + ' ' +
                                    (mySmezhenX + 1) + ',' + ((mySmezhenY - 10) - (i - 1) * 8) + ' ' +
                                    (mySmezhenX + 9) + ',' + ((mySmezhenY - 10) - (i - 1) * 8) +
                                    '" stroke="' + myColorActive + '" stroke-width="2" fill="' + myColorFill2 + '"/>';
                            }

                            // подкос металл - ромбики
                            if (myObjectStrut === 'metal') {
                                myFigure +=
                                    '<polyline points="' +
                                    (mySmezhenX + 9) + ',' + ((mySmezhenY - 10) - (i - 1) * 8) + ' ' +
                                    (mySmezhenX + 14) + ',' + ((mySmezhenY - 15) - (i - 1) * 8) + ' ' +
                                    (mySmezhenX + 9) + ',' + ((mySmezhenY - 20) - (i - 1) * 8) + ' ' +
                                    (mySmezhenX + 4) + ',' + ((mySmezhenY - 15) - (i - 1) * 8) + ' ' +
                                    (mySmezhenX + 9) + ',' + ((mySmezhenY - 10) - (i - 1) * 8) +
                                    '" stroke="' + myColorActive + '" stroke-width="2" fill="' + myColorFill2 + '"/>';
                            }

                            // подкос дерево - труегольник перевернутый
                            if (myObjectStrut === 'wood') {
                                myFigure +=
                                    '<polyline points="' +
                                    (mySmezhenX + 9) + ',' + (mySmezhenY - (10 + (i - 1) * 8)) + ' ' +
                                    (mySmezhenX + 17) + ',' + (mySmezhenY - (20 + (i - 1) * 8)) + ' ' +
                                    (mySmezhenX + 1) + ',' + (mySmezhenY - (20 + (i - 1) * 8)) + ' ' +
                                    (mySmezhenX + 9) + ',' + (mySmezhenY - (10 + (i - 1) * 8)) +
                                    '" stroke="' + myColorActive + '" stroke-width="2" fill="' + myColorFill2 + '"/>';
                            }
                        }
                    }

                    // основная иконка (чтобы была слоем выше)
                    switch (Number(myObjectCurrent.towerMaterial)) {

                        // дерево - круг
                        case 1:
                            myFigure +=
                                '<g>' +
                                '<circle r="10" cx="' +
                                (mySmezhenX + 9) + '" cy="' + (mySmezhenY + 9) +
                                '" fill="' + myColorActive + '"/>' +
                                '<circle r="8" cx="' +
                                (mySmezhenX + 9) + '" cy="' + (mySmezhenY + 9) +
                                '" fill="' + myColorFill + '"/>' +
                                '</g>';

                            // приставка - квадрат или треугольник внутри круга, если опора деревянная
                            switch (myObjectCurrent.annex) {
                                case 'concrete':
                                    // железобетон - квадрат
                                    myFigure +=
                                        '<polyline points="' +
                                        (mySmezhenX + 4) + ',' + (mySmezhenY + 4) + ' ' +
                                        (mySmezhenX + 14) + ',' + (mySmezhenY + 4) + ' ' +
                                        (mySmezhenX + 14) + ',' + (mySmezhenY + 14) + ' ' +
                                        (mySmezhenX + 4) + ',' + (mySmezhenY + 14) + ' ' +
                                        (mySmezhenX + 4) + ',' + (mySmezhenY + 4) +
                                        '" stroke="#999" stroke-width="2" fill="' + myColorFill + '"/>'; // stroke="' + myColorActive + '"
                                    break;
                                case 'metal':
                                    // металл - треуголник
                                    myFigure +=
                                        '<polyline points="' +
                                        (mySmezhenX + 9) + ',' + (mySmezhenY + 1) + ' ' +
                                        (mySmezhenX + 14) + ',' + (mySmezhenY + 13) + ' ' +
                                        (mySmezhenX + 4) + ',' + (mySmezhenY + 13) + ' ' +
                                        (mySmezhenX + 9) + ',' + (mySmezhenY + 1) +
                                        '" stroke="#999" stroke-width="2" fill="' + myColorFill + '"/>'; // stroke="' + myColorActive + '"
                                    break;
                            }
                            break;

                        // металл - треугольник (!было 4)
                        case 2:
                            myFigure +=
                                '<polyline points="' +
                                (mySmezhenX + 9) + ',' + (mySmezhenY + 0) + ' ' +
                                (mySmezhenX + 18) + ',' + (mySmezhenY + 18) + ' ' +
                                (mySmezhenX + 0) + ',' + (mySmezhenY + 18) + ' ' +
                                (mySmezhenX + 9) + ',' + (mySmezhenY + 0) +
                                '" stroke="' + myColorActive + '" stroke-width="2" fill="' + myColorFill + '"/>';
                            break;

                        // ж/б - квадрат
                        case 3:

                            myFigure +=
                                '<polyline points="' +
                                (mySmezhenX + 0) + ',' + (mySmezhenY + 0) + ' ' +
                                (mySmezhenX + 0) + ',' + (mySmezhenY + 18) + ' ' +
                                (mySmezhenX + 18) + ',' + (mySmezhenY + 18) + ' ' +
                                (mySmezhenX + 18) + ',' + (mySmezhenY + 0) + ' ' +
                                (mySmezhenX + 0) + ',' + (mySmezhenY + 0) +
                                '" stroke="' + myColorActive + '" stroke-width="2" fill="' + myColorFill + '"/>';
                            break;

                        // композитная - ромбик
                        case 5:
                            myFigure +=
                                '<polyline points="' +
                                (mySmezhenX + 9) + ',' + (mySmezhenY + 0) + ' ' +
                                (mySmezhenX + 17) + ',' + (mySmezhenY + 9) + ' ' +
                                (mySmezhenX + 9) + ',' + (mySmezhenY + 18) + ' ' +
                                (mySmezhenX + 1) + ',' + (mySmezhenY + 9) + ' ' +
                                (mySmezhenX + 9) + ',' + (mySmezhenY + 0) +
                                '" stroke="' + myColorActive + '" stroke-width="2" fill="' + myColorFill + '"/>';
                            break;
                    }

                    // линия связи
                    if (myObjectCurrent.eqCommLine === 1) {

                        // проверка, куда рисовать линию связи
                        if (myObjectCurrent.eqOPN === 1 || myObjectCurrent.eqDischarger === 1 || myObjectCurrent.eqGrounding === 1 || myObjectCurrent.eqAdapter === 1) {
                            // ниже еще есть элементы - рисовать вбок
                            myFigure +=
                                '<text x="' + (mySmezhenX - 16) + '" y="' + (mySmezhenY + 35) + '" font-family="Monospace" font-size="16" fill="' + myColorActive + '">C</text>';
                        } else {
                            // ниже ничего нет - рисовать вниз
                            myFigure +=
                                '<polyline points="' +
                                (mySmezhenX + 9) + ',' + myHeightMax + ' ' +
                                (mySmezhenX + 9) + ',' + (myHeightMax + 14) +
                                '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' +
                                '<text x="54" y="' + (myHeightMax + 24) + '" font-family="Monospace" font-size="16" fill="' + myColorActive + '">C</text>';
                            myHeightMax += 24;
                        }
                    }

                    // фонарь
                    if (myObjectCurrent.eqLamp === 1) {
                        myFigure +=
                            '<polyline points="' +
                            (mySmezhenX + 9) + ',' + (mySmezhenY + 18) + ' ' +
                            (mySmezhenX + 9) + ',' + (mySmezhenY + 25) + ' ' +
                            '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' +

                            '<polyline points="' +
                            (mySmezhenX + 9) + ',' + (mySmezhenY + 25) + ' ' +
                            (mySmezhenX + 19) + ',' + (mySmezhenY + 25) +
                            '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' +

                            '<circle r="8" cx="' + // круг
                            (mySmezhenX + 25) + '" cy="' + (mySmezhenY + 25) +
                            '" fill="' + myColorActive + '"/>' +
                            '<circle r="6" cx="' +
                            (mySmezhenX + 25) + '" cy="' + (mySmezhenY + 25) +
                            '" fill="#' + myColorFill2 + '"/>' +

                            '<polyline points="' + // 2 полоски внутри круга
                            (mySmezhenX + 20) + ',' + (mySmezhenY + 20) + ' ' +
                            (mySmezhenX + 30) + ',' + (mySmezhenY + 30) +
                            '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' +
                            '<polyline points="' +
                            (mySmezhenX + 20) + ',' + (mySmezhenY + 30) + ' ' +
                            (mySmezhenX + 30) + ',' + (mySmezhenY + 20) +
                            '" stroke="' + myColorActive +
                            '" stroke-width="2" fill="#fff"/>';
                        if ((mySmezhenX + 8 + 30) > myWidthMax) myWidthMax = (mySmezhenX + 8 + 30);
                        if ((mySmezhenY + 25) > myHeightMax) myHeightMax = (mySmezhenY + 25); // только высота ножки
                    }

                    // оборудование грозозащиты
                    // ОПН
                    if (myObjectCurrent.eqOPN === 1) {
                        myFigure +=
                            '<polyline points="' +// корпус
                            (mySmezhenX + 9) + ',' + myHeightMax + ' ' +
                            (mySmezhenX + 9) + ',' + (myHeightMax + 14) + ' ' +
                            (mySmezhenX + 5) + ',' + (myHeightMax + 14) + ' ' +
                            (mySmezhenX + 5) + ',' + (myHeightMax + 28) + ' ' +
                            (mySmezhenX + 13) + ',' + (myHeightMax + 28) + ' ' +
                            (mySmezhenX + 13) + ',' + (myHeightMax + 14) + ' ' +
                            (mySmezhenX + 9) + ',' + (myHeightMax + 14) +
                            '" stroke="' + myColorActive + '" stroke-width="2" fill="' + myColorFill2 + '"/>' +

                            '<polyline points="' + // косая
                            (mySmezhenX + 0) + ',' + (myHeightMax + 16) + ' ' +
                            (mySmezhenX + 17) + ',' + (myHeightMax + 24) + ' ' +
                            (mySmezhenX + 17) + ',' + (myHeightMax + 28) + ' ' +
                            (mySmezhenX + 17) + ',' + (myHeightMax + 24) +
                            ' " stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' +

                            '<polyline points="' + // ножка к заземлению
                            (mySmezhenX + 9) + ',' + (myHeightMax + 28) + ' ' +
                            (mySmezhenX + 9) + ',' + (myHeightMax + 36) +
                            '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' +

                            '<polyline points="' + // заземление
                            (mySmezhenX + 2) + ',' + (myHeightMax + 36) + ' ' +
                            (mySmezhenX + 16) + ',' + (myHeightMax + 36) +
                            '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' +
                            '<polyline points="' +
                            (mySmezhenX + 4) + ',' + (myHeightMax + 39) + ' ' +
                            (mySmezhenX + 14) + ',' + (myHeightMax + 39) +
                            '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' +
                            '<polyline points="' +
                            (mySmezhenX + 6) + ',' + (myHeightMax + 42) + ' ' +
                            (mySmezhenX + 12) + ',' + (myHeightMax + 42) +
                            '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>';
                        myHeightMax += 42;
                    }

                    // разрядник
                    if (myObjectCurrent.eqDischarger === 1) {
                        myFigure +=
                            '<polyline points="' +// корпус
                            (mySmezhenX + 9) + ',' + myHeightMax + ' ' +
                            (mySmezhenX + 9) + ',' + (myHeightMax + 14) + ' ' +
                            (mySmezhenX + 5) + ',' + (myHeightMax + 14) + ' ' +
                            (mySmezhenX + 5) + ',' + (myHeightMax + 28) + ' ' +
                            (mySmezhenX + 13) + ',' + (myHeightMax + 28) + ' ' +
                            (mySmezhenX + 13) + ',' + (myHeightMax + 14) + ' ' +
                            (mySmezhenX + 9) + ',' + (myHeightMax + 14) +
                            '" stroke="' + myColorActive + '" stroke-width="2" fill="' + myColorFill2 + '"/>' +

                            '<polyline points="' + // внутри корпуса
                            (mySmezhenX + 9) + ',' + (myHeightMax + 14) + ' ' +
                            (mySmezhenX + 9) + ',' + (myHeightMax + 24) +
                            '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' +
                            '<polyline points="' +
                            (mySmezhenX + 5) + ',' + (myHeightMax + 24) + ' ' +
                            (mySmezhenX + 13) + ',' + (myHeightMax + 24) +
                            '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' +
                            '<polyline points="' +
                            (mySmezhenX + 5) + ',' + (myHeightMax + 20) + ' ' +
                            (mySmezhenX + 13) + ',' + (myHeightMax + 20) +
                            '" stroke="' + myColorActive + '" stroke-width="3" fill="#fff"/>' +

                            '<polyline points="' + // ножка к заземлению
                            (mySmezhenX + 9) + ',' + (myHeightMax + 28) + ' ' +
                            (mySmezhenX + 9) + ',' + (myHeightMax + 36) +
                            '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' +

                            '<polyline points="' + // заземление
                            (mySmezhenX + 2) + ',' + (myHeightMax + 36) + ' ' +
                            (mySmezhenX + 16) + ',' + (myHeightMax + 36) +
                            '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' +
                            '<polyline points="' +
                            (mySmezhenX + 4) + ',' + (myHeightMax + 39) + ' ' +
                            (mySmezhenX + 14) + ',' + (myHeightMax + 39) +
                            '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' +
                            '<polyline points="' +
                            (mySmezhenX + 6) + ',' + (myHeightMax + 42) + ' ' +
                            (mySmezhenX + 12) + ',' + (myHeightMax + 42) +
                            '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>';

                        myHeightMax += 42;
                    }

                    // заземление
                    if (myObjectCurrent.eqGrounding === 1) {
                        myFigure +=
                            '<polyline points="' +// ножка к заземлению
                            (mySmezhenX + 9) + ',' + (myHeightMax + 0) + ' ' +
                            (mySmezhenX + 9) + ',' + (myHeightMax + 14) +
                            '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' +
                            '<polyline points="' + // заземление
                            (mySmezhenX + 2) + ',' + (myHeightMax + 14) + ' ' +
                            (mySmezhenX + 16) + ',' + (myHeightMax + 14) +
                            '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' +
                            '<polyline points="' +
                            (mySmezhenX + 4) + ',' + (myHeightMax + 17) + ' ' +
                            (mySmezhenX + 14) + ',' + (myHeightMax + 17) +
                            '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' +
                            '<polyline points="' +
                            (mySmezhenX + 6) + ',' + (myHeightMax + 20) + ' ' +
                            (mySmezhenX + 12) + ',' + (myHeightMax + 20) +
                            '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>';
                        myHeightMax += 20;
                    }

                    // оборудование прочее
                    // адаптер
                    if (myObjectCurrent.eqAdapter === 1) {
                        myFigure +=
                            '<polyline points="' + // корпус
                            (mySmezhenX + 9) + ',' + myHeightMax + ' ' +
                            (mySmezhenX + 9) + ',' + (myHeightMax + 14) + ' ' +
                            (mySmezhenX + 5) + ',' + (myHeightMax + 14) + ' ' +
                            (mySmezhenX + 5) + ',' + (myHeightMax + 28) + ' ' +
                            (mySmezhenX + 13) + ',' + (myHeightMax + 28) + ' ' +
                            (mySmezhenX + 13) + ',' + (myHeightMax + 14) + ' ' +
                            (mySmezhenX + 9) + ',' + (myHeightMax + 14) +
                            '" stroke="' + myColorActive + '" stroke-width="2" fill="' + myColorFill2 + '"/>' +

                            '<polyline points="' + // усик 1
                            (mySmezhenX + 5) + ',' + (myHeightMax + 28) + ' ' +
                            (mySmezhenX + 2) + ',' + (myHeightMax + 35) +
                            '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' +
                            '<polyline points="' + // усик 2
                            (mySmezhenX + 13) + ',' + (myHeightMax + 28) + ' ' +
                            (mySmezhenX + 16) + ',' + (myHeightMax + 35) +
                            '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>';
                        myHeightMax += 35;
                    }

                    // аварийная
                    if (myObjectCurrent.eqAccident === 1) {
                        myFigure +=
                            '<circle r="4" cx="' +
                            (mySmezhenX + 9) + '" cy="' + (mySmezhenY + 9) +
                            '" fill="#ed3237"/>';
                    }

                    // подьем запрещен
                    if (myObjectCurrent.eqNoUp === 1) {
                        myFigure +=
                            '<polyline points="' +
                            (mySmezhenX - 2) + ',' + (mySmezhenY - 2) + ' ' +
                            (mySmezhenX + 20) + ',' + (mySmezhenY + 20) +
                            '" stroke="#ed3237" stroke-width="2" fill="#fff"/>' +
                            '<polyline points="' +
                            (mySmezhenX - 2) + ',' + (mySmezhenY + 20) + ' ' +
                            (mySmezhenX + 20) + ',' + (mySmezhenY - 2) +
                            '" stroke="#ed3237" stroke-width="2" fill="#fff"/>';
                        if ((mySmezhenX + 20) > myWidthMax) myWidthMax = (mySmezhenX + 20);
                        if ((mySmezhenY + 20) > myHeightMax) myHeightMax = (mySmezhenY + 20);
                    }

                    //console.log("-------------------------");
                    //console.log("опора: " + myObjectCurrent.viewName);

                    // оборудование коммутационное
                    // подсчет кол-ва линий от заданной точки
                    let mySpanMapIDs = funKolPolylinesThisPlacemark(getMapID);
                    // берем все ответвления
                    mySpanMapIDs = mySpanMapIDs.get('all');
                    //console.log("mySpanMapIDs:");

                    if (typeof (mySpanMapIDs) != null && mySpanMapIDs.length > 0) {
                        // пролеты от этой точки есть

                        // символ разьединителя
                        myFigure +=
                            '<symbol id="symbolDisconnector">' +
                            '<g>' +
                            '<polyline points="0,15 10,15" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' + // горизонтальная
                            '<polyline points="10,15 20,6" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' + // нож
                            '<polyline points="20,12 20,18" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' + // вертикальная на др.стороне
                            '<polyline points="20,15 30,15" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' + // горизонтальная на др.стороне
                            '</g>' +
                            '</symbol>';

                        // символ реклоузера
                        myFigure +=
                            '<symbol id="symbolReklouzer">' +
                            '<g>' +
                            '<polyline points="0,15 10,15" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' + // горизонтальная
                            '<polyline points="10,15 20,6" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' + // нож
                            '<polyline points="20,12 20,18" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' + // вертикальная на др.стороне
                            '<polyline points="20,15 30,15" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' + // горизонтальная на др.стороне
                            '</g>' +
                            '</symbol>';

                        // символ выключателя нагрузки
                        myFigure +=
                            '<symbol id="symbolVNa">' +
                            '<g>' +
                            '<polyline points="0,15 10,15" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' + // горизонтальная
                            '<polyline points="10,15 20,6" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' + // нож
                            '<polyline points="20,12 20,18" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' + // вертикальная на др.стороне
                            '<polyline points="20,15 30,15" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>' + // горизонтальная на др.стороне
                            '</g>' +
                            '</symbol>';

                        // сканировать все линии, где принимает участие данная точка
                        mySpanMapIDs.forEach(function (mySpanMapID) {

                            // вершины
                            let myStartMapID = mmObjects[mySpanMapID].startMapID;
                            let myStartCoord = [mmObjects[myStartMapID].lat, mmObjects[myStartMapID].long];
                            let myEndMapID = mmObjects[mySpanMapID].endMapID;
                            let myEndCoord = [mmObjects[myEndMapID].lat, mmObjects[myEndMapID].long];
                            let myPoints = mmObjects[mySpanMapID].points;
                            let myPointsStartPlus = typeof (myPoints[1]) !== 'undefined' ? myPoints[1] : myEndMapID;
                            let myPointsEndMinus = typeof (myPoints[myPoints.length - 2]) !== 'undefined' ? myPoints[myPoints.length - 2] : myStartMapID;

                            for (let i = 1; i <= 3; i++) {
                                let myFieldName = '';
                                switch (i) {
                                    case 1:
                                        // разьединитель
                                        myFieldName = 'Disconnector';
                                        break;
                                    case 2:
                                        // реклоузер
                                        myFieldName = 'Reklouzer';
                                        break;
                                    case 3:
                                        // выключатель нагрузки
                                        myFieldName = 'VNa';
                                        break;
                                }

                                let myReturn = null;
                                if (getMapID === myStartMapID && mmObjects[mySpanMapID][('eq' + myFieldName + 'Start')] === 1) {
                                    //console.log(myFieldName + " - на start-е");
                                    // угол и смещение
                                    myReturn = funSvgGetAngle(myStartCoord, myPointsStartPlus);
                                } else {
                                    if (getMapID === myEndMapID && mmObjects[mySpanMapID][('eq' + myFieldName + 'End')] === 1) {
                                        //console.log(myFieldName + " - на end-е");
                                        // угол и смещение
                                        myReturn = funSvgGetAngle(myEndCoord, myPointsEndMinus);
                                    }
                                }
                                //console.log("Полученный угол:");
                                //console.log(myReturn);

                                // использовать обьект SVG
                                if (myReturn != null) {
                                    myFigure +=
                                        '<use xlink:href="#symbol' + myFieldName + '" ' +
                                        'transform="translate(' + (43 + myReturn['smezhenX']) + ' ' + (43 + myReturn['smezhenY']) + ') rotate(' + myReturn['angle'] + ' 15 15)" />';

                                    // запас, чтоб прорисовалось и не сдвинулась
                                    if ((mySmezhenX + 9 + 50) > myWidthMax) myWidthMax = (mySmezhenX + 9 + 50);
                                    if ((mySmezhenY + 9 + 50) > myHeightMax) myHeightMax = (mySmezhenY + 9 + 50);
                                    // myWidthMax += 30;
                                    // myHeightMax += 30;
                                }
                            }

                        });
                    }

                    myCorrection = -9;
                    break;

                case 'substation':
                    myFigure =
                        '<rect width="30" height="15" x="' +
                        (mySmezhenX - 15) + '" y="' + (mySmezhenY - 7) +
                        '" fill="' + myColorActive + '"/>';
                    if ((mySmezhenX + 15) > myWidthMax) myWidthMax = (mySmezhenX + 15);
                    if ((mySmezhenY + 8) > myHeightMax) myHeightMax = (mySmezhenY + 8);
                    myCorrection = 0;
                    break;

                case 'customer':
                    myFigure = '<circle r="5" cx="' +
                        (mySmezhenX + 5) + '" cy="' + (mySmezhenY + 5) +
                        '" fill="' + myColorActive + '"/>';
                    if ((mySmezhenX + 5 + 5) > myWidthMax) myWidthMax = (mySmezhenX + 5 + 5);
                    if ((mySmezhenY + 5 + 5) > myHeightMax) myHeightMax = (mySmezhenY + 5 + 5);
                    myCorrection = -4;
                    break;

                default:
            }

            // запас, чтоб прорисовалось и не сдвинулась
            myHeightMax += +9;

            // для отладки контур
            if (false) {
                myFigure += '<polyline points="' +
                    0 + ',' + 0 + ' ' +
                    0 + ',' + myHeightMax +
                    '" stroke="#ed3237" stroke-width="1"/>';
                myFigure += '<polyline points="' +
                    0 + ',' + myHeightMax + ' ' +
                    myWidthMax + ',' + myHeightMax +
                    '" stroke="#ed3237" stroke-width="1"/>';
                myFigure += '<polyline points="' +
                    myWidthMax + ',' + myHeightMax + ' ' +
                    myWidthMax + ',' + 0 +
                    '" stroke="#ed3237" stroke-width="1"/>';
                myFigure += '<polyline points="' +
                    myWidthMax + ',' + 0 + ' ' +
                    0 + ',' + 0 +
                    '" stroke="#ed3237" stroke-width="1"/>';
            }

            // изменяемость от масштаба
            if (myScaleMap === 18) {
                myScaleSVG = 0.8; // 20 метров
            }
            if (myScaleMap === 19) {
                myScaleSVG = 1.3; // 10 метров
            }
            if (myScaleMap === 20) {
                myScaleSVG = 1.8; // 6 метров
            }
            if (myScaleMap === 21) {
                myScaleSVG = 2.3; // 3 метра
            }

            myStrokaCoordinat =
                'viewBox = "0 0 ' + (myWidthMax) + ' ' + (myHeightMax) + '" ' +
                'width = "' + (myWidthMax * myScaleSVG) + '" ' +
                'height = "' + (myHeightMax * myScaleSVG) + '" ' +
                'style = "margin-left: ' + (-50 + myCorrection) * myScaleSVG + 'px; margin-top: ' + (-50 + myCorrection) * myScaleSVG + 'px;">';

            // console.log("myScaleMap = " + myScaleMap);
            // console.log("myScaleSVG = " + myScaleSVG);
            // console.log("myCorrection = " + myCorrection);
            // console.log("myStrokaCoordinat = " + myStrokaCoordinat);
        } else {
            // просто точку ставить
            myFigure = '<circle r="2" cx="2" cy="2" fill="' + myColorActive + '"/>';
            // имя не отображать
            myNeedViewName = false;
            myStrokaCoordinat = 'style = "margin-left: -2px; margin-top: -2px;">';
        }

        let myIconLayout = ymaps.templateLayoutFactory.createClass(
            '<div class="placemark_layout_container">' +
            '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" ' +
            myStrokaCoordinat +
            myFigure +
            '</svg>' +
            ((myNeedViewName) ? ('<p style="white-space: nowrap; font-size:smaller; color:#FFFFFF;">' + myObjectCurrent.viewName + '</p>') : '') +
            '</div>'
        );

        // перерисовать эту иконку на карте
        mmCollection.get(getMapID).options.set('iconLayout', myIconLayout);
    }

    // ---------------------------------------------------------------
    // угол и смещение
    function funSvgGetAngle(getCoord1, getCoord2) {

        // определить географические координаты
        let myLat1 = getCoord1[0];
        let myLong1 = getCoord1[1];
        let myLat2 = getCoord2[0];
        let myLong2 = getCoord2[1];

        //console.log("Координаты вершин для определения угла:");
        //console.log(myLat1, myLat2, myLong1, myLong2);

        // расстояние между точками (гипотенуза)
        let myLength1 = ymaps.coordSystem.geo.getDistance(
            getCoord1, getCoord2
        ).toFixed(1);

        // ---------------------------------------------------------------
        // смещение точки
        let myOffset = 30;
        let mySmezhenX = 0;
        let mySmezhenY = 0;
        let myAngle = 0;
        let mySinA = 0;
        let myCosA = 0;

        // ---------------------------------------------------------------
        if (myLat2 >= myLat1 && myLong2 >= myLong1) {
            // 1-ая четверь
            //console.log("1-ая четверь");

            // проекция точки на ось Y
            let myCoord3 = [myLat1, myLong2];

            //  длина до воображаемой точки (противолежащий катет)
            let myLength2 = ymaps.coordSystem.geo.getDistance(
                getCoord1, myCoord3
            ).toFixed(1);

            // синус
            mySinA = myLength2 / myLength1;
            // угол
            myAngle = ((Math.asin(mySinA)) * 180 / Math.PI) - 90;

            mySmezhenX = myOffset;
            mySmezhenY = -myOffset;
        }

        // ---------------------------------------------------------------
        if (myLat2 <= myLat1 && myLong2 >= myLong1) {
            // 2-ая четверь
            //console.log("2-ая четверь");

            // проекция точки на ось Y
            let myCoord3 = [myLat1, myLong2];

            //  длина до воображаемой точки (противолежащий катет)
            let myLength2 = ymaps.coordSystem.geo.getDistance(
                getCoord1, myCoord3
            ).toFixed(1);

            // косинус
            myCosA = myLength2 / myLength1;
            // угол
            myAngle = ((Math.acos(myCosA)) * 180 / Math.PI);

            mySmezhenX = myOffset;
            mySmezhenY = myOffset;
        }

        // ---------------------------------------------------------------
        if (myLat2 <= myLat1 && myLong2 <= myLong1) {
            // 3-ая четверь
            //console.log("3-ая четверь");

            // проекция точки на ось Y
            let myCoord3 = [myLat1, myLong2];

            //  длина до воображаемой точки (противолежащий катет)
            let myLength2 = ymaps.coordSystem.geo.getDistance(
                getCoord1, myCoord3
            ).toFixed(1);

            // синус
            mySinA = myLength2 / myLength1;
            // угол
            myAngle = ((Math.asin(mySinA)) * 180 / Math.PI) - 90;

            mySmezhenX = -myOffset;
            mySmezhenY = myOffset;
        }

        // ---------------------------------------------------------------
        if (myLat2 >= myLat1 && myLong2 <= myLong1) {
            // 4-ая четверь
            //console.log("4-ая четверь");

            // проекция точки на ось Y
            let myCoord3 = [myLat1, myLong2];

            //  длина до воображаемой точки (противолежащий катет)
            let myLength2 = ymaps.coordSystem.geo.getDistance(
                getCoord1, myCoord3
            ).toFixed(1);

            // косинус
            myCosA = myLength2 / myLength1;
            // угол
            myAngle = ((Math.acos(myCosA)) * 180 / Math.PI);

            mySmezhenX = -myOffset;
            mySmezhenY = -myOffset;
        }
        // ---------------------------------------------------------------

        //console.log("myAngle = " + myAngle);
        if (isNaN(myAngle)) myAngle = 0;

        // возвращаемый параметр
        return {
            'angle': myAngle,
            'smezhenX': mySmezhenX,
            'smezhenY': mySmezhenY
        };
    }

</script>
