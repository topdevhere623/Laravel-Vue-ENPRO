<script type="text/javascript">

    // добавление обьекта на карту

    // ---------------------------------------------------------------
    // образец обьекта карты с полями по-умолчанию
    // !!! список полей должен быть одинаков с AclineMapController@mapObjectOne
    function ObjectOne() {

        // конструкторы
        this.deleted = false; // по-умолчанию
        this.mapID = null;
        this.dbID = null;
        this.dbIOID = null;
        this.dbConnectivitycodeID = null;
        this.mapType = null;
        this.type = mmDefaulPlacemarkType; // по-умолчанию

        this.name = null;
        this.localName = null;
        this.address = null;
        this.viewName = null;
        this.hint = null;

        this.lat = null;
        this.long = null;
        this.startMapID = null;
        this.endMapID = null;
        this.points = null;
        this.lineToCustomer = 0; // по-умолчанию - 701, 702, 0 или 1 (воздушный пролет, кабельный участок, нет линии или ввод к Потребителю соответственно) - разная линии рисуется

        this.towerMaterial = mmDefaultPlacemarkTowerMaterialID; // по-умолчанию
        this.towerKind = mmDefaultPlacemarkTowerKindID; // по-умолчанию
        this.towerInfo = null;
        this.towerConstruction = null;

        this.propN = null;
        this.guy = null;
        this.strut = null;
        this.strutN = null;
        this.annex = null;

        this.wireMark = null;
        this.layingCondition = null;
        this.wireS = null;
        this.wireN = null;
        this.wireLength = null;
        this.wirePhaseN = null;
        this.cabelsN = null;
        this.gabarit = null;

        this.eqDisconnectorStart = null;
        this.eqDisconnectorStartInfo = null;
        this.eqDisconnectorEnd = null;
        this.eqDisconnectorEndInfo = null;

        this.eqReklouzerStart = null;
        this.eqReklouzerStartInfo = null;
        this.eqReklouzerEnd = null;
        this.eqReklouzerEndInfo = null;

        this.eqVNaStart = null;
        this.eqVNaStartInfo = null;
        this.eqVNaEnd = null;
        this.eqVNaEndInfo = null;

        this.eqDischarger = null;
        this.eqDischargerInfo = null;
        this.eqOPN = null;
        this.eqOPNInfo = null;
        this.eqGrounding = null;

        this.eqOtherLine = null;
        this.eqCommLine = null;
        this.eqLamp = null;
        this.eqAdapter = null;
        this.eqAccident = null;
        this.eqNoUp = null;

        this.photos = []; // по-умолчанию
        this.spanLength = null;
        this.isActive = false; // по-умолчанию (выделена или нет)

        this.isDoubleAcline = false; // по-умолчанию (участвует ли в совместном подвесе или нет)
        this.aclinesObject = new Map([ // список линий, в которых участвует
            ['count', 0],
            ['text', ''],
            ['IDs', ''],
        ]);
    }

    // ---------------------------------------------------------------
    // добавление нового обьекта после клика на карте
    function funNewObjectAdd() {

        // если текущая точка ТП или Потребитель, то от нее нельзя ничего добавлять дальше
        if (mmCurrentPlacemarkMapID != null && mmObjects.length > 1) {
            if (mmObjects[mmCurrentPlacemarkMapID].type === 'substation' || mmObjects[mmCurrentPlacemarkMapID].type === 'customer') {
                // сообщение Пользователю
                alert('Извините, от ТП и Потребителя нельзя добавлять дальнейшие точки. Измените, пожалуйста, активную точку на опору.');
                // досрочный выход
                return;
            }
        }

        // удалить маркер предполагаемой новой точки
        funMarkerNewPointDelete();

        // образец обьекта карты с полями по-умолчанию
        let myPlacemark = new ObjectOne();
        myPlacemark.type = String($("#sRBNewPlacemarkType").val());
        myPlacemark.lat = mmCurrentCoords[0];
        myPlacemark.long = mmCurrentCoords[1];
        // добавить одну точку на карту
        funPlacemarkAdd(myPlacemark);

        // добавить одну линию на карту
        if (mmLastPlacemarkMapID != null) {
            // предыдущая точка есть - соединить линией

            // образец обьекта карты с полями по-умолчанию
            let myPolyline = new ObjectOne();
            myPolyline.type = String($("#sRBNewPolylineType").val());
            myPolyline.startMapID = mmLastPlacemarkMapID;
            myPolyline.endMapID = mmCurrentPlacemarkMapID;
            // добавить одну линию на карту
            funPolylineAdd(myPolyline);
        }

        console.log("Массив обьектов:");
        console.log(mmObjects);
    }

    // ---------------------------------------------------------------
    // добавить список обьектов на карту
    function funAddObjectsToMapFromArray(getObjects) {

        // очистить карту и глобальные переменные, которые можно обнулять
        funMapClear();

        // показывать/скрыть значек loading
        funAjaxLoadingImg(true);
        // всплывающая подсказка
        toastr.info('Началась загрузка данных из черновика...');
        // признак групповой операции
        mmIsGroupOperation = true;

        // сканировать переданный массив
        if (getObjects != null && getObjects.length > 0) {

            // записать новые значения
            mmObjects = getObjects;

            // сканировать переданный массив
            // учесть, что после сохраненного JSON цифры и логические значения будут как символьные
            mmObjects.forEach(function (item) {

                switch (item.mapType) {
                    case "placemark":
                        // добавить одну точку на карту (ч.2 - создать Placemark)
                        funPlacemarkAddToMap(item);
                        break;
                    case "polyline":
                        // добавить одну линию на карту (ч.2 - создать Polyline)
                        funPolylineAddToMap(item);
                        break;
                }
            });
        }

        // признак групповой операции
        mmIsGroupOperation = false;
        // всплывающая подсказка
        toastr.success('Загрузка данных из черновика завершена...');
        // показывать/скрыть значек loading
        funAjaxLoadingImg(false);
    }

    // ---------------------------------------------------------------
    // добавить одну точку на карту
    function funPlacemarkAdd(getObject) {

        // добавить одну точку на карту (ч.1 - записать в массив)
        let myObject = funPlacemarkAddToArray(getObject);
        // добавить одну точку на карту (ч.2 - создать Placemark)
        funPlacemarkAddToMap(myObject);

        // возвращаемый параметр - присвоенный номер
        return myObject.mapID;
    }

    // ---------------------------------------------------------------
    // добавить одну точку на карту (ч.1 - записать в массив)
    function funPlacemarkAddToArray(getObject) {

        // тип обьекта
        getObject.mapType = 'placemark';
        // id обьекта на карте (из всех обьектов)
        getObject.mapID = mmObjects.length; // +1 не надо, массив с нуля начинается

        // видимое имя
        let myNewViewName = '';
        if (mmCurrentPlacemarkMapID != null) {
            // генерация нового порядкового номера точки от имени родителя
            myNewViewName = funGenerateNameNPlacemark(mmObjects[mmCurrentPlacemarkMapID].viewName);
        } else {
            // определить максимальное кол-во placemark на карте
            let myMaxPlacemarkN = 0;
            mmObjects.forEach(function (item) {
                if (item.mapType === 'placemark') {
                    myMaxPlacemarkN++;
                }
            });
            myNewViewName = (myMaxPlacemarkN === 0) ? 1 : myMaxPlacemarkN;
        }

        if (getObject.name == null || getObject.name === '') getObject.name = String(myNewViewName);
        if (getObject.localName == null || getObject.localName === '') getObject.localName = String(myNewViewName);
        if (getObject.address == null || getObject.address === '') getObject.address = String(myNewViewName);

        // видимое имя для точки от типа - в массиве и на карте + обновить SVG
        getObject = funGetViewNamePlacemark(getObject);

        // записать все поля обьекта в массив
        mmObjects[getObject.mapID] = {};
        for (key in getObject) {
            mmObjects[getObject.mapID][key] = getObject[key];
        }

        // возвращаемый параметр
        return getObject;
    }

    // ---------------------------------------------------------------
    // добавить одну точку на карту (ч.2 - создать Placemark)
    function funPlacemarkAddToMap(getObject) {

        // новый обьект карты
        let myPlacemark = new ymaps.Placemark(
            [getObject.lat, getObject.long],
            properties =
                {
                    id: getObject.mapID,
                    hintContent: getObject.hint,
                    iconContent: getObject.hint, // выводится этот текст рядом скартинкой, если imageWithContent
                },
            options =
                {
                    visible: !getObject.deleted,
                    draggable: !mmChMapViewFix, // можно ли передвигать
                    hideIconOnBalloonOpen: false, // балун открывается, метка при этом не закрывается
                    iconLayout: null,
                    iconShape: {
                        // круг описывается в виде центра и радиуса для активного клика
                        type: 'Circle',
                        coordinates: [0, 0],
                        radius: 10
                    },
                }
        );

        // записать в коллекцию карты
        mmCollection.add(myPlacemark);
    }

    // ---------------------------------------------------------------
    // добавить одну линию на карту
    function funPolylineAdd(getObject) {

        // добавить одну линию на карту (ч.1 - записать в массив)
        let myObject = funPolylineAddToArray(getObject);
        // добавить одну линию на карту (ч.2 - создать Polyline)
        funPolylineAddToMap(myObject);

        // имя и всплывающий хинт у линии
        myObject = funApplyPolylineHint(getObject);
        // идет ли на Потребителя
        myObject = funLineIsToCustomer(getObject);

        // возвращаемый параметр - присвоенный номер
        return myObject.mapID;
    }

    // ---------------------------------------------------------------
    // добавить одну линию на карту (ч.1 - записать в массив)
    function funPolylineAddToArray(getObject) {

        // тип обьекта
        getObject.mapType = 'polyline';
        // id обьекта на карте (из всех обьектов)
        getObject.mapID = mmObjects.length; // +1 не надо, массив с нуля начинается

        // записать все поля обьекта в массив
        mmObjects[getObject.mapID] = {};
        for (key in getObject) {
            mmObjects[getObject.mapID][key] = getObject[key];
        }

        // возвращаемый параметр
        return getObject;
    }

    // ---------------------------------------------------------------
    // добавить одну линию на карту (ч.2 - создать Polyline)
    function funPolylineAddToMap(getObject) {

        // тест координат вершин линии
        getObject.points = funPolylineBeforeAddToMapTest(getObject);

        // новый обьект карты
        let myPolyline = new ymaps.Polyline(
            getObject.points,
            properties =
                {
                    id: getObject.mapID,
                    hintContent: getObject.hint,
                },
            options =
                {
                    visible: !getObject.deleted,
                    draggable: 0, // можно ли передвигать
                    drawing: true,
                    strokeColor: mmObjectsProperties[getObject.lineToCustomer]['strokeColor'],
                    strokeWidth: mmObjectsProperties[getObject.lineToCustomer]['strokeWidth'],
                    strokeStyle: {
                        style: mmObjectsProperties[getObject.lineToCustomer]['strokeStyle-style'],
                        offset: mmObjectsProperties[getObject.lineToCustomer]['strokeStyle-offset']
                    },
                }
        );

        // mmCollection.get(newMapID).editor.startEditing();
        // mmCollection.get(newMapID).editor.startDrawing();

        // записать в коллекцию карты
        mmCollection.add(myPolyline);
    }

    // тест координат вершин линии
    // проверка, чтобы пролет не был оторван от своих вершин (такое возможно, если совместную опору передвинули например)
    // координаты вершины пролета должны соответсвовать его характерным точкам начало/конец
    function funPolylineBeforeAddToMapTest(getObject) {

        // первоначальные координаты
        let myPointOld = getObject.points;

        // координаты вершин
        let myStartGeo = [mmObjects[getObject.startMapID].lat, mmObjects[getObject.startMapID].long];
        let myEndGeo = [mmObjects[getObject.endMapID].lat, mmObjects[getObject.endMapID].long];

        // если массива х.т. не было
        if (getObject.points === null || !Array.isArray(getObject.points)) {
            // вставить пустой массив
            getObject.points = [];
        }

        // если массив пустой
        if ((getObject.points).length === 0) {
            // массив пустой
            (getObject.points).push(myStartGeo, myEndGeo);
        }

        // если вершины отличаются от начала и конца х.т.
        if (Number(getObject.type) === 702) {
            // кабельная линия 702 - дописать точки

            let myStartFound = false;
            let myEndFound = false;
            for (let i = 0; i < (getObject.points).length; i++) {
                if ((getObject.points)[i][0] === myStartGeo[0] && (getObject.points)[i][1] === myStartGeo[1]) {
                    myStartFound = true;
                }
                if ((getObject.points)[i][0] === myEndGeo[0] && (getObject.points)[i][1] === myEndGeo[1]) {
                    myEndFound = true;
                }
            }
            if (myStartFound === false) {
                // нет, еще нет
                // вставить в начало
                (getObject.points).unshift(myStartGeo);
            }
            if (myEndFound === false) {
                // нет, еще нет
                // вставить в начало
                (getObject.points).push(myEndGeo);
            }
        } else {
            // воздушная линия - 701 - заменить на новые точки
            getObject.points = [];
            getObject.points = [myStartGeo, myEndGeo];
        }

        // записать в массив
        mmObjects[getObject.mapID].points = getObject.points;

        //console.log("Тест координат. Характерные точки были:");
        //console.log(myPointOld);
        //console.log("Стали:");
        //console.log(getObject.points);

        // возвращаемый параметр
        return getObject.points
    }

    // ---------------------------------------------------------------
    // видимое имя для точки от типа - в массиве и на карте + обновить SVG
    function funGetViewNamePlacemark(getObject) {

        // записать в обьект
        switch (getObject.type) {
            case "tower":
                getObject.viewName = getObject.localName; // диспетчерский номер
                getObject.hint = getObject.localName;
                break;
            case "substation":
                getObject.viewName = getObject.name; // имя
                getObject.hint = getObject.name;
                break;
            case "customer":
                getObject.viewName = ''; // адрес
                getObject.hint = getObject.address;
                break;
        }

        // записать в массив
        if (typeof (mmObjects[getObject.mapID]) !== 'undefined') {
            mmObjects[getObject.mapID].viewName = getObject.viewName;
            mmObjects[getObject.mapID].hint = getObject.hint;
        }

        // записать в карту
        if (typeof (mmCollection.get(getObject.mapID)) !== 'undefined') {
            mmCollection.get(getObject.mapID).options.set(
                {
                    'hintContent': getObject.hint + 11111,
                    'iconContent': getObject.hint + 22222,
                });
            // надпись в иконке svg
            funSVGmake(getObject.mapID);
        }

        // возвращаемый параметр
        return getObject;
    }

    // ---------------------------------------------------------------
    // переопределить ID текущей и прошлой точки, показать детали, подсветить активную, создать иконку svg
    function funChangeLastCurrentPlacemark(getMapID) {

        // переопределить точки
        mmLastPlacemarkMapID = mmCurrentPlacemarkMapID;
        mmCurrentPlacemarkMapID = getMapID;

        if (mmClipboard.length > 0) {
            // множественный выбор есть
            // с цветом иконок ничего не делаем
        } else {
            // множественного выбора нет

            // пересоздать иконки для активной точки и прошлой (поменять красный цвет между двумя точками)
            if (mmLastPlacemarkMapID != null) {
                // убрать активность
                mmObjects[mmLastPlacemarkMapID].isActive = false;
                // создать иконку svg
                funSVGmake(mmLastPlacemarkMapID);
            }
            if (mmCurrentPlacemarkMapID != null) {
                mmObjects[mmCurrentPlacemarkMapID].isActive = true;
                // создать иконку svg
                funSVGmake(mmCurrentPlacemarkMapID);
            }

            // показать детали выбранной точки или множественного списка, показать детали, подсветить активную, создать иконку svg
            funRBviewPlacemark(mmObjects[getMapID]);
        }
    }

    // ---------------------------------------------------------------
    // имя и всплывающий хинт у линии
    function funApplyPolylineHint(getObject) {

        let getStartMapID = getObject.startMapID;
        let getEndMapID = getObject.endMapID;
        let getWireMark = getObject.wireMark;

        if (getStartMapID != null && getEndMapID != null) {

            // длина пролета/участка
            let myDistance = funGetDistance(getObject);

            // марка провода
            let myWireMark = '';
            if (typeof (getWireMark) !== 'undefined' && getWireMark != null && getWireMark !== '' && typeof (mmSpravs['aclinesegmentinfo']) !== 'undefined') {
                let myWireMarkFind = mmSpravs['aclinesegmentinfo'].find(item => Number(item.id) === Number(getWireMark));
                if (typeof (myWireMarkFind) !== 'undefined') {
                    myWireMark = ' (' + myWireMarkFind.assetinfokey + ')';
                }
            }

            // имя и всплывающий хинт
            let myHint =
                mmObjects[getStartMapID].viewName + ' - ' + mmObjects[getEndMapID].viewName
                + ((myDistance === 0) ? '' : (', ' + myDistance + ' м.'))
                + myWireMark;

            // записать в обьект
            getObject.name = myHint;
            getObject.hint = myHint;
            getObject.spanLength = myDistance;

            // записать в массив
            if (typeof (mmObjects[getObject.mapID]) !== 'undefined') {
                mmObjects[getObject.mapID].name = getObject.name;
                mmObjects[getObject.mapID].hint = getObject.hint;
                mmObjects[getObject.mapID].spanLength = getObject.spanLength;
            }

            // записать в карту
            if (typeof (mmCollection.get(getObject.mapID)) !== 'undefined') {
                mmCollection.get(getObject.mapID).options.set('hintContent', getObject.hint);
            }
        }

        // возвращаемый параметр
        return getObject;
    }

    // ---------------------------------------------------------------
    // проверка является ли линия с Потребителем
    function funLineIsToCustomer(getObject) {

        let myLineIsToCustomer = getObject.type; // 701, 702, 0 или 1 (воздушный пролет, кабельный участок, нет линии или ввод к Потребителю соответственно) - разная линии рисуется

        if (typeof (getObject.startMapID) !== 'undefined' && typeof (getObject.endMapID) !== 'undefined') {

            if (mmObjects[getObject.startMapID].type === 'customer' || mmObjects[getObject.endMapID].type === 'customer') {
                // да, один из концов линии - Потребитель
                myLineIsToCustomer = 1;
            }
        }

        // записать в обьект
        getObject.lineToCustomer = myLineIsToCustomer;

        // записать в массив
        if (typeof (mmObjects[getObject.mapID]) !== 'undefined') {
            mmObjects[getObject.mapID].lineToCustomer = getObject.lineToCustomer;
        }

        // записать в карту
        if (typeof (mmCollection.get(getObject.mapID)) !== 'undefined') {
            mmCollection.get(getObject.mapID).options.set(
                {
                    'strokeColor': mmObjectsProperties[getObject.lineToCustomer]['strokeColor'],
                    'strokeWidth': mmObjectsProperties[getObject.lineToCustomer]['strokeWidth'],
                    'strokeStyle':
                        {
                            'style': mmObjectsProperties[getObject.lineToCustomer]['strokeStyle-style'],
                            'offset': mmObjectsProperties[getObject.lineToCustomer]['strokeStyle-offset'],
                        },
                });
        }

        // возвращаемый параметр
        return getObject;
    }

</script>
