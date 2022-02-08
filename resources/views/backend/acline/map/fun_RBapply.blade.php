<script type="text/javascript">

    // применить в правом блоке RB

    // ---------------------------------------------------------------
    // применить изменения в подробностях одной точки или множественного выбора
    function funRBapplyPlacemark() {

        if (mmClipboard.length > 0) {
            // множественный выбор
            // в цикле пройтись по всем точкам
            mmClipboard.forEach(function (myMapID) {
                // применить изменения в подробностях одной точки и заново показать детали
                funRBapplyPlacemarkOne(myMapID, true);
            });
        } else {
            // одиночный выбор
            // применить изменения в подробностях одной точки и заново показать детали
            funRBapplyPlacemarkOne(Number($('#hRBPlacemarkMapID').val()), false);
        }

        // сообщение пользователю
        toastr.success('Готово...');
    }

    // ---------------------------------------------------------------
    // применить изменения в подробностях одной точки и заново показать детали
    function funRBapplyPlacemarkOne(getMapID, getMultiSelect) {

        // текущий обьект
        myCurrentObject = mmObjects[getMapID];

        // записать в обьект
        myCurrentObject.type = $('#sRBPlacemarkType').val();


        myCurrentObject.dbConnectivitycodeID = funChangeUndefinedOrNoToNull($('#sRBPlacemarkTerminal').val(), 'number');
        myCurrentObject.towerMaterial = $('#sRBPlacemarkTowerMaterial').val() != null ? Number($('#sRBPlacemarkTowerMaterial').val()) : mmDefaultPlacemarkTowerMaterialID;
        myCurrentObject.towerKind = $('#sRBPlacemarkTowerKind').val() != null ? Number($('#sRBPlacemarkTowerKind').val()) : mmDefaultPlacemarkTowerKindID;
        myCurrentObject.towerInfo = funChangeUndefinedOrNoToNull($('#sRBPlacemarkTowerInfo').val(), 'number');
        myCurrentObject.towerConstruction = funChangeUndefinedOrNoToNull($('#sRBPlacemarkTowerConstruction').val(), 'number');

        myCurrentObject.propN = funChangeUndefinedOrNoToNull($('#sRBPlacemarkPropN').val());
        myCurrentObject.guy = funChangeUndefinedOrNoToNull($('#sRBPlacemarkGuy').val());
        myCurrentObject.strut = funChangeUndefinedOrNoToNull($('#sRBPlacemarkStrut').val());
        myCurrentObject.strutN = funChangeUndefinedOrNoToNull($('#sRBPlacemarkStrutN').val());
        myCurrentObject.annex = funChangeUndefinedOrNoToNull($('#sRBPlacemarkAnnex').val());

        myCurrentObject.eqDischarger = $('#chRBPlacemarkEqDischarger').is(':checked') ? 1 : 0;
        myCurrentObject.eqDischargerInfo = funChangeUndefinedOrNoToNull($('#sRBPlacemarkEqDischargerInfo').val());
        myCurrentObject.eqOPN = $('#chRBPlacemarkEqOPN').is(':checked') ? 1 : 0;
        myCurrentObject.eqOPNInfo = funChangeUndefinedOrNoToNull($('#sRBPlacemarkEqOPNInfo').val());
        myCurrentObject.eqGrounding = $('#chRBPlacemarkEqGrounding').is(':checked') ? 1 : 0;

        myCurrentObject.eqOtherLine = $('#chRBPlacemarkEqOtherLine').is(':checked') ? 1 : 0;
        myCurrentObject.eqCommLine = $('#chRBPlacemarkEqCommLine').is(':checked') ? 1 : 0;
        myCurrentObject.eqLamp = $('#chRBPlacemarkEqLamp').is(':checked') ? 1 : 0;
        myCurrentObject.eqAdapter = $('#chRBPlacemarkEqAdapter').is(':checked') ? 1 : 0;
        myCurrentObject.eqAccident = $('#chRBPlacemarkEqAccident').is(':checked') ? 1 : 0;
        myCurrentObject.eqNoUp = $('#chRBPlacemarkEqNoUp').is(':checked') ? 1 : 0;

        if (!getMultiSelect) {
            // это не множественный выбор

            myCurrentObject.name = funChangeUndefinedOrNoToNull($('#iRBPlacemarkName').val());
            myCurrentObject.localName = funChangeUndefinedOrNoToNull($('#iRBPlacemarkLocalName').val());
            myCurrentObject.address = funChangeUndefinedOrNoToNull($('#iRBPlacemarkAddress').val());
            myCurrentObject.type = funChangeUndefinedOrNoToNull($('#sRBPlacemarkType').val());

            // видимое имя для точки от типа - в массиве и на карте + обновить SVG
            myCurrentObject = funGetViewNamePlacemark(myCurrentObject);

            // записать новые координаты в карту, чтоб перерисовалось
            mmCollection.get(myCurrentObject.mapID).geometry.setCoordinates([Number($('#iRBPlacemarkLat').val()), Number($('#iRBPlacemarkLong').val())]);
        } else {
            // это множественный список
            // видимое имя не трогаем, но иконку обновить нужно
            funSVGmake(myCurrentObject.mapID);
        }

        // записать в массив
        mmObjects[myCurrentObject.mapID] = myCurrentObject;

        // найти линии, где упоминается эта точка, в конце или в начале, чтобы обновить у линии хинт, и проверить ее не стала ли точка Потребителем
        mmObjects.forEach(function (item) {

            if (item.mapType === 'polyline' && (item.startMapID === myCurrentObject.mapID || item.endMapID === myCurrentObject.mapID) && item.deleted === false) {

                // проверка является ли линия с Потребителем
                funLineIsToCustomer(item);
                // имя и всплывающий хинт у линии
                funApplyPolylineHint(item);
            }
        });

        // показать детали выбранной точки или множественного списка, показать детали, подсветить активную, создать иконку svg (чтоб повторно обновились поля)
        funRBviewPlacemark(myCurrentObject);
    }

    // ---------------------------------------------------------------
    // применить изменения в подробностях линии и заново показать детали
    function funRBapplyPolyline() {

        // текущий обьект
        myCurrentObject = mmObjects[Number($('#hRBPolylineMapID').val())];

        // записать в обьект
        myCurrentObject.type = $('#sRBPolylineType').val();
        myCurrentObject.lineToCustomer = (funLineIsToCustomer(myCurrentObject)).lineToCustomer;
        myCurrentObject.wireMark = funChangeUndefinedOrNoToNull($('#sRBPolylineWireMark').val());
        myCurrentObject.layingCondition = funChangeUndefinedOrNoToNull($('#sRBPolylineLayingCondition').val());
        myCurrentObject.wireS = Number($('#iRBPolylineWireS').val());
        myCurrentObject.wireN = Number($('#iRBPolylineWireN').val());
        myCurrentObject.wireLength = Number($('#iRBPolylineWireLength').val());
        myCurrentObject.wirePhaseN = Number($('#iRBPolylineWirePhaseN').val());
        myCurrentObject.cabelsN = Number($('#iRBPolylineCabelsN').val());
        myCurrentObject.gabarit = Number($('#iRBPolylineGabarit').val());

        // преобразование характерных точек
        let myPoints = [];
        let myValue = $('#tRBPolylineCoord').val();
        // убрать перевод строки и пробелы
        myValue = myValue.replace(/\r?\\n/g, '')
        myValue = myValue.replace(/\s/g, '');
        if (myValue !== '') {
            // преобразовать в массив
            myValue = myValue.split(';');
            let coord = null;
            myValue.forEach(function (item) {
                coord = item.split(',');
                if (!isNaN(Number(coord[0])) && !isNaN(Number(coord[1]))) {
                    myPoints.push([Number(coord[0]), Number(coord[1])]);
                }
            });
        }
        // дописать начало и конец, если их удали Пользователь (чтоб линия не исчезла!)
        let myStartInsert = true;
        let myEndInsert = true;
        myPoints.forEach(function (item) {
            if (item[0] === mmObjects[myCurrentObject.startMapID].lat && item[1] === mmObjects[myCurrentObject.startMapID].long) myStartInsert = false;
            if (item[0] === mmObjects[myCurrentObject.endMapID].lat && item[1] === mmObjects[myCurrentObject.endMapID].long) myEndInsert = false;
        });
        if (myStartInsert) {
            // start не было - добавить
            //console.log("Дописал в характерные точки start");
            myPoints.unshift([mmObjects[myCurrentObject.startMapID].lat, mmObjects[myCurrentObject.startMapID].long]);
        }
        if (myEndInsert) {
            // end не было - добавить
            //console.log("Дописал в характерные точки end");
            myPoints.push([mmObjects[myCurrentObject.endMapID].lat, mmObjects[myCurrentObject.endMapID].long]);
        }
        //console.log("характерные точки после применения:");
        //console.log(myPoints);
        myCurrentObject.points = myPoints;

        // оборудование коммутационное
        // разьединитель
        myCurrentObject.eqDisconnectorStart = $('#chRBPolylineEqDisconnectorStart').is(':checked') ? 1 : 0;
        myCurrentObject.eqDisconnectorStartInfo = funChangeUndefinedOrNoToNull($('#sRBPolylineEqDisconnectorStartInfo').val());
        myCurrentObject.eqDisconnectorEnd = $('#chRBPolylineEqDisconnectorEnd').is(':checked') ? 1 : 0;
        myCurrentObject.eqDisconnectorEndInfo = funChangeUndefinedOrNoToNull($('#sRBPolylineEqDisconnectorEndInfo').val());
        // реклоузер
        myCurrentObject.eqReklouzerStart = $('#chRBPolylineEqReklouzerStart').is(':checked') ? 1 : 0;
        myCurrentObject.eqReklouzerStartInfo = funChangeUndefinedOrNoToNull($('#sRBPolylineEqReklouzerStartInfo').val());
        myCurrentObject.eqReklouzerEnd = $('#chRBPolylineEqReklouzerEnd').is(':checked') ? 1 : 0;
        myCurrentObject.eqReklouzerEndInfo = funChangeUndefinedOrNoToNull($('#sRBPolylineEqReklouzerEndInfo').val());
        // выключатель нагрузки
        myCurrentObject.eqVNaStart = $('#chRBPolylineEqVNaStart').is(':checked') ? 1 : 0;
        myCurrentObject.eqVNaStartInfo = funChangeUndefinedOrNoToNull($('#sRBPolylineEqVNaStartInfo').val());
        myCurrentObject.eqVNaEnd = $('#chRBPolylineEqVNaEnd').is(':checked') ? 1 : 0;
        myCurrentObject.eqVNaEndInfo = funChangeUndefinedOrNoToNull($('#sRBPolylineEqVNaEndInfo').val());

        // имя и всплывающий хинт у линии
        myCurrentObject = funApplyPolylineHint(myCurrentObject);

        // записать в массив
        mmObjects[myCurrentObject.mapID] = myCurrentObject;
        // записать в карту новые координаты, чтоб перерисовалось
        mmCollection.get(myCurrentObject.mapID).geometry.setCoordinates(myPoints);

        // создать иконки svg в вершинах (на случай, если пролету задали разьединитель , например)
        funSVGmake(mmObjects[myCurrentObject.mapID].startMapID, false);
        funSVGmake(mmObjects[myCurrentObject.mapID].endMapID, false);

        // показать детали выбранной линии (чтоб повторно обновились поля)
        funRBviewPolyline(myCurrentObject);

        // сообщение пользователю
        toastr.success('Готово...');
    }

    // ---------------------------------------------------------------
    // применить изменения в подробностях сегмента
    function funRBapplySegment() {

        // сканировать пролеты/участки в сегменте
        if (typeof (mmSegments[mmCurrentSegmentMapID]) !== 'undefined') {
            mmSegments[mmCurrentSegmentMapID].forEach(function (item) {

                // записать в массив
                mmObjects[item].wireMark = funChangeUndefinedOrNoToNull($('#sRBSegmentWireMark').val());
                mmObjects[item].layingCondition = funChangeUndefinedOrNoToNull($('#sRBSegmentLayingCondition').val());
                mmObjects[item].wireS = Number($('#iRBSegmentWireS').val());
                mmObjects[item].wireN = Number($('#iRBSegmentWireN').val());
                mmObjects[item].wireLength = Number($('#iRBSegmentWireLength').val());
                mmObjects[item].wirePhaseN = Number($('#iRBSegmentWirePhaseN').val());
                mmObjects[item].cabelsN = Number($('#iRBSegmentCabelsN').val());

                // имя и всплывающий хинт у линии
                item = funApplyPolylineHint(item);

            });
        }

        // сообщение пользователю
        toastr.success('Готово...');
    }

</script>
