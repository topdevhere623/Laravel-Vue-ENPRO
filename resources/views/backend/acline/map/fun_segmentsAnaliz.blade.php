<script type="text/javascript">

    // ---------------------------------------------------------------
    // разбивка линии на сегменты
    function funSegments() {

        mmSegments = [];
        mmMaxSegmentN = 0;
        if (!mmObjects.length > 0) return;

        // функция определения, с какой точки начать анализ сегментов (точка, от которой отходит только одна линия)
        let myFirstMapID = funSegmentFirstPoint();
        if (myFirstMapID != null) {
            // идем дальше от этой первой точки

            // рекурсивная функция иследовать линию от заданной точки
            funSegmentRecursExploreLine(myFirstMapID);

            //console.log("-------------------------------");
            //console.log("Сегменты после первого анализа:");
            //console.log(JSON.parse(JSON.stringify(mmSegments)));

            // в сегментах еще разбить на сегменты
            if (mmSegments.length > 0) {

                // функция разбиения сегмента, если на одном конце пролета ТП или Потребитель
                funSegmentIfSubstationOrCustomer();

                //console.log("-------------------------------");
                //console.log("Сегменты после ТП или Потребителя:");
                //console.log(JSON.parse(JSON.stringify(mmSegments)));

                // функция разбиения сегмента, если меняется тип линии
                funSegmentIfChangeType();
            }

            //console.log("-------------------------------");
            //console.log("Сегменты финальный вариант:");
            //console.log(JSON.parse(JSON.stringify(mmSegments)));
        }
    }

    // ---------------------------------------------------------------
    // функция определения, с какой точки начать анализ сегментов (точка, от которой отходит только одна линия)
    function funSegmentFirstPoint() {
        let myFirstMapID = null;
        for (let i = 0; i < mmObjects.length; i++) {
            if (mmObjects[i].mapType === 'placemark' && mmObjects[i].type !== 'customer' && mmObjects[i].deleted === false) {

                // подсчет кол-ва линий от заданной точки
                let myKolPolylines = funKolPolylinesThisPlacemark(mmObjects[i].mapID);
                // берем ответвления все
                myKolPolylines = myKolPolylines.get('all');

                if (myKolPolylines.length === 1) {
                    myFirstMapID = mmObjects[i].mapID;
                    //console.log("Начальная точка в анализе сегментов: " + myFirstMapID + " ее имя: " + mmObjects[myFirstMapID]["viewName"]);
                    return myFirstMapID;
                }
            }
        }
    }

    // ---------------------------------------------------------------
    // функция разбиения сегмента, если на одном конце пролета ТП или Потребитель
    function funSegmentIfSubstationOrCustomer() {

        let mySegmentsLength = mmSegments.length; // именно надо через переменную, потому что при добалвении нового уходит в бесконечный цикл

        //console.log("Массив сегментов");
        //console.log(JSON.parse(JSON.stringify(mmSegments)));

        for (let mySegmentN = 0; mySegmentN <= mySegmentsLength - 1; mySegmentN++) {

            let mySegment = mmSegments[mySegmentN];
            let mySegmentLength = mySegment.length;

            //console.log("Один сегмент");
            //console.log(JSON.parse(JSON.stringify(mySegment)));

            for (let mySegmentItemN = 0; mySegmentItemN <= mySegment.length - 1; mySegmentItemN++) {
                let mySegmentItem = mySegment[mySegmentItemN];

                //console.log("Номер пролета");
                //console.log(mySegmentItemN);
                //console.log("Его содержимое");
                //console.log(mySegmentItem);

                let myStartMapID = mmObjects[mySegmentItem].startMapID;
                let myStartType = mmObjects[myStartMapID].type;
                let myEndMapID = mmObjects[mySegmentItem].endMapID;
                let myEndType = mmObjects[myEndMapID].type;

                // если ТП на конце вренменно убрали из правила 2021-05-28
                if (myStartType === 'customer' || myEndType === 'customer') { // myStartType === 'substation' || myEndType === 'substation' ||

                    if (mmSegments[mySegmentN].length > 1) {
                        // да, актуально для разделения (сегмент имеет в себе больше 1-го элемента)

                        // добавить новый сегмент разделив имеющийся
                        mmMaxSegmentN++;
                        mmSegments[mmMaxSegmentN] = [];
                        mmSegments[mmMaxSegmentN].push(mySegmentItem);
                        mmSegments[mySegmentN].splice(mySegmentItemN, 1);

                        //console.log("Разбить сегмент!");
                        //console.log("mmMaxSegmentN = " + mmMaxSegmentN);
                        //console.log("Массив сегментов");
                        //console.log(JSON.parse(JSON.stringify(mmSegments)));

                        // повторить анализ массива сегментов
                        mySegmentItemN = mySegmentItemN - 1;
                        continue;
                    }
                }
            }
        }
    }

    // ---------------------------------------------------------------
    // функция разбиения сегмента, если меняется тип линии
    function funSegmentIfChangeType() {

        let myNeedScan = true;
        while (myNeedScan) {

            myNeedScan = false;
            let myTypeCurrent = '';
            let myTypeLast = '';
            let myWireSCurrent = '';
            let myWireSLast = '';
            let myWireMarkCurrent = '';
            let myWireMarkLast = '';

            mmSegments.forEach(function (item, i) {

                if (item.length > 1) {
                    // да, актуально для разделения (сегмент имеет в себе больше 1-го элемента)

                    myTypeCurrent = '';
                    myTypeLast = '';
                    myWireSCurrent = '';
                    myWireSLast = '';
                    myWireMarkCurrent = '';
                    myWireMarkLast = '';
                    item.forEach(function (item2, i2) {

                        // текущее значение
                        myTypeCurrent = mmObjects[item2].type;
                        myWireSCurrent = mmObjects[item2].wireS;
                        myWireMarkCurrent = mmObjects[item2].wireMark;

                        // проверка, сменилось ли
                        if (
                            (myTypeLast !== '' && Number(myTypeLast) !== Number(myTypeCurrent)) ||
                            (myWireSLast !== '' && Number(myWireSLast) !== Number(myWireSCurrent)) ||
                            (myWireMarkLast !== '' && Number(myWireMarkLast) !== Number(myWireMarkCurrent))) {
                            // тип поменялся

                            // увеличить счетчик для сегмента
                            mmMaxSegmentN++;
                            mmSegments[mmMaxSegmentN] = mmSegments[i].splice(i2, item.length);
                            // нужно будет заново сканировать
                            myNeedScan = true;
                        }

                        // записать в прошлое значение
                        myTypeLast = myTypeCurrent;
                        myWireSLast = myWireSCurrent;
                        myWireMarkLast = myWireMarkCurrent;
                    });
                } else {
                    // не актуально для разделения, (сегмент имеет в себе толко 1-н элемент)
                }
            });
        }
    }

    // ---------------------------------------------------------------
    // рекурсивная функция иследовать линию от заданной точки
    function funSegmentRecursExploreLine(getMapID) {

        //console.log("-----------------------------------------------------");
        //console.log("Буду исследовать линию в точке " + mmObjects[getMapID].viewName + "(" + getMapID + ")");

        // подсчет кол-ва линий от заданной точки
        let myKolPolylines = funKolPolylinesThisPlacemark(getMapID);
        let myKolPolylinesAll = myKolPolylines.get('all');
        let myKolPolylinesOnlyCustomers = myKolPolylines.get('onlyCustomers');

        // console.log("Кол-во линий в ней (все) = " + myKolPolylinesAll.length);
        // console.log("Кол-во линий в ней (только Потребители) = " + myKolPolylinesOnlyCustomers.length);
        if (myKolPolylinesAll.length > 0) {
            // линии есть
            // console.log("Линии все:");
            // console.log(myKolPolylinesAll);

            // удалить из исследуемого массива, чтоб в бесконечность не уходило
            myKolPolylinesAll = funSegmentMinusPolylines(myKolPolylinesAll); // myKolPolylinesAll.splice(i, 1);
            myKolPolylinesOnlyCustomers = funSegmentMinusPolylines(myKolPolylinesOnlyCustomers);

            myKolPolylinesAll.forEach(function (myCurrentPolylineMapID, i) {
                //console.log("Текущая линия myCurrentPolylineMapID = " + myCurrentPolylineMapID);

                // конечная точка текущей линии
                let myPolylineEndMapID = (getMapID === mmObjects[myCurrentPolylineMapID].startMapID) ? mmObjects[myCurrentPolylineMapID].endMapID : mmObjects[myCurrentPolylineMapID].startMapID;

                //console.log("myKolPolylinesAll.length = " + myKolPolylinesAll.length);
                //console.log("myKolPolylinesOnlyCustomers.length = " + myKolPolylinesOnlyCustomers.length);
                // console.log("mmObjects[getMapID].type = " + mmObjects[getMapID].type);
                // console.log("mmObjects[myPolylineEndMapID].type = " + mmObjects[myPolylineEndMapID].type);

                // проверка, создать новый сегмент или нет
                let myNeedNewegment = false;
                if (myKolPolylinesOnlyCustomers.length > 0) {
                    // потребители есть в этой связке
                    if (myKolPolylinesAll.length - myKolPolylinesOnlyCustomers.length >= 2) {
                        myNeedNewegment = true;
                    }
                } else {
                    // потребителей нет в этой связке
                    if (myKolPolylinesAll.length >= 2) {
                        // да, это разветвление больше 2-х линий (3-тью линию выше через функцию funSegmentMinusPolylines уже убрал)
                        myNeedNewegment = true;
                    }
                }

                // создать новый сегмент
                if (myNeedNewegment) {
                    // увеличить порядковый номер, если это не начало
                    if (mmSegments.length > 0) {
                        mmMaxSegmentN++;
                        //console.log("Увеличен порядковый номер сегмента: " + mmMaxSegmentN);
                    }
                }
                // записать пролет в сегмент
                if (typeof (mmSegments[mmMaxSegmentN]) === 'undefined') {
                    mmSegments[mmMaxSegmentN] = [];
                }
                mmSegments[mmMaxSegmentN].push(myCurrentPolylineMapID);
                //console.log("В сегмент: " + mmMaxSegmentN + " добавлен пролет: " + myCurrentPolylineMapID);

                //console.log("Ухожу в рекурсию myPolylineEndMapID = " + myPolylineEndMapID);
                // рекурсивная функция иследовать линию от заданной точки
                funSegmentRecursExploreLine(myPolylineEndMapID);
            });
        }
        return;
    }

    // ---------------------------------------------------------------
    // удалить линии из прошлых уже учтенных сегментов
    function funSegmentMinusPolylines(myArr) {

        if (myArr.length > 0 && mmSegments.length > 0) {
            myArr.forEach(function (item, i) {
                mmSegments.forEach(function (item2) {
                    item2.forEach(function (item3) {
                        if (item === item3) {
                            myArr.splice(i, 1);
                        }
                    });
                });
            });

        }
        // возвращаемый параметр
        return myArr;
    }

    // ---------------------------------------------------------------
    // подсчет кол-ва линий от заданной точки
    // вернет массив с 3-мя массива внутри: всех, только Потребителей, без Потребителей
    function funKolPolylinesThisPlacemark(getMapID) {

        let myKolPolylinesAll = [];
        let myKolPolylinesOnlyCustomers = [];
        let myKolPolylinesNoCustomers = [];

        mmObjects.forEach(function (item) {
            if (item.mapType === 'polyline' && (item.startMapID === getMapID || item.endMapID === getMapID) && item.deleted === false) {

                // эта точка является вершиной
                let mySpanMapID = item.mapID;
                //console.log("Точка getMapID = " + getMapID + " находится в пролете mySpanMapID = " + mySpanMapID)

                // записать в массив
                myKolPolylinesAll.push(mySpanMapID);

                // проверка является ли линия с Потребителем
                if (mmObjects[item.startMapID].type === 'customer' || mmObjects[item.endMapID].type === 'customer') {
                    // да, один из концов - Потребитель
                    myKolPolylinesOnlyCustomers.push(mySpanMapID);
                } else {
                    myKolPolylinesNoCustomers.push(mySpanMapID);
                }
            }
        });

        // возвращаемый параметр
        let myReturn = new Map([
            ['all', myKolPolylinesAll],
            ['onlyCustomers', myKolPolylinesOnlyCustomers],
            ['noCustomers', myKolPolylinesNoCustomers],
        ]);

        // console.log("кол-во линий в точке: " + myKolPolylinesAll.length);
        // console.log(myKolPolylinesAll);
        // console.log(myKolPolylinesOnlyCustomers);
        // console.log(myKolPolylinesNoCustomers);

        // возвращаемый параметр
        return myReturn;
    }

    // ---------------------------------------------------------------
    // найти mapID Polyline по ее вершинам
    function funPolylineOnPlacemarks(getStartMapID, getEndMapID) {

        mmObjects.forEach(function (item) {
            if (item['mapType'] === 'polyline' && item['startMapID'] === getStartMapID && item['endMapID'] === getEndMapID && item['deleted'] === false) {
                // возвращаемый параметр
                return item.mapID;
            }
        });
        // возвращаемый параметр (если линия не нашлась)
        return null;
    }

</script>
