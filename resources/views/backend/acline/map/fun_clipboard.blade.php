<script type="text/javascript">

    // ---------------------------------------------------------------
    // множественный выбор

    // ---------------------------------------------------------------
    // добавить обьект во множественный выбор
    function funClipboardAdd(getMapID) {

        if (getMapID != null) {
            // проверить, такая точка уже есть в массиве или нет
            if (mmClipboard.includes(Number(getMapID))) {
                // да, уже в массиве есть - убрать

                for (let i = 0; i < mmClipboard.length; i++) {
                    if (Number(mmClipboard[i]) === Number(getMapID)) {
                        mmClipboard.splice(i, 1);
                    }
                }
                // убрать активность
                mmObjects[getMapID].isActive = false;
                // создать иконку svg
                funSVGmake(mmCurrentPlacemarkMapID);
                // сообщение Пользователю
                toastr.success('[' + mmObjects[getMapID].viewName + '] удален из множественного выбора...');
            } else {
                // нет, в массиве нет

                if (mmObjects[getMapID]['type'] === 'tower') {
                    // записать в массив
                    mmClipboard.push(Number(getMapID));
                    // добавить активность
                    mmObjects[getMapID].isActive = true;
                    // создать иконку svg
                    funSVGmake(mmCurrentPlacemarkMapID);
                    // сообщение Пользователю
                    toastr.success('[' + mmObjects[getMapID]['viewName'] + '] добавлено в множественный выбор...');
                } else {
                    // сообщение Пользователю
                    toastr.warning('В множественный выбор можно добавить только [Опоры]...');
                }
            }
        }

        if (mmClipboard.length > 0) {
            // множественный выбор есть

            // отобразить и очистить окно сообщений
            $("#dClipboard").css("display", "block");
            $('#dClipboardMessage').empty();

            // записать список в окно сообщений
            let myStroka = 'Множественный выбор: ';
            mmClipboard.forEach(function (item) {
                myStroka = myStroka + mmObjects[item]['viewName'] + ','
            });
            $('#dClipboardMessage').html(myStroka);
        } else {
            // множественного выбора нет
            // скрыть окно сообщений
            $("#dClipboard").css("display", "none");
        }

        // показать детали выбранной точки или множественного списка, показать детали, подсветить активную, создать иконку svg
        funRBviewPlacemark(mmObjects[getMapID]);

        //console.log(mmClipboard);
    }

    // ---------------------------------------------------------------
    // очистка множестсвенного выбора
    function funClipboardClear() {

        // запомнить, что было в массиве
        let myClipboard_Old = JSON.parse(JSON.stringify(mmClipboard));

        // очистить массив
        mmClipboard = [];

        // в цикле пройтись по всем точкам
        myClipboard_Old.forEach(function (myMapID) {
            // убрать активность
            mmObjects[myMapID].isActive = false;
            // снять выделени с указанных точек
            funSVGmake(myMapID);
        });

        // очистить и скрыть окно сообщений
        $('#dClipboardMessage').empty();
        $("#dClipboard").css("display", "none");

        // сообщение пользователю
        toastr.success('Список для множественного выбора очищен...');
    }

    // ---------------------------------------------------------------
    // удалить с карты обьекты, которые не вошли в обнаруженные сегменты
    async function funDeleteWithoutSegment() {

        // накопительные массивы
        let myObjectsFromSegments = [];
        let myObjectsForDelete = [];

        // разбивка линии на сегменты
        funSegments();
        //console.log(mmSegments);

        if (mmSegments.length > 0) {
            for (let mySegmentN = 0; mySegmentN < mmSegments.length; mySegmentN++) {
                for (let mySegmentSpanN = 0; mySegmentSpanN <= mmSegments[mySegmentN].length - 1; mySegmentSpanN++) {

                    // текущий id-пролета
                    let mySpanMapID = mmSegments[mySegmentN][mySegmentSpanN];
                    // start пролета
                    let myStartMapID = mmObjects[mySpanMapID].startMapID;
                    // end пролета
                    let myEndMapID = mmObjects[mySpanMapID].endMapID;

                    // добавить в накопительный массив, если еще не было такого обьекта
                    myObjectsFromSegments.push(mySpanMapID);
                    if (!myObjectsFromSegments.includes(myStartMapID)) {
                        myObjectsFromSegments.push(myStartMapID);
                    }
                    if (!myObjectsFromSegments.includes(myEndMapID)) {
                        myObjectsFromSegments.push(myEndMapID);
                    }
                }
            }
        }
        //console.log("Накопительный массив:");
        //console.log(myObjectsFromSegments);

        // прогнать массив обьектов и сравнить его с накопительным
        let myRaznicaN = 0;
        if (mmObjects.length > 0) {
            for (let myObjectN = 0; myObjectN < mmObjects.length; myObjectN++) {
                // текущий обьект
                let myCurrentObject = mmObjects[myObjectN];
                if (myCurrentObject.deleted === false) {
                    if (!myObjectsFromSegments.includes(myCurrentObject.mapID)) {
                        // обьект не нашелся
                        // добавить активность
                        mmObjects[myObjectN].isActive = true;
                        // запомнить
                        myObjectsForDelete.push(myCurrentObject.mapID);
                        myRaznicaN++;
                    } else {
                        // обьект нашелся
                        // убрать активность
                        mmObjects[myObjectN].isActive = false;
                    }

                    // повторная перекраска
                    switch (myCurrentObject.mapType) {
                        case "placemark":
                            funSVGmake(myCurrentObject.mapID);
                            break;
                        case "polyline":
                            funPolylineOneActive(myCurrentObject.mapID, mmObjects[myObjectN].isActive);
                            break;
                    }
                }
            }
        }
        console.log("Потерянных обьектов: " + myRaznicaN);
        console.log(myObjectsForDelete);

        // задержка, чтоб красным успело перерисоваться
        await sleep(300);

        if (myObjectsForDelete.length > 0) {
            // потерянные обьекты есть
            // вопрос Пользователю
            if (confirm(
                'Найдено "потерянных" обьектов (выделены красным): ' + myRaznicaN +
                '\n Вы уверены, что хотите удалить их?')) {

                // да, удалить
                // признак групповой операции
                mmIsGroupOperation = true;
                for (let myObjectN = 0; myObjectN < myObjectsForDelete.length; myObjectN++) {
                    // текущий обьект
                    let myCurrentObject = mmObjects[myObjectsForDelete[myObjectN]];

                    // удаление обьекта
                    switch (myCurrentObject.mapType) {
                        case "placemark":
                            toastr.info('Удаление [' + myCurrentObject.viewName + ']...');
                            console.log("Удаление myCurrentObject.mapID = " + myCurrentObject.mapID);
                            funPlacemarkDelete(myCurrentObject.mapID);
                            break;
                        case "polyline":
                            funPolylineDelete(myCurrentObject.mapID);
                            break;
                    }
                }
                // признак групповой операции
                mmIsGroupOperation = false;
            } else {
                // нет, не удалять

                // убрать подсветку со всех
                funActiveAllOff();
                return;
            }
        } else {
            // потерянных обьектов нет
            // сообщение пользователю
            toastr.success('Не обнаружено потерянных обьектов...');
        }

        // показать детали текущей ЛЭП
        funRBviewAcline();
    }
</script>
