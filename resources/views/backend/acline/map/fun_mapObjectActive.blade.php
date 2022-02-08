<script type="text/javascript">

    // подсветить активный обьект

    // ---------------------------------------------------------------
    // подсветить/убрать подстветку с активной линии на карте
    function funPolylineActive(regim = null) {

        if (regim === 'off') {
            mmLastPolylineMapID = mmCurrentPolylineMapID;
            mmCurrentPolylineMapID = null;
        }

        // убрать подсветку со "старой" активной линии
        if (mmLastPolylineMapID != null) {
            funPolylineOneActive(mmLastPolylineMapID, false);
        }

        // подсветить "новую" активную линию
        if (mmCurrentPolylineMapID != null) {
            funPolylineOneActive(mmCurrentPolylineMapID, true);
        }
    }

    // ---------------------------------------------------------------
    // подсветить или убрать подстветку на линии
    function funPolylineOneActive(getMapID, getActive = false) {

        let myStrokeColor = (getActive) ? 'strokeColorActive' : 'strokeColor';
        // проверка является ли линия с Потребителем
        mmCollection.get(getMapID).options.set('strokeColor', mmObjectsProperties[mmObjects[getMapID].lineToCustomer][myStrokeColor]);
    }

    // ---------------------------------------------------------------
    // подсветить или убрать подстветку с активного сегмента на карте
    function funSegmentActive(regim = null) {

        if (regim === 'off') {
            mmLastSegmentMapID = mmCurrentSegmentMapID;
            mmCurrentSegmentMapID = null;
        }

        // убрать подсветку со "старого" активного сегмента
        if (mmLastSegmentMapID != null) {
            mmSegments[mmLastSegmentMapID].forEach(function (item) {
                // подсветить или убрать подстветку на линии
                funPolylineOneActive(item, false);
            });
        }

        // подсветить "новый" активный сегмент
        if (mmCurrentSegmentMapID != null) {
            mmSegments[mmCurrentSegmentMapID].forEach(function (item) {
                // подсветить или убрать подстветку на линии
                funPolylineOneActive(item, true);
            });
        }
    }

    // ---------------------------------------------------------------
    // убрать подсветку со всех
    function funActiveAllOff() {
        if (mmObjects.length > 0) {
            for (let myObjectN = 0; myObjectN < mmObjects.length; myObjectN++) {

                // текущий обьект
                let myCurrentObject = mmObjects[myObjectN];

                // убрать активность
                mmObjects[myObjectN].isActive = false;

                // повторная перекраска иконки
                switch (myCurrentObject.mapType) {
                    case "placemark":
                        funSVGmake(myCurrentObject.mapID);
                        break;
                    case "polyline":
                        funPolylineOneActive(myCurrentObject.mapID, false);
                        break;
                }
            }
        }
    }

</script>
