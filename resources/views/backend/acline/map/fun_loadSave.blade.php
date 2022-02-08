<script type="text/javascript">

    // загрузить/сохранить данные

    // ---------------------------------------------------------------
    // загрузить данные
    function funLoad() {

        // получить данные из PHP
        let getData = new Map([
            ['acline', {!! json_encode($acline, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT) !!}],
            ['aclinesegments', {!! json_encode($aclinesegments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT) !!}],
            ['spans', {!! json_encode($spans, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT) !!}],
            ['towers', {!! json_encode($towers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT) !!}],
            ['customers', {!! json_encode($customers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT) !!}],
            ['disconnectors', {!! json_encode($disconnectors, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT) !!}],
            ['dischargers', {!! json_encode($dischargers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT) !!}],
            ['crossings', {!! json_encode($crossings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT) !!}],
        ]);
        console.log("Данные, полученные с бекенда через PHP:");
        console.log(getData);

        // черновик
        let myDraftOld = JSON.parse((getData.get('acline')).map_json);

        // постройка карты
        if (myDraftOld != null && myDraftOld !== '') {
            // черновик есть
            console.log("Загрузка карты из черновика:");
            //console.log(myDraftOld);

            // для совместимости со старыми версиями и актуальности данных - дополнить черновик новыми полями и более свежей информацией
            let myDraftNew = funDraftUpdate(myDraftOld);

            // добавить список обьектов на карту
            funAddObjectsToMapFromArray(myDraftNew);

        } else {
            // черновика нет
            console.log("Загрузка карты из базы данных");

            // постройка карты по "правилам" из БД
            funLoadOnDB(getData);
        }

        // функция сохранения состояния - массива точек после загрузки или сохранения
        funStateSave();
        // записать шаг в историю
        funHistoreSave();
        // автомасштаб карты
        funAutoScale();

        console.log("Массив обьектов после загрузки:");
        console.log(mmObjects);
    }

    // ---------------------------------------------------------------
    // постройка карты по "правилам" из БД
    function funLoadOnDB(getData) {

        // показывать/скрыть значек loading
        funAjaxLoadingImg(true);
        // всплывающая подсказка
        toastr.info('Началась загрузка данных из базы данных...');
        // признак групповой операции
        mmIsGroupOperation = true;

        // переданные данные
        let getAcline = getData.get('acline');
        let getAclinesegments = getData.get('aclinesegments');
        let getSpans = getData.get('spans');
        let getTowers = getData.get('towers');
        let getCustomers = getData.get('customers');
        let getDisconnectors = getData.get('disconnectors');
        let getDischargers = getData.get('dischargers');
        let getCrossings = getData.get('crossings');

        // сканировать сегменты
        if (getAclinesegments != null && getAclinesegments.length > 0) {
            getAclinesegments.forEach(function (aclinesegment) {

                // значения сегмента
                let myWireMark = (typeof (aclinesegment['wiremark_id']) !== 'undefined' ? aclinesegment['wiremark_id'] : null);
                let myLayingCondition = (typeof (aclinesegment['layingcondition_id']) !== 'undefined' ? aclinesegment['layingcondition_id'] : null);
                let myWireS = aclinesegment['wires'];
                let myWireN = aclinesegment['wiren'];
                let myWireLength = aclinesegment['wirelength'];
                let myWirePhaseN = aclinesegment['wirephasen'];
                let myCabelsN = aclinesegment['cabelsn'];

                // сканировать пролеты/участки этого сегмента
                if (aclinesegment.spans != null && aclinesegment.spans.length > 0) {
                    (aclinesegment.spans).forEach(function (span) {

                        // найти в полном списке пролетов/участков
                        let mySpanFull = getSpans.find(item => item.id === span['id']);
                        if (typeof (mySpanFull) !== 'undefined') {

                            // значения пролета/участка
                            let mySpanDbID = mySpanFull['id'];
                            let mySpanDbIOID = mySpanFull['identifiedobject_id'];
                            let myStartIOID = mySpanFull['startIO_id'];
                            let myEndIOID = mySpanFull['endIO_id'];
                            let myPoints = (mySpanFull['points'] != null && mySpanFull['points'] !== '') ? JSON.parse(mySpanFull['points']) : [];
                            let mySpanType = (mySpanFull['spantype'] == null) ? 0 : mySpanFull['spantype'];
                            let myGabarit = mySpanFull['gabarit'];
                            let myPhotos = mySpanFull['photos'];

                            // вершины пролета/участка - опоры, Потребители или ТП
                            let mySpanPoints = [];
                            for (let pointN = 0; pointN < 2; pointN++) {

                                let myPoint = (pointN === 0) ? myStartIOID : myEndIOID;

                                // поиск в опорах
                                let myType = null;
                                //console.log('поиск в опорах');
                                let myPointFull = getTowers.find(item => item.identifiedobject_id === myPoint);
                                if (typeof (myPointFull) !== 'undefined') {
                                    if (myPointFull['fict_tp'] === 1) {
                                        // фиктивная ТП
                                        myType = 'substation';
                                    } else {
                                        myType = 'tower';
                                    }
                                } else {
                                    // поиск в поставщиках
                                    //console.log('поиск в поставщиках');
                                    myPointFull = getCustomers.find(item => item.identifiedobject_id === myPoint);
                                    if (typeof (myPointFull) !== 'undefined') {
                                        //console.log('нашел в поставщиках!');
                                        myType = 'customer';
                                    }
                                }

                                // проверка, не наносили ли уже эту точку на карту
                                if (typeof (myPointFull) !== 'undefined') {
                                    // опора определена

                                    let myMapObject = mmObjects.find(item => (item.dbID === myPointFull['id'] && item.dbIOID === myPointFull['identifiedobject_id'])); // было узкое место!!!
                                    if (typeof (myMapObject) === 'undefined') {
                                        // на карте еще нет такой точки

                                        // поиск в разрядниках (здесь сразу 2-а обьекта хранится: разрядник, ОПН)
                                        let myEqDischarger = null;
                                        let myEqDischargerInfo = null;
                                        let myEqOPN = null;
                                        let myEqOPNInfo = null;

                                        getDischargers.forEach(function (discharger) {
                                            if (myPointFull['identifiedobject_id'] === discharger['startIO_id']) {
                                                let myEqDischargerType = discharger['type'];

                                                switch (myEqDischargerType) {
                                                    case 1:
                                                        myEqDischarger = 1;
                                                        myEqDischargerInfo = discharger['dischargerinfo_id'];
                                                        break;
                                                    case 2:
                                                        myEqOPN = 1;
                                                        myEqOPNInfo = discharger['dischargerinfo_id'];
                                                        break;
                                                }
                                            }
                                        });

                                        // образец обьекта карты с полями по-умолчанию
                                        let myPlacemark = new ObjectOne();

                                        myPlacemark.dbID = myPointFull['id'];
                                        myPlacemark.dbIOID = myPointFull['identifiedobject']['id'];
                                        myPlacemark.dbConnectivitycodeID = myPointFull['connectivitycode_id'];
                                        myPlacemark.type = myType;
                                        myPlacemark.name = myPointFull['identifiedobject']['name'];
                                        myPlacemark.localName = myPointFull['identifiedobject']['localname'];
                                        myPlacemark.address = myPointFull['identifiedobject']['address'];
                                        myPlacemark.lat = myPointFull['identifiedobject']['lat'];
                                        myPlacemark.long = myPointFull['identifiedobject']['long'];
                                        myPlacemark.towerMaterial = (typeof (myPointFull['towermaterial_id']) !== 'undefined') ? myPointFull['towermaterial_id'] : null;
                                        myPlacemark.towerKind = (typeof (myPointFull['towerkind_id']) !== 'undefined') ? myPointFull['towerkind_id'] : null;
                                        myPlacemark.towerInfo = (typeof (myPointFull['towerinfo_id']) !== 'undefined') ? myPointFull['towerinfo_id'] : null;
                                        myPlacemark.towerConstruction = (typeof (myPointFull['towerconstructionkind_id']) !== 'undefined') ? myPointFull['towerconstructionkind_id'] : null;
                                        myPlacemark.propN = myPointFull['propn'];
                                        myPlacemark.guy = myPointFull['guy'];
                                        myPlacemark.strut = (typeof (myPointFull['strut']) !== 'undefined') ? myPointFull['strut'] : null;
                                        myPlacemark.strutN = myPointFull['strutn'];
                                        myPlacemark.annex = (typeof (myPointFull['annex']) !== 'undefined') ? myPointFull['annex'] : null;
                                        myPlacemark.eqDischarger = myEqDischarger;
                                        myPlacemark.eqDischargerInfo = myEqDischargerInfo;
                                        myPlacemark.eqOPN = myEqOPN;
                                        myPlacemark.eqOPNInfo = myEqOPNInfo;
                                        myPlacemark.eqOtherLine = myPointFull['eqotherline'];
                                        myPlacemark.eqCommLine = myPointFull['eqcommline'];
                                        myPlacemark.eqLamp = myPointFull['eqlamp'];
                                        myPlacemark.eqAdapter = myPointFull['eqadapter'];
                                        myPlacemark.eqAccident = myPointFull['eqaccident'];
                                        myPlacemark.eqNoUp = myPointFull['eqnoup'];
                                        myPlacemark.photos = (myPointFull['photos'] != null && myPointFull['photos'] !== '') ? JSON.parse(myPointFull['photos']) : [];
                                        myPlacemark.aclinesObject = myPointFull['aclinesObject'];
                                        // добавить одну точку на карту
                                        mySpanPoints[pointN] = funPlacemarkAdd(myPlacemark);
                                    } else {
                                        // на карте такая точка уже есть
                                        mySpanPoints[pointN] = myMapObject['mapID'];
                                    }
                                } else {
                                    console.log("myPoint = " + myPoint + " - ни в опорах, ни в поставщиках не найдено!");
                                }
                            }

                            // проставить пролеты
                            if (mySpanPoints[0] >= 0 && mySpanPoints[1] >= 0) {

                                // поиск в разьединителях (здесь сразу 3-и обьекта хранится: разьединииель, реклоузер, выключатель нагрузки)
                                let myEqDisconnectorStart = 0;
                                let myEqDisconnectorStartInfo = null;
                                let myEqDisconnectorEnd = 0;
                                let myEqDisconnectorEndInfo = null;
                                let myEqReklouzerStart = 0;
                                let myEqReklouzerStartInfo = null;
                                let myEqReklouzerEnd = 0;
                                let myEqReklouzerEndInfo = null;
                                let myEqVNaStart = 0;
                                let myEqVNaStartInfo = null;
                                let myEqVNaEnd = 0;
                                let myEqVNaEndInfo = null;

                                getDisconnectors.forEach(function (disconnector) {
                                    if (disconnector['span_id'] === mySpanDbID) {
                                        let myEqDisconnectorType = disconnector['type'];

                                        // определить какой вершиной является текущая точка
                                        if (disconnector['startIO_id'] === mmObjects[mySpanPoints[0]]['dbIOID']) {
                                            switch (myEqDisconnectorType) {
                                                case 1:
                                                    myEqDisconnectorStart = 1;
                                                    myEqDisconnectorStartInfo = disconnector['disconnectorinfo_id'];
                                                    break;
                                                case 2:
                                                    myEqReklouzerStart = 1;
                                                    myEqReklouzerStartInfo = disconnector['disconnectorinfo_id'];
                                                    break;
                                                case 3:
                                                    myEqVNaStart = 1;
                                                    myEqVNaStartInfo = disconnector['disconnectorinfo_id'];
                                                    break;
                                            }
                                        }
                                        if (disconnector['startIO_id'] === mmObjects[mySpanPoints[1]]['dbIOID']) {
                                            switch (myEqDisconnectorType) {
                                                case 1:
                                                    myEqDisconnectorEnd = 1;
                                                    myEqDisconnectorEndInfo = disconnector['disconnectorinfo_id'];
                                                    break;
                                                case 2:
                                                    myEqReklouzerEnd = 1;
                                                    myEqReklouzerEndInfo = disconnector['disconnectorinfo_id'];
                                                    break;
                                                case 3:
                                                    myEqVNaEnd = 1;
                                                    myEqVNaEndInfo = disconnector['disconnectorinfo_id'];
                                                    break;
                                            }
                                        }
                                    }
                                });

                                // образец обьекта карты с полями по-умолчанию
                                let myPolyline = new ObjectOne();

                                myPolyline.dbID = mySpanDbID;
                                myPolyline.dbIOID = mySpanDbIOID;
                                myPolyline.startMapID = mySpanPoints[0];
                                myPolyline.endMapID = mySpanPoints[1];
                                myPolyline.points = myPoints;
                                myPolyline.type = mySpanType;
                                myPolyline.wireMark = myWireMark;
                                myPolyline.layingCondition = myLayingCondition;
                                myPolyline.wireS = myWireS;
                                myPolyline.wireN = myWireN;
                                myPolyline.wireLength = myWireLength;
                                myPolyline.wirePhaseN = myWirePhaseN;
                                myPolyline.cabelsN = myCabelsN;
                                myPolyline.gabarit = myGabarit;
                                myPolyline.eqDisconnectorStart = myEqDisconnectorStart;
                                myPolyline.eqDisconnectorStartInfo = myEqDisconnectorStartInfo;
                                myPolyline.eqDisconnectorEnd = myEqDisconnectorEnd;
                                myPolyline.eqDisconnectorEndInfo = myEqDisconnectorEndInfo;
                                myPolyline.eqReklouzerStart = myEqReklouzerStart;
                                myPolyline.eqReklouzerStartInfo = myEqReklouzerStartInfo;
                                myPolyline.eqReklouzerEnd = myEqReklouzerEnd;
                                myPolyline.eqReklouzerEndInfo = myEqReklouzerEndInfo;
                                myPolyline.eqVNaStart = myEqVNaStart;
                                myPolyline.eqVNaStartInfo = myEqVNaStartInfo;
                                myPolyline.eqVNaEnd = myEqVNaEnd;
                                myPolyline.eqVNaEndInfo = myEqVNaEndInfo;

                                // добавить одну линию на карту
                                funPolylineAdd(myPolyline);
                            }
                        } else {
                            console.log("Пролет не найден!");
                        }
                    });
                }
            });
        }

        // признак групповой операции
        mmIsGroupOperation = false;
        // всплывающая подсказка
        toastr.success('Загрузка данных из базы данных завершена...');
        // показывать/скрыть значек loading
        funAjaxLoadingImg(false);
    }

    // ---------------------------------------------------------------
    // для совместимости со старыми версиями и актуальности данных - дополнить черновик новыми полями и более свежей информацией
    // например, не было полей isActive, isDoubleAcline, aclinesObject
    function funDraftUpdate(getDraftOld) {

        // обновленный черновик
        let myDraftNew = [];

        // сканировать переданный массив по строкам
        if (getDraftOld != null && getDraftOld.length > 0) {
            for (let index in getDraftOld) {

                // добавление новых полей, если черновик сохранялся давно и в нем этих полей может быть нет
                // образец обьекта карты с полями по-умолчанию
                let myObject = new ObjectOne();

                myDraftNew[index] = {};
                // сканировать образец по полям
                for (let key in myObject) {

                    // взять с черновика, если такое поле есть
                    if (typeof (getDraftOld[index][key]) !== 'undefined') {
                        myObject[key] = getDraftOld[index][key];
                    }

                    // записать обратно в массив черновика
                    myDraftNew[index][key] = myObject[key];
                }
            }
        }

        // console.log('Старый черновик');
        // console.log(getDraftOld);
        // console.log('Новый черновик');
        // console.log(myDraftNew);

        // возвращаемый параметр
        return myDraftNew;
    }

    // ---------------------------------------------------------------
    // сохранить данные в БД
    function funSave() {

        // сообщение пользователю
        toastr.info('Начался процесс сохранения данных карты. Дождитесь, пожалуйста, его завершения...');

        // данные ЛЭП
        let myAclineID = Number($('#sRBAclineID').text());

        // разбивка линии на сегменты
        funSegments();

        console.log("-------------------------------");
        console.log("До сохранения линии с ID = " + myAclineID);
        console.log("Массив сегментов mmSegments:");
        console.log(mmSegments);
        console.log("Массив обьектов mmObjects:");
        console.log(mmObjects);

        let myUrl = '{{ route('acline.map.update') }}';
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            url: myUrl,
            data: {
                aclineID: myAclineID,
                aclineName: $('#iRBAclineName').val().trim(),
                aclineNameDefault: '{{ $myAclineNameDefault }}',
                aclineStatus: $('#sRBAclineStatus').val(),
                aclineBaseVoltage: $('#sRBAclineBaseVoltage').val(),
                mmSegments: JSON.stringify(mmSegments),
                mmObjects: JSON.stringify(mmObjects),
            },
            method: "post",
            success: function (result) {
                // успешное сохранение
                //console.log("Ответ после успешного сохранения");
                //console.log(result);

                console.log("получено с контроллера ID = " + result.aclineID);
                if (myAclineID == null || myAclineID === '' | myAclineID === 0) {
                    // это новая линия
                    // дописать в url присвоенный id
                    history.pushState(null, null, window.location.href + "/" + result.aclineID);
                }
                // запись ID линии в поле (чтоб при повторном сохранении еще одна запись не создалась)
                $('#sRBAclineID').text(result.aclineID);

                // записать новое имя линии, если было по-умолчанию
                $('#iRBAclineName').val(result.aclineName);
                $('#tAclineName').text(result.aclineName);

                // записать всем обьектам присвоенные IDs из базы (как и в бекенде, чтоб одинаково было - потому что в черновике бекенда еще null может быть, если только одно сохранение было)
                console.log("пришло с бекенда ID");
                console.log(result.ids);
                (result.ids).forEach(function (item) {
                    mmObjects[item['mapID']]['dbID'] = item['dbID'];
                    mmObjects[item['mapID']]['dbIOID'] = item['dbIOID'];
                });


                //console.log("-------------------------------");
                //console.log("После сохранения линии и присвоения DbID, dbIOID:");
                //console.log(mmObjects);

                // очистка множестсвенного выбора
                funClipboardClear();
                // функция сохранения состояния - массива точек после загрузки или сохранения
                funStateSave();

                // сгенерировать карту по массиву mmObjects в SVG-формате
                //let myReturn = funSVGMakeMapOnmmObjects(myArr);
                // сохранить карту SVG на диске
                //funSVGSave(myReturn.get('SVGMap'));

                toastr.success('Данные карты успешно сохранены...');
            },
            error: function (jqXHR, exception) {
                // при сохранении возникла ошибка
                //console.log("Ответ в случае ошибки");
                //console.log(exception);
                //console.log(jqXHR);

                // сообщение пользователю
                alert(
                    'Извините, операция не завершена!' +
                    '\n Сервер не смог обработать запрос!' +
                    '\n\n Код: ' + jqXHR.status +
                    '\n Сообщение: ' + jqXHR.statusText +
                    '\n Подробности: ' + jqXHR.responseJSON.message);
            }
        });
    }
</script>
