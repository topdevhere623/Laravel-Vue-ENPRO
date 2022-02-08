<script type="text/javascript">

    // карта как SVG-изображение

    // ---------------------------------------------------------------
    // получить список ближайших обьектов в видимой области карты
    // !!! не используется
    // getCurrentBounds - координаты окна, который предал Яндекс после смещения или смены зума
    function funNearObjects(getCurrentBounds) {

        // получить список ближайших обьектов по дистанции
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            url: '/api/getNearObjects',
            data: {
                currentBounds: getCurrentBounds,
            },
            method: "post",
        }).done(function (result) {
            //console.log("ближайшие обьекты в видимой области карты:");
            //console.log(result);

            // сгенерировать карту подсказку ближайших в видимой области точек, кроме точек, которые есть в массиве mmObjects, в SVG-формате
            //let mySVGMap = funSVGMakeMapOnBoundsWithoutmmObjects(getCurrentBounds, result);
            // наложить SVG-изображение поверх карты
            //funSVGAddToMap(getCurrentBounds, mySVGMap);
            funSVGAddToMap(getCurrentBounds, result);
        });
    }

    // ---------------------------------------------------------------
    // сгенерировать карту подсказку ближайших в видимой области точек, кроме точек, которые есть в массиве mmObjects, в SVG-формате
    // !!! не используется
    function funSVGMakeMapOnBoundsWithoutmmObjects(getCurrentBounds, getArrOnBounds) {

        //console.clear();

        let myWidthMax = 1280; // 1920
        let myHeightMax = 1024; // 1080
        let myScaleSVG = 1;

        let myColorActive = 'red';
        let myColorFill = '#ccc';

        let myFigure = '';
        let myX = '';
        let myY = '';
        let myCurrentLat = '';
        let myCurrentLong = '';
        let myFound = null;

        let myMinLat = getCurrentBounds[0][0];
        let myMaxLat = getCurrentBounds[1][0];
        let myMinLong = getCurrentBounds[0][1];
        let myMaxLong = getCurrentBounds[1][1];

        // отразить по горизонтали (из-за того, что отсчет по Y в SVG идет вниз)
        let temp = myMaxLat;
        myMaxLat = myMinLat;
        myMinLat = temp;

        // для расчета координата
        let pxOneX = (myMaxLong - myMinLong) / (myWidthMax);
        let pxOneY = (myMaxLat - myMinLat) / (myHeightMax);
        if (pxOneX === 0) pxOneX = 1;
        if (pxOneY === 0) pxOneY = 1;
        //console.log("pxOneX: " + pxOneX);
        //console.log("pxOneY: " + pxOneY);

        // текущий масштаб карты
        let myCurrentScaleMap = Number(myMap.getZoom()); // текущий масштаб карты
        // радиус точки
        let myPointR = 3;
        if (myCurrentScaleMap > 14 && myCurrentScaleMap <= 16) myPointR = 5;
        if (myCurrentScaleMap > 16 && myCurrentScaleMap <= 18) myPointR = 8;
        if (myCurrentScaleMap > 18 && myCurrentScaleMap <= 20) myPointR = 15;
        if (myCurrentScaleMap > 20) myPointR = 40;
        console.log('текущий масштаб карты: ' + myCurrentScaleMap);
        console.log('радиус точки: ' + myPointR);

        // крайние точки
        if (getArrOnBounds.length > 0) {
            // да, точки есть

            // нанести на холст SVG
            getArrOnBounds.forEach(function (item) {

                // поиск в mmObjects
                myFound = mmObjects.find(item2 => (item2.deleted = false && item2.lat !== null && item2.long !== null && Number(item2.lat) === Number(item['lat']) && Number(item2.long) === Number(item['long'])));
                //console.log(myFound);
                if (typeof(myFound) === 'undefined') {
                    // такой точки нет
                    myX = (item['long'] - myMinLong) / pxOneX;
                    myY = (item['lat'] - myMinLat) / pxOneY;
                    myFigure += '<circle r="' + myPointR + '" cx="' + myX + '" cy="' + myY + '" fill="' + myColorActive + '" onclick="alert(234234)"/>';
                }
            });
        }

        // холст SVG
        let mySVGMap =
            '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" ' +
            //'<script xlink:href="public/uploads/svgMapScripts.js"/>' +
            //'onload="load(evt)" ' +
            'viewBox = "0 0 ' + myWidthMax + ' ' + myHeightMax + '" ' +
            'width = "' + (myWidthMax * myScaleSVG) + '" ' +
            'height = "' + (myHeightMax * myScaleSVG) + '">' +
            '<g>' + myFigure + '</g>' +
            '</svg>';

        // console.log("Холст SVG:");
        // console.log(mySVGMap);

        // возвращаемый параметр
        return mySVGMap;
    }

    // ---------------------------------------------------------------
    // наложить SVG-изображение поверх карты
    // !!! не используется
    function funSVGAddToMap(getCurrentBounds, getSVGMap) {

        // координаты углов
        let myLatT = getCurrentBounds[0][0];
        let myLatB = getCurrentBounds[1][0];
        let myLongL = getCurrentBounds[0][1];
        let myLongR = getCurrentBounds[1][1];

        let myTL = [myLatT, myLongL];
        let myTR = [myLatT, myLongR];
        let myBR = [myLatB, myLongR];
        let myBL = [myLatB, myLongL];


        if (typeof(myPolygonNearObjects) !== 'undefined') myMap.geoObjects.remove(myPolygonNearObjects);

        // новый полигон
        myPolygonNearObjects = new ymaps.Polygon([
                // координаты вершин многоугольника
                [myTL, myTR, myBR, myBL]
            ],
            // описываем свойства геообъекта
            {
            }, {
                // описываем опции геообъекта
                // фоновое изображение.
                fillImageHref: 'data:image/svg+xml;utf8,' + encodeURIComponent(getSVGMap), // '/public/uploads/aclineMap.svg'
                // тип заливки фоном
                fillMethod: 'stretch',
                // убираем видимость обводки
                stroke: false,
                fillOpacity: 0.3,
                // цвет обводки.
                strokeColor: '#0000FF',
                // ширина обводки
                strokeWidth: 2,
                zIndex: 999999999999
            }
        );
        // добавляем многоугольник на карту
        myMap.geoObjects.add(myPolygonNearObjects);
        // подпишемся на событие клика на полигоне
        myPolygonNearObjects.events.add('click', function (e) {
            console.log("Событие клика на полигоне");

            // закрыть все балуны
            myMap.balloon.close();
            // записать новые координаты
            mmCurrentCoords = e.get('coords');

            console.log("Координаты:");
            console.log(mmCurrentCoords);

            // показать детали маркера предполагаемой новой точки
            funRBviewMarkerNewPoint();
        });

        //console.log("Вершины полигона:");
        //console.log(myTL);
        //console.log(myTR);
        //console.log(myBR);
        //console.log(myBL);
    }

    // ---------------------------------------------------------------
    // сгенерировать карту по массиву mmObjects в SVG-формате
    // !!! не используется
    function funSVGMakeMapOnmmObjects(getArr) {

        // let myWidthMin = 1;
        // let myWidthMax = 1280; // 1920
        // let myHeightMin = 1;
        // let myHeightMax = 1024; // 1080
        //
        // let myMinLat = 180;
        // let myMaxLat = -180;
        // let myMinLong = 180;
        // let myMaxLong = -180;
        //
        // let myScaleSVG = 1;
        //
        // let myColorActive = 'red';
        // let myColorFill = '#ccc';
        //
        // let myFigure = '';
        // let x = '';
        // let y = '';
        // let x1 = '';
        // let y1 = '';
        // let x2 = '';
        // let y2 = '';
        // let coordStart = '';
        // let coordEnd = '';
        // let myCurrentLat = '';
        // let myCurrentLong = '';
        //
        // console.clear();
        //
        // // крайние точки
        // getArr.forEach(function (item) {
        //     if (item['mapType'] === 'placemark') {
        //
        //         myCurrentLat = Number(item['lat']);
        //         myCurrentLong = Number(item['long']);
        //
        //         if (myCurrentLat < myMinLat) {
        //             myMinLat = myCurrentLat;
        //         }
        //         if (myCurrentLat > myMaxLat) {
        //             myMaxLat = myCurrentLat;
        //         }
        //         if (myCurrentLong < myMinLong) {
        //             myMinLong = myCurrentLong;
        //         }
        //         if (myCurrentLong > myMaxLong) {
        //             myMaxLong = myCurrentLong;
        //         }
        //     }
        // });
        //
        // // отразить по горизонтали (из-за того, что отсчет по Y в SVG идет вниз)
        // let temp = myMaxLat;
        // myMaxLat = myMinLat;
        // myMinLat = temp;
        //
        // //console.log("Минимальная и максимальная широта: " + myMinLat + ' - ' + myMaxLat);
        // //console.log("Минимальная и максимальная долгота: " + myMinLong + ' - ' + myMaxLong);
        //
        // // для расчета координата
        // let pxOneX = (myMaxLong - myMinLong) / (myWidthMax - myWidthMin);
        // let pxOneY = (myMaxLat - myMinLat) / (myHeightMax - myHeightMin);
        //
        // // нанести на холст SVG
        // getArr.forEach(function (item) {
        //     // точки
        //     if (item['mapType'] === 'placemark') {
        //
        //         x = (item['long'] - myMinLong) / pxOneX + myWidthMin;
        //         y = (item['lat'] - myMinLat) / pxOneY + myHeightMin;
        //         myFigure += '<circle r="1" cx="' + x + '" cy="' + y + '" fill="' + myColorActive + '"/>';
        //     }
        //     // линии
        //     if (item['mapType'] === 'polyline') {
        //         // вершины
        //         x1 = '';
        //         x2 = '';
        //         coordStart = getArr.find(item2 => Number(item2.mapID) === Number(item['startMapID']));
        //         if (typeof(coordStart) != 'undefined') {
        //             x1 = (coordStart['long'] - myMinLong) / pxOneX + myWidthMin;
        //             y1 = (coordStart['lat'] - myMinLat) / pxOneY + myHeightMin;
        //         }
        //         coordEnd = getArr.find(item2 => Number(item2.mapID) === Number(item['endMapID']));
        //         if (typeof(coordStart) != 'undefined') {
        //             x2 = (coordEnd['long'] - myMinLong) / pxOneX + myWidthMin;
        //             y2 = (coordEnd['lat'] - myMinLat) / pxOneY + myHeightMin;
        //         }
        //         if (x1 !== '' && x2 !== '') {
        //             myFigure += '<polyline points="' + x1 + ',' + y1 + ' ' + x2 + ',' + y2 + '" stroke="' + myColorActive + '" stroke-width="2" fill="#fff"/>';
        //         }
        //     }
        // });
        //
        // // холст SVG
        // let mySVGMap =
        //     '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" ' +
        //     'viewBox = "0 0 ' + (myWidthMax) + ' ' + (myHeightMax) + '" ' +
        //     'width = "' + (myWidthMax * myScaleSVG) + '" ' +
        //     'height = "' + (myHeightMax * myScaleSVG) + '">' +
        //     '<g>' +
        //     myFigure +
        //     '</g>' +
        //     '</svg>';
        //
        // console.log("Холст SVG:");
        // console.log(mySVGMap);
        //
        // // возвращаемый параметр
        // let myReturn = new Map([
        //     ['TL', [myMinLat, myMinLong]],
        //     ['TR', [myMinLat, myMaxLong]],
        //     ['BL', [myMaxLat, myMaxLong]],
        //     ['BR', [myMaxLat, myMinLong]],
        //     ['SVGMap', mySVGMap],
        // ]);
        // return myReturn;
    }

    // ---------------------------------------------------------------
    // сохранить карту SVG на диске
    // !!! не используется
    function funSVGSave(getSVGMap) {

        // отправить на бекенд для сохранения на диске
        {{--let myUrl = '{{ route('acline.map.saveSVG') }}';--}}
        {{--$.ajax({--}}
        {{--headers: {--}}
        {{--'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),--}}
        {{--},--}}
        {{--url: myUrl,--}}
        {{--data: {--}}
        {{--SVGMap: getSVGMap,--}}
        {{--},--}}
        {{--method: "post",--}}
        {{--}).done(function (result) {--}}

        {{--//console.log(result.ids);--}}
        {{--console.log("Статус операции: " + result);--}}
        {{--});--}}
    }

</script>
