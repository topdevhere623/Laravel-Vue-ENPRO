<script type="text/javascript">

    // JS-скрипты касательно карты Яндекса

    // ---------------------------------------------------------------
    // определить адрес по карте по координатам
    function funGetAddress(getMapID, getField) {

        // получение координаты точки с карты
        let myCoordinates = mmCollection.get(getMapID).geometry.getCoordinates();

        if (myCoordinates != null) {

            // определение адреса (асинхронный запрос!)
            let myReverseGeocoder = ymaps.geocode([myCoordinates[0], myCoordinates[1]]);
            myReverseGeocoder.then(function (res) {

                // адрес
                let myAddress = res.geoObjects.get(0).properties.get('text');

                // сообщение пользователю до преобразования
                toastr.success('Адрес определен как: ' + myAddress);

                if (typeof (myAddress) !== 'undefined') {

                    // убрать слова из адреса
                    myAddress = myAddress.replace('Россия,', '');
                    myAddress = myAddress.replace('Свердловская область,', '');
                    myAddress = myAddress.replace('улица ', 'ул.');

                    // записать в поле
                    $('#' + getField).val(myAddress);
                }
            });
        }
    }

    // ---------------------------------------------------------------
    // расстояние между точками
    function funGetDistance(getObject) {

        let myDistance = 0;

        // 2-ой вариант через геометрию обьекта Polyline (сразу его кривизна по характерным точкам будет учитываться)
        if (getObject.mapType === 'polyline' && typeof (mmCollection.get(getObject.mapID)) !== 'undefined') {
            let myPolyline = mmCollection.get(getObject.mapID);
            myDistance = Math.ceil(myPolyline.geometry.getDistance());
        }

        // возвращаемое значение
        return myDistance;
    }

    // ---------------------------------------------------------------
    // автомасштаб карты
    function funAutoScale() {

        // чтоб были видны все обьекты коллекции
        if (mmCollection.getLength() > 0) {
            // обьекты в коллекции есть
            myMap.setBounds(mmCollection.getBounds());
        }
    }

</script>
