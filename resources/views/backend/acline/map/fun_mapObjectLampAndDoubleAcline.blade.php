<script type="text/javascript">

    // SVG-рисование поверх карты

    // ---------------------------------------------------------------
    // клик на переключателе
    function funChMapViewLamp() {

        // записать значение переключателя в глобальную переменную
        mmChMapViewLamp = ($('#chMapViewLamp').is(':checked')) ? 1 : 0;

        if (mmChMapViewLamp === 1) {
            // да, показывать нужно
            // линии освещения поверх карты
            funLamp();
        } else {
            // нет, показывать не нужно
            // удалить с карты линии освещения
            funLampDelete();
        }
    }

    // ---------------------------------------------------------------
    // клик на переключателе
    function funChMapViewDoubleAcline() {

        // записать значение переключателя в глобальную переменную
        mmChMapViewDoubleAcline = ($('#chMapViewDoubleAcline').is(':checked')) ? 1 : 0;

        if (mmChMapViewDoubleAcline === 1) {
            // да, показывать нужно
            // линии совместного подвеса поверх карты
            funDoubleAcline();
        } else {
            // нет, показывать не нужно
            // удалить с карты линии совместного подвеса
            funDoubleAclineDelete();
        }
    }

    // ---------------------------------------------------------------
    // линии освещения поверх карты
    function funLamp() {

        if (mmChMapViewLamp === 0) {
            // нет, показывать не нужно
            // досрочный выход
            return;
        }

        // линии освещения поверх карты - ч.1 - получить массив точек
        let myArr = funLampLoad();
        // линии освещения поверх карты - ч.2 - создать Polyline
        let mySVG = funLampMakePolyline(myArr);

        // в этом варианте на карте клик не обрабатывался
        // линии освещения поверх карты - нарисовать изображение
        // !!! НЕ ИСПОЛЬЗУЕТСЯ
        //let mySVG = funLampMakeImage(myArr);
        // добавить к карте подготовленное изображение SVG
        // !!! НЕ ИСПОЛЬЗУЕТСЯ
        //funSVGAddToMap(mySVG);
    }

    // ---------------------------------------------------------------
    // линии совместного подвеса поверх карты
    function funDoubleAcline() {

        if (mmChMapViewDoubleAcline === 0) {
            // нет, показывать не нужно
            // досрочный выход
            return;
        }

        // линии совместного подвеса поверх карты - ч.1 - получить массив точек
        let myArr = funDoubleAclineLoad();
        // линии совместного подвеса поверх карты - ч.2 - создать Polyline
        let mySVG = funDoubleAclineMakePolyline(myArr);
    }

    // ---------------------------------------------------------------
    // линии освещения поверх карты - ч.1 - получить массив точек
    function funLampLoad() {

        // из имеющихся обьектов выявить только те точки, которые имеют фонари в видимой области
        let myObjectsFilterPlacemark = mmObjects.filter(function (item) {
            return item.eqLamp === 1;
        });
        // пролеты, имеющие нужный признак в вершинах
        let myArr = funSVGArrPolylines(myObjectsFilterPlacemark, 'or');

        // возвращаемый параметр
        return myArr;
    }

    // ---------------------------------------------------------------
    // линии совместного подвеса поверх карты - ч.1 - получить массив точек
    function funDoubleAclineLoad() {

        // из имеющихся обьектов выявить только те точки, которые имеют признак в видимой области
        let myObjectsFilterPlacemark = mmObjects.filter(function (item) {
            return item.isDoubleAcline === true;
            //return item.aclinesObject['count'] > 1;
        });
        // пролеты, имеющие нужный признак в вершинах
        let myArr = funSVGArrPolylines(myObjectsFilterPlacemark, 'and');

        // возвращаемый параметр
        return myArr;
    }

    // ---------------------------------------------------------------
    // линии освещения поверх карты - ч.2 - создать Polyline
    function funLampMakePolyline(getArr) {

        // очистить коллекцию
        mmCollectionLamp.removeAll();

        // смещение от основной линии (от основных координат)
        let mySmezhen15 = 0.0001;
        let mySmezhen21 = 0.000005;
        let mySmezhen = mySmezhen15;
        // текущий масштаб карты
        let myScaleMap = myMap.getZoom();
        // изменяемость смещения от масштаба
        if (myScaleMap >= 15) {
            let myDelta = (mySmezhen15 - mySmezhen21) / 6;
            mySmezhen = mySmezhen21 + (21 - myScaleMap) * myDelta;
        }
        //console.log('Текущий масштаб: ' + myScaleMap);
        //console.log('Смещение: ' + mySmezhen);

        if (getArr.length > 0) {
            // да, обьекты есть

            // сканировать пролеты и подготовить массив точек для ломаной
            getArr.forEach(function (item) {
                //console.log("Пролет с освещением: " + item.mapID);

                let myStartMapID = mmObjects.find(item2 => Number(item2.mapID) === Number(item['startMapID']));
                let myEndMapID = mmObjects.find(item2 => Number(item2.mapID) === Number(item['endMapID']));

                // добавлять по одной линии в коллекцию
                // ломанной рисовать не получится, т.к. обьекты в массиве могут быть не по порядку (даже если проверку начало/конеч добавить), и ломанная будет перечеркнутая или пересекаемая саму себя
                // полигон тоже не подойдет, во-первых, он как фигура зациклен, и внутри не смог клики обрабатывать
                let myPolyline = new ymaps.Polyline([
                        [myStartMapID.lat + mySmezhen, myStartMapID.long + mySmezhen],
                        [myEndMapID.lat + mySmezhen, myEndMapID.long + mySmezhen]
                    ],
                    properties =
                        {
                            hintContent: "Линия освещения",
                        },
                    options =
                        {
                            // можно ли передвигать
                            draggable: false,
                            drawing: true,
                            // цвет границ
                            strokeColor: '#f7ff00',
                            // прозрачность (полупрозрачная заливка)
                            opacity: 0.6,
                            // ширина линии
                            strokeWidth: 2,
                            // стиль линии
                            strokeStyle: 'shortdash'
                        }
                );

                // добавить в коллекцию
                mmCollectionLamp.add(myPolyline);
            });
        }
    }

    // ---------------------------------------------------------------
    // линии совместного подвеса поверх карты - ч.2 - создать Polyline
    function funDoubleAclineMakePolyline(getArr) {

        // очистить коллекцию
        mmCollectionDoubleAcline.removeAll();

        // смещение от основной линии (от основных координат)
        let mySmezhen15 = 0.0001;
        let mySmezhen21 = 0.000005;
        let mySmezhen = mySmezhen15;
        // текущий масштаб карты
        let myScaleMap = myMap.getZoom();
        // изменяемость смещения от масштаба
        if (myScaleMap >= 15) {
            let myDelta = (mySmezhen15 - mySmezhen21) / 6;
            mySmezhen = mySmezhen21 + (21 - myScaleMap) * myDelta;
        }
        //console.log('Текущий масштаб: ' + myScaleMap);
        //console.log('Смещение: ' + mySmezhen);

        // по другую сторону от линии освещения
        mySmezhen = -mySmezhen;

        if (getArr.length > 0) {
            // да, обьекты есть

            // сканировать пролеты и подготовить массив точек для ломаной
            getArr.forEach(function (item) {
                //console.log("Пролет с освещением: " + item.mapID);

                let myStartMapID = mmObjects.find(item2 => Number(item2.mapID) === Number(item['startMapID']));
                let myEndMapID = mmObjects.find(item2 => Number(item2.mapID) === Number(item['endMapID']));

                // добавлять по одной линии в коллекцию
                // ломанной рисовать не получится, т.к. обьекты в массиве могут быть не по порядку (даже если проверку начало/конеч добавить), и ломанная будет перечеркнутая или пересекаемая саму себя
                // полигон тоже не подойдет, во-первых, он как фигура зациклен, и внутри не смог клики обрабатывать
                let myPolyline = new ymaps.Polyline([
                        [myStartMapID.lat + mySmezhen, myStartMapID.long + mySmezhen],
                        [myEndMapID.lat + mySmezhen, myEndMapID.long + mySmezhen]
                    ],
                    properties =
                        {
                            hintContent: "Совместный подвес",
                        },
                    options =
                        {
                            // можно ли передвигать
                            draggable: false,
                            drawing: true,
                            // цвет границ
                            strokeColor: '#e600ff',
                            // прозрачность (полупрозрачная заливка)
                            opacity: 0.6,
                            // ширина линии
                            strokeWidth: 2,
                            // стиль линии
                            strokeStyle: 'shortdash'
                        }
                );

                // добавить в коллекцию
                mmCollectionDoubleAcline.add(myPolyline);
            });
        }
    }

    // ---------------------------------------------------------------
    // пролеты, имеющие нужный признак в вершинах
    function funSVGArrPolylines(getArrPlacemark, getCondition = 'or') {
        // возвращаемый массив
        let myArr = [];

        if (getArrPlacemark.length > 0) {

            // чисто список mapID из полученного выше массива
            let getArrPlacemarkMapID = getArrPlacemark.map(function (item) {
                return item.mapID;
            });

            if (getArrPlacemarkMapID.length > 0) {

                // только пролеты 701/702
                // из имеющихся обьектов выявить только пролеты/участки 701/702
                let myObjectsFilterPolyline = mmObjects.filter(function (item) {
                    return Number(item.lineToCustomer) === 701 || Number(item.lineToCustomer) === 702;
                });

                if (myObjectsFilterPolyline.length > 0) {

                    // из пролетов выявить только те, которые имеют вершины с признаком
                    let myObjectsFilterPolylineNeed = [];
                    switch (getCondition) {
                        case "or":
                            myObjectsFilterPolylineNeed = myObjectsFilterPolyline.filter(function (item) {
                                return getArrPlacemarkMapID.indexOf(item.startMapID) != -1 || getArrPlacemarkMapID.indexOf(item.endMapID) != -1;
                            });
                            break;
                        case "and":
                            myObjectsFilterPolylineNeed = myObjectsFilterPolyline.filter(function (item) {
                                return getArrPlacemarkMapID.indexOf(item.startMapID) != -1 && getArrPlacemarkMapID.indexOf(item.endMapID) != -1;
                            });
                            break;
                    }

                    // возвращаемый массив
                    myArr = myObjectsFilterPolylineNeed;
                }
            }
        }

        // возвращаемый параметр
        return myArr;
    }

    // ---------------------------------------------------------------
    // удалить с карты линии освещения
    function funLampDelete() {
        // очистить коллекцию
        mmCollectionLamp.removeAll();
    }

    // ---------------------------------------------------------------
    // удалить с карты линии совместного подвеса
    function funDoubleAclineDelete() {
        // очистить коллекцию
        mmCollectionDoubleAcline.removeAll();
    }

    // ---------------------------------------------------------------
    // линии освещения поверх карты - нарисовать изображение
    // !!! НЕ ИСПОЛЬЗУЕТСЯ
    function funLampMakeImage(getArr) {

        // полученные параметры
        let myArr = getArr.get('Arr');
        let myMinLat = getArr.get('myMinLat');
        let myMaxLat = getArr.get('myMaxLat');
        let myMinLong = getArr.get('myMinLong');
        let myMaxLong = getArr.get('myMaxLong');

        // отразить по горизонтали (из-за того, что отсчет по Y в SVG идет вниз)
        let temp = myMaxLat;
        myMaxLat = myMinLat;
        myMinLat = temp;

        // для расчета координата
        let pxOneX = (myMaxLong - myMinLong) / (mmSVGWidthMax);
        let pxOneY = (myMaxLat - myMinLat) / (mmSVGHeightMax);
        if (pxOneX === 0) pxOneX = 1;
        if (pxOneY === 0) pxOneY = 1;

        // построение рисунка
        let myFigure = '';
        let mySmezhen = 5; // смещение от основной линии (от основных координат)
        if (myArr.length > 0) {
            // да, обьекты есть

            // нанести на холст SVG
            myArr.forEach(function (item) {
                // пролеты
                let x1 = '';
                let y1 = '';
                let x2 = '';
                let y2 = '';
                let coordStart = mmObjects.find(item2 => Number(item2.mapID) === Number(item['startMapID']));
                if (typeof (coordStart) != 'undefined') {
                    x1 = (coordStart['long'] - myMinLong) / pxOneX + mySmezhen;
                    y1 = (coordStart['lat'] - myMinLat) / pxOneY + mySmezhen;
                }
                let coordEnd = mmObjects.find(item2 => Number(item2.mapID) === Number(item['endMapID']));
                if (typeof (coordStart) != 'undefined') {
                    x2 = (coordEnd['long'] - myMinLong) / pxOneX + mySmezhen;
                    y2 = (coordEnd['lat'] - myMinLat) / pxOneY + mySmezhen;
                }
                if (x1 !== '' && x2 !== '') {
                    myFigure += '<polyline points="' +
                        x1 + ',' + y1 + ' ' +
                        x2 + ',' + y2 +
                        '" stroke="#FFF" stroke-width="2" fill="#fff"/>';
                }
            });
        }

        // холст SVG
        let mySVG =
            '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" ' +
            'viewBox = "0 0 ' + mmSVGWidthMax + ' ' + mmSVGHeightMax + '" ' +
            'width = "' + mmSVGWidthMax + '" ' +
            'height = "' + mmSVGHeightMax + '">' +
            '<g>' + myFigure + '</g>' +
            '</svg>';

        //console.log("Холст SVG:");
        //console.log(mySVG);

        // возвращаемый параметр
        return mySVG;
    }

    // добавить к карте подготовленное изображение SVG
    // !!! НЕ ИСПОЛЬЗУЕТСЯ
    function funSVGAddToMap(getSVG) {

        console.log("Холст SVG:");
        console.log(getSVG);

        // координаты углов
        let myBounds = myMap.getBounds();
        let myLatT = myBounds[0][0];
        let myLatB = myBounds[1][0];
        let myLongL = myBounds[0][1];
        let myLongR = myBounds[1][1];

        let myTL = [myLatT, myLongL];
        let myTR = [myLatT, myLongR];
        let myBR = [myLatB, myLongR];
        let myBL = [myLatB, myLongL];

        //console.log("Вершины полигона:");
        //console.log(myTL);
        //console.log(myTR);
        //console.log(myBR);
        //console.log(myBL);

        //if (typeof (myPolygonNearObjects) !== 'undefined') myMap.geoObjects.remove(myPolygonNearObjects);
        mmCollectionLamp.removeAll();

        // новый полигон
        myPolygonNearObjects = new ymaps.Polygon([
                // координаты вершин многоугольника
                [myTL, myTR, myBR, myBL]
            ],
            // описываем свойства геообъекта
            {
                clickable: false, // клики внутри полигона чтоб работали
            }, {
                // описываем опции геообъекта
                // фоновое изображение.
                fillImageHref: 'data:image/svg+xml;utf8,' + encodeURIComponent(getSVG), // '/public/uploads/aclineMap.svg'
                // тип заливки фоном
                fillMethod: 'stretch',
                // убираем видимость обводки
                stroke: false,
                fillOpacity: 1,
                // цвет обводки.
                strokeColor: '#0000FF',
                // ширина обводки
                strokeWidth: 2,
                clickable: false, // клики внутри полигона чтоб работали
                zIndex: -1,
            }
        );

        // добавляем многоугольник на карту
        //myMap.geoObjects.add(myPolygonNearObjects);

        mmCollectionLamp.add(myPolygonNearObjects);
    }

</script>
