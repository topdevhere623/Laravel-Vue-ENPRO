<script type="text/javascript">

    // ---------------------------------------------------------------
    // генерация нового порядкового номера точки от имени родителя
    function funGenerateNameNPlacemark(getName) {

        getName = String(getName);

        let myHave = true;
        let myNewName = '';
        let myBazis = '';
        let myUvelichivat = 0;
        let myRazdelitel = '';
        let myPosRazdelitel = null;
        let thisOtvetvlenie = null;

        // подсчет кол-ва линий от заданной точки
        let myKolPolylines = funKolPolylinesThisPlacemark(mmCurrentPlacemarkMapID);
        // берем все ответвления
        myKolPolylines = myKolPolylines.get('all');

        if (myKolPolylines.length > 1) {
            // это ответвление
            thisOtvetvlenie = true;
            //console.log("это ответвление");
            myBazis = getName;
            myUvelichivat = 0;
        } else {
            // это не ответвление
            thisOtvetvlenie = false;
            //console.log("это не ответвление");

            // просматривать справа налево в поисках первого разделителя (точки, тире или дефиса)
            for (let i = (getName.length - 1); i >= 0; i--) {
                if (getName[i] === '.') {
                    myPosRazdelitel = i;
                    myRazdelitel = '.';
                    break;
                }
                if (getName[i] === '-') {
                    myPosRazdelitel = i;
                    myRazdelitel = '-';
                    break;
                }
                if (getName[i] === '/') {
                    myPosRazdelitel = i;
                    myRazdelitel = '/';
                    break;
                }
            }

            if (myRazdelitel === '') {
                // разделителя нет
                //console.log("разделителя нет");

                // нужно разделить начальные буквы до цифр
                for (let i = (getName.length - 1); i >= 0; i--) {
                    if (getName[i] === '0' || getName[i] === '1' || getName[i] === '2' || getName[i] === '3' || getName[i] === '4' || getName[i] === '5' || getName[i] === '6' || getName[i] === '7' || getName[i] === '8' || getName[i] === '9') {
                    } else {
                        myBazis = getName.slice(0, i);
                        myUvelichivat = Number(getName.slice(i + 1, getName.length));
                        break;
                    }
                }
            } else {
                // разделитель есть
                //console.log("разделитель есть");

                myBazis = getName.slice(0, myPosRazdelitel);
                myUvelichivat = Number(getName.slice(myPosRazdelitel + 1, getName.length));
            }
        }

        let i = 0;
        while (myHave) {
            i++;
            // предполагаемое новое имя
            //console.log("myBazis: " + myBazis);
            //console.log("myUvelichivat: " + myUvelichivat);

            if (thisOtvetvlenie) {
                // это ответвление
                myNewName = myBazis + '.' + (Number(myUvelichivat) + i);
            } else {
                // это не ответвление
                if (myRazdelitel === '') {
                    // разделителя нет
                    myNewName = myBazis + (Number(myUvelichivat) + i);
                } else {
                    // разделитель есть
                    myNewName = myBazis + myRazdelitel + (Number(myUvelichivat) + i);
                }
            }

            // проверка существования имени точки
            myHave = funTestNameNPlacemark(myNewName);
        }

        //console.log("Новое имя: " + myNewName);
        // возвращаемый параметр
        return String(myNewName);
    }

    // ---------------------------------------------------------------
    // проверка существования имени точки
    function funTestNameNPlacemark(getName) {

        let myReturn = false;
        mmObjects.forEach(function (item) {
            if (String(item.viewName) === String(getName) && item.deleted === false) {
                myReturn = true;
            }
        });
        // возвращаемый параметр
        return myReturn
    }

    // ---------------------------------------------------------------
    // удалить фото
    function funDeletePhoto(photoN) {

        // вопрос Пользователю
        if (!confirm('Вы уверены, что хотите удалить фото?')) return;

        mmObjects[mmCurrentPlacemarkMapID].photos.splice(photoN, 1);
        // показать детали выбранной точки или множественного списка, показать детали, подсветить активную, создать иконку svg
        funRBviewPlacemark(mmObjects[mmCurrentPlacemarkMapID]);
    }

    // ---------------------------------------------------------------
    // очистить карту и глобальные переменные, которые можно обнулять
    function funMapClear() {

        // удалить маркер предполагаемой новой точки (если есть)
        funMarkerNewPointDelete();

        // удалить все обьекты в коллекциях
        if (typeof (mmCollection) !== 'undefined') mmCollection.removeAll();
        if (typeof (mmCollectionOther) !== 'undefined') mmCollectionOther.removeAll();
        if (typeof (mmCollectionLamp) !== 'undefined') mmCollectionLamp.removeAll();
        if (typeof (mmCollectionDoubleAcline) !== 'undefined') mmCollectionDoubleAcline.removeAll();
        // удалить все обьекты с карты вместе с коллекциями
        //myMap.geoObjects.removeAll();

        // очистить поисковый адаптер
        mySearchControl.options.set('provider', new MapSearchProvider());

        // иницилизация глобальных переменных, которые можно обнулять
        funGlobalVarsCanResetInit();

        // показать детали текущей ЛЭП
        funRBviewAcline();
    }

    // ---------------------------------------------------------------
    // получить предыдущий/следующий обьект
    function funBackForwardObject(regim) {

        if (mmCurrentPlacemarkMapID != null) {
            // сканировать весь список - взять только точки
            let myArr = [];
            mmObjects.forEach(function (item, i, arr) {
                if (item.mapType === 'placemark' && item.deleted === false) {
                    myArr.push(item.mapID);
                }
            });
            if (myArr.length > 0) {
                // первый и последний элементы
                let myFirst = myArr[0];
                let myLast = myArr[myArr.length - 1];
                // порядок текущего элемента
                let myCurrent;
                myArr.forEach(function (item, i, arr) {
                    if (item === mmCurrentPlacemarkMapID) {
                        myCurrent = i;
                    }
                });
                // новый запрашиваемый элемент
                let myReturn;
                if (regim === 'back') {
                    myReturn = typeof (myArr[myCurrent - 1]) !== 'undefined' ? myArr[myCurrent - 1] : myLast;
                } else {
                    myReturn = typeof (myArr[myCurrent + 1]) !== 'undefined' ? myArr[myCurrent + 1] : myFirst;
                }
                // переопределить ID текущей и прошлой точки, показать детали, подсветить активную, создать иконку svg
                funChangeLastCurrentPlacemark(myReturn);
                // "перелет" по карте
                funFlyTo('mapID', mmCurrentPlacemarkMapID, false);
            }
        }
    }

    // ---------------------------------------------------------------
    // замена на 'undefined' или 'no' на null (или переведет в Number)
    function funChangeUndefinedOrNoToNull(getVar, getRegim = null) {

        // если null, то ничего не делать
        if (getVar == null) return null;

        let myReturn = null;
        if (typeof (getVar) === 'undefined' || getVar === 'no' || getVar === 'null') {
            // заменить на null
            myReturn = null;
        } else {
            // заменить
            myReturn = (getRegim === 'number') ? Number(getVar) : getVar;
        }

        return myReturn;
    }

    // ---------------------------------------------------------------
    // вывод времени выполнения в консоль
    function DebugTimeConsole(getFunctionName, getDebugStart, getDebugEnd) {
        let myReturn = '';
        let debugTime = getDebugEnd - getDebugStart;
        if (debugTime < 1000) {
            myReturn = debugTime + " мс.";
        } else {
            if ((debugTime / 1000) > 1 && (debugTime / 1000) < 60) {
                myReturn = Math.round(debugTime / 1000) + " секунд!!! ( = " + debugTime + " мс.)";
            } else {
                myReturn = (debugTime / 1000 / 60) + " минут!!!!!! ( = " + debugTime + " мс.)";
            }
        }
        console.log("Время выполнения " + getFunctionName + "(): " + myReturn);
    }

    // ---------------------------------------------------------------
    // текущий класс напряжения
    function funCurrentBaseVoltage() {

        // записать в глобальную переменную
        mmDefaultBaseVoltage = $('#sRBAclineBaseVoltage').val();
        //console.log("напряжение изменено на  = " + mmDefaultBaseVoltage);
        // доступаные значения в выпадающем списке
        if (mmDefaultBaseVoltage === '6' || mmDefaultBaseVoltage === '10') {
            $("#sRBPlacemarkType option[value='customer']").hide();
        } else {
            $("#sRBPlacemarkType option[value='customer']").show();
        }

        // из полного справочника марок проводов получить отсеченный по напряжению (6,10, 0,4) и типу линии (701, 702)
        funChangeWireMark('all');
    }

    // ---------------------------------------------------------------
    // зафиксировать объекты на карте
    function funChMapViewFix() {

        // записать значение переключателя в глобальную переменную
        mmChMapViewFix = ($('#chMapViewFix').is(':checked')) ? 1 : 0;

        // сканировать все точки
        mmObjects.forEach(function (item) {
            if (item.mapType === 'placemark' && item.deleted === false) {
                mmCollection.get(item.mapID).options.set('draggable', !mmChMapViewFix);
            }
        });
    }

    // ---------------------------------------------------------------
    // редактировать кабельные линии
    function funChMapViewPolylineEdit() {

        // записать значение переключателя в глобальную переменную
        mmChMapViewPolylineEdit = ($('#chMapViewPolylineEdit').is(':checked')) ? 1 : 0;

        // сканировать все линии
        mmObjects.forEach(function (item) {
            if (item.mapType === 'polyline' && Number(item.type) === 702 && item.deleted === false) {

                if (mmChMapViewPolylineEdit === 1) {
                    // начать редактировать
                    mmCollection.get(item.mapID).editor.startEditing();
                    //mmCollection.get(item.mapID).editor.startDrawing(); // добавление новых вершин
                } else {
                    // завершить редактирование
                    mmCollection.get(item.mapID).editor.stopEditing();
                    //mmCollection.get(item.mapID).editor.stopDrawing(); // добавление новых вершин
                    // новые характерные точки
                    let points = mmCollection.get(item.mapID).geometry.getCoordinates();
                    //console.log(points);

                    // исключить начало и конец
                    //points = points.slice(0, points.length - 1);
                    //points = points.slice(1);
                    //console.log(points);

                    // сохранить новые характерные точки
                    mmObjects[item.mapID].points = points;
                    // создать иконку svg для самой точки (например, разьединитель чтоб был параллельно линиии)
                    // для вершины start
                    funSVGmake(item.startMapID);
                    // для вершины end
                    funSVGmake(item.endMapID);
                }
            }
        });
    }

    // ---------------------------------------------------------------
    // паспорт линии
    function funGetPassport() {

        // сперва сохранить
        funSave();

        // данные ЛЭП
        let myAclineID = Number($('#sRBAclineID').text());

        if (myAclineID > 0) {
            // id линии определен

            // URL паспорта
            // window.location.protocol
            let myURL = 'http://' + document.location.hostname + '/admin/acline/report/1/' + myAclineID;

            // открыть паспорт в отдельном окне
            //location.href = myURL; // переход в этом же окне
            window.open(myURL, '_blank'); // открыть в новом окне
        } else {
            // id линии не определен
            // всплывающая подсказка
            toastr.warning('Извините, невозможно сгенерировтаь паспорт - нет id линии...');
        }
    }

    // ---------------------------------------------------------------
    // иммитация нажатия на кнопку Применить
    function funBApplyClick() {

        $('#bApplyPlacemark').click();
    }

    // ---------------------------------------------------------------
    // задержка скрипта (например, чтоб на карте успело красным перерисоваться перед alert-ом)
    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

</script>
