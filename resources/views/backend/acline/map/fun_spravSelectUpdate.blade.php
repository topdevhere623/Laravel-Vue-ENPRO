<script type="text/javascript">

    // ---------------------------------------------------------------
    // изменение ниспадающих списков select-ов справочников

    // ---------------------------------------------------------------
    // продублированная загрузка всех справочников (чтоб иметь массив JS, и не делать Ajax запросы, и не увеличивать длину страницу, если просто присвоить JSON-ом)
    function funSpravsLoad() {

        mmSpravs = {};
        // список справочников, которые нужно загрузить
        let mySpravNames = ['towerinfo', 'aclinesegmentinfo', 'towerkind', 'towermaterial', 'substation'];
        mySpravNames.forEach(function (item) {
            // загрузка одного справочника
            funSpravOneLoad(item);
        });
        //console.log(mmSpravs);
    }

    // ---------------------------------------------------------------
    // загрузка одного справочника
    function funSpravOneLoad(getSpravName) {

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            url: '/api/getModelRecords',
            async: false,
            data: {
                modelName: getSpravName,
            },
            method: "post",
        }).done(function (result) {
            //console.log(result);
            // записать в глобальную перменную
            mmSpravs[getSpravName] = result;
        });
    }

    // ---------------------------------------------------------------
    // смена марки опоры (towerInfo)
    function funChangeTowerInfo() {

        // указанная марка опоры
        if (typeof (mmSpravs['towerinfo']) !== 'undefined') {
            let myCurrentTowerInfo = mmSpravs['towerinfo'].find(item => item.id === Number($('#sRBPlacemarkTowerInfo').val()));
            if (typeof (myCurrentTowerInfo) !== 'undefined') {

                // console.log("Загруженный справочник марок:");
                // console.log(mmSpravs['towerinfo']);
                // console.log("Указанная марка опоры: " + myCurrentTowerInfo);
                // console.log("в ней название: " + myCurrentTowerInfo['name']);
                // console.log("в ней марка: " + myCurrentTowerInfo['mark']);
                // console.log("в ней материал: " + myCurrentTowerInfo['towermaterial_id']);
                // console.log("в ней конструкция: " + myCurrentTowerInfo['towerkind_id']);
                // console.log("После смены марки принудительно записал:");

                // проверить, известен ли материал опоры
                if (typeof (myCurrentTowerInfo['towermaterial_id']) !== 'undefined' && myCurrentTowerInfo['towermaterial_id'] !== '') {
                    // сделать принудительный выбор материала
                    $('#sRBPlacemarkTowerMaterial').val(Number(myCurrentTowerInfo['towermaterial_id']));
                } else {
                    // не указано
                    $('#sRBPlacemarkTowerMaterial').val("no");
                }
                //console.log("sRBPlacemarkTowerMaterial = " + $('#sRBPlacemarkTowerMaterial').val());

                // проверить, известно ли назначение опоры
                if (typeof (myCurrentTowerInfo['towerkind_id']) !== 'undefined' && myCurrentTowerInfo['towerkind_id'] !== '') {
                    // сделать принудительный выбор материала
                    $('#sRBPlacemarkTowerKind').val(Number(myCurrentTowerInfo['towerkind_id']));
                } else {
                    // не указано
                    $('#sRBPlacemarkTowerKind').val("no");
                }
                //console.log("sRBPlacemarkTowerKind = " + $('#sRBPlacemarkTowerKind').val());

                // проверить, известен ли подкос
                if (typeof (myCurrentTowerInfo['strut']) !== 'undefined' && myCurrentTowerInfo['strut'] === 1) {
                    // сделать принудительный выбор подкоса - ж/б
                    $('#sRBPlacemarkStrut').val('concrete');
                    $('#sRBPlacemarkStrutN').val(1);
                } else {
                    // не указано
                    $('#sRBPlacemarkStrut').val("no");
                    $('#sRBPlacemarkStrutN').val("no");
                }
                //console.log("sRBPlacemarkStrut = " + $('#sRBPlacemarkStrut').val());
                //console.log("sRBPlacemarkStrutN = " + $('#sRBPlacemarkStrutN').val());

                // принудительно обновить выпадающие списки
                $('select').selectric('refresh');
            }
        }
    }

    // ---------------------------------------------------------------
    // показать сечение для текущей марки провода
    function funChangeWireMarkS(getObject) {

        let myMarkField = '';
        let myWireSkField = '';
        if (getObject === 'polyline') {
            myMarkField = $('#sRBPolylineWireMark').val();
            myWireSkField = $('#iRBPolylineWireS');
        }
        if (getObject === 'segment') {
            myMarkField = $('#sRBSegmentWireMark').val();
            myWireSkField = $('#iRBSegmentWireS');
        }

        // указанная марка провода
        if (typeof (mmSpravs['aclinesegmentinfo']) !== 'undefined') {
            let myCurrentWireMark = mmSpravs['aclinesegmentinfo'].find(item => item.id === Number(myMarkField));
            if (typeof (myCurrentWireMark) !== 'undefined') {

                // сделать принудительное заполнение сечения
                myWireSkField.val(myCurrentWireMark['s']);

                // принудительно обновить выпадающие списки
                $('select').selectric('refresh');
            }
        }
    }

    // ---------------------------------------------------------------
    // из полного справочника марок проводов получить отсеченный по напряжению (6,10, 0,4) и типу линии (701, 702)
    function funChangeWireMark(getObject, getType = null, getCurrentValue = null) {

        // console.log('Полный справочник марок проводов:');
        // console.log(mmSpravs['aclinesegmentinfo']);
        // console.log(mmDefaultBaseVoltage);
        // console.log(getType);

        if (typeof (mmSpravs['aclinesegmentinfo']) !== 'undefined') {
            let myNewSpravWireMark = mmSpravs['aclinesegmentinfo'];
            // отсечь по напряжению
            // !!! на 0,4 нет строк в справочнике
            if (Number(mmDefaultBaseVoltage) === 6 || Number(mmDefaultBaseVoltage) === 10) {
                myNewSpravWireMark = mmSpravs['aclinesegmentinfo'].filter(function (item) {
                    return Number(item['voltageid']) === Number(mmDefaultBaseVoltage);
                });
            }
            // console.log('Урезанный справочник марок проводов после напряжения:');
            // console.log(myNewSpravWireMark);
            // отсечь по типу линии (701, 702), если указали
            if (getType != null) {
                myNewSpravWireMark = myNewSpravWireMark.filter(function (item) {
                    return Number(item['subclass']) === Number(getType);
                });
            }
            // console.log('Урезанный справочник марок проводов после типа:');
            // console.log(myNewSpravWireMark);

            let myBegin = 0;
            let myEnd = 0;
            switch (getObject) {
                case 'polyline':
                    myBegin = 1;
                    myEnd = 1;
                    break;
                case 'segment':
                    myBegin = 2;
                    myEnd = 2;
                    break;
                case 'all':
                    myBegin = 1;
                    myEnd = 2;
                    break;
            }

            let mySelect = '';
            for (let i = myBegin; i <= myEnd; i++) {

                if (i === 1) {
                    mySelect = "sRBPolylineWireMark";
                }
                if (i === 2) {
                    mySelect = "sRBSegmentWireMark";
                }

                // очистить список
                let objSel1 = document.getElementById(mySelect);
                objSel1.innerHTML = null;
                objSel1.options[objSel1.options.length] = new Option("не указано", "no");

                // заполнить список
                if (myNewSpravWireMark.length > 0) {
                    myNewSpravWireMark.forEach(function (item, i, arr) {
                        objSel1.options[objSel1.options.length] = new Option(item['assetinfokey'], item['id']);
                    });
                }

                // сделать активным значение, если указано
                if (getCurrentValue != null) {
                    $("#" + mySelect + " option[value=" + getCurrentValue + "]").attr("selected", "selected");
                }
            }

            // принудительно обновить выпадающие списки
            $('select').selectric('refresh');
        }
    }

    // ---------------------------------------------------------------
    // получить список ближайших ТП
    function funGetNearSubstations() {

        // очистить список ТП, секций шин, терминалов
        funClearSelect("sRBPlacemarkSubstation");
        funClearSelect("sRBPlacemarkBusbarsection");
        funClearSelect("sRBPlacemarkTerminal");

        // координата
        let myLat = mmCollection.get(mmCurrentPlacemarkMapID).geometry.getCoordinates()[0];
        let myLong = mmCollection.get(mmCurrentPlacemarkMapID).geometry.getCoordinates()[1];
        let myCurrentCoords = [myLat, myLong];

        // получить список ближайших обьектов по дистанции
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            url: '/api/getNearObjectsOnDistance',
            data: {
                modelName: 'Substation',
                currentCoords: myCurrentCoords,
            },
            method: "post",
        }).done(function (result) {
            //console.log("список ближайших ТП:");
            //console.log(result);

            // заполнить список
            if (typeof (result) !== 'undefined' && result.length > 0) {
                let objSel1 = document.getElementById("sRBPlacemarkSubstation");
                result.forEach(function (item) {
                    objSel1.options[objSel1.options.length] = new Option(item['name'], item['id']); // здесь name без IO подставляется
                });
            }

            // принудительно обновить выпадающие списки
            $('select').selectric('refresh');
        });
    }

    // ---------------------------------------------------------------
    // смена ТП
    function funChangeSubstation() {

        // очистить список секций шин, терминалов
        funClearSelect("sRBPlacemarkBusbarsection");
        //funClearSelect("sRBPlacemarkTerminal");

        // получение списка секции шин от этого ТП
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            url: '/api/getBusbarsectionsOnSubstation',
            data: {
                findValue: $('#sRBPlacemarkSubstation').val(),
                baseVoltage: mmDefaultBaseVoltage,
            },
            method: "post",
        }).done(function (result) {
            //console.log("getBusbarsectionsOnSubstation");
            //console.log(result);

            if (typeof (result) !== 'undefined' && result.length > 0) {
                // заполнить ниспадающий список секции шин
                funMakeSelect("sRBPlacemarkBusbarsection", result);

                // принудительно обновить выпадающие списки
                $('select').selectric('refresh');
            }
        });
    }

    // ---------------------------------------------------------------
    // смена секции шин
    function funChangeBusbarsection() {

        // очистить список терминалов
        funClearSelect("sRBPlacemarkTerminal");

        // получение списка терминалов от этой секции шин
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            url: '/api/getTerminalsOnBusbarsection',
            data: {
                findValue: $('#sRBPlacemarkBusbarsection').val(),
                baseVoltage: mmDefaultBaseVoltage,
            },
            method: "post",
        }).done(function (result) {
            //console.log("getTerminalsOnBusbarsection");
            //console.log(result);

            if (typeof (result) !== 'undefined' && result.length > 0) {
                // заполнить ниспадающий список терминалов
                let objSel1 = document.getElementById("sRBPlacemarkTerminal");
                result.forEach(function (item) {
                    objSel1.options[objSel1.options.length] = new Option(item.identifiedobject['name'], item['connectivitycode_id']); // здесь не id, а connectivitycode_id
                });

                // принудительно обновить выпадающие списки
                $('select').selectric('refresh');
            }
        });
    }

    // ---------------------------------------------------------------
    // получить терминал по connectivitycode_id
    function funGetTerminalConnectivitycodeID(getConnectivitycodeID) {

        // очистить список терминалов
        funClearSelect("sRBPlacemarkTerminal");
        if (typeof (getConnectivitycodeID) !== 'undefined' && getConnectivitycodeID != null && getConnectivitycodeID !== "no") {
            // получение списка терминалов от этой секции шин
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                url: '/api/getTerminalOnConnectivitycode',
                data: {
                    findValue: getConnectivitycodeID, // в терминале единственный его connectivitycode здесь
                },
                method: "post",
            }).done(function (result) {
                //console.log("funGetTerminalConnectivitycodeID для: " + getConnectivitycodeID);
                //console.log(result);

                if (typeof (result) !== 'undefined') {
                    // заполнить ниспадающий список терминалов
                    let objSel1 = document.getElementById("sRBPlacemarkTerminal");
                    objSel1.options[objSel1.options.length] = new Option(result.identifiedobject['name'], result['connectivitycode_id']); // здесь не id, а connectivitycode_id
                    // сделать активной записью
                    $('#sRBPlacemarkTerminal').val(result['connectivitycode_id']);

                    // принудительно обновить выпадающие списки
                    $('select').selectric('refresh');
                }
            });
        }
    }

    // ---------------------------------------------------------------
    // очистить ниспадающий список
    function funClearSelect(getSelect) {

        // очистить список
        let objSel1 = document.getElementById(getSelect);
        objSel1.innerHTML = null;

        // записать первоначальное значение - "не определено"
        objSel1.options[objSel1.options.length] = new Option("не указано", "no");

        // принудительно обновить выпадающие списки
        if (getSelect) $('#' + getSelect).selectric('refresh');
        else $('select').selectric('refresh');

    }

    // ---------------------------------------------------------------
    // заполнить ниспадающий список
    function funMakeSelect(getSelect, getData) {

        // заполнить список
        let objSel1 = document.getElementById(getSelect);
        if (typeof (getData) !== 'undefined' && getData.length > 0) {
            getData.forEach(function (item) {
                objSel1.options[objSel1.options.length] = new Option(item.identifiedobject['name'], item['id']);
            });
        }

        // принудительно обновить выпадающие списки
        $('select').selectric('refresh');
    }
</script>
