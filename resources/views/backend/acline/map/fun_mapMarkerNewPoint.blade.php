<script type="text/javascript">

    // все что связано с маркером предполагаемой новой точки

    // ---------------------------------------------------------------
    // добавить маркер предполагаемой новой точки
    function funMarkerNewPointAdd() {

        // имеет ли права
        if (!mmUserHasEditRights) return false;

        let myName = 'Предполагаемая новая точка';
        mmMarkerNewPoint = new ymaps.Placemark(
            [mmCurrentCoords[0], mmCurrentCoords[1]],
            {
                hintContent: myName,
                iconContent: myName,
            }, {
                draggable: 1, // можно ли передвигать
                iconLayout: 'default#image',
                iconImageHref: '/public/uploads/map_lep_icons/newMarker/target_active.png',
                iconImageSize: [30, 30],
                iconImageOffset: [-15, -15],
            },
        );
        myMap.geoObjects.add(mmMarkerNewPoint);

        // подпишемся на событие перетаскивая маркера в новое место
        mmMarkerNewPoint.geometry.events.add('change', function (e) {

            // обновить текущие координаты
            mmCurrentCoords = e.get('newCoordinates');
        });
    }

    // ---------------------------------------------------------------
    // удалить маркер предполагаемой новой точки
    function funMarkerNewPointDelete() {

        // удалить
        if (typeof(mmMarkerNewPoint) !== 'undefined') {
            myMap.geoObjects.remove(mmMarkerNewPoint);
        }
    }

</script>
