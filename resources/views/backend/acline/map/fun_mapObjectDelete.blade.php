<script type="text/javascript">

    // удаление активного обьекта с карты

    // ---------------------------------------------------------------
    // удаление активной точки
    function funPlacemarkDelete(getMapID = null) {

        // использовать глобальную переменную, если ничего не передали
        let myCurrentPlacemarkMapID = (getMapID === null) ? mmCurrentPlacemarkMapID : getMapID;

        if (myCurrentPlacemarkMapID != null) {

            if (mmObjects[myCurrentPlacemarkMapID].isDoubleAcline === true) {
                // эта точка участвует в совместном подвесе
                // сообщение пользовтелю
                alert('Извините, эта точка участвует в совместном подвесе. ' +
                    '\n Удалить ее нельзя!');
                // досрочный выход
                return;
            }

            // вопрос Пользователю
            if (!mmIsGroupOperation) {
                if (!confirm('Вы уверены, что хотите удалить выделенную точку и связанные с ней линии?')) return;
            }

            // удалить линии, где упоминается эта точка, в конце или в начале
            mmObjects.forEach(function (item) {
                if (item.startMapID === myCurrentPlacemarkMapID || item.endMapID === myCurrentPlacemarkMapID) {
                    // скрыть с карты
                    mmCollection.get(item.mapID).options.set('visible', false);
                    // записать в массив
                    mmObjects[item.mapID].deleted = true;
                }
            });

            // удалить точку с карты
            // скрыть с карты
            mmCollection.get(myCurrentPlacemarkMapID).options.set('visible', false);
            // записать в массив
            mmObjects[myCurrentPlacemarkMapID].deleted = true;

            if (myCurrentPlacemarkMapID === mmCurrentPlacemarkMapID) {
                // указанное ID совпадает с глобальной переменной

                // очистить глобальную переменную
                mmLastPlacemarkMapID = null;
                mmCurrentPlacemarkMapID = null;
                mmCurrentPolylineMapID = null;
                mmLastPolylineMapID = null;
                mmCurrentSegmentMapID = null;
                mmLastSegmentMapID = null;
            }

            if (!mmIsGroupOperation) {
                // показать детали текущей ЛЭП
                funRBviewAcline();
            }
        }
    }

    // ---------------------------------------------------------------
    // удаление активной линии
    function funPolylineDelete(getMapID = null) {

        // использовать глобальную переменную, если ничего не передали
        let myCurrentPolylineMapID = (getMapID === null) ? mmCurrentPolylineMapID : getMapID;

        if (myCurrentPolylineMapID != null) {

            // вопрос Пользователю
            if (!mmIsGroupOperation) {
                if (!confirm('Вы уверены, что хотите удалить выделенную линию?')) return;
            }

            // удалить линию с карты
            // скрыть с карты
            mmCollection.get(myCurrentPolylineMapID).options.set('visible', false);
            // записать в массив
            mmObjects[myCurrentPolylineMapID].deleted = true;

            if (myCurrentPolylineMapID === mmCurrentPolylineMapID) {
                // указанное ID совпадает с глобальной переменной

                // очистить глобальную переменную
                mmCurrentPolylineMapID = null;
                mmLastPolylineMapID = null;
                mmCurrentSegmentMapID = null;
                mmLastSegmentMapID = null;
            }

            if (!mmIsGroupOperation) {
                // показать детали текущей ЛЭП
                funRBviewAcline();
            }
        }
    }

</script>
