<script type="text/javascript">

    // события обьектов карты

    // ---------------------------------------------------------------
    // обработчик клика на обьекте коллекции данной ЛЭП
    function funOnClickEventCollection(e) {

        // текущий обьект
        let myObject = mmObjects[e.get('target').properties.get('id')];

        switch (myObject.mapType) {
            case 'placemark':
                // обработчик клика на обьекте коллекции данной ЛЭП - клик на точке
                funOnClickEventCollectionPlacemark(myObject);
                break;
            case 'polyline':
                // обработчик клика на обьекте коллекции данной ЛЭП - клик на линии
                funOnClickEventCollectionPolyline(myObject);
                break;
        }
    }

    // ---------------------------------------------------------------
    // обработчик клика на обьекте коллекции данной ЛЭП - клик на точке
    function funOnClickEventCollectionPlacemark(getObject) {

        // переопределить ID текущей и прошлой точки, показать детали, подсветить активную, создать иконку svg
        funChangeLastCurrentPlacemark(getObject.mapID);

        // удержана клавиша Ctrl или Alt
        if (window.CtrlDown || window.AltDown) {
            // соединить линией

            // образец обьекта карты с полями по-умолчанию
            let myPolyline = new ObjectOne();
            myPolyline.type = String(mmDefaulPolylineType);
            myPolyline.startMapID = mmLastPlacemarkMapID;
            myPolyline.endMapID = mmCurrentPlacemarkMapID;
            // добавить одну линию на карту
            funPolylineAdd(myPolyline);
        }

        // удержана клавиша Shift
        if (window.ShiftDown) {

            // добавить обьект во множественный выбор
            funClipboardAdd(getObject.mapID);
        }

        if (mmObjects[getObject.mapID].towerInfo != null) {
            // запросить изображение на модель

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                url: '/api/getModelRecords',
                //async: false,
                data: {
                    modelName: 'Towerinfo',
                    modelID: getObject.towerInfo,
                },
                method: "post",
            }).done(function (result) {
                //console.log("запрошенное изображение модели:");
                //console.log(result);

                // изображение марки опоры
                let myPhoto = '';
                if (result.img !== '') {

                    // вариант с миниатуюрой
                    //myPhoto = myImg.split('.jpeg')[0]; // до расширения
                    //myPhoto += '_hd.jpeg';

                    myPhoto += "<div>";
                    myPhoto += "<a class='image' data-fancybox='gallery' href='/public/uploads/models/towerinfo/" + getObject.towerInfo + '/' + result.img + "'>";
                    myPhoto += "<img src='/public/uploads/models/towerinfo/" + getObject.towerInfo + "/" + result.img + "' class='balloon_photo' onerror='this.style.display = &quot;none&quot;'>";
                    myPhoto += "</a>";
                    myPhoto += "</div>";
                }

                // вывести в балун точки
                let myBalloonContentBody =
                    myPhoto +
                    '<br>' +
                    'Наименование: ' + (result.name === '' ? '-' : result.name) +
                    '<br>' +
                    'Обозначение: ' + (result.album === '' ? '-' : result.album) +
                    '</div>';

                mmCollection.get(getObject.mapID).properties.set('balloonContentHeader', result.mark);
                mmCollection.get(getObject.mapID).properties.set('balloonContentBody', myBalloonContentBody);
                //mmCollection.get(getObject.mapID).properties.set('balloonContentFooter', 'подвал');
                // принудительно открыть надо в коллекции
                mmCollection.get(getObject.mapID).balloon.open();
            });
        } else {
            // let myText = '<div class="ya_map">' +
            //     getObject.viewName +
            //     '</div>';
        }
    }

    // ---------------------------------------------------------------
    // обработчик клика на обьекте коллекции данной ЛЭП - клик на линии
    function funOnClickEventCollectionPolyline(getObject) {

        // выбор, что подсветить, сегмент или только пролет
        let myNeedSelectSpan = false;
        let myNeedSelectSegment = false;

        // разбивка линии на сегменты
        funSegments();
        if (mmSegments.length > 0) {

            // узнать в каком сегменте находится элемент, на котором кликнули
            mmSegments.forEach(function (item, i) {
                item.forEach(function (item2) {
                    if (getObject.mapID === item2) {
                        mmLastSegmentMapID = mmCurrentSegmentMapID;
                        mmCurrentSegmentMapID = i;
                    }
                });
            });
        }

        if ($('#dRBSegment').css('display') === 'none') {
            // детали сегмента еще не показаны
            myNeedSelectSegment = true;
        } else {
            // детали сегмента уже показаны
            if (mmLastSegmentMapID === mmCurrentSegmentMapID) {
                // кликнули снова в тот же сегмент
                myNeedSelectSpan = true;
            } else {
                // кликнули на другой сегмент
                myNeedSelectSegment = true;
            }
        }

        // подсветить весь сегмент
        if (myNeedSelectSegment) {
            // показать детали выбранного сегмента
            funRBviewSegment(getObject.type);
            // подсветить или убрать подстветку с активного сегмента на карте
            funSegmentActive();
        }

        // подсветить только один пролет
        if (myNeedSelectSpan) {
            // обновить текущий обьект
            mmLastPolylineMapID = mmCurrentPolylineMapID;
            mmCurrentPolylineMapID = getObject.mapID;
            // показать детали выбранной линии
            funRBviewPolyline(getObject);
            // подсветить/убрать подстветку с активной линии на карте
            funPolylineActive();
        }
    }

    // ---------------------------------------------------------------
    // обработчик добавления нового обьекта для данной ЛЭП
    function funOnAddEventCollection(e) {

        // текущий обьект
        let myObject = mmObjects[e.get('child').properties.get('id')];
        if (myObject.mapType === "placemark") {

            // переопределить ID текущей и прошлой точки, показать детали, подсветить активную, создать иконку svg
            funChangeLastCurrentPlacemark(myObject.mapID);

            // подписываемся на событие изменения положения точки (одной из вершин)
            mmCollection.get(myObject.mapID).geometry.events.add('change', function (e) {

                // новые координаты
                let myNewCoords = e.get('newCoordinates');
                // переопределить координат точки и всех линии, в которой возможно участвовала эта перетаскиваемая точка
                funPlacemarkMoveAndDragend(myObject, myNewCoords);
            });

            // подписываемся на завершение перемещения
            mmCollection.get(myObject.mapID).events.add('dragend', function (e) {

                // переопределить ID текущей и прошлой точки, показать детали, подсветить активную, создать иконку svg
                funChangeLastCurrentPlacemark(myObject.mapID);

                // записать шаг в историю
                funHistoreSave();
            });
        }

        // линии освещения поверх карты
        funLamp();
        // линии совместного подвеса поверх карты
        funDoubleAcline();
    }

    // ---------------------------------------------------------------
    // переопределить координат точки и всех линии, в которой возможно участвовала эта перетаскиваемая точка
    function funPlacemarkMoveAndDragend(getObject, getNewCoords) {

        //console.log("Новые координаты при перетаскивании:");
        //console.log(getNewCoords);

        // записать в массив для самой точки
        mmObjects[getObject.mapID].lat = getNewCoords[0];
        mmObjects[getObject.mapID].long = getNewCoords[1];

        // создать иконку svg для самой точки (например, разьединитель чтоб был параллельно линиии)
        funSVGmake(getObject.mapID);
        // линии освещения поверх карты
        funLamp();
        // линии совместного подвеса поверх карты
        funDoubleAcline();

        // найти линии, где упоминается эта точка, в конце или в начале
        mmObjects.forEach(function (item) {

            // поиск только в неудаленных
            if (item.deleted === false) {

                if (item.startMapID === getObject.mapID || item.endMapID === getObject.mapID) {

                    // для участвующей линии в начале
                    if (item.startMapID === getObject.mapID) {
                        // перемещение начала точки

                        // записать в карту
                        mmCollection.get(item.mapID).geometry.set(0, getNewCoords);

                        // записать в массив
                        mmObjects[item.mapID].points = mmCollection.get(item.mapID).geometry.getCoordinates();

                        // имя и всплывающий хинт у линии
                        item = funApplyPolylineHint(item);
                    }

                    // для участвующей линии в конце
                    if (item.endMapID === getObject.mapID) {
                        // перемещение конца точки

                        // записать в карту
                        // характерные точки для этой линии
                        let myPoints = mmCollection.get(item.mapID).geometry.getCoordinates();
                        mmCollection.get(item.mapID).geometry.set((myPoints.length - 1), getNewCoords);

                        // записать в массив
                        mmObjects[item.mapID].points = mmCollection.get(item.mapID).geometry.getCoordinates();

                        // имя и всплывающий хинт у линии
                        item = funApplyPolylineHint(item);
                    }
                }
            }
        });
    }

    // ---------------------------------------------------------------
    // обработчик клика на обьекте коллекции другой ЛЭП
    function funOnClickEventCollectionOther(e) {

        // // текущий обьект
        // let myObjectOtherFromMap = e.get('target');
        // let myObjectOtherMapID = myObjectOtherFromMap.properties.get('mapID');
        // let myObjectOther = mmObjectsOther[myObjectOtherMapID];
        //
        // console.log('Событие клика на опоре из другой линии:');
        // console.log(myObjectOther);
        //
        // if (myObjectOther.type === 'tower') {
        //     // это опора
        // }
    }

</script>
