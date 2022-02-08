<script type="text/javascript">

    // содержимое модальных окон

    // ---------------------------------------------------------------
    // содержимое модального окна настроек отображения карты
    function funModalMapSettings() {

        // переключатель Другие линии
        let myChMapViewNearObjects =
            '<div class="checkbox"><label>' +
            '<input type="checkbox" id="chMapViewNearObjects" onchange="funChMapViewNearObjects()"' + (mmChMapViewNearObjects === 1 ? ' checked' : '') + '>' +
            '<span class="box"></span>' +
            '<span>Другие линии</span>' +
            '</label>' +
            '</div>';

        // переключатель Линии освещения
        let myChMapViewLamp =

            '<div class="checkbox"><label>' +
            '<input type="checkbox" id="chMapViewLamp" onchange="funChMapViewLamp()"' + (mmChMapViewLamp === 1 ? ' checked' : '') + '>' +
            '<span class="box"></span>' +
            '<span>Линии освещения</span>' +
            '</label>' +
            '</div>';

        // переключатель Линии совместного подвеса
        let myChMapViewDoubleAcline =
            '<div class="checkbox"><label>' +
            '<input type="checkbox" id="chMapViewDoubleAcline" onchange="funChMapViewDoubleAcline()"' + (mmChMapViewDoubleAcline === 1 ? ' checked' : '') + '>' +
            '<span class="box"></span>' +
            '<span>Линии совместного подвеса</span>' +
            '</label>' +
            '</div>';

        // переключатель Зафиксировать карту
        let myChMapViewFix =
            '<div class="checkbox">' +
            '<label>' +
            '<input type="checkbox" id="chMapViewFix" onchange="funChMapViewFix()"' + (mmChMapViewFix === 1 ? ' checked' : '') + '>' +
            '<span class="box"></span>' +
            '<span>Зафиксировать карту</span>' +
            '</label>' +
            '</div>';

        // переключатель Редактировать кабельные линии
        let myChMapViewPolylineEdit =
            '<div class="checkbox">' +
            '<label>' +
            '<input type="checkbox" id="chMapViewPolylineEdit" onchange="funChMapViewPolylineEdit()"' + (mmChMapViewPolylineEdit === 1 ? ' checked' : '') + '>' +
            '<span class="box"></span>' +
            '<span>Редактировать кабельные линии</span>' +
            '</label>' +
            '</div>';

        {{-- кнопка поиск "потерянных" --}}
        let myButtonDeleteWithoutSegment =
            '<div class="checkbox">' +
            '<button class="link-icon" onclick="funDeleteWithoutSegment()">' +
            '<span class="icon icon-bucket"></span>Поиск "потерянных"' +
            '</button>' +
            '</div>';

        // итоговое содержимое контента
        let MyContent =
            '<div class="row">' +
            '<div class="col-lg-6">' +
            myChMapViewNearObjects +
            myChMapViewLamp +
            myChMapViewDoubleAcline +
            myChMapViewFix +
            myChMapViewPolylineEdit +
            '</div>' +
            '<div class="col-lg-6">' +
            myButtonDeleteWithoutSegment +
            '</div>' +
            '</div>';

        // показать готовое модальное окно
        funModalOpen('Настройки отображения карты', MyContent);
    }

    // ---------------------------------------------------------------
    // показать готовое модальное окно
    function funModalOpen(getTitle, getContent) {

        //console.log("Показать модальное окно с содержиымым:");
        //console.log(getContent);

        // очистить модальное окно
        $('#modalMessageContent').empty();
        // заголовок модального окна
        $('#modalMessageTitle').text(getTitle);
        // содержимое модального окна
        $('#modalMessageContent').html(getContent);
        // показать модальное окно
        $('#modalMessage').modal('show');
    }

    // ---------------------------------------------------------------
    // содержимое модального окна выбора способа добавления опоры - присоедить или поглотить
    // !!! НЕ ИСПОЛЬЗУЕТСЯ - этот шаг пропускаем
    function funModalAddTowerFromOther(getMapID) {

        // текущий обьект на карте (выделен красным)
        if (mmCurrentPlacemarkMapID === null) {
            alert('Извините, текущий обьект на карте еще не определен! \n Сперва укажите его, пожалуйста!');
            return;
        }
        let myObject = mmObjects[mmCurrentPlacemarkMapID];
        //console.log("В модальном обьект текущий с карты");
        //console.log(myObject);

        // получить обьект добавления с другой линии
        let myObjectOther = funAddTowerFromOther_MakeObject(getMapID);
        //console.log("В модальном обьект с другой линии");
        //console.log(myObjectOther);

        // кнопки
        let MyContentButtonMergeOther = '<button type="button" class="btn btn-blue" onClick="funAddTowerFromOther_MergeOther(' + getMapID + ')">' +
            'Взять другую опору за основу! (присоединить текущую)' +
            '</button>';
        let MyContentButtonMergeYour = '<button type="button" class="btn btn-danger" onClick="funAddTowerFromOther_MergeYour(' + getMapID + ')" disabled>' +
            'Взять текущую опору за основу! (поглотить другую)' +
            '</button>';

        // изображения
        let MyContentPhotosMergeOther = '<div>';
        (myObjectOther.photos).forEach(function (item) {

            MyContentPhotosMergeOther += "<div>";
            MyContentPhotosMergeOther += "<a class='image' data-fancybox='gallery' href='" + mmPathTowerImages + item + "'>";
            MyContentPhotosMergeOther += "<img src='" + mmPathTowerImages + item + "' class='modal_merge_other_photo' onerror='this.style.display = &quot;none&quot;'>";
            MyContentPhotosMergeOther += "</a>";
            MyContentPhotosMergeOther += "</div>";
        });
        MyContentPhotosMergeOther += '</div>';

        let MyContentPhotosMergeYour = '<div>';
        (myObject.photos).forEach(function (item) {

            MyContentPhotosMergeYour += "<div>";
            MyContentPhotosMergeYour += "<a class='image' data-fancybox='gallery' href='" + mmPathTowerImages + item + "'>";
            MyContentPhotosMergeYour += "<img src='" + mmPathTowerImages + item + "' class='modal_merge_other_photo' onerror='this.style.display = &quot;none&quot;'>";
            MyContentPhotosMergeYour += "</a>";
            MyContentPhotosMergeYour += "</div>";
        });
        MyContentPhotosMergeYour += '</div>';

        // итоговое содержимое контента
        let MyContent =
            '<div class="row">' +
            '<div class="col-lg-6">' +
            '<h4>Обьект с другой линии</h4>' +
            MyContentButtonMergeOther +
            '<br> ID: ' + myObjectOther.dbID +
            '<br> Диспетчерский номер: ' + myObjectOther.viewName +
            '<br> Марка: ' + myObjectOther.towerInfo +
            '<br> Материал: ' + myObjectOther.towerMaterial +
            '<br> Назначение: ' + myObjectOther.towerKind +
            '<br> Конструкция: ' + myObjectOther.towerConstruction +
            MyContentPhotosMergeOther +
            '</div>' +
            '<div class="col-lg-6">' +
            '<h4>Текущий обьект на карте</h4>' +
            MyContentButtonMergeYour +
            '<br> ID: ' + myObject.dbID +
            '<br> Диспетчерский номер: ' + myObject.viewName +
            '<br> Марка: ' + myObject.towerInfo +
            '<br> Материал: ' + myObject.towerMaterial +
            '<br> Назначение: ' + myObject.towerKind +
            '<br> Конструкция: ' + myObject.towerConstruction +
            MyContentPhotosMergeYour +
            '</div>' +
            '</div>';

        // показать готовое модальное окно
        funModalOpen('Укажите, пожалуйста, способ добавления опоры', MyContent);
    }
</script>
