<script type="text/javascript">

    // показывать ли другие обьекты рядом в видимой области карты

    // ---------------------------------------------------------------
    // клик на переключателе
    function funChMapViewNearObjects() {

        // записать значение переключателя в глобальную переменную
        mmChMapViewNearObjects = ($('#chMapViewNearObjects').is(':checked')) ? 1 : 0;

        if (mmChMapViewNearObjects === 1) {
            // да, показывать нужно
            // отрисовка других точек - запрос в базу и отображение на карте
            funNearObjectsLoad();
        } else {
            // нет, показывать не нужно
            // удалить все прочие обьекты
            funNearObjectsDelete();
        }
    }

    // ---------------------------------------------------------------
    // отрисовка других точек - запрос в базу и отображение на карте
    function funNearObjectsLoad() {

        // получить список ближайших обьектов в видимой области карты
        if (mmChMapViewNearObjects === 0) {
            // нет, показывать не нужно
            // досрочный выход
            return;
        }

        // сообщение пользователю
        toastr.info('Получение других обьектов для карты...');

        // ID текущей ЛЭП
        let myAclineID = Number($('#sRBAclineID').text());
        // координаты видимой области карты
        let myBounds = myMap.getBounds();

        let myUrl = '{{ route('acline.map.loadNearObjects') }}';
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            url: myUrl,
            data: {
                aclineID: myAclineID,
                bounds: myBounds,
            },
            method: "post",
        }).done(function (result) {
            // для отладки
            console.log("Другие обьекты с бекенда:");
            console.log(result);

            // добавление других обьектов на карту
            funNearObjectsView(result);

            // сообщение пользователю
            toastr.success('Другие обьекты для карты получены...');
        });
    }

    // ---------------------------------------------------------------
    // добавление других обьектов на карту
    function funNearObjectsView(getObjectsOther = null) {

        // текущий масштаб карты
        let myScaleMap = myMap.getZoom();
        let myScaleSVG = 0.5; // приемлимый масштаб
        // изменяемость от масштаба
        if (myScaleMap > 14 && myScaleMap <= 15) myScaleSVG = 0.8;
        if (myScaleMap > 15 && myScaleMap <= 16) myScaleSVG = 1.2;
        if (myScaleMap > 16 && myScaleMap <= 17) myScaleSVG = 1.5;
        if (myScaleMap > 17 && myScaleMap <= 18) myScaleSVG = 2;
        if (myScaleMap > 18 && myScaleMap <= 20) myScaleSVG = 2.5;
        if (myScaleMap > 20) myScaleSVG = 5;

        //console.log("myScaleMap = " + myScaleMap);
        //console.log("myScaleSVG = " + myScaleSVG);

        // иконки для прочих обьектов
        let myIconLayoutBegin = '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" ';

        // ТП
        let myIconLayoutViewBoxSubstation = 'viewBox = "0 0 30 15" width = "' + (30 * myScaleSVG) + '" height = "' + (15 * myScaleSVG) + '" >'; // style="margin-top:4px; margin-left:4px;"
        let myIconLayoutFigureSubstation = '<rect width="30" height="15" x="-15" y="-7" fill="#999"/>';
        let myIconLayoutSubstation = ymaps.templateLayoutFactory.createClass(
            myIconLayoutBegin +
            myIconLayoutViewBoxSubstation +
            myIconLayoutFigureSubstation +
            '</svg>'
        );

        // опора
        let myIconLayoutViewBoxTower = 'viewBox = "0 0 10 10" width = "' + (10 * myScaleSVG) + '" height = "' + (10 * myScaleSVG) + '" style = "margin-left: ' + (-5) + 'px; margin-top: ' + (-5) + 'px;">'; // border: solid red 1px;
        let myIconLayoutFigureTower = '<circle r="3" cx="3" cy="3" fill="#999"/>';
        let myIconLayoutTower = ymaps.templateLayoutFactory.createClass(
            myIconLayoutBegin +
            myIconLayoutViewBoxTower +
            myIconLayoutFigureTower +
            '</svg>'
        );

        // удалить все прочие обьекты
        funNearObjectsDelete();
        // сбросить порядковый номер для связки массива с обьектом карты
        let myNN = 0;

        // добавить ТП (реальное, не фиктивное)
        if (false) {
            if (typeof (getObjectsOther.substations) !== 'undefined' && getObjectsOther.substations.length > 0) {
                (getObjectsOther.substations).forEach(function (item) {

                    myNN++;

                    // новый обьект карты
                    let myPlacemark = new ymaps.Placemark(
                        [item.lat, item.long],
                        {
                            // сторонние пользовательские данные
                            mapID: myNN,

                            hintContent: item.viewName,
                            iconContent: item.viewName,
                        },
                        {
                            draggable: false, // можно ли передвигать
                            hideIconOnBalloonOpen: false, // балун открывается, метка при этом не закрывается
                            iconLayout: myIconLayoutSubstation,
                            iconShape: {
                                // круг описывается в виде центра и радиуса для активного клика
                                type: 'Circle',
                                coordinates: [0, 0],
                                radius: 10 * myScaleSVG,
                            },
                        }
                    );
                    // записать в коллекцию карты
                    mmCollectionOther.add(myPlacemark);

                    // образец обьекта карты с полями по-умолчанию
                    let myObjectOther = new ObjectOne();
                    myObjectOther.mapID = myNN;
                    myObjectOther.dbID = item.id;
                    myObjectOther.dbIOID = item.identifiedobject_id;
                    myObjectOther.mapType = 'placemark';
                    myObjectOther.type = 'substation';
                    myObjectOther.name = item.name;
                    myObjectOther.localName = item.name;
                    myObjectOther.address = item.name;
                    myObjectOther.viewName = item.name;
                    myObjectOther.hint = item.name;
                    myObjectOther.lat = item.lat;
                    myObjectOther.long = item.long;

                    // записать все поля обьекта в массив
                    mmObjectsOther[myObjectOther.mapID] = {};
                    for (key in myObjectOther) {
                        mmObjectsOther[myObjectOther.mapID][key] = myObjectOther[key];
                    }
                    mmObjectsOther[myObjectOther.mapID]['objectMap'] = myPlacemark;
                });
            }
        }

        // добавить опоры и фиктивные ТП
        if (typeof (getObjectsOther.towers) !== 'undefined' && getObjectsOther.towers.length > 0) {
            (getObjectsOther.towers).forEach(function (item) {

                myNN++;

                // для балуна
                let myBalloonContentHeader = '';
                let myBalloonContentBody = '';
                let myBalloonContentFooter = '';
                if (item.fict_tp === 0) {
                    // это опора, а не фиктивное ТП

                    // фото для балуна
                    let myPhoto = '';
                    let myPhotosArr = (typeof (item.photos) !== null && item.photos !== '' && item.photos !== '[]') ? JSON.parse(item.photos) : [];
                    if (typeof (myPhotosArr[0]) != 'undefined') {

                        myPhoto += "<div>";
                        myPhoto += "<a class='image' data-fancybox='gallery' href='" + mmPathTowerImages + myPhotosArr[0] + "'>";
                        myPhoto += "<img src='" + mmPathTowerImages + myPhotosArr[0] + "' class='balloon_photo' onerror='this.style.display = &quot;none&quot;'>";
                        myPhoto += "</a>";
                        myPhoto += "</div>";
                    }

                    // информация для балуна
                    myBalloonContentHeader = '<div>' + item.viewName + '</div>';
                    myBalloonContentBody =
                        '<div class="row">' +
                        (myPhoto === '' ? '' : '<div class="col-lg-6">' + myPhoto + '</div>') +
                        '<div class="col-lg-6">' +
                        'Фото:&nbsp;' + (myPhotosArr.length > 0 ? myPhotosArr.length + ' шт.' : '-') +
                        '<br>' +
                        'Марка:&nbsp;' + (typeof (item.towerInfoObject['name']) === 'undefined' ? '-' : item.towerInfoObject['name']) +
                        '<br>' +
                        'Материал:&nbsp;' + (typeof (item.towerMaterialObject['name']) === 'undefined' ? '-' : item.towerMaterialObject['name']) +
                        '<br>' +
                        'Назначение:&nbsp;' + (typeof (item.towerKindObject['name']) === 'undefined' ? '-' : item.towerKindObject['name']) +
                        '<br>' +
                        'Конструкция:&nbsp;' + (typeof (item.towerConstructionObject['name']) === 'undefined' ? '-' : item.towerConstructionObject['name']) +
                        '<br>' +
                        'Линия(-и):&nbsp;' + (item.aclinesObject['count'] > 0 ? item.aclinesObject['text'] : '-') +
                        '</div>' +
                        '</div>';
                    myBalloonContentFooter = 'Добавить эту опору как совместную:' +
                        '<br><button type="button" class="btn btn-primary" onclick="funAddTowerFromOther(' + myNN + ', &quot;new&quot;)">новую</button>' +
                        '<button type="button" class="btn btn-primary" onclick="funAddTowerFromOther(' + myNN + ', &quot;merge&quot;)">обьединив с текущей</button>';
                }

                // новый обьект карты
                let myPlacemark = new ymaps.Placemark(
                    [item.lat, item.long],
                    {
                        // сторонние пользовательские данные
                        mapID: myNN,

                        iconContent: item.viewName,
                        hintContent: item.viewName,
                        balloonContentHeader: myBalloonContentHeader,
                        balloonContentBody: myBalloonContentBody,
                        balloonContentFooter: myBalloonContentFooter,
                    }, {
                        draggable: false, // можно ли передвигать
                        hideIconOnBalloonOpen: false, // балун открывается, метка при этом не закрывается
                        iconLayout: (item.fict_tp === 0) ? myIconLayoutTower : myIconLayoutSubstation,
                        iconShape: {
                            // круг описывается в виде центра и радиуса для активного клика
                            type: 'Circle',
                            coordinates: [0, 0],
                            radius: 10 * myScaleSVG,
                        },
                    }
                );
                // записать в коллекцию карты
                mmCollectionOther.add(myPlacemark);

                // образец обьекта карты с полями по-умолчанию
                let myObjectOther = new ObjectOne();
                myObjectOther.mapID = myNN;
                myObjectOther.dbID = item.id;
                myObjectOther.dbIOID = item.identifiedobject_id;
                myObjectOther.mapType = 'placemark';
                myObjectOther.type = (item.fict_tp === 0) ? 'tower' : 'substation';
                myObjectOther.name = item.viewName;
                myObjectOther.localName = item.viewName;
                myObjectOther.address = item.viewName;
                myObjectOther.viewName = item.viewName;
                myObjectOther.hint = item.viewName;
                myObjectOther.lat = item.lat;
                myObjectOther.long = item.long;
                myObjectOther.towerMaterial = item.towermaterial_id;
                myObjectOther.towerKind = item.towerkind_id;
                myObjectOther.towerInfo = item.towerinfo_id;
                myObjectOther.towerConstruction = item.towerconstructionkind_id;
                myObjectOther.propN = item.propn;
                myObjectOther.guy = item.guy;
                myObjectOther.strut = item.strut;
                myObjectOther.strutN = item.strutn;
                myObjectOther.annex = item.annex;
                myObjectOther.eqOtherLine = item.eqotherline;
                myObjectOther.eqCommLine = item.eqcommline;
                myObjectOther.eqLamp = item.eqlamp;
                myObjectOther.eqAdapter = item.eqadapter;
                myObjectOther.eqAccident = item.eqaccident;
                myObjectOther.eqNoUp = item.eqnoup;
                myObjectOther.photos = (typeof (item.photos) !== null && item.photos !== '' && item.photos !== '[]') ? JSON.parse(item.photos) : [];

                // записать все поля обьекта в массив
                mmObjectsOther[myObjectOther.mapID] = {};
                for (key in myObjectOther) {
                    mmObjectsOther[myObjectOther.mapID][key] = myObjectOther[key];
                }
                mmObjectsOther[myObjectOther.mapID]['objectMap'] = myPlacemark;
            });
        }

        // добавить пролеты 701
        if (typeof (getObjectsOther.spans) !== 'undefined' && getObjectsOther.spans.length > 0) {
            (getObjectsOther.spans).forEach(function (item) {

                myNN++;

                // цвет линии
                switch (Number(item.voltage_id)) {
                    case 6:
                        myStrokeColor = '#06ff00';
                        break;
                    case 10:
                        myStrokeColor = '#ff0000';
                        break;
                    case 35:
                        myStrokeColor = '#f600ff';
                        break;
                    default:
                        // 0.4
                        myStrokeColor = '#ffba00';
                }

                // имя и всплывающий хинт
                let myHint = item.name_start + ' - ' + item.name_end + ' (' + item.name_acline + ')';

                // новый обьект карты
                let myPolyline = new ymaps.Polyline(
                    [
                        [item.lat_start, item.long_start],
                        [item.lat_end, item.long_end]
                    ],
                    properties =
                        {
                            // сторонние пользовательские данные
                            mapID: myNN,

                            hintContent: myHint,
                            balloonContentHeader: '<div>' + item.name_acline + '</div>',
                            balloonContentBody: '',
                            balloonContentFooter: '<a href="/admin/acline/map/edit/' + item.acline_id + '" target="_blank" class="btn btn-primary">Перейти к линии</a>',
                        },
                    options =
                        {
                            draggable: false, // можно ли передвигать
                            drawing: true,
                            strokeColor: myStrokeColor,
                            strokeWidth: 2,
                        }
                );
                // записать в коллекцию карты
                mmCollectionOther.add(myPolyline);

                // образец обьекта карты с полями по-умолчанию
                let myObjectOther = new ObjectOne();
                myObjectOther.mapID = myNN;
                myObjectOther.dbID = item.id;
                myObjectOther.dbIOID = item.identifiedobject_id;
                myObjectOther.mapType = 'polyline';
                myObjectOther.type = 701;
                myObjectOther.hint = myHint;
                myObjectOther.points = (typeof (item.points) !== null && item.points !== '' && item.points !== '[]') ? JSON.parse(item.points) : [];
                myObjectOther.gabarit = item.gabarit;

                // записать все поля обьекта в массив
                mmObjectsOther[myObjectOther.mapID] = {};
                for (key in myObjectOther) {
                    mmObjectsOther[myObjectOther.mapID][key] = myObjectOther[key];
                }
                mmObjectsOther[myObjectOther.mapID]['objectMap'] = myPolyline;
            });
        }
    }

    // ---------------------------------------------------------------
    // удалить все прочие обьекты
    function funNearObjectsDelete() {

        // очистить массив
        mmObjectsOther = [];
        // очистить коллекцию
        mmCollectionOther.removeAll();
    }

    // ---------------------------------------------------------------
    // добавить опору из другой ЛЭП
    function funAddTowerFromOther(getMapID, getRegim) {

        // закрыть все балуны
        myMap.balloon.close();

        // получить обьект добавления с другой линии
        let myObjectOther = funAddTowerFromOther_MakeObject(getMapID);
        console.log("Обьект добавления с другой линии:");
        console.log(myObjectOther);

        switch (getRegim) {
            case 'new':
                // новая

                funPlacemarkAdd(myObjectOther);

                break;
            case 'merge':
                // обьединить

                // текущий обьект на карте (выделен красным)
                if (mmCurrentPlacemarkMapID === null) {
                    alert('Извините, текущий обьект на карте еще не определен! \n Сперва укажите его, пожалуйста!');
                    // досрочный выход
                    return;
                }

                // присвоить текущему обьекту на карте данные опоры с другой линии
                // записать в массив
                for (key in myObjectOther) {

                    // если поле - фото
                    if (key === 'photos') {
                        // фото не заменить, а пополнить

                        // фото, которые были
                        let myPhotosOld = mmObjects[mmCurrentPlacemarkMapID]['photos'];
                        // фото, которые хочу взять для обновления
                        let myPhotosNew = myObjectOther['photos'];
                        let myPhotosKey = {};
                        myPhotosOld.concat(myPhotosNew).forEach(function (item) {
                            myPhotosKey[item] = true;
                        });
                        let myPhotosUnique = Object.keys(myPhotosKey);
                        // вставить их обратно в черновик
                        mmObjects[mmCurrentPlacemarkMapID][key] = myPhotosUnique;

                        console.log("Фото после обьединения опор:");
                        console.log(myPhotosUnique);

                        // повторная иттерация цикла к другому полю
                        continue;
                    }

                    // все другие поля
                    mmObjects[mmCurrentPlacemarkMapID][key] = myObjectOther[key];
                }

                // обновленный обьект
                let myObject = mmObjects[mmCurrentPlacemarkMapID];
                console.log("Опора после обьединения:");
                console.log(myObject);

                // записать в карту (связынные с этой точкой линии сами по событию перерисуются)
                mmCollection.get(myObject.mapID).geometry.setCoordinates([myObject.lat, myObject.long]);

                // показать детали выбранной точки или множественного списка, показать детали, подсветить активную, создать иконку svg
                funRBviewPlacemark(myObject);

                // открытие модального окна выбора способа добавления опоры - присоедить или поглотить
                // !!! НЕ ИСПОЛЬЗУЕТСЯ - этот шаг пропускаем
                //funModalAddTowerFromOther(getMapID);

                break;
        }
    }

    // ---------------------------------------------------------------
    // получить обьект добавления с другой линии
    function funAddTowerFromOther_MakeObject(getMapID) {
        // текущий обьект
        let myObjectOther = mmObjectsOther[getMapID];

        // дописать поле
        myObjectOther.isDoubleAcline = true;
        myObjectOther.isActive = true;

        // удалить поле
        delete myObjectOther.objectMap;
        delete myObjectOther.mapID;

        // console.log('Опора для совместного подвеса:');
        // console.log("getMapID = " + getMapID);
        // console.log(myObjectOther);

        // возвращаемый параметр
        return myObjectOther;
    }

    // ---------------------------------------------------------------
    // добавить опору из другой ЛЭП - взять другую опору за основу! (присоединить текущую)
    // !!! НЕ ИСПОЛЬЗУЕТСЯ
    function funAddTowerFromOther_MergeOther(getMapID) {
        console.log("взять другую опору за основу! (присоединить текущую)");

        // скрыть модальное окно
        $('#modalMessage').modal("hide");
        // очистить модальное окно
        $('#modalMessageContent').empty();

        // получить обьект добавления с другой линии
        let myObjectOther = funAddTowerFromOther_MakeObject(getMapID);
        console.log("Обьект добавления с другой линии:");
        console.log(myObjectOther);

        // присвоить текущему обьекту на карте данные опоры с другой линии
        // записать в массив
        for (key in myObjectOther) {
            mmObjects[mmCurrentPlacemarkMapID][key] = myObjectOther[key];
        }

        // обновленный обьект
        let myObject = mmObjects[mmCurrentPlacemarkMapID];
        console.log("Опора после обьединения:");
        console.log(myObject);

        // записать в карту (связынные с этой точкой линии сами по событию перерисуются)
        mmCollection.get(myObject.mapID).geometry.setCoordinates([myObject.lat, myObject.long]);

        // показать детали выбранной точки или множественного списка, показать детали, подсветить активную, создать иконку svg
        funRBviewPlacemark(myObject);
    }

    // ---------------------------------------------------------------
    // добавить опору из другой ЛЭП - взять текущую опору за основу! (поглотить другую)
    // !!! НЕ ИСПОЛЬЗУЕТСЯ
    function funAddTowerFromOther_MergeYour(getMapID) {
        console.log("взять текущую опору за основу! (поглотить другую)");

        // скрыть модальное окно
        $('#modalMessage').modal("hide");
        // очистить модальное окно
        $('#modalMessageContent').empty();

        // получить обьект добавления с другой линии
        let myObjectOther = funAddTowerFromOther_MakeObject(getMapID);
        console.log("Обьект добавления с другой линии:");
        console.log(myObjectOther);

        return;

        if (false) {
            let myUrl = '{{ route('acline.map.update') }}';
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                url: myUrl,
                data: {
                    aclineID: myAclineID,
                },
                method: "post",
                success: function (result) {
                    // успешное сохранение
                },
                error: function (jqXHR, exception) {
                    // при сохранении возникла ошибка
                }
            });
        }
    }

</script>
