<script type="text/javascript">

    // показать детали в правом блоке RB

    // ---------------------------------------------------------------
    // показать детали текущей ЛЭП
    function funRBviewAcline() {

        // текущий класс напряжения
        funCurrentBaseVoltage();

        // показать детали в правой колонке, подсветить активную линию
        funRBview('acline');
    }

    // ---------------------------------------------------------------
    // показать детали выбранной точки или множественного списка, показать детали, подсветить активную, создать иконку svg
    function funRBviewPlacemark(getObject) {

        if (!mmIsGroupOperation) {
            console.log("Показать детали выбранной точки для: " + getObject.viewName);
            console.log(getObject);

            // признак множественного выбора
            let myMultiselect = (mmClipboard.length > 0) ? true : false;

            // видимость полей
            let myPlacemarkType = getObject.type;
            switch (myPlacemarkType) {
                case 'tower':
                    $('#h3RBPlacemarkType').text(myMultiselect ? 'Множественный выбор' : 'Опора');

                    $('#trRBPlacemarkName').hide(0);
                    if (myMultiselect) {
                        $('#trRBPlacemarkLocalName').hide(0);
                    } else {
                        $('#trRBPlacemarkLocalName').show(0);
                    }
                    $('#trRBPlacemarkAddress').hide(0);
                    $('#trRBPlacemarkTowerMaterial').show(0);
                    $('#trRBPlacemarkTowerKind').show(0);
                    $('#trRBPlacemarkTowerInfo').show(0);
                    $('#trRBPlacemarkTowerConstruction').show(0);
                    $('#trRBPlacemarkPropN').show(0);
                    $('#trRBPlacemarkGuy').show(0);
                    $('#trRBPlacemarkStrut').show(0);
                    $('#trRBPlacemarkAnnex').show(0);
                    $('#trRBPlacemarkEqDischarge').show(0);
                    $('#trRBPlacemarkEqOther').show(0);

                    $('#trRBPlacemarkSubstation').hide(0);
                    $('#trRBPlacemarkBusbarsection').hide(0);
                    $('#trRBPlacemarkTerminal').hide(0);
                    break;
                case 'substation':
                    $('#h3RBPlacemarkType').text(myMultiselect ? 'Множественный выбор' : 'ТП');

                    if (myMultiselect) {
                        $('#trRBPlacemarkName').hide(0);
                    } else {
                        $('#trRBPlacemarkName').show(0);
                    }
                    $('#trRBPlacemarkLocalName').hide(0);
                    $('#trRBPlacemarkAddress').hide(0);
                    $('#trRBPlacemarkTowerMaterial').hide(0);
                    $('#trRBPlacemarkTowerKind').hide(0);
                    $('#trRBPlacemarkTowerInfo').hide(0);
                    $('#trRBPlacemarkTowerConstruction').hide(0);
                    $('#trRBPlacemarkPropN').hide(0);
                    $('#trRBPlacemarkGuy').hide(0);
                    $('#trRBPlacemarkStrut').hide(0);
                    $('#trRBPlacemarkAnnex').hide(0);
                    $('#trRBPlacemarkEqDischarge').hide(0);
                    $('#trRBPlacemarkEqOther').hide(0);

                    // получить список ближайших ТП по текущим координатам
                    funGetNearSubstations();
                    // получить терминал по connectivitycode_id
                    funGetTerminalConnectivitycodeID(getObject.dbConnectivitycodeID);

                    $('#trRBPlacemarkSubstation').show(0);
                    $('#trRBPlacemarkBusbarsection').show(0);
                    $('#trRBPlacemarkTerminal').show(0);
                    break;
                case 'customer':
                    $('#h3RBPlacemarkType').text(myMultiselect ? 'Множественный выбор' : 'Потребитель');

                    $('#trRBPlacemarkName').hide(0);
                    $('#trRBPlacemarkLocalName').hide(0);
                    if (myMultiselect) {
                        $('#trRBPlacemarkAddress').hide(0);
                    } else {
                        $('#trRBPlacemarkAddress').show(0);
                    }
                    $('#trRBPlacemarkTowerMaterial').hide(0);
                    $('#trRBPlacemarkTowerKind').hide(0);
                    $('#trRBPlacemarkTowerInfo').hide(0);
                    $('#trRBPlacemarkTowerConstruction').hide(0);
                    $('#trRBPlacemarkPropN').hide(0);
                    $('#trRBPlacemarkGuy').hide(0);
                    $('#trRBPlacemarkStrut').hide(0);
                    $('#trRBPlacemarkAnnex').hide(0);
                    $('#trRBPlacemarkEqDischarge').hide(0);
                    $('#trRBPlacemarkEqOther').hide(0);

                    $('#trRBPlacemarkSubstation').hide(0);
                    $('#trRBPlacemarkBusbarsection').hide(0);
                    $('#trRBPlacemarkTerminal').hide(0);
                    break;
            }

            // координаты
            if (myMultiselect) {
                // скрыть поля для множественного выбора
                $('#iRBPlacemarkLat').hide(0);
                $('#iRBPlacemarkLong').hide(0);
            } else {
                // одиночный выбор
                $('#iRBPlacemarkLat').show(0);
                $('#iRBPlacemarkLong').show(0);
                $('#iRBPlacemarkLat').val(mmCollection.get(mmCurrentPlacemarkMapID).geometry.getCoordinates()[0]);
                $('#iRBPlacemarkLong').val(mmCollection.get(mmCurrentPlacemarkMapID).geometry.getCoordinates()[1]);
            }

            // значения
            $('#hRBPlacemarkMapID').val(getObject.mapID); // скрытое поле
            $('#iRBPlacemarkName').val(getObject.name);
            $('#iRBPlacemarkLocalName').val(getObject.localName);
            $('#iRBPlacemarkAddress').val(getObject.address);
            $('#sRBPlacemarkType').val(getObject.type);
            $('#sRBPlacemarkTowerMaterial').val(getObject.towerMaterial);
            $('#sRBPlacemarkTowerKind').val(getObject.towerKind);
            $('#sRBPlacemarkTowerInfo').val(getObject.towerInfo);
            $('#sRBPlacemarkTowerConstruction').val(getObject.towerConstruction);
            $('#sRBPlacemarkPropN').val(getObject.propN);
            $('#sRBPlacemarkGuy').val(getObject.guy);
            $('#sRBPlacemarkStrut').val(getObject.strut);
            $('#sRBPlacemarkStrutN').val(getObject.strutN);
            $('#sRBPlacemarkAnnex').val(getObject.annex);

            $('#chRBPlacemarkEqDischarger').prop('checked', (getObject.eqDischarger === 1) ? true : false);
            $('#sRBPlacemarkEqDischargerInfo').val(getObject.eqDischargerInfo);
            $('#chRBPlacemarkEqOPN').prop('checked', (getObject.eqOPN === 1) ? true : false);
            $('#sRBPlacemarkEqOPNInfo').val(getObject.eqOPNInfo);
            $('#chRBPlacemarkEqGrounding').prop('checked', (getObject.eqGrounding === 1) ? true : false);

            $('#chRBPlacemarkEqOtherLine').prop('checked', (getObject.eqOtherLine === 1) ? true : false);
            $('#chRBPlacemarkEqCommLine').prop('checked', (getObject.eqCommLine === 1) ? true : false);
            $('#chRBPlacemarkEqLamp').prop('checked', (getObject.eqLamp === 1) ? true : false);
            $('#chRBPlacemarkEqAdapter').prop('checked', (getObject.eqAdapter === 1) ? true : false);
            $('#chRBPlacemarkEqAccident').prop('checked', (getObject.eqAccident === 1) ? true : false);
            $('#chRBPlacemarkEqNoUp').prop('checked', (getObject.eqNoUp === 1) ? true : false);

            // можно ли изменить тип точки (если только одна линия отходит, то да)
            // подсчет кол-ва линий от заданной точки
            let myKolPolylines = funKolPolylinesThisPlacemark(mmCurrentPlacemarkMapID);
            // берем все ответвления
            myKolPolylines = myKolPolylines.get('all');
            //console.log("myKolPolylines: " + myKolPolylines);

            let myChangePlacemarkType = (myKolPolylines.length > 1) ? false : true;
            //console.log("myChangePlacemarkType: " + myChangePlacemarkType);

            if (myMultiselect) {
                $("#sRBPlacemarkType").prop('disabled', false);
            } else {
                $("#sRBPlacemarkType").prop('disabled', !myChangePlacemarkType);
            }

            // принудительно обновить выпадающие списки
            $('select').selectric('refresh');

            // показать детали в правой колонке, подсветить активную линию
            funRBview('placemark');

            // фотографии
            let myHTML = '';
            if (getObject.photos != null && getObject.photos.length > 0) {

                (getObject.photos).forEach(function (item, i) {

                    myHTML += "<div class='item'>";
                    myHTML += "<a class='image' data-fancybox='gallery' " +
                        "href='" + mmPathTowerImages + item + "' " +
                        "style='background-image: url(" + mmPathTowerImages + item + ")'" +
                        "onerror='this.style.display = &quot;none&quot;'" +
                        ">";
                    myHTML += "</a>";
                    myHTML += "<i class='close-bt' onClick='funDeletePhoto(" + i + ")' ></i>";
                    myHTML += "</div>";
                });
            }
            $('#dPlacemarkPhotos').html(myHTML)
                .append('<label class="item fileUploadLabel" for="galleryUpload"></label>')
        }
    }

    // ---------------------------------------------------------------
    // показать детали выбранной линии
    function funRBviewPolyline(getObject) {

        console.log("Показать детали выбранной линии: ");
        console.log(getObject);

        if (!mmIsGroupOperation) {

            // показать детали в правой колонке, подсветить активную линию
            funRBview('polyline');

            // значения
            $('#hRBPolylineMapID').val(getObject.mapID); // скрытое поле
            $('#sRBPolylineType').val(getObject.type);
            $('#sRBPolylineWireMark').val(getObject.wireMark);
            $('#sRBPolylineLayingCondition').val(getObject.layingCondition);
            $('#iRBPolylineWireS').val(getObject.wireS);
            $('#iRBPolylineWireN').val(getObject.wireN);
            $('#iRBPolylineWireLength').val(getObject.wireLength);
            $('#iRBPolylineWirePhaseN').val(getObject.wirePhaseN);
            $('#iRBPolylineCabelsN').val(getObject.cabelsN);
            $('#iRBPolylineGabarit').val(getObject.gabarit);
            $('#tRBPolylineCoord').val(getObject.points.join('; \n')); // характерные точки

            // вершины - id и названия
            let myStartMapID = getObject.startMapID;

            let myStartViewName = (mmObjects[myStartMapID].viewName).slice(0, 11);
            if (myStartViewName !== mmObjects[myStartMapID].viewName) myStartViewName += '...';
            let myEndMapID = getObject.endMapID;
            let myEndViewName = (mmObjects[myEndMapID].viewName).slice(0, 11);
            if (myEndViewName !== mmObjects[myEndMapID].viewName) myEndViewName += '...';

            // оборудование коммутационное
            // разьединитель
            $('#lRBPolylineEqDisconnectorStart').text(myStartViewName);
            $('#chRBPolylineEqDisconnectorStart').prop('checked', (getObject.eqDisconnectorStart === 1) ? true : false);
            $('#sRBPolylineEqDisconnectorStartInfo').val(getObject.eqDisconnectorStartInfo);
            $('#lRBPolylineEqDisconnectorEnd').text(myEndViewName);
            $('#chRBPolylineEqDisconnectorEnd').prop('checked', (getObject.eqDisconnectorEnd === 1) ? true : false);
            $('#sRBPolylineEqDisconnectorEndInfo').val(getObject.eqDisconnectorEndInfo);
            // реклоузер
            $('#lRBPolylineEqReklouzerStart').text(myStartViewName);
            $('#chRBPolylineEqReklouzerStart').prop('checked', (getObject.eqReklouzerStart === 1) ? true : false);
            $('#sRBPolylineEqReklouzerStartInfo').val(getObject.eqReklouzerStartInfo);
            $('#lRBPolylineEqReklouzerEnd').text(myEndViewName);
            $('#chRBPolylineEqReklouzerEnd').prop('checked', (getObject.eqReklouzerEnd === 1) ? true : false);
            $('#sRBPolylineEqReklouzerEndInfo').val(getObject.eqReklouzerEndInfo);
            // выключатель нагрузки
            $('#lRBPolylineEqVNaStart').text(myStartViewName);
            $('#chRBPolylineEqVNaStart').prop('checked', (getObject.eqVNaStart === 1) ? true : false);
            $('#sRBPolylineEqVNaStartInfo').val(getObject.eqVNaStartInfo);
            $('#lRBPolylineEqVNaEnd').text(myEndViewName);
            $('#chRBPolylineEqVNaEnd').prop('checked', (getObject.eqVNaEnd === 1) ? true : false);
            $('#sRBPolylineEqVNaEndInfo').val(getObject.eqVNaEndInfo);

            // расчет длины пролета
            $('#sRBPolylineSpanLength').text(funGetDistance(getObject));

            // принудительно обновить выпадающие списки
            $('select').selectric('refresh');

            // видимость полей
            switch (Number(getObject.type)) {
                case 701:
                    $('#tRBPolylineSpanLength').text('длина пролета');
                    $('#tRBPolylineWireMark').text('марка провода');
                    $('#trRBPolylineGabarit').show(0); // габарит
                    $('#trRBPolylineWireN').show(0); // число проводов
                    $('#trRBPolylineWireLength').show(0); // длина провода
                    $('#trRBPolylineWirePhaseN').hide(0); // проводов в фазе
                    $('#trRBPolylineLayingCondition').hide(0); // условие прокладки
                    $('#trRBPolylineCabelsN').hide(0); // кабелей в траншее
                    $('#trRBPolylineCoord').hide(0); // характерные точки
                    break;
                case 702:
                    $('#tRBPolylineSpanLength').text('длина участка');
                    $('#tRBPolylineWireMark').text('марка кабеля');
                    $('#trRBPolylineGabarit').hide(0);
                    $('#trRBPolylineWireN').hide(0);
                    $('#trRBPolylineWireLength').hide(0);
                    $('#trRBPolylineWirePhaseN').show(0);
                    $('#trRBPolylineLayingCondition').show(0);
                    $('#trRBPolylineCabelsN').show(0);
                    $('#trRBPolylineCoord').show(0);
                    break;
            }
            $('#h3RBPolylineType').text(mmObjectsProperties[Number(getObject.type)].name);

            // из полного справочника марок проводов получить отсеченный по напряжению (6,10, 0,4) и типу линии (701, 702)
            funChangeWireMark('polyline', getObject.type, $('#sRBPolylineWireMark').val());
        }
    }

    // ---------------------------------------------------------------
    // показать детали выбранного сегмента
    function funRBviewSegment(getType) {

        if (!mmIsGroupOperation) {

            // показать детали в правой колонке, подсветить активную линию
            funRBview('segment');

            // значения
            $('#sRBSegmentMapID').text(mmCurrentSegmentMapID);
            $('#sRBSegmentKolSpan').text((typeof (mmSegments[mmCurrentSegmentMapID]) !== 'undefined') ? mmSegments[mmCurrentSegmentMapID].length : 0);

            // суммарная длины всех пролетов/участков в сегменте
            let mySegmentSpanLength = 0;
            if (typeof (mmSegments[mmCurrentSegmentMapID]) !== 'undefined') {
                mmSegments[mmCurrentSegmentMapID].forEach(function (item) {

                    // длина текущего пролета/участка
                    mySegmentSpanLength += Number(funGetDistance(mmObjects[item]));
                });
            }
            $('#sRBSegmentSpanLength').text(mySegmentSpanLength);

            // первый пролет/участок сегмента
            if (typeof (mmSegments[mmCurrentSegmentMapID]) !== 'undefined' && mmSegments[mmCurrentSegmentMapID][0] !== 'undefined') {
                // значения от первого пролета/участка
                $('#sRBSegmentWireMark').val(mmObjects[mmSegments[mmCurrentSegmentMapID][0]].wireMark);
                $('#iRBSegmentWireS').val(mmObjects[mmSegments[mmCurrentSegmentMapID][0]].wireS);
                $('#iRBSegmentWireN').val(mmObjects[mmSegments[mmCurrentSegmentMapID][0]].wireN);
                $('#iRBSegmentWireLength').val(mmObjects[mmSegments[mmCurrentSegmentMapID][0]].wireLength);
                $('#iRBSegmentWirePhaseN').val(mmObjects[mmSegments[mmCurrentSegmentMapID][0]].wirePhaseN);
                $('#sRBSegmentLayingCondition').val(mmObjects[mmSegments[mmCurrentSegmentMapID][0]].layingCondition);
                $('#iRBSegmentCabelsN').val(mmObjects[mmSegments[mmCurrentSegmentMapID][0]].cabelsN);
            } else {
                $('#sRBSegmentWireMark').val(null);
                $('#iRBSegmentWireS').val(null);
                $('#iRBSegmentWireN').val(null);
                $('#iRBSegmentWireLength').val(null);
                $('#iRBSegmentWirePhaseN').val(null);
                $('#sRBSegmentLayingCondition').val(null);
                $('#iRBSegmentCabelsN').val(null);
            }

            // видимость полей
            switch (Number(getType)) {
                case 701:
                    $('#tRBSegmentKolSpan').text('Кол-во пролетов');
                    $('#tRBSegmentSpanLength').text('Итоговая длина всех пролетов');
                    $('#tRBSegmentWireMark').text('марка провода');
                    $('#trRBSegmentWireN').show(0); // число проводов
                    $('#trRBSegmentWireLength').show(0); // длина провода
                    $('#trRBSegmentWirePhaseN').hide(0); // проводов в фазе
                    $('#trRBSegmentLayingCondition').hide(0); // условие прокладки
                    $('#trRBSegmentCabelsN').hide(0); // кабелей в траншее
                    break;
                case 702:
                    $('#tRBSegmentKolSpan').text('Кол-во участков');
                    $('#tRBSegmentSpanLength').text('Итоговая длина всех участков');
                    $('#tRBSegmentWireMark').text('марка кабеля');
                    $('#trRBSegmentWireN').hide(0);
                    $('#trRBSegmentWireLength').hide(0);
                    $('#trRBSegmentWirePhaseN').show(0);
                    $('#trRBSegmentLayingCondition').show(0);
                    $('#trRBSegmentCabelsN').show(0);
                    break;
            }

            // из полного справочника марок проводов получить отсеченный по напряжению (6,10, 0,4) и типу линии (701, 702)
            funChangeWireMark('segment', getType, $('#sRBSegmentWireMark').val());
        }
    }

    // ---------------------------------------------------------------
    // показать детали маркера предполагаемой новой точки
    function funRBviewMarkerNewPoint() {

        // не массовая ли загрузка и имеет ли права
        if (!mmIsGroupOperation && mmUserHasEditRights) {

            // показать детали в правой колонке, подсветить активную линию
            funRBview('markerNewPoint');

            // значения
            $('#sRBMarkerNewPointLat').text(mmCurrentCoords[0]);
            $('#sRBMarkerNewPointLong').text(mmCurrentCoords[1]);

            // удалить маркер предполагаемой новой точки
            funMarkerNewPointDelete();
            // добавить маркер предполагаемой новой точки
            funMarkerNewPointAdd();
        }
    }

    // ---------------------------------------------------------------
    // показать детали в правой колонке, подсветить активную линию
    function funRBview(getRegim) {

        if (!mmIsGroupOperation) {

            // все скрыть
            $("#dRBMarkerNewPoint").css("display", "none");
            $("#dRBAcline").css("display", "none");
            $("#dRBSegment").css("display", "none");
            $("#dRBPolyline").css("display", "none");
            $("#dRBPlacemark").css("display", "none");

            // показать нужный блок - по-умолчанию для ЛЭП
            let myNeedBlockView = 'dRBAcline';
            switch (getRegim) {
                case "markerNewPoint":
                    myNeedBlockView = 'dRBMarkerNewPoint';
                    break;
                case "placemark":
                    myNeedBlockView = 'dRBPlacemark';
                    break;
                case "polyline":
                    myNeedBlockView = 'dRBPolyline';
                    break;
                case "segment":
                    myNeedBlockView = 'dRBSegment';
                    break;
            }
            $("#" + myNeedBlockView).css("display", "block");

            if (getRegim !== 'markerNewPoint') {
                // удалить маркер предполагаемой новой точки
                funMarkerNewPointDelete();
            }

            if (getRegim === 'polyline' || getRegim === 'segment') {
                if (getRegim !== 'polyline') {
                    // подсветить/убрать подстветку с активной линии на карте
                    funPolylineActive('off');
                } else {
                    // подсветить или убрать подстветку с активного сегмента на карте
                    funSegmentActive('off');
                }
            } else {
                // подсветить/убрать подстветку с активной линии на карте
                funPolylineActive('off');
                // подсветить или убрать подстветку с активного сегмента на карте
                funSegmentActive('off');
            }
        }
    }

</script>
