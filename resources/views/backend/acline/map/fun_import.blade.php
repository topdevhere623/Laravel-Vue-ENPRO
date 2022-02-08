<script type="text/javascript">

    // ---------------------------------------------------------------
    // импорт с KML, GPX, XSDE, TRACK
    $(document).on('change', '#importUpload', function (ev) {

        // загруженный файл
        let myFiles = document.getElementById("importUpload").files;
        // имя файла
        let myFileName = myFiles[0].name;
        // расширение
        let myFileExt = ((myFileName).split('.').pop());
        // только имя файла, без расширения
        let myFileNameOnly = myFileName.replace('.' + myFileExt, '');

        // расширение в верхний регистр для сравнения
        myFileExt = myFileExt.toUpperCase();

        // console.log("myFileName = " + myFileName);
        // console.log("myFileExt = " + myFileExt);
        // console.log("myFileNameOnly = " + myFileNameOnly);


        if (myFileExt !== 'KML' && myFileExt !== 'GPX' && myFileExt !== 'XSDE' && myFileExt !== 'XML' && myFileExt !== 'TRACK') {
            // всплывающая подсказка
            toastr.error('Извините, данный формат не поддерживается! Используйте, пожалуйста, файлы с расширенинм [KML], [GPX], [XSDE] или [TRACK]...');
            // досрочный выход
            return;
        }

        // console.log("Filename: " + myFiles[0].name);
        // console.log("Type MIME: " + myFiles[0].type);
        // console.log("Расширение через точку: " + myFileExt);
        // console.log("Size: " + myFiles[0].size + " bytes");

        if (myFiles.length > 0) {

            // всплывающая подсказка
            toastr.info('Начался процесс импорта данных. Дождитесь, пожалуйста, его завершения...');

            // создание формы
            let data = new FormData();
            data.append("file", myFiles[0]);

            let myUrl = '{{ route('acline.map.import') }}';
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                url: myUrl,
                data: data,
                dataType: 'text',
                processData: false, // не обрабатываем файлы
                contentType: false, // так jQuery скажет серверу что это строковой запрос
                method: "POST",
                success: function (result) {
                    // запрос прошел успешно
                    //console.log(result);

                    // признак групповой операции
                    mmIsGroupOperation = true;

                    let document = JSON.parse(result);

                    // режим вставки, как опоры у воздушной линии, или как характерные точки у кабельной
                    if (myFileExt === 'TRACK') {
                        letImportRegim = 'points';
                    } else {
                        letImportRegim = $('input[name=importRegim]:checked').val(); // towers или points
                    }
                    let pointsGeo = [];
                    let myName = '';
                    let myLat = '';
                    let myLong = '';

                    // нужно ли соединиять линией для воздушных линий
                    let myNeedLine = true;
                    if ((myFileExt === 'KML' || myFileExt === 'GPX') && letImportRegim !== 'points') {
                        myNeedLine = confirm('Нужно ли соединять импортируемые точки линиями?');
                    }

                    // нужно ли вставить имя файла в поле названия ЛЭП
                    if (confirm('Заменить ли имя ЛЭП на импортируемое имя файла?')) {
                        // да, вставить имя импортируемого афйла
                        // в поле справа
                        $('#iRBAclineName').val(myFileNameOnly);
                        // в верхний левый угол
                        $('#tAclineName').text((myFileNameOnly).slice(0, 30))
                    }

                    if (myFileExt === 'KML' || myFileExt === 'GPX' || myFileExt === 'TRACK') {

                        let placemarks = null;
                        switch (myFileExt) {
                            case 'KML':
                                placemarks = document.Document['Placemark'];
                                break;
                            case 'GPX':
                                placemarks = document['wpt'];
                                break;
                            case 'TRACK':
                                placemarks = JSON.parse(document);
                                placemarks = placemarks['points'];
                                break;
                        }
                        //console.log(placemarks);
                        if (typeof (placemarks) !== 'undefined' && placemarks != null) {
                            // точки есть
                            // сканировать точки
                            let myPlacemark = null;
                            let myPolyline = null;
                            placemarks.forEach(function (item, i) {
                                //console.log(item);

                                switch (myFileExt) {
                                    case 'KML':
                                        if (typeof (item['name']) !== 'undefined' && typeof (item['Point']) !== 'undefined' && typeof (item['Point']['coordinates']) !== 'undefined') {
                                            myName = item['name'];
                                            let myCoords = (item['Point']['coordinates']).split(','); // разбить на широту и долготу
                                            myLat = myCoords[1];
                                            myLong = myCoords[0];
                                        }
                                        break;
                                    case 'GPX':
                                        if (typeof (item['@attributes']) !== 'undefined' && typeof (item['name']) !== 'undefined') {
                                            myName = item['name'];
                                            myLat = item['@attributes']['lat'];
                                            myLong = item['@attributes']['lon'];
                                        }
                                        break;
                                    case 'TRACK':
                                        if (typeof (item['pt']) !== 'undefined') {
                                            myName = item['pt'];
                                        }
                                        if (typeof (item['gprmc']) !== 'undefined') {
                                            myLat = item['gprmc']['Latitude'];
                                            myLong = item['gprmc']['Longitude'];
                                        }
                                        break;
                                    default:
                                        myName = '';
                                        myLat = '';
                                        myLong = '';
                                }

                                if (myLat !== '' && myLong !== '') {
                                    switch (letImportRegim) {
                                        case 'towers':
                                            // режим добавления опоры для воздушной линии

                                            // образец обьекта карты с полями по-умолчанию
                                            myPlacemark = new ObjectOne();
                                            myPlacemark.lat = myLat;
                                            myPlacemark.long = myLong;
                                            myPlacemark.type = 'tower';
                                            myPlacemark.name = myName;
                                            myPlacemark.localName = myName;
                                            myPlacemark.address = myName;
                                            // добавить одну точку на карту
                                            funPlacemarkAdd(myPlacemark);

                                            if (myNeedLine) {
                                                if (mmLastPlacemarkMapID != null) {

                                                    // образец обьекта карты с полями по-умолчанию
                                                    myPolyline = new ObjectOne();
                                                    myPolyline.startMapID = mmLastPlacemarkMapID;
                                                    myPolyline.endMapID = mmCurrentPlacemarkMapID;
                                                    myPolyline.type = 701;
                                                    // добавить одну линию на карту
                                                    funPolylineAdd(myPolyline);
                                                }
                                            }
                                            break;
                                        case 'points':
                                            // режим добавления характерной точки для кабельной линии
                                            pointsGeo.push([myLat, myLong]);
                                            break;
                                    }
                                }
                            });

                            // если режим характерных точек и больше двух координат, то сперва поставить 2-е точки и добавить линию на карту
                            if (letImportRegim === 'points' && pointsGeo.length > 1) {

                                // поменять начало и конец, чтоб логичнее было нарисовано
                                let myEnd = pointsGeo[0];
                                let myStart = pointsGeo[pointsGeo.length - 1];

                                // исключить начало и конец
                                // pointsGeo = pointsGeo.slice(0, pointsGeo.length - 1);
                                // pointsGeo = pointsGeo.slice(1);

                                myName = 'Begin';
                                // образец обьекта карты с полями по-умолчанию
                                let myPlacemark = new ObjectOne();
                                myPlacemark.lat = myStart[0];
                                myPlacemark.long = myStart[1];
                                myPlacemark.type = 'tower';
                                myPlacemark.name = myName;
                                myPlacemark.localName = myName;
                                myPlacemark.address = myName;
                                // добавить одну точку на карту
                                funPlacemarkAdd(myPlacemark);

                                myName = 'End';
                                // образец обьекта карты с полями по-умолчанию
                                myPlacemark = new ObjectOne();
                                myPlacemark.lat = myEnd[0];
                                myPlacemark.long = myEnd[1];
                                myPlacemark.type = 'tower';
                                myPlacemark.name = myName;
                                myPlacemark.localName = myName;
                                myPlacemark.address = myName;
                                // добавить одну точку на карту
                                funPlacemarkAdd(myPlacemark);

                                // образец обьекта карты с полями по-умолчанию
                                let myPolyline = new ObjectOne();
                                myPolyline.startMapID = mmCurrentPlacemarkMapID;
                                myPolyline.endMapID = mmLastPlacemarkMapID;
                                myPolyline.points = pointsGeo;
                                myPolyline.type = 702;
                                // добавить одну линию на карту
                                funPolylineAdd(myPolyline);
                            }
                        } else {
                            // точек нет
                            // всплывающая подсказка
                            toastr.error('Не получено никаких данных при импорте...');
                            return;
                        }
                    }

                    // импорт из Модуса
                    if (myFileExt === 'XSDE' || myFileExt === 'XML') {

                        // здесь привязка к географическим координатам
                        let arrScale = document.Pages.Page.GeoPasport.Point;
                        // здесь воздушные и кабельные линии, а также поиск Tech=""
                        let arrSDE = document.Pages.Page.SDEObjects.Type;
                        // здесь RTID="" в TechData
                        let arrTechData = Array.isArray(document.TechsRegistry.TechData) ? document.TechsRegistry.TechData : [document.TechsRegistry.TechData];

                        // для расчета координата
                        let x1 = Number(arrScale[0]['@attributes']['SDECoord'].split(' ')[1]);
                        let y1 = Number(arrScale[0]['@attributes']['SDECoord'].split(' ')[0]);
                        let x2 = Number(arrScale[1]['@attributes']['SDECoord'].split(' ')[1]);
                        let y2 = Number(arrScale[1]['@attributes']['SDECoord'].split(' ')[0]);
                        let lat1 = Number(arrScale[0]['@attributes']['GEOCoord'].split(' ')[1]);
                        let long1 = Number(arrScale[0]['@attributes']['GEOCoord'].split(' ')[0]);
                        let lat2 = Number(arrScale[1]['@attributes']['GEOCoord'].split(' ')[1]);
                        let long2 = Number(arrScale[1]['@attributes']['GEOCoord'].split(' ')[0]);

                        let pxOneX = (lat2 - lat1) / (x2 - x1);
                        let pxOneY = (long2 - long1) / (y2 - y1);

                        //console.log("-------------- координаты: ");
                        //console.log(arrScale);
                        //console.log("x1 = " + x1 + ' ' + "y1 = " + y1 + ' ' + "x2 = " + x2 + ' ' + "y2 = " + y2);
                        //console.log("lat1 = " + lat1 + ' ' + "long1 = " + long1 + ' ' + "lat2 = " + lat2 + ' ' + "long2 = " + long2);

                        let arrSDE701 = [];
                        let arrSDE702 = [];
                        let arrSDETowers = [];
                        let arrSDEDisconnectors = [];
                        if (typeof (arrSDE) !== 'undefined') {
                            arrSDE.forEach(function (item) {

                                // воздушная_линия
                                if (typeof (item['@attributes']['ObjectType']) !== 'undefined' && item['@attributes']['ObjectType'] === 'воздушная_линия' && typeof (item['SDE']) !== 'undefined') {
                                    arrSDE701 = item['SDE'];
                                }
                                // кабельная линия
                                if (typeof (item['@attributes']['ObjectType']) !== 'undefined' && item['@attributes']['ObjectType'] === 'кабельная_линия' && typeof (item['SDE']) !== 'undefined') {
                                    arrSDE702 = item['SDE'];
                                }

                                // опора2
                                if (typeof (item['@attributes']['ObjectType']) !== 'undefined' && item['@attributes']['ObjectType'] === 'опора2' && typeof (item['SDE']) !== 'undefined') {
                                    arrSDETowers = item['SDE'];
                                }

                                // разьединитель)
                                if (typeof (item['@attributes']['ObjectType']) !== 'undefined' && item['@attributes']['ObjectType'] === 'разъединитель' && typeof (item['SDE']) !== 'undefined') {
                                    arrSDEDisconnectors = item['SDE'];
                                }

                            });
                        }
                        //console.log("-------------- воздушная линия:");
                        //console.log(arrSDE701);
                        //console.log("-------------- кабельная линия:");
                        //console.log(arrSDE702);
                        //console.log("-------------- опоры:");
                        //console.log(arrSDETowers);

                        // детельное описание по опорам
                        let arrTechDataTowers = [];
                        //console.log("arrTechData");
                        //console.log(arrTechData);
                        if (typeof (arrTechData) !== 'undefined') {
                            arrTechData.forEach(function (item) {

                                // рекурсивная функция сканиирования ветки XML при импорте
                                arrTechDataTowers = funRecursImportTechData(arrTechDataTowers, item);
                                arrTechDataTowers = Array.isArray(arrTechDataTowers) ? arrTechDataTowers : [arrTechDataTowers];
                            });
                        }
                        //console.log("-------------- детельная информация по опорам arrTechDataTowers:");
                        //console.log(arrTechDataTowers);

                        // точки, которые уже нанес на карту (чтобы не было двойников)
                        let myArr = [];

                        // сканировать линии
                        let arrSDEScan = [];
                        for (let myLineN = 1; myLineN <= 2; myLineN++) {

                            let myLineType = null;
                            switch (myLineN) {
                                case 1:
                                    // воздушная линия
                                    arrSDEScan = Array.isArray(arrSDE701) ? arrSDE701 : [arrSDE701];
                                    myLineType = 701;
                                    break;
                                case 2:
                                    // кабельная линия
                                    arrSDEScan = Array.isArray(arrSDE702) ? arrSDE702 : [arrSDE702];
                                    myLineType = 702;
                                    break;
                            }

                            let myTech = null;

                            //console.log("Буду сканировать линию:");
                            //console.log(myLineN);
                            //console.log(myLineType);
                            //console.log(arrSDEScan);

                            if (typeof (arrSDEScan) !== 'undefined') {
                                // сканировать сегменты линии
                                arrSDEScan.forEach(function (item) {

                                    //console.log("item");
                                    //console.log(item);

                                    mmCurrentPlacemarkMapID = null;
                                    mmLastPlacemarkMapID = null;

                                    let points = item['@attributes']['points'].split(',');
                                    //console.log("points");
                                    //console.log(points);

                                    if (typeof (points) !== 'undefined') {
                                        pointsGeo = [];
                                        points.forEach(function (item2) {

                                            // округлить координаты внутри пары
                                            let item2_1 = Math.round(Number(item2.split(' ')[0]));
                                            let item2_2 = Math.round(Number(item2.split(' ')[1]));
                                            item2 = item2_1 + ' ' + item2_2;

                                            // перевести в географические координаты
                                            myLat = (item2_2 - x1) * pxOneX + lat1;
                                            myLong = (item2_1 - y1) * pxOneY + long1;

                                            // записать в массив
                                            if (myLineType === 702) pointsGeo.push([myLat, myLong]);

                                            // проверка, нет ли уже на карте
                                            //console.log("проверка, нетли " + item2 + " уже на карте");
                                            let myInMap = false;
                                            if (typeof (myArr) !== 'undefined') {
                                                myArr.forEach(function (item5) {
                                                    if (item5['coords'] === item2) {
                                                        myInMap = true;
                                                        mmLastPlacemarkMapID = mmCurrentPlacemarkMapID;
                                                        mmCurrentPlacemarkMapID = item5['mapID'];
                                                        //console.log("да, она есть. Буду вести линию по точкам");
                                                        //console.log("mmLastPlacemarkMapID = " + mmLastPlacemarkMapID);
                                                        //console.log("mmCurrentPlacemarkMapID = " + mmCurrentPlacemarkMapID);
                                                    }
                                                });
                                            }

                                            if (myInMap === false) {
                                                //console.log(item2 + ' - такая точка еще не была');
                                                // искать в опорах2 Tech
                                                if (typeof (arrSDETowers) !== 'undefined') {
                                                    arrSDETowers.forEach(function (item3) {

                                                        // если округлять координаты
                                                        let item3_1 = Math.round(Number(item3['@attributes']['origin'].split(' ')[0]));
                                                        let item3_2 = Math.round(Number(item3['@attributes']['origin'].split(' ')[1]));

                                                        if (item2_1 === item3_1 && item2_2 === item3_2) {
                                                            myTech = item3['@attributes']['Tech'];
                                                            //console.log("Tech = " + myTech);

                                                            // искать в RTID TechData
                                                            //console.log("arrTechDataTowers");
                                                            //console.log(arrTechDataTowers);
                                                            if (typeof (arrTechDataTowers) !== 'undefined') {
                                                                arrTechDataTowers.forEach(function (item4) {
                                                                    if (item4['@attributes']['className'] === 'Tower2' && item4['@attributes']['RTID'] === myTech) {

                                                                        myName = item4['@attributes']['DispName'];
                                                                        myName = myName.replace('RUS', '');
                                                                        //console.log("myName = " + myName);

                                                                        // дополнительные данные (если есть)
                                                                        // адрес
                                                                        let myAddress = null;

                                                                        // материал опоры
                                                                        let myTowerMaterial = null;
                                                                        if (typeof (item4['@attributes']['material']) !== 'undefined') {
                                                                            switch (item4['@attributes']['material']) {
                                                                                case "concrete":
                                                                                    myTowerMaterial = 3;
                                                                                    break;
                                                                                case "wood":
                                                                                    myTowerMaterial = 1;
                                                                                    break;
                                                                                case "metall":
                                                                                    myTowerMaterial = 4;
                                                                                    break;
                                                                                default:
                                                                                    myTowerMaterial = 3;
                                                                            }
                                                                        } else {
                                                                            myTowerMaterial = mmDefaultPlacemarkTowerMaterialID;
                                                                        }

                                                                        // назначение опоры
                                                                        let myTowerKind = null;
                                                                        if (typeof (item4['@attributes']['TowerLocation']) !== 'undefined') {
                                                                            switch (item4['@attributes']['TowerLocation']) {
                                                                                case "angle":
                                                                                    // угловая
                                                                                    myTowerKind = 3;
                                                                                    break;
                                                                                case "TJoint":
                                                                                    // Ответвительная
                                                                                    myTowerKind = 6;
                                                                                    break;
                                                                                case "transposition":
                                                                                    // траспозиционная
                                                                                    myTowerKind = 4;
                                                                                    break;
                                                                                case "terminal":
                                                                                    // концевая
                                                                                    myTowerKind = 10;
                                                                                    break;
                                                                                default:
                                                                            }
                                                                        }

                                                                        // опора, ТП или Потребитель
                                                                        let myType = 'tower';
                                                                        if (typeof (item4['@attributes']['TowerLocation']) !== 'undefined') {
                                                                            switch (item4['@attributes']['TowerLocation']) {
                                                                                case 'TPInput':
                                                                                    myType = 'substation';
                                                                                    break;
                                                                                case 'BuildingInput':
                                                                                    myType = 'customer';
                                                                                    break;
                                                                                default:
                                                                                    myType = 'tower';
                                                                            }
                                                                        }

                                                                        // марка опоры
                                                                        let myTowerInfo = null;

                                                                        // конструкция опоры
                                                                        let myTowerConstruction = null;
                                                                        if (typeof (item4['@attributes']['TowerConstructive']) !== 'undefined') {
                                                                            switch (item4['@attributes']['TowerConstructive']) {
                                                                                case "A":
                                                                                    myTowerConstruction = 3;
                                                                                    break;
                                                                                case "AP":
                                                                                    myTowerConstruction = 5;
                                                                                    break;
                                                                                case "P": // ????
                                                                                    myTowerConstruction = 2;
                                                                                    break;
                                                                                case "T": // ????
                                                                                    myTowerConstruction = 4;
                                                                                    break;
                                                                                case "Y": // ????
                                                                                    myTowerConstruction = 6;
                                                                                    break;
                                                                                case "V": // ????
                                                                                    myTowerConstruction = 7;
                                                                                    break;
                                                                                default:
                                                                            }
                                                                        }

                                                                        let myPropN = null;
                                                                        let myGuy = (typeof (item4['@attributes']['guyed']) !== 'undefined') ? 1 : null;
                                                                        let myAnnex = null;

                                                                        // разьединитель (поиск, не указан ли ли он для этой опоры в другой ветке)
                                                                        let myEqDisconnector = null;
                                                                        let myEqDisconnectorInfo = null;
                                                                        if (typeof (item4.TechData) !== 'undefined') {
                                                                            //console.log(item4.TechData);
                                                                            if (item4.TechData['@attributes']['className'] === 'Disconnector') {
                                                                                myEqDisconnector = 'on';
                                                                            }
                                                                        }

                                                                        // реклоузер и выключатель нагпузки
                                                                        let myEqReklouzer = null;
                                                                        let myEqReklouzerInfo = null;
                                                                        let myEqVNa = null;
                                                                        let myEqVNaInfo = null;

                                                                        let myEqDischarger = (typeof (item4['@attributes']['TowerDischarger']) !== 'undefined') ? 1 : null;
                                                                        let myEqDischargerInfo = null;
                                                                        let myEqOPN = (typeof (item4['@attributes']['TowerOPN']) !== 'undefined') ? 1 : null;
                                                                        let myEqOPNInfo = null;
                                                                        let myEqGrounding = (typeof (item4['@attributes']['grounded']) !== 'undefined') ? 1 : null;

                                                                        let myStrut = null;
                                                                        let myStrutN = null;
                                                                        if (typeof (item4['@attributes']['StrutNo']) !== 'undefined') {
                                                                            myStrutN = item4['@attributes']['StrutNo'];
                                                                            myStrut = 'concrete';
                                                                        }

                                                                        let myEqOtherLine = (typeof (item4['@attributes']['JointV']) !== 'undefined') ? 1 : null;
                                                                        let myEqCommLine = (typeof (item4['@attributes']['JointC']) !== 'undefined') ? 1 : null;
                                                                        let myEqLamp = (typeof (item4['@attributes']['towerLamp']) !== 'undefined') ? 1 : null;
                                                                        let myEqAdapter = (typeof (item4['@attributes']['Adapter']) !== 'undefined') ? 1 : null;
                                                                        let myEqAccident = (typeof (item4['@attributes']['damage']) !== 'undefined') ? 1 : null;
                                                                        let myEqNoUp = (typeof (item4['@attributes']['upForbid']) !== 'undefined') ? 1 : null;

                                                                        //console.log("Относительные координаты: " + item2.split(' ')[0] + " , " + item2.split(' ')[1]);
                                                                        //console.log("Расчетные географичесике координаты: myLat = " + myLat + " , myLong = " + myLong);
                                                                        //console.log(item4);

                                                                        if (myName !== '' && myLat !== '' && myLong !== '') {

                                                                            // образец обьекта карты с полями по-умолчанию
                                                                            let myPlacemark = new ObjectOne();
                                                                            myPlacemark.lat = myLat;
                                                                            myPlacemark.long = myLong;
                                                                            myPlacemark.type = myType;
                                                                            myPlacemark.name = myName;
                                                                            myPlacemark.localName = myName;
                                                                            myPlacemark.address = myName;
                                                                            myPlacemark.towerMaterial = myTowerMaterial;
                                                                            myPlacemark.towerKind = myTowerKind;
                                                                            myPlacemark.towerInfo = myTowerInfo;
                                                                            myPlacemark.towerConstruction = myTowerConstruction;
                                                                            myPlacemark.propN = myPropN;
                                                                            myPlacemark.guy = myGuy;
                                                                            myPlacemark.strut = myStrut;
                                                                            myPlacemark.strutN = myStrutN;
                                                                            myPlacemark.annex = myAnnex;
                                                                            myPlacemark.eqDischarger = myEqDischarger;
                                                                            myPlacemark.eqDischargerInfo = myEqDischargerInfo;
                                                                            myPlacemark.eqOPN = myEqOPN;
                                                                            myPlacemark.eqOPNInfo = myEqOPNInfo;
                                                                            myPlacemark.eqGrounding = myEqGrounding;
                                                                            myPlacemark.eqOtherLine = myEqOtherLine;
                                                                            myPlacemark.eqCommLine = myEqCommLine;
                                                                            myPlacemark.eqLamp = myEqLamp;
                                                                            myPlacemark.eqAdapter = myEqAdapter;
                                                                            myPlacemark.eqAccident = myEqAccident;
                                                                            myPlacemark.eqNoUp = myEqNoUp;
                                                                            // добавить одну точку на карту
                                                                            funPlacemarkAdd(myPlacemark);

                                                                            // пополнить массив
                                                                            let item2_1 = Math.round(Number(item2.split(' ')[0]));
                                                                            let item2_2 = Math.round(Number(item2.split(' ')[1]));

                                                                            myArr.push({
                                                                                'coords': item2_1 + " " + item2_2, // item2,
                                                                                'mapID': mmCurrentPlacemarkMapID,
                                                                                'keylink': item4['@attributes']['keylink'],

                                                                            });

                                                                            // добавление линии на карту
                                                                            if (myNeedLine) {
                                                                                if (mmLastPlacemarkMapID != null && mmCurrentPlacemarkMapID != null && mmLastPlacemarkMapID !== mmCurrentPlacemarkMapID) {
                                                                                    //console.log("передаю характерные точки pointsGeo:");
                                                                                    //console.log(pointsGeo);

                                                                                    // образец обьекта карты с полями по-умолчанию
                                                                                    let myPolyline = new ObjectOne();
                                                                                    myPolyline.startMapID = mmLastPlacemarkMapID;
                                                                                    myPolyline.endMapID = mmCurrentPlacemarkMapID;
                                                                                    myPolyline.points = pointsGeo;
                                                                                    myPolyline.type = myLineType;
                                                                                    // myEqDisconnector,
                                                                                    // myEqDisconnectorInfo,
                                                                                    // myEqReklouzer,
                                                                                    // myEqReklouzerInfo,
                                                                                    // myEqVNa,
                                                                                    // myEqVNaInfo
                                                                                    // добавить одну линию на карту
                                                                                    funPolylineAdd(myPolyline);
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                });
                                                            }
                                                        }
                                                    });
                                                }
                                            } else {
                                                //console.log(item2 + ' - такая точка уже была');

                                                // добавление линии на карту
                                                if (myNeedLine) {
                                                    if (mmLastPlacemarkMapID != null && mmCurrentPlacemarkMapID != null && mmLastPlacemarkMapID !== mmCurrentPlacemarkMapID) {
                                                        //console.log("передаю характерные точки pointsGeo:");
                                                        //console.log(pointsGeo);

                                                        // образец обьекта карты с полями по-умолчанию
                                                        let myPolyline = new ObjectOne();
                                                        myPolyline.startMapID = mmLastPlacemarkMapID;
                                                        myPolyline.endMapID = mmCurrentPlacemarkMapID;
                                                        myPolyline.points = pointsGeo;
                                                        myPolyline.type = myLineType;
                                                        // myEqDisconnector,
                                                        // myEqDisconnectorInfo,
                                                        // myEqReklouzer,
                                                        // myEqReklouzerInfo,
                                                        // myEqVNa,
                                                        // myEqVNaInfo
                                                        // добавить одну линию на карту
                                                        funPolylineAdd(myPolyline);
                                                    }
                                                }
                                            }
                                        });
                                    }
                                });
                            }
                        }
                    }

                    // автомасштаб карты
                    funAutoScale();
                    // всплывающая подсказка
                    toastr.success('Импорт данных успешно завершен...');

                    // признак групповой операции
                    mmIsGroupOperation = false;

                    console.log("Массив обьектов после импорта:");
                    console.log(mmObjects);
                },
                error: function (error) {
                    // запрос прошел не успешно
                    // всплывающая подсказка
                    toastr.error('Ошибка при импорте данных...');
                }
            });
        }
    });

    // ---------------------------------------------------------------
    // рекурсивная функция сканиирования ветки XML при импорте
    function funRecursImportTechData(getArrAdd, getBranch) {

        if (typeof (getBranch['TechData']) !== 'undefined') {
            // рекурсивная функция сканиирования ветки XML при импорте
            funRecursImportTechData(getArrAdd, getBranch['TechData'])
        }

        if (typeof (getBranch['@attributes']) !== 'undefined' && getBranch['@attributes']['className'] === 'Tower2') {
            getArrAdd.push(getBranch);
        }

        if (Array.isArray(getBranch)) {
            // да, это массив - сканировать
            getBranch.forEach(function (item) {

                if (typeof (item['TechData']) !== 'undefined') {
                    // рекурсивная функция сканиирования ветки XML при импорте
                    funRecursImportTechData(getArrAdd, item['TechData'])
                }

                if (typeof (item['@attributes']) !== 'undefined' && item['@attributes']['className'] === 'Tower2') {
                    getArrAdd.push(item);
                }
            });
        }

        // возвращаемый параметр
        return getArrAdd;
    }

</script>
