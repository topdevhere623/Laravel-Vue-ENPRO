<script type="text/javascript">

    // все что связанос "перелетом: по карте

    // ---------------------------------------------------------------
    // "перелет" по карте
    function funFlyTo(getRegim = null, getParam, getNeedMarker = null) {

        // режим работы ('mapID' или 'coords')
        if (getRegim != null) {

            let myCoords = null;
            switch (getRegim) {
                case 'mapID':
                    // по mapID
                    console.log("Перелет по mapID");
                    if (getParam != null) {
                        myCoords = mmCollection.get(getParam).geometry.getCoordinates();
                    }
                    break;
                case 'coords':
                    // по координатам
                    console.log("Перелет по coords");
                    myCoords = getParam;
                    break;
            }

            // перелет по координатам
            if (myCoords != null) {
                console.log("Координаты перелета:");
                console.log(myCoords);
                myMap.panTo(myCoords, {flying: 1});

                // нужен ли маркер
                if (getNeedMarker) {
                    // разместить маркер перелета на карте
                    myFunMarkerFlyAdd(myCoords);
                }
            }
        }
    }

    // ---------------------------------------------------------------
    // разместить маркер перелета на карте
    function myFunMarkerFlyAdd(getCoords) {

        let myName = 'Место поиска';
        mmMarkerFly = new ymaps.Placemark(
            getCoords, // координаты метки объекта
            {
                hintContent: myName, // подсказка метки
                balloonContent:
                    '<div class="ya_map">' +
                    myName + '<br \/>' +
                    '</div>'
            }, {
                draggable: 0 // можно передвигать
            }, {
                preset: 'islands#greenDotIcon', // тип метки
            },
        );
        // добавление метки на карту
        myMap.geoObjects.add(mmMarkerFly);

        //console.log("Разместить маркер перелета с координатами:");
        //console.log(getCoords);
    }

    // ---------------------------------------------------------------
    // удалить маркер перелета с карты
    function myFunMarkerFlyDelete() {
        if (typeof (mmMarkerFly) !== 'undefined') {
            myMap.geoObjects.remove(mmMarkerFly);
        }
    }

</script>
